<?php //var_dump($args);?>
<div class="row" >
    <div class="col-10">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[apply_id]" placeholder="--><?php //echo Yii::t('license_licensepdf', 'apply_id'); ?><!--">-->
                <!--                </div>-->
                <!--                <div class="col-xs-3 padding-lr5" >-->
                <!--                    <input type="text" class="form-control input-sm" name="q[main_proname]" placeholder="--><?php //echo Yii::t('license_licensepdf', 'program_name'); ?><!--">-->
                <!--                </div>-->
                <input id="program_id" type="hidden" name="q[program_id]" value="<?php echo $program_id  ?>">
                <input id="type_id" type="hidden" name="q[type_id]" value="<?php echo $type_id  ?>">
                <?php
                if(array_key_exists('discipline',$args)){
                    ?>
                    <input  type="hidden" name="q[dash_discipline]" value="<?php echo $args['discipline']  ?>">
                    <?php
                }
                ?>
                <?php
                if(array_key_exists('group_name',$args)){
                    ?>
                    <input  type="hidden" name="q[dash_group_name]" value="<?php echo urlencode($args['group_name'])  ?>">
                    <?php
                }
                ?>
                <?php
                if(array_key_exists('form_id',$args)){
                    ?>
                    <input  type="hidden" name="q[dash_form_id]" value="<?php echo $args['form_id']  ?>">
                    <?php
                }
                ?>
                <div class="form-group " style="padding-bottom:5px;width: 160px;">
                    <input type="text" id="check_no" class="form-control input-sm" style="width: 100%" name="q[check_no]" placeholder="Ref No." value="<?php echo array_key_exists('check_no',$args)?$args['check_no']:""; ?>">
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 160px">
                    <input type="text" id="subject" class="form-control input-sm" style="width: 100%" name="q[subject]" placeholder="Subject" value="<?php echo array_key_exists('subject',$args)?$args['subject']:""; ?>">
                </div>

                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 40px;height:30px">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_start_date" name="q[start_date]" id="q_start_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"  value="<?php echo array_key_exists('start_date',$args)?$args['start_date']:""; ?>"/>
                        <div class="input-group-append" data-target="#q_start_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 20px;height:30px">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_end_date" name="q[end_date]" id="q_end_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>"  value="<?php echo array_key_exists('end_date',$args)?$args['end_date']:""; ?>"/>
                        <div class="input-group-append" data-target="#q_end_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                
                <!--status: type_id==2 RFA; type_id==1 RFI;-->
                <?php if($status == '-1'){  //草稿    ?>
                    <input type="hidden" name="q[status]" value="-1">
                <?php }
                else{ ?>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 140px;">
                        <select class="form-control input-sm" name="q[status]" id="status" style="width: 100%;">
                            <option value="">--Status--</option>
                            <option value="0" <?php if($args['status'] == '0') echo "selected"; ?>>Ongoing</option>
                            <option value="1" <?php if($args['status'] == '1') echo "selected"; ?>><?php if ($type_id == '2') { echo 'Replied';} else{echo "Closed";}?></option>
                            <option value="2" <?php if($args['status'] == '2') echo "selected"; ?>>Overdue</option>
                        </select>
                    </div>
                <?php } ?>
                    
                <!--type -->
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 140px;">
                    <select class="form-control input-sm" name="q[select_type]" id="select_type" style="width: 100%;">
                        <option value="-1" >--Type--</option>
                        <option value="0" <?php if($args['select_type'] == '0') echo "selected"; ?>>My Creation</option>
                        <option value="1" <?php if($args['select_type'] == '1') echo "selected"; ?>>CC Me</option>
                        <option value="2" <?php if($args['select_type'] == '2') echo "selected"; ?>>To Me</option>
                        <option value="3" <?php if($args['select_type'] == '3') echo "selected"; ?>>All</option>
                    </select>
                </div>

                <?php if($type_id == '2'){ ?>
                
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width:140px">
                        <select class="form-control input-sm" name="q[outcome]" id="type" style="width: 100%;">
                            <option value="" >--Outcome--</option>
                            <option value="3" <?php if($args['outcome'] == '3') echo "selected"; ?>>In-Principal No Objection</option>
                            <option value="4" <?php if($args['outcome'] == '4') echo "selected"; ?>>Accepted with Comments</option>
                            <option value="6" <?php if($args['outcome'] == '6') echo "selected"; ?>>Rejected</option>
                            <option value="7" <?php if($args['outcome'] == '7') echo "selected"; ?>>Re-test / Revise & Resubmit</option>
                            <option value="11" <?php if($args['outcome'] == '11') echo "selected"; ?>>Approved</option>
                            <option value="12" <?php if($args['outcome'] == '12') echo "selected"; ?>>Not Approved</option>
                        </select>
                    </div>
                    
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width:100px">
                        <select class="form-control input-sm" name="q[rvo]" id="rvo" style="width: 100%;">
                            <option value="" >--Rvo--</option>
                            <option value="1" <?php if($args['rvo'] == '1') echo "selected"; ?>>Yes</option>
                            <option value="2" <?php if($args['rvo'] == '2') echo "selected"; ?>>No</option>
                        </select>
                    </div>
                <?php } ?>    
                    
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width:100px">
                        <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
                    </div>
            </form>
        </div>
    </div>
    <?php if($status != '-1'){ ?>
        <div class="col-2">
            <div class="dataTables_filter" >
                <label style="margin-left: 10px;">
                    <?php if (Yii::app()->user->getState('operator_role') == '01'){ ?>
                        <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>','<?php echo $type_id ?>')">Create</button>
                    <?php } ?>
                </label>
            </div>
        </div>
    <?php } ?>
    </div>
    
    
<script type="application/javascript">
    //Date picker
    // $('#q_start_date').daterangepicker({
    //     singleDatePicker:true,
    //     showDropdowns:true,
    //     minYear:1901,
    //     maxYear:parseInt(moment().format('YYYY'),10)
    // }
    // //     function(start, end, label) {
    // //     var years =moment().diff(start, 'years');
    // //     alert("You are "+ years +" years old!");
    // // }
    // );
    $(function () {
        $('#q_start_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#q_end_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
    })
</script>