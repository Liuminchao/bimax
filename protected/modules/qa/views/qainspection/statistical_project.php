<div class="row">
    <div class="col-xs-2 padding-lr5" style="width: 180px;padding-left: 15px">
        <select class="form-control input-sm" name="q[con_id]" id="program_id" style="width: 100%;" >
            <?php
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $args['program_id'] = $program_id;
            $program_list = Program::McProgramList($args);
            if($program_list) {
                foreach ($program_list as $k => $name) {
                    echo "<option value='{$k}'>{$name}</option>";
                }
            }
            ?>
        </select>
    </div>
</div>
<div class="row" style="padding-top: 8px;">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header">
                <div class="col-xs-10">
                    <div class="dataTables_length">

                        <div class="input-group has-error" style="float:left;padding-top:8px">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <?php
                                $second_time = Utils::MonthToEn(date('Y-m',strtotime('-2 month')));
                                ?>
                                <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                                       value="<?php echo $second_time ?>" id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" width="100px"/>
                            </div>
                        </div>
                        <a class="tool-a-search" style="padding-top: 8px"  href="javascript:itemQuery_first();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="first-chart" style="height: 400px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header">
                <div class="col-xs-10">
                    <div class="dataTables_length">
                        <div class="input-group has-error" style="float:left;padding-top: 8px" >
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <?php
                                $first_time = Utils::MonthToEn(date('Y-m',strtotime('-1 month')));
                                ?>
                                <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                                       value="<?php echo $first_time ?>" id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" width="100px"/>
                            </div>
                        </div>
                        <a class="tool-a-search" style="padding-top: 8px" href="javascript:itemQuery_second();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="second-chart" style="height: 400px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-success">

            </div><!-- /.box -->
        </div>
    </div>
</div>
<script src="js/jquery.flot.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var itemQuery_first = function () {
        var id=$("#program_id").find("option:selected").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
//		alert(id);
//		alert(date);
        var arr = [];
        var placeholder = $("#first-chart");
        placeholder.unbind();
        $.getJSON("index.php?r=qa/qainspection/cntbyproject&id="+id+'&date='+date, function (result) {
            var j = 0;
            if(result != null){
                $.each(result, function (i, field) {
                    arr[j] = {
                        label: field.type_name,
                        data: field.cnt
                    }
                    j++;
                });
                $.plot(placeholder, arr, {
                    series: {
                        pie: {
                            show: true,
                            combine: {
                                color: "#999",
                                threshold: 0.01
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });
            }else{
                placeholder.empty();
            }

        });
    }
    var itemQuery_second = function () {
        var id=$("#program_id").find("option:selected").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var date = $("#q_end_date").val();
//		alert(id);
//		alert(date);
        var arr = [];
        var placeholder = $("#second-chart");
        placeholder.unbind();
        $.getJSON("index.php?r=qa/qainspection/cntbyproject&id="+id+'&date='+date, function (result) {
            var j = 0;
            if(result != null) {
                $.each(result, function (i, field) {
                    arr[j] = {
                        label: field.type_name,
                        data: field.cnt
                    }
                    j++;
                });
                $.plot(placeholder, arr, {
                    series: {
                        pie: {
                            show: true,
                            combine: {
                                color: "#999",
                                threshold: 0.01
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });
            }else{
                placeholder.empty();
            }
        });
    }
</script>