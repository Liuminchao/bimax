<div class="row" style="margin-left: -20px;">
    <div class="col-xs-11">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[apply_id]" placeholder="--><?php //echo Yii::t('license_licensepdf', 'apply_id'); ?><!--">-->
                <!--                </div>-->
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[main_proname]" placeholder="--><?php //echo Yii::t('license_licensepdf', 'program_name'); ?><!--">-->
                <!--                </div>-->
                <div class="col-xs-2 padding-lr5" style="width: 150px;">
                    <select class="form-control input-sm" name="q[program_id]" id="program_id" style="width: 100%;">
                        <!--                        <option value="">----><?php //echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!----</option>-->
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
                <div class="col-xs-2 padding-lr5" style="width: 90px;">
                    <select class="form-control input-sm" name="q[type]" id="type" style="width: 100%;">
                        <!--                        <option value="">----><?php //echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!----</option>-->
                        <option value='1'>RFI</option>
                        <option value='2'>RFA</option>
                        <option value='3'>ALL</option>
                    </select>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <select class="form-control input-sm" name="q[status]" id="status" style="width: 100%;">
                        <option value=''>Please Status</option>
                        <option value='-1'>Draft</option>
                        <option value='0'>Sent</option>
                        <option value='1'>Replied</option>
                        <option value='2'>Closed</option>
                        <option value='3'>Approve</option>
                        <option value='4'>Reject</option>
                        <option value='5'>Read</option>
                        <option value='6'>Approve With Comment</option>
                    </select>
                </div>
                <!--                <div class="col-xs-2 padding-lr5" style="width: 120px;">-->
                <!--                    <select class="form-control input-sm" name="q[status]" id="type" style="width: 100%;">-->
                <!--                        <!--                        <option value="">----><?php ////echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!--<!----</option>-->
                <!--                        <option value='S'>Sent</option>-->
                <!--                        <option value='C'>Close</option>-->
                <!--                        <option value='R'>Read</option>-->
                <!--                    </select>-->
                <!--                </div>-->
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[con_id]" id="form_con_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('comp_routine', 'company'); ?>--</option>
                        <?php
                        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
                        $contractor_list = Contractor::Mc_scCompList($args);
                        if($contractor_list) {
                            foreach ($contractor_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="col-xs-1 padding-lr5" style="width: 50px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-1 padding-lr5" style="width: 30px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"
                                   id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"/>
                        </div>
                    </div>
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-1">
        <div class="dataTables_filter" >
            <label>
                <?php if (Yii::app()->user->getState('operator_role') == '01'){ ?>
                    <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>')">Create</button>
                <?php } ?>
                <!--                <button class="btn btn-primary btn-sm" onclick="itemTabs()">图片上传</button>-->
            </label>
        </div>
    </div>
</div>
<script type="application/javascript">
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
            url: "index.php?r=license/licensepdf/querytype",
            data: {program_id:$("#program_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                selOpt.remove();
                if (!data) {
                    return;
                }
                selObj.append("<option value=''>--<?php echo Yii::t('license_licensepdf', 'ptw_type'); ?>--</option>");
                for (var o in data) {//console.log(o);
                    selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
</script>