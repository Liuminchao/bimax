<div class="row" >
    <div class="col-12">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">

                <?php  $contractor_id = Yii::app()->user->contractor_id;   ?>
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[check_id]" placeholder="--><?php //echo Yii::t('comp_safety', 'check_id'); ?><!--">-->
                <!--                </div>-->
                <input type="hidden" name="q[clt_type]" value="<?php echo $clt_type; ?>">
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px">
                    <input type="text" name="q[apply_name]" class="form-control input-sm" placeholder="<?php echo Yii::t('comp_qa', 'applicant_name'); ?>" style="width: 100%" value="<?php echo $args['apply_name']==''?'':$args['apply_name']; ?>" >
                </div>

                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px">
                    <select name="q[con_id]" id="form_con_id" class="form-control input-sm" style="width: 100%">
                        <option value="">--<?php echo Yii::t('comp_routine', 'company'); ?>--</option>
                        <?php
                            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                            $args['program_id'] = $program_id;
                            $contractor_list = Contractor::Mc_scCompList($args);
                            if($contractor_list) {
                                foreach ($contractor_list as $k => $name) {
                                    echo "<option value='".$k."'";
                                    if ($args['con_id'] == $k) {
                                    	echo " selected";
                                    }
                                    echo ">".$name."</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 40px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_start_date" name="q[start_date]" id="q_start_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" value="<?php echo $args['start_date']==''?'':$args['start_date']; ?>"/>
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
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_end_date" name="q[end_date]" id="q_end_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" value="<?php echo $args['end_date']==''?'':$args['end_date']; ?>" />
                        <div class="input-group-append" data-target="#q_end_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                
<!--                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 150px">-->
<!--                    <select class="form-control input-sm" name="q[type_id]" id="type_id" style="width: 100%;">-->
<!--                        <option value="">----><?php //echo Yii::t('comp_qa', 'discipline'); ?><!----</option>-->
<!--                        --><?php
//                            $type_list = QaCheckType::AllType();
//                            $form_type = QaChecklist::formType();
//                            $form_type_id = '';
//                            foreach ($type_list as $val => $value){
//                                if($form_type_id != $form_type[$value['form_type']]){
//                                    $form_type_id = $form_type[$value['form_type']];
//                                    $form_type_name = $form_type[$form_type_id];
//                                    echo "<optgroup label='$form_type_id'>";
//                                }
//                                echo "<option value='".$val."'";
//                                if ($args['type_id'] == $val) {
//                                    echo " selected";
//                                }
//                                echo ">".$value['type_name']."</option>";
//                            }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 180px">-->
<!--                    <select class="form-control input-sm" id="form_id" name="q[form_id]" style="width: 100%;">-->
<!--                        <option value="">----><?php //echo Yii::t('comp_qa', 'form_type'); ?><!----</option>-->
<!--                    </select>-->
<!--                </div>-->
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