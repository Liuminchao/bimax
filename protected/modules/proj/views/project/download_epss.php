
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[title]" placeholder="--><?php //echo Yii::t('tbm_meeting', 'title'); ?><!--">-->
                <!--                </div>-->
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[type_id]" id="type_id" style="width: 100%;">
<!--                        <option value="">----><?php //echo Yii::t('comp_ra', 'type'); ?><!----</option>-->
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $type_list = EpssType::levelText();
                        if($type_list) {
                            foreach ($type_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <?php
                            $second_time = Utils::MonthToEn(date('Y-m',strtotime('-2 month')));
                            ?>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                                   value="<?php echo $second_time ?>" id="month" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" width="100px"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" id="sbtn" onclick="itemExport('<?php echo $program_id ?>')" class="btn btn-primary btn-lg"><?php echo Yii::t('proj_report', 'export'); ?></button>
        </div>
    </div>
</div>

<script type="text/javascript">
    //项目组成员
    var itemExport = function (id) {
        var type_id = $('#type_id').val();
        var month = $('#month').val();
        window.location = "index.php?r=proj/report/exportepss&program_id="+id+"&type_id="+type_id+"&month="+month;
        //window.location = "index.php?r=proj/assignuser/edit&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
</script>
