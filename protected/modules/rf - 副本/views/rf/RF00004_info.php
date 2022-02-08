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
<?php
    $rf_model = RfList::model()->findByPk($check_id);
    $form_id = $rf_model->form_id;
    $form_model = RfFormType::model()->findByPk($form_id);
    $apply_user_id = $rf_model->apply_user_id;
    $type = $rf_model->type;
    $step = $rf_model->current_step;
    $check_no = $rf_model->check_no;
    $status = $rf_model->status;
    $check_list = RfList::dealList($check_id);
    $detail_list = RfDetail::dealList($check_id);
    $type_list = RfDetail::typeList();
    $color_list = RfDetail::typecolorList();
    $link_check_id = $rf_model->link_check_id;
    $user_phone = Yii::app()->user->id;
    $user = Staff::userByPhone($user_phone);
    if(count($user)>0){
        $user_model = Staff::model()->findByPk($user[0]['user_id']);
        $user_id = $user_model->user_id;
    }else{
        $user_id = Yii::app()->user->id;
    }
    $other_to = RfUser::otherTo($check_id);
    $other_group = RfUser::otherGroup($check_id);
?>
<div class="container" style="padding-top: 8px;">
    <div class="row">
        <div class="col-1">
        </div>
        <div class="col-10" style="background-color: #F2F2F2;text-align: center">
            <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                <strong id='msginfo'></strong><span id='divMain'></span>
            </div>
            <div class="row" style="margin-top: 8px;">
                <div class="col-sm-1 control-label padding-lr5" style="margin-left: 15px;padding-top:7px;">
                    <button type="button" class="btn btn-primary" style="background-color: #FF9966;" onclick="back()">Back</button>
                </div>
                <div class="col-sm-10 control-label padding-lr5" style="float: right;padding-top:7px;">
                    <?php
                    $apply_user_id = $rf_model->apply_user_id;
                    $operator_id = Yii::app()->user->id;
                    $user = Staff::userByPhone($operator_id);
                    $detail = RfDetail::dealListByStep($check_id,$step);
                    $reply = RfUser::userList($check_id,$step,'1');
                    $type = RfUser::userListByRecord($check_id,$user[0]['user_id']);
                    //发起人
                    if($status == '0' || $status == '2'){
                        if($user[0]['user_id'] == $apply_user_id){
//                            if($user[0]['user_id'] == $reply[0]['user_id']){
//                                echo "<button type='button' class='btn btn-primary' style='background-color:#20B2AA;margin-right: 40px;' onclick='reply(\"$check_id\")'>Reply</button>";
//                            }

                            echo "<button type='button' class='btn btn-primary' style='margin-right:40px;background-color: #FF9966;' onclick='close_check(\"$check_id\")'>Mark as Closed</button>";
                        }
//                        else{
//                            if($user[0]['user_id'] == $reply[0]['user_id']){
//                                echo "<button type='button' class='btn btn-primary' style='background-color:#20B2AA;margin-right: 40px;' onclick='reply(\"$check_id\")'>Reply</button>";
//                            }
//                        }
                        if($type == '1'){
                            echo "<button type='button' class='btn btn-primary' style='background-color:#20B2AA;margin-right: 40px;' onclick='reply(\"$check_id\")'>Reply</button>";
                        }else if($type != '0'){
                            echo "<button type='button' class='btn btn-primary' style='background-color:#20B2AA;margin-right: 40px;' onclick='comment(\"$check_id\")'>Comment</button>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="row" style="margin-top: 8px;">
                <label for="program_name" class="col-sm-1 control-label padding-lr5" style="margin-left: 15px;">Ref No.</label>
                <div class="col-sm-5 padding-lr5" style="padding-top:7px;text-align: left">
                    <?php
                    echo $check_no;
                    ?>
                </div>
            </div>

            <?php
            foreach($detail_list as $m => $n){
                $remark = $n['remark'];
                $deal_type = $n['deal_type'];
                $deal_user_id = $n['user_id'];
                $deal_user = Staff::model()->findByPk($deal_user_id);
                $deal_user_name = $deal_user->user_name;
                $step = $n['step'];
                $rf_user_list = RfUser::userListByStep($check_id,$step);
                $contractor_id = Yii::app()->user->getState('contractor_id');
                $attach_list = RfRecordAttachment::dealListBystep($check_id,$step);
                $item_list = RfRecordItem::dealListBystep($check_id,$step);
                $item_data = json_decode($item_list[0]['item_data'],true);
                if($n['status'] == '1' || $n['status'] == '4'){
                    $apply_time = Utils::DateToEn($n['record_time']);
                    echo "<div class='panel panel-success' style='background-color: #F2F9FA;'>
                            <div class='row' style='margin-top: 8px;'>
                                <label for='rogram_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Type*</label>
                                <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    echo $form_model->form_name;
                    echo "</div><div class='col-sm-6 padding-lr5' style='padding-top:7px;text-align: left;'><span class='label label-default' style='float: right;margin-right: 42px;color:#333;background-color: $color_list[$deal_type]'>$type_list[$deal_type]</span><br><span style='float: right;margin-right: 42px;'>$apply_time</span></div></div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>From</label>
                            <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    $rf_model = RfList::model()->findByPk($check_id);
                    $apply_user_id = $rf_model->apply_user_id;
                    $user_model = Staff::model()->findByPk($apply_user_id);
                    $user_name = $user_model->user_name;
                    echo $user_name;
                    echo "</div></div>";

                    $group_id = $rf_model->group_id;
                    $rf_group = RfGroup::model()->findByPk($group_id);
                    $group_name = $rf_group->group_name;
                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>To $group_name</label>
                            <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    $to_user = '';
                    foreach($rf_user_list as $i => $j){
                        if($j['type'] == '1'){
                            $user_model = Staff::model()->findByPk($j['user_id']);
                            $user_name = $user_model->user_name;
                            $to_user.=$user_name.' ';
                        }
                    }
                    echo $to_user;
                    echo "</div></div>";

//                if($n['status'] == '1'){
//                    $group_id = $other_group[0]['group_id'];
//                    $rf_group = RfGroup::model()->findByPk($group_id);
//                    $group_name = $rf_group->group_name;
//                    echo "<div class='row' style='margin-top: 8px;'>
//                                    <div class='form-group'>
//                                        <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>To $group_name</label>
//                                        <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>";
//                    $other_to_user = '';
//                    foreach($other_to as $i => $j){
//                        if($j['type'] == '1'){
//                            $user_model = Staff::model()->findByPk($j['user_id']);
//                            $user_name = $user_model->user_name;
//                            $other_to_user.=$user_name.' ';
//                        }
//                    }
//                    echo $other_to_user;
//                    echo "</div></div></div>";
//                }

                    echo "<div class='row' style='margin-top: 8px;border-bottom:4px solid #F2F2F2;padding-bottom:8px;' >
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Cc</label>
                            <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    $cc_user = '';
                    foreach($rf_user_list as $i => $j){
                        if($j['type'] == '2'){
                            $user_model = Staff::model()->findByPk($j['user_id']);
                            $user_name = $user_model->user_name;
                            if($cc_user != ''){
                                $cc_user.=';';
                            }
                            $cc_user.=$user_name;
                        }
                    }
                    echo $cc_user;
                    echo "</div></div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Subject*</label>
                            <div class='col-sm-6 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    echo $item_list[0]['subject'];
                    echo "</div></div>";

                    echo "<div class='row' >
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Discipline</label>";
                    if($item_list[0]['discipline'] == '1'){
                        echo "
                                <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                                    <input  type='radio' disabled/>Architecture
                                </div>
                                <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                                    <input  type='radio'  checked='checked' disabled/>Structural
                                </div>
                                <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                                    <input  type='radio'  disabled/>M&E
                                </div>
                                ";
                    }else if($item_list[0]['discipline'] == '2'){
                        echo "
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio' checked='checked' disabled/>Architecture
                                </div>
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  disabled/>Structural
                                </div>
                                <div class='col-sm-2 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  disabled/>M&E
                                </div>
                                ";
                    }else if($item_list[0]['discipline'] == '3'){
                        echo "
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio' disabled/>Architecture
                                </div>
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  disabled/>Structural
                                </div>
                                <div class='col-sm-2 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  checked='checked' disabled/>M&E
                                </div>
                                ";
                    }
                    echo "</div>";

                    echo "<div class='row' >
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Regarding to</label>";
                    if($item_data['regarding_to'] == '1'){
                        echo "
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio' checked='checked' disabled/>Material / Sample / Data
                                </div>
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  disabled/>Document
                                </div>
                                <div class='col-sm-2 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  disabled/>Shop Drawings
                                </div>
                                ";
                    }else if($item_data['regarding_to'] == '2'){
                        echo "
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio' disabled/>Material / Sample / Data
                                </div>
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  checked='checked' disabled/>Document
                                </div>
                                <div class='col-sm-2 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  disabled/>Shop Drawings
                                </div>
                                ";
                    }else if($item_data['regarding_to'] == '3'){
                        echo "
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio' disabled/>Material / Sample / Data
                                </div>
                                <div class='col-sm-3 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  disabled/>Document
                                </div>
                                <div class='col-sm-2 padding-lr5' style='text-align: left;margin-top:5px;'>
                                    <input  type='radio'  checked='checked' disabled/>Shop Drawings
                                </div>
                                ";
                    }
                    echo "</div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Rvo</label>";
                    if($item_data['rvo'] == '1'){
                        echo "<div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>
                                <input type='radio' id='yes_rvo' name='rf[rvo]'  value='1' style='padding-left: 4px;' checked disabled > Yes
                          </div>
                          <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>         
                                <input type='radio' id='no_rvo' name='rf[rvo]'  value='2' style='padding-left: 4px;' disabled > No
                          </div>";
                    }else if($item_data['rvo'] == '2'){
                        echo "<div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>
                                <input type='radio' id='yes_rvo' name='rf[rvo]'  value='1' style='padding-left: 4px;'  disabled > Yes
                          </div>
                          <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>         
                                <input type='radio' id='no_rvo' name='rf[rvo]'  value='2' style='padding-left: 4px;' checked disabled > No
                          </div>";
                    }else{
                        echo "<div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>
                                <input type='radio' id='yes_rvo' name='rf[rvo]'  value='1' style='padding-left: 4px;'  disabled > Yes
                          </div>
                          <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>         
                                <input type='radio' id='no_rvo' name='rf[rvo]'  value='2' style='padding-left: 4px;'  disabled > No
                          </div>";
                    }
                    echo "</div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left;text-decoration:underline'>Matter:</label>
                      </div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Attached Dwg Ref. No.</label>
                            <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    echo $item_data['ref_no'];
                    echo "</div></div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Specifications Clause</label>
                            <div class='col-sm-3 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    echo $item_data['clause'];
                    echo "</div></div>";

                    $description = $item_data['description'];
                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Description</label>
                            <div class='col-sm-8 padding-lr5'>
                                <textarea rows='10' id='description' name = 'item[description]' style='width:100%' disabled>$description</textarea>
                            </div>
                            </div>
                          </div> ";

                    $prepared_by = $item_data['prepared_by'];
                    $subcon = $item_data['subcon'];
                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Prepared By</label>
                            <div class='col-sm-3 padding-lr5' style='padding-top: 7px;text-align: left;'>$prepared_by</div>
                            <label for='program_name' class='col-sm-2 control-label padding-lr5' style='text-align: left'>Subcon</label>
                            <div class='col-sm-3 padding-lr5' style='padding-top: 7px;text-align: left;'>$subcon</div>
                      </div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                                    <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Verified By</label>
                                        <div class=' col-sm-3 padding-lr5' style='padding-top: 7px;text-align: left;'>";
                    echo $item_data['verified_by'];
                    echo "</div>";
                    echo "<label for='program_name' class='col-sm-2 control-label padding-lr5' style='text-align: left'>Latest Date to Reply</label>
                            <div class=' col-sm-3 padding-lr5' style='padding-top: 7px;text-align: left;'>";
                    $rf_model = RfList::model()->findByPk($check_id);
                    $valid_time = $rf_model->valid_time;
                    $Date = Utils::DateToEn($item_list[0]['valid_time']);
                    echo $Date;
                    echo "</div></div>";


                    if(count($attach_list)>0){
                        $count = count($attach_list);
                    }else{
                        $count = 'N/A';
                    }
                    echo "<div class='row' style='margin-top: 8px;'>
                                    <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Attachment(".$count.")</label>
                                    <div class='col-sm-5 padding-lr5' style='padding-top:7px;text-align: left;'>";
                    if(count($attach_list)>0){
                        echo "<button type='button' class='btn btn-primary' onclick='zip(\"$check_id\",\"$step\")'>
                                            Download Zip
                                        </button>
                                        <button type='button' class='btn btn-primary' onclick='save_all(\"$check_id\",\"$step\")'>
                                            Save As
                                        </button>";
                    }
                    echo "            </div>
                             </div>";

                    echo "<div class='row' >
                                <table id='attach' class='offset-md-1' style='width: 70%'>";
                    foreach($attach_list as $i => $j){
                        $file_path = $j['doc_path'];
                        $doc_path = $j['doc_path'];
                        $doc_name = $j['doc_name'];
                        $doc_arr = explode('.',$j['doc_name']);
                        if($doc_arr[1] == 'dwg' || $doc_arr[1] == 'zip' || $doc_arr[1] == 'rar'){
                            echo "<tr><td align='left'><img src='img/attach.png'></td><td align='left'>$doc_name</td> <td align='center' width='15%'><a onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer'>Download</a></td><td align='center' width='15%'><a onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer'>Save as</a></td></tr>";
                        }else{
                            echo "<tr><td align='left'><img src='img/attach.png'></td><td align='left'>$doc_name</td> <td align='right' width='15%'><a onclick='previewdoc(\"$file_path\")' style='cursor:pointer'>Preview</a></td><td align='center' width='15%'><a onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer'>Download</a></td><td align='center' width='15%'><a onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer'>Save as</a></td></tr>";
                        }
                    }
                    echo "</table></div>";

                    echo "<div class='row' style='height:30px;'></div>";
                }else if($n['status'] == '3'){
                    $apply_time = Utils::DateToEn($n['record_time']);
                    echo "<div class='panel panel-success' style='background-color: #F2F9FA;'>
                            <div class='row' style='margin-top: 8px;'>
                                <label for='program_name' class='col-sm-3 control-label padding-lr5' style='text-align: center'>
                                    <span class='glyphicon glyphicon-user' aria-hidden='true'>$deal_user_name</span>
                                </label>
                                <label for='program_name' class='col-sm-1 control-label padding-lr5' style='text-align: left'>To*</label>
                                <div class='col-sm-3 padding-lr5' style='padding-top:7px;'>";
                    $to_user = '';
                    foreach($rf_user_list as $i => $j){
                        if($j['type'] == '1'){
                            $user_model = Staff::model()->findByPk($j['user_id']);
                            $user_name = $user_model->user_name;
                            $to_user.=$user_name.' ';
                        }
                    }
                    echo $to_user;
                    echo "</div>
                                <div class='col-sm-6 padding-lr5' style='padding-top:7px;'><span class='label label-default' style='float: right;margin-right: 42px;color: #333;background-color: $color_list[$deal_type]'>$type_list[$deal_type]</span><br><span style='float: right;margin-right: 42px;'>$apply_time</span>
                            </div>
                        </div>";

                    echo "<div class='row' style='margin-top: 8px;' >
                                <label for='program_name' class='col-sm-1 offset-md-3 control-label padding-lr5' style='text-align: left'>Cc</label>
                                <div class='col-sm-3 padding-lr5' style='padding-top:7px;'>";
                    $cc_user = '';
                    foreach($rf_user_list as $i => $j){
                        if($j['type'] == '2'){
                            $user_model = Staff::model()->findByPk($j['user_id']);
                            $user_name = $user_model->user_name;
                            if($cc_user != ''){
                                $cc_user.=';';
                            }
                            $cc_user.=$user_name;
                        }
                    }
                    echo $cc_user;
                    echo "</div></div>";

                    $params = $n['params'];
                    if($params != '') {
                        $params_arr = json_decode($params, true);
                        if(array_key_exists('valid_time',$params_arr)){
                            $params_arr = json_decode($params,true);
                            echo "<div class='row' style='margin-top: 8px;'>
                                <div class='form-group'>
                                <label for='program_name' class='col-sm-3 offset-md-3 control-label padding-lr5' style='text-align: left'>Latest Date to Reply</label>
                                    <div class='input-group col-sm-3 padding-lr5' style='padding-top: 7px;text-align: left;'>";
                            $Date = Utils::DateToEn($params_arr['valid_time']);
                            echo $Date;
                            echo "</div></div></div>";
                        }
                    }

                    if(count($attach_list)>0){
                        $count = count($attach_list);
                    }else{
                        $count = 'N/A';
                    }
                    echo "<div class='row' style='margin-top: 8px;'>
                            <div class='form-group'>
                                <label for='program_name' class='col-sm-2 offset-md-3 control-label padding-lr5' style='text-align: left'>Attachment(".$count.")</label>
                                <div class='col-sm-5 padding-lr5'>";

                    if(count($attach_list)>0){
                        echo "<button type='button' class='btn btn-primary' onclick='zip(\"$check_id\",\"$step\")'>
                                        Download Zip
                                    </button>
                                    <button type='button' class='btn btn-primary' onclick='save_all(\"$check_id\",\"$step\")'>
                                        Save As
                                    </button>";
                    }

                    echo "</div>
                        </div>";

                    echo "<div class='row' >
                            <table id='attach' class='offset-md-3' style='width: 60%'>";
                    foreach($attach_list as $i => $j){
                        $file_path = $j['doc_path'];
                        $doc_path = $j['doc_path'];
                        $doc_name = $j['doc_name'];
                        $doc_arr = explode('.',$j['doc_name']);
                        if($doc_arr[1] == 'dwg' || $doc_arr[1] == 'zip' || $doc_arr[1] == 'rar'){
                            echo "<tr> <td align='right'><img src='img/attach.png'></td><td align='left'>$doc_name</td> <td align='center' width='15%'><a onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer'>Download</a></td><td align='center' width='15%'><a onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer'>Save as</a></td></tr>";
                        }else{
                            echo "<tr> <td align='right'><img src='img/attach.png'></td><td align='left'>$doc_name</td> <td align='center' width='15%'><a onclick='previewdoc(\"$file_path\")' style='cursor:pointer'>Preview</a></td><td align='center' width='15%'><a onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer'>Download</a></td><td align='center' width='15%'><a onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer'>Save as</a></td></tr>";
                        }
                    }
                    echo " </table></div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-4 offset-md-3 control-label padding-lr5' style='text-align: left'>Message</label>
                        </div>

                        <div class='row' style='margin-top: 8px;'>
                            <div class='col-sm-8 offset-md-3 padding-lr5'>
                                <textarea rows='10' id='message' name = 'rf[message]' style='width:100%' disabled>$remark</textarea>
                            </div>
                        </div>

                    </div>";
                }else if($n['status'] == '5'){
                    $apply_time = Utils::DateToEn($n['record_time']);
                    echo "<div class='panel panel-success' style='background-color: #F2F9FA;'>
                            ";

                    if(count($attach_list)>0){
                        $count = count($attach_list);
                    }else{
                        $count = 'N/A';
                    }
                    echo "<div class='row' style='margin-top: 8px;'>
                                <label for='program_name' class='col-sm-3 control-label padding-lr5' style='text-align: center'>
                                    <span class='glyphicon glyphicon-user' aria-hidden='true'>$deal_user_name</span>
                                </label>
                                <label for='program_name' class='col-sm-2 control-label padding-lr5' style='text-align: left'>Attachment(".$count.")</label>
                                <div class='col-sm-5 padding-lr5'>";
                    if(count($attach_list)>0){
                        echo "<button type='button' class='btn btn-primary' onclick='zip(\"$check_id\",\"$step\")'>
                                        Download Zip
                                    </button>
                                    <button type='button' class='btn btn-primary' onclick='save_all(\"$check_id\",\"$step\")'>
                                        Save As
                                    </button>";
                    }

                    echo "</div>
                                <div class='col-sm-4 padding-lr5'><span class='label label-default' style='float: right;margin-right: 42px;color: #333;background-color: $color_list[$deal_type]'>$type_list[$deal_type]</span><br><span style='float: right;margin-right: 42px;'>$apply_time</span>
                            </div>
                        </div>";

                    echo "<div class='row' >
                            <table id='attach' class='offset-md-3' style='width: 60%'>";
                    foreach($attach_list as $i => $j){
                        $file_path = $j['doc_path'];
                        $doc_path = $j['doc_path'];
                        $doc_name = $j['doc_name'];
                        $doc_arr = explode('.',$j['doc_name']);
                        if($doc_arr[1] == 'dwg' || $doc_arr[1] == 'zip' || $doc_arr[1] == 'rar'){
                            echo "<tr> <td align='right'><img src='img/attach.png'></td><td align='left'>$doc_name</td> <td align='center' width='15%'><a onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer'>Download</a></td><td align='center' width='15%'><a onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer'>Save as</a></td></tr>";
                        }else{
                            echo "<tr> <td align='right'><img src='img/attach.png'></td><td align='left'>$doc_name</td> <td align='center' width='15%'><a onclick='previewdoc(\"$file_path\")' style='cursor:pointer'>Preview</a></td><td align='center' width='15%'><a onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer'>Download</a></td><td align='center' width='15%'><a onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer'>Save as</a></td></tr>";
                        }
                    }
                    echo " </table></div>";

                    echo "<div class='row' style='margin-top: 8px;'>
                            <label for='program_name' class='col-sm-4 offset-md-3 control-label padding-lr5' style='text-align: left'>Message</label>
                        </div>

                        <div class='row' style='margin-top: 8px;'>
                            <div class='col-sm-8 offset-md-3 padding-lr5'>
                                <textarea rows='10' id='message' name = 'rf[message]' style='width:100%' disabled>$remark</textarea>
                            </div>
                        </div>
                    </div>";
                }}
            ?>
        </div>
        <div class="col-1">
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

    })

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
            if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|doc|docx|xls|xlsx)$/.test(file.name)) {
                alert("Please upload document in either .gif, .jpeg, .jpg, .png, .doc, .xls, .xlsx, .docx or .pdf format.");
                return false;
            }
            ra_tag = file.name.lastIndexOf(".");
            ra_length = file.name.length;
            //获取后缀名
            ra_type=file.name.substring(ra_tag,ra_length);
            ra_name = file.name.substr(0,ra_tag);
            var video_src_file = file.name;
            containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
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
                                var $tr = $("<tr> <td  align='left' ><img  src='img/attach.png' ></td><td  align='left' >"+video_src_file+"</td> <td  align='right' width='25%'>"+"<a onclick='previewdoc(\""+value.file1+"')'>Preview</a></td>"+"<td  align='right' width='25%'><a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                            }else{
                                var $tr = $("<tr> <td  align='right'><img  src='img/attach.png' >"+video_src_file+"</td> <td  align='right'>"+"<a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
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

    //取消
    function cancel (program_id,type) {
        window.location = "index.php?r=rf/rf/list&program_id="+program_id+"&type_id=1";
    }
    //下载
    function download_one (doc_path,doc_name) {
        window.location = "index.php?r=rf/rf/download&doc_path="+doc_path+"&doc_name="+doc_name;
    }
    //下载压缩包
    function zip (check_id,step) {
        window.location = "index.php?r=rf/rf/zip&check_id="+check_id+"&step="+step;
    }

    function save_all(check_id,step) {
        var diag = new Dialog();
        diag.Width = 930;
        diag.Height = 980;
        diag.Title = "DMS";
        diag.URL = "showcomponent&step="+step+"&check_id="+check_id+"&login_program_id=<?php echo $login_program_id; ?>";
        diag.show();
    }
    function save_one(check_id,doc_path) {
        doc_path = encodeURIComponent(doc_path);
        var diag = new Dialog();
        diag.Width = 930;
        diag.Height = 980;
        diag.Title = "DMS";
        diag.URL = "showview&path="+doc_path+"&check_id="+check_id+"&login_program_id=<?php echo $login_program_id; ?>";
        diag.show();
    }
    //回复
    function reply(check_id) {
        window.location = "index.php?r=rf/rf/reply&check_id="+check_id;
    }
    //评论
    function comment(check_id) {
        window.location = "index.php?r=rf/rf/comment&check_id="+check_id;
    }
    //转发
    function forward(check_id) {
        var program_id = $('#program_id').val();
        var type_id = $('#type_id').val();
        window.location = "index.php?r=rf/rf/forward&check_id="+tag+"&program_id="+program_id+"&type="+type_id;
    }
    //关闭
    function close_check(check_id) {
        if (!confirm('Confirm to Close?' )) {
            return;
        }
        $.ajax({
            data:{check_id:check_id},
            url: "index.php?r=rf/rf/close",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                window.location = "index.php?r=rf/rf/info&check_id="+check_id;
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统超时');
                $('#msgbox').show();
            }
        });
    }
            //返回
    function back () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['rf/rf/list']; ?>";
    }
</script>
