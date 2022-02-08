<link href="css/select2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .content {
        padding: 0px 15px;
        background: #F2F2F2;
    }
    body{ background-color: #F2F2F2;}
    .form-group {
        height: 38px;
    }
</style>
<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    'autoValidation' => false,
//    "action" => "javascript:formSubmit()",
//    'enableAjaxSubmit' => false,
//    'ajaxUpdateId' => 'content-body',
//    'role' => 'form', //可省略
//    'formClass' => 'form-horizontal', //可省略 表单对齐样式

));
?>
<div class="container" style="padding-top: 8px;">
    <div class="row">
        <div class="col-1">
        </div>
        <div class="col-10" style="background-color: #F2F9FA;text-align: center">
            <div class="row" style="margin-top: 8px;">
                <div class="col-sm-1 offset-md-1 padding-lr5" style="padding-top:7px;">
                    <button type="button" class="btn btn-primary" style="background-color: #FF9966;" onclick="cancel('<?php echo $link_check_id; ?>')">Cancel</button>
                </div>
            </div>
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>
            <div class="row" >
                <div class ="col-md-4 offset-md-9">
                    <input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $program_id; ?>">
                    <input type="hidden" id="type_id" name="rf[type_id]" value="2">
                    <input type="hidden" id="forward_status" name="rf[forward_status]" value="<?php echo $forward_status; ?>">
                    <input type="hidden" id="link_check_id" name="rf[link_check_id]" value="<?php echo $link_check_id; ?>">
                    <input type="hidden" id="filebase64"/>
                </div>
            </div>
            <?php
            $rf_model = RfList::model()->findByPk($link_check_id);
            $check_no = $rf_model->check_no;
            $subject = $rf_model->subject;
            $item_list = RfRecordItem::dealList($link_check_id);
            $attach_list = RfRecordAttachment::dealListBystep($link_check_id,'1');
            $link_list = explode(',', $link_check_id);
            $link_cnt = count($link_list);
            $no_list = RfNoSet::regionList($program_id,$type);
            if($link_cnt == 1){
                $check_no = $rf_model->check_no;
                $self_no_list = explode('-',$check_no);
            }else{
                $self_no_list = array();
            }
            $no_list = RfNoSet::regionList($program_id,$type);
            ?>

            <div class="row" style="margin-top: 8px;border-bottom:4px solid #F2F2F2;padding-bottom:8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Type<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-3 padding-lr5">
                    <select id="template_type" name="rf[template_type]" class="form-control" check-type="required" onchange="changeview()">
                        <?php
                        $form_list = RfFormType::formList($program_id,$type);
                        foreach ($form_list as $i => $form){
                            $form_id = $form['form_id'];
                            $form_name = $form['form_name'];
                            if($form_id == $current_form_id){
                                echo "<option value='".$form_id."' selected>$form_name</option>";
                            }else{
                                echo "<option value='".$form_id."' >$form_name</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-5 padding-lr5" style="padding-bottom: 0;margin-left:15px;">
                    <label   style="width:17%;float: left;text-align:left;padding-top: 7px;">Ref no.<span style="color: #c12e2a">*</span></label>
                    <select id="no_co" class="form-control" name="rf[no_co]" style="float: left;width:67px;" check-type="required" >
                        <option value='0' selected>-Co-</option>
                        <?php
                        if(!empty($no_list[A])){
                            foreach($no_list[A] as $cnt => $region){
                                if(count($self_no_list) >0 && $region == $self_no_list[0]){
                                    echo "<option value='{$region}' selected>$region</option>";
                                }else{
                                    echo "<option value='{$region}' >$region</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <select id="no_site" class="form-control" name="rf[no_site]" style="float: left;width:77px;margin-left: 2px;" check-type="required" >
                        <option value='0' selected>-Site-</option>
                        <?php
                        if(!empty($no_list[B])){
                            foreach($no_list[B] as $cnt => $region){
                                if(count($self_no_list) >0 && $region == $self_no_list[1]){
                                    echo "<option value='{$region}' selected>$region</option>";
                                }else{
                                    echo "<option value='{$region}' >$region</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <select id="no_discipline" class="form-control" name="rf[no_discipline]" style="float: left;width:90px;margin-left: 2px;" check-type="required" >
                        <option value='0' selected>-Discipline-</option>
                        <?php
                        if(!empty($no_list[C])){
                            foreach($no_list[C] as $cnt => $region){
                                if(count($self_no_list) >0 && $region == $self_no_list[2]){
                                    echo "<option value='{$region}' selected>$region</option>";
                                }else{
                                    echo "<option value='{$region}' >$region</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <?php
                    if(count($self_no_list) >0){
                        echo "<input type='text' class='form-control' id='no' name='rf[no]' placeholder='001' style='float: left;width:13%;margin-left: 2px;' value='{$self_no_list[4]}'>";
                    }else{
                        echo "<input type='text' class='form-control' id='no' name='rf[no]' placeholder='001' style='float: left;width:13%;margin-left: 2px;' >";
                    }
                    ?>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">To<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-3 padding-lr5" style="text-align: left">
                    <select id="to_contractor_id" class="form-control" onchange="to_create_data()">
                        <?php
                        $contractor_list = Contractor::CompanyListByProgram($program_id);
                        $group_list = RfGroup::groupByProgram($program_id);
                        if(count($group_list)>0){
                            echo "<option value=''>--Select Group--</option>";
                            foreach ($group_list as $group_id => $group_name) {
                                $group_id = 'Group'.$group_id;
                                echo "<option value='{$group_id}'>{$group_name}</option>";
                            }
                        }
                        //                        echo "<option value=''>--Select Company--</option>";
                        //                        foreach ($contractor_list as $contractor_id => $contractor_name) {
                        //                            echo "<option value='{$contractor_id}'>{$contractor_name}</option>";
                        //                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-5 padding-lr5" style="margin-left: 15px;">
                    <select id="to"  class="form-control" multiple="multiple" >
                    </select>
                </div>
                <!--                <button type="button" class="btn btn-primary" style="background-color: #169BD5">Directory</button>-->
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left"></label>
                <label class="col-sm-3">
                    <input type="radio" name="rf[deal_type]"  id="approval" value="1" >
                    Request for Approval
                </label>
                <!--                <label class="col-sm-5 ">-->
                <!--                    <input type="radio" name="rf[deal_type]" id="review" value="2" >-->
                <!--                    Request for Review-->
                <!--                </label>-->
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Cc</label>
                <div class="col-sm-3 padding-lr5" style="text-align: left">
                    <select id="cc_contractor_id" class="form-control" onchange="cc_create_data()">
                        <?php
                        $contractor_list = Contractor::CompanyListByProgram($program_id);
                        $group_list = RfGroup::groupByProgram($program_id);
                        if(count($group_list)>0){
                            echo "<option value=''>--Select Group--</option>";
                            foreach ($group_list as $group_id => $group_name) {
                                $group_id = 'Group'.$group_id;
                                echo "<option value='{$group_id}'>{$group_name}</option>";
                            }
                        }
                        //                        echo "<option value=''>--Select Company--</option>";
                        //                        foreach ($contractor_list as $contractor_id => $contractor_name) {
                        //                            echo "<option value='{$contractor_id}'>{$contractor_name}</option>";
                        //                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-5 padding-lr5" style="margin-left: 15px;">
                    <select id="cc"  class="form-control" multiple="multiple" class="form-control">
                    </select>
                </div>
                <!--                <button type="button" class="btn btn-primary" style="background-color: #169BD5">Directory</button>-->
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Submission<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-3 padding-lr5">
                    <select id="submission" name="rf[submission]" class="form-control" check-type="required" >
                        <option value="1" selected="selected">1st Submission</option>
                        <option value="2">2nd Submission</option>
                        <option value="3">3nd Submission</option>
                    </select>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Subject<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-8 padding-lr5" style="text-align: left;">
                    <input id="subject" class="form-control" name="rf[subject]" check-type="required" required-message="Project Name can not be empty"  type="text" value="" >
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">RVO<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input type="radio" id="yes_rvo" name="item[rvo]"  value="1" /> Yes
                </div>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input type="radio" id="no_rvo" name="item[rvo]"  value="2" style="padding-left: 4px;"  /> No
                </div>
                <div class="col-sm-2 padding-lr5" style="text-align: left;">

                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Submission for<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="submission_for" name="item[submission_for]" type="radio" value="1" />Material / Sample / Data
                </div>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="submission_for" name="item[submission_for]" type="radio" value="2" />Document
                </div>
                <div class="col-sm-2 padding-lr5" style="text-align: left;">
                    <input id="submission_for" name="item[submission_for]" type="radio" value="3" />Shop Drawings
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Actions Required<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="action_req" name="item[action_req]" type="radio" value="1" />For Record
                </div>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="action_req" name="item[action_req]" type="radio" value="2" />For Approval
                </div>
                <div class="col-sm-2 padding-lr5" style="text-align: left;">
                    <input id="action_req" name="item[action_req]" type="radio" value="3" />For Endorsement
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Discipline<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="discipline" name="rf[discipline]" type="radio" value="1" />Structural
                </div>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="discipline" name="rf[discipline]" type="radio" value="2" />Architecture
                </div>
                <div class="col-sm-2 padding-lr5" style="text-align: left;">
                    <input id="discipline" name="rf[discipline]" type="radio" value="3" />M&E
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Trade</label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <select id="submission" name="item[trade]" class="form-control" check-type="required" >
                        <?php
                        $trade_list = RfGroup::tradeList();
                        foreach($trade_list as $trade_id => $trade_val){
                            echo "<option value='{$trade_id}' >$trade_val</option>";
                        }
                        ?>
                    </select>
                </div>
                <label for="program_name" class="col-sm-2  control-label padding-lr5" style="text-align: left">Latest Date to Reply</label>
                <div class="form-group col-sm-3 padding-lr5" >
                    <div class="input-group date padding-lr5" data-target-input="nearest" style="padding-left: 5px;">
                        <?php
                        $Date = Utils::DateToEn(date('Y-m-d',strtotime('+14 day')));
                        ?>
                        <input type="text" class="form-control datetimepicker-input b_date_ins" data-target="#q_start_date" name="q[start_date]"
                               id="valid_time"   value="<?php echo $Date; ?>"/>
                        <div class="input-group-append" data-target="#valid_time" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">a.Item Submitted</label>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-8 padding-lr5">
                    <textarea rows="10" id="a_item" name = "item[a_item]" style="width:100%"></textarea>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">b.Item Submitted</label>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_spec_clause" name="item[check_spec_clause]" type="checkbox" value="0" />Specification Clause(e)</div>
                <div class="col-sm-5 padding-lr5"><input id="spec_clause" class="form-control" name="item[spec_clause]"   type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_contract_draw" name="item[check_contract_draw]" type="checkbox" value="0" />Contract Drawing No.</div>
                <div class="col-sm-5 padding-lr5"><input id="contract_draw" class="form-control" name="item[contract_draw]"  type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_product_spec" name="item[check_product_spec]" type="checkbox" value="0" />Product Specifications</div>
                <div class="col-sm-5 padding-lr5"><input id="product_spec" class="form-control" name="item[product_spec]"   type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_meth_state" name="item[check_meth_state]" type="checkbox" value="0" />Method Statement</div>
                <div class="col-sm-5 padding-lr5"><input id="meth_state" class="form-control" name="item[meth_state]"   type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_pe" type="checkbox" name="item[check_pe]" value="0" />PE calculations (endsorsed)</div>
                <div class="col-sm-5 padding-lr5"><input id="pe" class="form-control" name="item[pe]"  type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_color_chart" type="checkbox" name="item[check_color_chart]" value="0" />Color chart</div>
                <div class="col-sm-5 padding-lr5"><input id="color_chart" class="form-control" name="item[color_chart]"   type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_hdb_letter" name="item[check_hdb_letter]" type="checkbox" value="0" />HDB Approved Letter</div>
                <div class="col-sm-5 padding-lr5"><input id="hdb_letter" class="form-control" name="item[hdb_letter]"  type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_test_report" name="item[check_test_report]" type="checkbox" value="0" />Document</div>
                <div class="col-sm-5 padding-lr5"><input id="test_report" class="form-control" name="item[test_report]"  type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-3 padding-lr5" style="text-align: left"><input id="check_others" name="item[check_others]"  type="checkbox" value="0" />Others</div>
                <div class="col-sm-5 padding-lr5"><input id="others" class="form-control" name="item[others]"  type="text" value="" ></div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Attachment</label>
                <div class="col-sm-8 padding-lr5" style="text-align: left">
                    <input id="file" multiple="multiple" class="form-control" check-type="" style="display:none" onchange="raupload(this)" name="File[file_path]" type="file" />
                    <button type="button" class="btn btn-primary" style="background-color: #169BD5" onclick="file.click()">Add</button>
                </div>
            </div>

            <div class="row" >
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-8 padding-lr5">
                    <table id="attach"  style="width: 70%">

                    </table>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <div class="col-sm-12 padding-lr5" style="text-align: center">
                    <!--                    <button type="button" class="btn btn-primary" style="background-color: #20B2AA" onclick="savedraft()" >Save as Draft</button>-->
                    <button id="save_btn" type="button" class="btn btn-primary" style="background-color: #33CCCC;" onclick="send()" >Send</button>
                </div>
            </div>

        </div>
        <div class="col-1">
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/loading_upload.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/select2/select2.js"></script>
<script type="text/javascript" src="js/layui.js" ></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#valid_time').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#to_contractor_id').select2();
        $('#cc_contractor_id').select2();
        to_create_data();
        cc_create_data();
        showTime();
    })

    document.onmousedown=function(event){
        $('#draft_btn').attr("disabled",false);
        $('#save_btn').attr("disabled",false);
    }

    function showTime() {
        $.ajax({
            url: "index.php?r=rf/rf/sendheart",
            data: {confirm:'hell world'},
            type: "POST",
            dataType: "json",
            success: function (res) {
                console.log(res.msg);
                setTimeout('showTime()', 600000);
            }
        })
    }

    function changeview() {
        var template_id = $('#template_type').val();
        var program_id = $('#program_id').val();
        var type = $('#type_id').val();
        var link_check_id = $('#link_check_id').val();
        var forward_status = $('#forward_status').val();
        window.location = "index.php?r=rf/rf/changeforward&program_id="+program_id+"&type="+type+"&form_id="+template_id+"&check_id="+link_check_id+"&forward_status="+forward_status;
    }

    function to_create_data() {
        var contractor_id = $('#to_contractor_id').val();
        if(contractor_id.indexOf("Group") == -1){
            $.ajax({
                url: "index.php?r=rf/rf/stafflist",
                data: {contractor_id: contractor_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    $('#to').empty();
                    $('#to').select2({
                        data: res,  //返回的数据
                    });

                }
            })
        }else{
            $.ajax({
                url: "index.php?r=rf/group/userlist",
                data: {group_id: contractor_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    $('#to').empty();
                    $('#to').select2({
                        data: res,  //返回的数据
                    });
                    // to_arr = [];
                    // $.each(res, function (index, data) {
                    //     to_arr.push(data.id);
                    // })
                    // $("#to").val(to_arr).trigger('change');
                }
            })
        }
    }
    cc_arr = [];
    function cc_create_data() {
        var contractor_id = $('#cc_contractor_id').val();
        if(contractor_id.indexOf("Group") == -1){
            $.ajax({
                url: "index.php?r=rf/rf/stafflist",
                data: {contractor_id: contractor_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#cc').empty();
                    $('#cc').select2({
                        data: res,  //返回的数据
                    });

                }
            })
        }else{
            $.ajax({
                url: "index.php?r=rf/group/userlist",
                data: {group_id: contractor_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#cc').empty();
                    $('#cc').select2({
                        data: res,  //返回的数据
                    });
                    // $.each(res, function (index, data) {
                    //     cc_arr.push(data.id);
                    // })
                    // $("#cc").val(cc_arr).trigger('change');
                }
            })
        }
    }

    // 删除一行
    function del_attachment(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标
        $(obj).parents("tr").remove();
    }
    //预览
    function previewdoc (path) {
        path = encodeURIComponent(path);
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }
    }

    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){

        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte

        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }

    var raupload = function(file){
        var file_list = $('#file')[0].files;
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|doc|docx|xls|xlsx|dwg|zip|rar)$/.test(file.name)) {
                alert("Please upload document in either .gif, .jpeg, .jpg, .png, .doc, .xls, .dwg, .zip, .rar, .xlsx, .docx or .pdf format.");
                return false;
            }
            ra_tag = file.name.lastIndexOf(".");
            ra_length = file.name.length;
            //获取后缀名
            ra_type=file.name.substring(ra_tag,ra_length);
            ra_name = file.name.substr(0,ra_tag);
            var video_src_file = file.name;
            containSpecial = new RegExp(/[\~\%\^\*\[\]\{\}\|\\\;\:\'\"\,\.\/\?]+/);
            status = containSpecial.test(ra_name);
            if(status == 'true'){
                alert('File name contains special characters, please check before uploading');
                return false;
            }
            var newFileName = video_src_file.split('.');

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            formData.append("file1", file);

            $.ajax({
                url: "https://shell.cmstech.sg/appupload",
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                beforeSend: function () {
                    addcloud();
                },
                success: function (data) {
                    removecloud();//去遮罩
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            if(ra_type == '.pdf' || ra_type == '.jpg' || ra_type == '.png' || ra_type == '.jpeg'){
                                var $tr = $("<tr> <td  align='left' ><img  src='img/attach.png' ></td><td  align='left' >"+video_src_file+"</td> <td  align='right' width='15%'>"+"<a onclick='previewdoc(\""+value.file1+"\")'>Preview</a></td>"+"<td  align='center' width='15%'><a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                            }else{
                                var $tr = $("<tr> <td  align='left' ><img  src='img/attach.png' ></td><td  align='left' >"+video_src_file+"</td> "+"<td  align='center' width='15%'><a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                            }
                            var $table = $("#attach");
                            $table.append($tr);
                        }
                    });
                }
            });
        })
    }

    //浏览PDF
    function preview_attachment(path){
        path = encodeURIComponent(path);
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }

    }

    $.fn.minchao_serialize = function () {
        var a = this.serializeArray();
        var $radio = $('input[type=radio]', this);
        var temp = {};
        $.each($radio, function () {
            if (!temp.hasOwnProperty(this.name)) {
                if ($("input[name='" + this.name + "']:checked").length == 0) {
                    temp[this.name] = "";
                    a.push({name: this.name, value:""});
                }
            }
        });
        //console.log(a);
        return jQuery.param(a);
    };

    //添加表单其他元素
    function send() {
        $('#save_btn').attr("disabled","disabled");
        var ccDesc = $("#cc").val();
        var toDesc = $("#to").val();
        var form_data = $('#form1').minchao_serialize();
        $.ajax({
            data:form_data+"&to="+toDesc+"&cc="+ccDesc,
            url: "index.php?r=rf/rf/send",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud();
            },
            success: function (data) {
                removecloud();//去遮罩
                if(data.status == '-1'){
                    // $('#msgbox').addClass('alert-danger fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    layui.use('layer', function(){
                        layer.msg(data.msg); //提示
                    })
                }
                if(data.status == '1'){
                    // $('#msgbox').addClass('alert-success fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    // alert('success');
                    layui.use('layer', function(){
                        layer.msg('success'); //提示
                    })
                    window.location = "index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&type_id=2";
                }
            },
            error: function () {
                removecloud();//去遮罩
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统超时');
                $('#msgbox').show();
            }
        });
    }

    //添加表单其他元素
    function savedraft() {
        var ccDesc = $("#cc").val();
        var toDesc = $("#to").val();
        var form_data = $('#form1').minchao_serialize();
        $.ajax({
            data:form_data+"&to="+toDesc+"&cc="+ccDesc,
            url: "index.php?r=rf/rf/savedraft",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud();
            },
            success: function (data) {
                removecloud();//去遮罩
                if(data.status == '-1'){
                    // $('#msgbox').addClass('alert-danger fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    layui.use('layer', function(){
                        layer.msg(data.msg); //提示
                    })
                }
                if(data.status == '1'){
                    // $('#msgbox').addClass('alert-success fa-ban');
                    // $('#msginfo').html(data.msg);
                    // $('#msgbox').show();
                    // sessionStorage.clear();
                    // alert('success');
                    layui.use('layer', function(){
                        layer.msg('success'); //提示
                    })
                    window.location = "index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&status=-1";
                }
            },
            error: function () {
                removecloud();//去遮罩
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统超时');
                $('#msgbox').show();
            }
        });
    }

    //取消
    function cancel(check_id) {
        var program_id = $('#program_id').val();
        var type_id = $('#type_id').val();
        if(check_id.indexOf(",") != -1){
            window.location = "index.php?r=rf/rf/list&program_id="+program_id+"&type_id="+type_id;
        }else{
            window.location = "index.php?r=rf/rf/info&check_id="+check_id;
        }
    }
    // 删除一行
    function del_to_tr(obj,value){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标 未使用
        $(obj).parents("tr").remove(); //实现删除tr
        var to_cnt = $('#to_cnt').val();
        var to_str = $('#to_str').val();
        var to_str =  to_str.replace(','+value+',', ',');
        var to_cnt = to_cnt -1;
        $('#to_cnt').val(to_cnt);
        $('#to_str').val(to_str);
    }
    // 删除一行
    function del_cc_tr(obj,value){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标 未使用
        $(obj).parents("tr").remove(); //实现删除tr
        var cc_cnt = $('#cc_cnt').val();
        var cc_str = $('#cc_str').val();
        var cc_str =  cc_str.replace(','+value+',', ',');
        var cc_cnt = cc_cnt -1;
        $('#cc_cnt').val(cc_cnt);
        $('#cc_str').val(cc_str);
    }
</script>
