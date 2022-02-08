<div class="row" >
    <div class="col-12">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">

                <?php  $contractor_id = Yii::app()->user->contractor_id;   ?>
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                <input type="hidden" name="q[source]" value="<?php echo $source; ?>">

                <?php
                    if($source == 'inspection'){
                ?>
                        <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px">
                            <select name="q[discipline]" id="discipline" class="form-control input-sm" style="width: 100%">
                                <option value="">--Discipline--</option>
                                <option value="01">C&S</option>
                                <option value="02">AR</option>
                                <option value="03">M&E</option>
                                <option value="00">NA</option>
                            </select>
                        </div>
                <?php
                    }
                ?>

                <?php
                if($source == 'DFMA'){
                    ?>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px">
                        <select name="q[discipline]" id="discipline" class="form-control input-sm" style="width: 100%">
                            <option value="">--Discipline--</option>
                            <option value="A">On-Site</option>
                            <option value="B">Fitting Out</option>
                            <option value="C">Carcass</option>
                            <option value="00">NA</option>
                        </select>
                    </div>
                    <?php
                }
                ?>
                
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 120px">
                    <input type="text" name="q[title]" class="form-control input-sm" placeholder="Description" style="width: 100%" value="<?php echo $args['title']==''?'':$args['title']; ?>" >
                </div>

                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 80px">
                    <input type="text" name="q[block]" class="form-control input-sm" placeholder="Block" style="width: 100%" value="<?php echo $args['block']==''?'':$args['block']; ?>" >
                </div>

                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 80px">
                    <input type="text" name="q[secondary_region]" class="form-control input-sm" placeholder="Level" style="width: 100%" value="<?php echo $args['secondary_region']==''?'':$args['secondary_region']; ?>" >
                </div>

                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 120px">
                    <input type="text" name="q[apply_user_name]" class="form-control input-sm" placeholder="Initiator" style="width: 100%" value="<?php echo $args['apply_user_name']==''?'':$args['apply_user_name']; ?>" >
                </div>

                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 120px">
                    <input type="text" name="q[rectify_name]" class="form-control input-sm" placeholder="Person to rectify" style="width: 100%" value="<?php echo $args['rectify_name']==''?'':$args['rectify_name']; ?>" >
                </div>

                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 40px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_start_date" name="q[start_date]" id="q_start_date"  placeholder="Issue date" value="<?php echo $args['start_date']==''?'':$args['start_date']; ?>"/>
                        <div class="input-group-append" data-target="#q_start_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 20px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_end_date" name="q[end_date]" id="q_end_date"  placeholder="Issue date" value="<?php echo $args['end_date']==''?'':$args['end_date']; ?>" />
                        <div class="input-group-append" data-target="#q_end_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="form-group padding-lr5" style="width:100px">
                        <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript">

    //form初始化
    function FormInit(node) {
        return node.html('<option value="">--<?php echo Yii::t('comp_qa', 'form_type'); ?>--</option>');
    }

    //公司和类型半联动
    $('#program_id').change(function(){
        //alert($(this).val());

        var selObj = $("#type_id");
        var selOpt = $("#type_id option");

        if ($(this).val() == 0) {
            selOpt.remove();
            return;
        }
        $.ajax({
            type: "POST",
            url: "index.php?r=qa/qainspection/querytype",
            data: {program_id:$("#program_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                selOpt.remove();
                if (!data) {
                    return;
                }
                selObj.append("<option value=''>--<?php echo Yii::t('comp_routine', 'check_type'); ?>--</option>");
                for (var o in data) {//console.log(o);
                    selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
    //类型和表单类型半联动
    $('#type_id').change(function(){

        var formObj = $("#form_id");
        var formOpt = $("#form_id option");
        FormInit(formObj);

        $.ajax({
            type: "POST",
            url: "index.php?r=qa/qainspection/queryform",
            data: {type_id:$("#type_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                for (var o in data) {//console.log(o);
                    formObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
    
    $(function () {
        $('#q_start_date').datetimepicker({
            format: 'DD MMM yyyy',
        });
        $('#q_end_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
    })

</script>