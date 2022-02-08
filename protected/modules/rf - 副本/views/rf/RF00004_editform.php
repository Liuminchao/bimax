<link href="css/select2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .content {
        padding: 0px 15px;
        background: #F2F2F2;
    }
    body{ background-color: #F2F2F2;}
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
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>
            <div class="row" >
                <div class ="col-md-4 offset-md-9">
                    <?php
                    $rf_model = RfList::model()->findByPk($check_id);
                    $type = $rf_model->type;
                    $item_data = json_decode($item_list[0]['item_data'],true);
                    $check_no = $rf_model->check_no;
                    $self_no_list = explode('-',$check_no);
                    $no_list = RfNoSet::regionList($program_id,$type);
                    ?>
                    <input type="hidden" id="program_id" name="rf[program_id]" value="<?php echo $program_id; ?>">
                    <input type="hidden" id="check_id" name="rf[check_id]" value="<?php echo $check_id; ?>">
                    <input type="hidden" id="form_id" name="rf[template_type]" value="<?php echo $form_id; ?>">
                    <input type="hidden" id="type_id" name="rf[type_id]" value="<?php echo $type; ?>">
                    <input type="hidden" id="to_user"  value="<?php echo $to_user; ?>">
                    <input type="hidden" id="cc_user"  value="<?php echo $cc_user; ?>">
                    <input type="hidden" id="link_check_id" name="rf[link_check_id]" value="">
                    <input type="hidden" id="filebase64"/>
                </div>
            </div>
            <?php
            $program_model = Program::model()->findByPk($program_id);
            $program_name = $program_model->program_name;
            $con_id = $program_model->contractor_id;
            $con_model = Contractor::model()->findByPk($con_id);
            $con_name = $con_model->contractor_name;
            $con_adr = $con_model->company_adr;
            $con_phone = $con_model->link_phone;
            ?>

            <!--        <div class="row" style="margin-top: 8px;">-->
            <!--            <div class="form-group">-->
            <!--                <label for="program_name" class="col-sm-1 offset-md-1 control-label padding-lr5" style="text-align: left">Ref No.</label>-->
            <!--                <div class="col-sm-3 padding-lr5">-->
            <!--                    <input id="check_no" name="rf[check_no]" class="form-control" style="background: #F2F2F2" type="text" value="--><?php //echo $check_list[0]['check_no'] ?><!--">-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->

            <div class="row" style="margin-top: 8px;border-bottom:4px solid #F2F2F2;padding-bottom:8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Ref no.<span style="color: #c12e2a">*</span></label>
                <div class="col-sm-5 padding-lr5" style="padding-bottom: 0;">
                    <select id="no_co" class="form-control" name="rf[no_co]" style="float: left;width:67px;" check-type="required" >
                        <option value='0' selected>-Co-</option>
                        <?php
                        if(!empty($no_list[A])){
                            foreach($no_list[A] as $cnt => $region){
                                if($region == $self_no_list[0]){
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
                                if($region == $self_no_list[1]){
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
                                if($region == $self_no_list[2]){
                                    echo "<option value='{$region}' selected>$region</option>";
                                }else{
                                    echo "<option value='{$region}' >$region</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                    <input type="text" class="form-control" id="no" name="rf[no]" value="<?php echo $self_no_list[4]; ?>" style="float: left;width:50px;margin-left: 2px;">
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">To*</label>
                <div class="col-sm-3 padding-lr5">
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
                        $apply_user = Staff::model()->findByPk($to_user);
                        $apply_contractor_id = $apply_user->contractor_id;
                        //                        echo "<option value=''>--Select Company--</option>";
                        //                        foreach ($contractor_list as $contractor_id => $contractor_name) {
                        //                            if($contractor_id == $apply_contractor_id){
                        //                                echo "<option value='{$contractor_id}' selected>{$contractor_name}</option>";
                        //                            }else{
                        //                                echo "<option value='{$contractor_id}'>{$contractor_name}</option>";
                        //                            }
                        //                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-5 padding-lr5" style="margin-left: 15px;">
                    <select id="to" name="rf[to]" class="form-control" multiple="multiple" >
                    </select>
                </div>
                <!--                <button type="button" class="btn btn-primary" style="background-color: #169BD5">Directory</button>-->
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Cc</label>
                <div class="col-sm-3 padding-lr5">
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
                    <select id="cc" name="rf[cc]" class="form-control" multiple="multiple" class="form-control">
                    </select>
                </div>
                <!--                <button type="button" class="btn btn-primary" style="background-color: #169BD5">Directory</button>-->
            </div>

            <div class="row" style="margin-top: 8px;">
                <div class="form-group">
                    <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Subject*</label>
                    <div class="col-sm-8 padding-lr5" style="margin-left: 15px;">
                        <input id="subject" class="form-control" name="rf[subject]" check-type="required" required-message="Project Name can not be empty"  type="text" value="<?php echo $item_list[0]['subject'] ?>" >
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Discipline</label>
                <?php if($item_list[0]['discipline'] == '1'){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="1" checked/>Structural
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="2" />Architecture
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="3" />M&E
                    </div>
                <?php }else if($item_list[0]['discipline'] == '2'){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="1" />Structural
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="2" checked/>Architecture
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="3" />M&E
                    </div>
                <?php }else if($item_list[0]['discipline'] == '3'){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="1" />Structural
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="2" />Architecture
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="3"  checked/>M&E
                    </div>
                <?php }else if($item_list[0]['discipline'] == ''){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="1" />Structural
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="2" />Architecture
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="discipline" name="rf[discipline]" type="radio" value="3" />M&E
                    </div>
                <?php } ?>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Regarding to</label>
                <?php if($item_data['regarding_to'] == '1'){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="1" checked/>Material / Sample / Data
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="2" />Document
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="3" />Shop Drawings
                    </div>
                <?php }else if($item_data['regarding_to'] == '2'){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="1" />Material / Sample / Data
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="2" checked/>Document
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="3" />Shop Drawings
                    </div>
                <?php }else if($item_data['regarding_to'] == '3'){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="1" />Material / Sample / Data
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="2" />Document
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="3" checked/>Shop Drawings
                    </div>
                <?php }else if($item_data['regarding_to'] == ''){ ?>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="1" />Material / Sample / Data
                    </div>
                    <div class="col-sm-3 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="2" />Document
                    </div>
                    <div class="col-sm-2 padding-lr5" style="text-align: left;">
                        <input id="regarding_to" name="item[regarding_to]" type="radio" value="3" />Shop Drawings
                    </div>
                <?php } ?>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Rvo</label>
                <div class="col-sm-8 padding-lr5" style="text-align: left;">
                    <?php
                    if($item_data['rvo'] == '1'){
                        ?>
                        <div class="col-sm-3 padding-lr5" style="text-align: left;">
                            <input type='radio' id='yes_rvo' name='item[rvo]'  value='1' checked   /> Yes
                        </div>
                        <div class="col-sm-3 padding-lr5" style="text-align: left;">
                            <input type='radio' id='no_rvo' name='item[rvo]'  value='2' style='padding-left: 4px;'  /> No
                        </div>
                        <?php
                    }else if($item_data['rvo'] == '2'){
                        ?>
                        <div class="col-sm-3 padding-lr5" style="text-align: left;">
                            <input type='radio' id='yes_rvo' name='item[rvo]'  value='1'   /> Yes
                        </div>
                        <div class="col-sm-3 padding-lr5" style="text-align: left;">
                            <input type='radio' id='no_rvo' name='item[rvo]'  value='2' style='padding-left: 4px;'  checked /> No
                        </div>
                    <?php }else{ ?>
                        <div class="col-sm-3 padding-lr5" style="text-align: left;">
                            <input type='radio' id='yes_rvo' name='item[rvo]'  value='1'  /> Yes
                        </div>
                        <div class="col-sm-3 padding-lr5" style="text-align: left;">
                            <input type='radio' id='no_rvo' name='item[rvo]'  value='2' style='padding-left: 4px;'  /> No
                        </div>
                    <?Php } ?>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left;text-decoration:underline">Matter:</label>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Attached Dwg Ref. No.</label>
                <div class="col-sm-8 padding-lr5" style="text-align: left;">
                    <input id="ref_no" class="form-control" name="item[ref_no]"   type="text" value="<?php echo $item_data['ref_no']; ?>" >
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Specifications Clause</label>
                <div class="col-sm-8 padding-lr5" style="text-align: left;">
                    <input id="clause" class="form-control" name="item[clause]"   type="text" value="<?php echo $item_data['clause']; ?>" >
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Description</label>
                <div class="col-sm-8 padding-lr5">
                    <textarea rows="10" id="description" name = "item[description]" style="width:100%"><?php echo $item_data['description']; ?></textarea>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Prepared By</label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="prepared_by" class="form-control" name="item[prepared_by]"   type="text" value="<?php echo $item_data['prepared_by']; ?>" >
                </div>
                <label for="program_name" class="col-sm-2  control-label padding-lr5" style="text-align: left">Subcon</label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="subcon" class="form-control" name="item[subcon]"   type="text" value="<?php echo $item_data['subcon']; ?>" >
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Verified By</label>
                <div class="col-sm-3 padding-lr5" style="text-align: left;">
                    <input id="verified_by" class="form-control" name="item[verified_by]"   type="text" value="<?php echo $item_data['verified_by']; ?>" >
                </div>
                <label for="program_name" class="col-sm-2  control-label padding-lr5" style="text-align: left">Latest Date to Reply</label>
                <div class="input-group col-sm-3 padding-lr5" style="padding-left: 5px;">
                    <?php
                    $Date = Utils::DateToEn($item_list[0]['valid_time']);
                    ?>
                    <input id="valid_time" class="form-control b_date_ins" style="background: #F2F2F2" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="rf[valid_time]" type="text" value="<?php echo $Date; ?>">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" style="text-align: left">Attachment</label>
                <div class="col-sm-8 padding-lr5">
                    <input id="file" multiple="multiple" class="form-control" check-type="" style="display:none" onchange="raupload(this)" name="File[file_path]" type="file" />
                    <button type="button" class="btn btn-primary" style="background-color: #169BD5" onclick="file.click()">Add</button>
                </div>
            </div>

            <div class="row" >
                <div for="program_name" class="col-sm-2 offset-md-1 control-label padding-lr5" ></div>
                <div class="col-sm-8 padding-lr5">
                    <table id="attach"  style="width: 70%">
                        <?php
                        foreach($attach_list as $i => $j){
                            $file_path = $j['doc_path'];
                            echo "<tr> <td align='left'><img src='img/attach.png'></td><td align='left'>".$j['doc_name']."</td> <td align='right' width='25%'><a onclick='previewdoc(\"$file_path\")'>Preview</a></td><td align='right' width='25%'><a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value=\"$file_path\"></td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>


            <div class="row" style="margin-top: 8px;margin-bottom: 50px; ">
                <div class="col-sm-12 padding-lr5" style="text-align: center">
                    <button id="save_btn" type="button" class="btn btn-primary" onclick="send()" >Send</button>
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
        $('#to_contractor_id').select2();
        $('#cc_contractor_id').select2();
        to_create_data('2');
        cc_create_data('2');
        showTime();
    })

    document.onmousedown=function(event){
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

    $("input").focus(function(){
        $('#save_btn').attr("disabled",false);
    });

    function changeview() {
        var template_id = $('#template_type').val();
        var program_id = $('#program_id').val();
        var type = $('#type_id').val();
        window.location = "index.php?r=rf/rf/changeview&program_id="+program_id+"&type="+type+"&form_id="+template_id;
    }

    function to_create_data(tag) {
        var contractor_id = $('#to_contractor_id').val();
        var check_id = $('#check_id').val();
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
        if(tag == '2'){
            $.ajax({
                url: "index.php?r=rf/rf/tolist",
                data: {check_id: check_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#cc').empty();
                    $('#to').select2({
                        data: res,  //返回的数据
                    });
                    var to_str = $("#to_user").val();
                    var to_arr = to_str.split( ',' );
                    console.log(to_arr);
                    $("#to").val(to_arr).trigger('change');
                }
            })
        }
    }
    cc_arr = [];
    function cc_create_data(tag) {
        var contractor_id = $('#cc_contractor_id').val();
        var check_id = $('#check_id').val();
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
        if(tag == '2'){
            $.ajax({
                url: "index.php?r=rf/rf/cclist",
                data: {check_id: check_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#cc').empty();
                    $('#cc').select2({
                        data: res,  //返回的数据
                    });
                    var cc_str = $("#cc_user").val();
                    var cc_arr = cc_str.split( ',' );
                    console.log(cc_arr);
                    $("#cc").val(cc_arr).trigger('change');
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

    //添加表单其他元素
    function send() {
        $('#save_btn').attr("disabled","disabled");
        var ccDesc = $("#cc").val();
        var toDesc = $("#to").val();
        var check_id = $('#check_id').val();
        var form_data = $('#form1').serialize();
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
                    window.location = "index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&type_id=1";
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
        var check_id = $('#check_id').val();
        $.ajax({
            data:$('#form1').serialize(),
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
                    window.location = "index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>";
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
    function cancel (program_id) {
        window.location = "index.php?r=rf/rf/list&program_id="+program_id;
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
