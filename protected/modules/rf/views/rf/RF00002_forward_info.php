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
$type = $rf_model->type;
$step = $rf_model->current_step;
$check_no = $rf_model->check_no;
$status = $rf_model->status;
$submission = $rf_model->submission;
$check_list = RfList::dealList($check_id);
$detail_list = RfDetail::dealList($check_id);
$type_list = RfDetail::typeList();
$link_check_id = $rf_model->link_check_id;
$other_to = RfUser::otherTo($check_id);
$color_list = RfDetail::typecolorList();
$other_group = RfUser::otherGroup($check_id);
echo "<div class='row' style='margin-top: 8px;'>
        <label for='program_name' class='col-sm-1 control-label padding-lr5' >Ref No.</label>
        <div class='col-sm-5 padding-lr5' style='text-align: left'>
            $check_no
        </div>
      </div>";
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
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>";
        if ($n == '1'){
            echo 'Request for Approval';
        }else{
            echo 'Request for Review';
        }
        echo "</div><div class='col-sm-6 padding-lr5' style='text-align: left;'><span class='badge bg-default' style='float: right;color:#333;background-color: $color_list[$deal_type]'>$type_list[$deal_type]</span><br><span style='float: right;'>$apply_time</span></div></div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>From</label>
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>";
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
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>";
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

//        if($n['status'] == '1'){
//            $group_id = $other_group[0]['group_id'];
//            $rf_group = RfGroup::model()->findByPk($group_id);
//            $group_name = $rf_group->group_name;
//            echo "<div class='row' style='margin-top: 8px;'>
//                                <div class='form-group'>
//                                    <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>To $group_name</label>
//                                    <div class='col-sm-3 padding-lr5' style='text-align: left;'>";
//            $other_to_user = '';
//            foreach($other_to as $i => $j){
//                if($j['type'] == '1'){
//                    $user_model = Staff::model()->findByPk($j['user_id']);
//                    $user_name = $user_model->user_name;
//                    $other_to_user.=$user_name.' ';
//                }
//            }
//            echo $other_to_user;
//            echo "</div></div></div>";
//        }

        echo "<div class='row' style='margin-top: 8px;border-bottom:4px solid #F2F2F2;padding-bottom:8px;' >
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Cc</label>
                <div class='col-sm-8 padding-lr5' style='text-align: left;'>";
        $cc_user = '';
        foreach($rf_user_list as $i => $j){
            if($j['type'] == '2'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $user_name = $user_model->user_name;
                $cc_user.=$user_name.' ';
            }
        }
        echo $cc_user;
        echo "</div></div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Submission</label>
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>";
        if($submission == '1'){
            echo '1st Submission';
        }else if($submission == '2'){
            echo '2nd Submission';
        }else if($submission == '3'){
            echo '3nd Submission';
        }
        echo "</div></div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Subject*</label>
                <div class='col-sm-6 padding-lr5' style='text-align: left;'>";
        echo $item_list[0]['subject'];
        echo "</div></div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Rvo</label>";
        if($item_data['rvo'] == '1'){
            echo "
                    <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                        <input type='radio'  style='padding-left: 4px;' checked disabled > Yes
                    </div>
                    <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                        <input type='radio'  style='padding-left: 4px;' disabled > No
                    </div>
                    <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                    </div> ";
        }else if($item_data['rvo'] == '2'){
            echo "
                    <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                        <input type='radio'  style='padding-left: 4px;' disabled > Yes
                    </div>
                    <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                        <input type='radio'  style='padding-left: 4px;' checked disabled > No
                    </div>
                    <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                    </div> ";
        }
        echo "</div>";

        echo "<div class='row' style='margin-top: 8px;'>
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
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                    <input  type='radio' checked='checked' disabled/>Architecture
                </div>
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                    <input  type='radio'  disabled/>Structural
                </div>
                <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                    <input  type='radio'  disabled/>M&E
                </div>
                ";
        }else if($item_list[0]['discipline'] == '3'){
            echo "
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                    <input  type='radio' disabled/>Architecture
                </div>
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>
                    <input  type='radio'  disabled/>Structural
                </div>
                <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                    <input  type='radio'  checked='checked' disabled/>M&E
                </div>
                ";
        }
        echo "</div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Type of service</label>
                <div class='col-sm-3 padding-lr5' style='text-align: left;'>";
        echo $item_data['service'];
        echo "</div></div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Latest Date to Reply</label>
                <div class='input-group col-sm-3 padding-lr5' style='text-align: left;'>";
        $rf_model = RfList::model()->findByPk($check_id);
        $valid_time = $rf_model->valid_time;
        $Date = Utils::DateToEn($item_list[0]['valid_time']);
        echo $Date;
        echo "</div></div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>Trade</label>
                <div class='input-group col-sm-3 padding-lr5' style='text-align: left;'>";
        $trade_list = RfGroup::tradeList();
        $trade_name = $trade_list[$item_data['trade']];
        echo $trade_name;
        echo "</div></div>";

        $mas_spc = $item_data['mas_spc'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Masterial/Equipment Specified</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $mas_spc
                </div>
        </div>";
        $loc_ins = $item_data['loc_ins'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Location To Be Installed</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $loc_ins
                </div>
              </div>";
        $mas_sub = $item_data['mas_sub'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Masterial/Equipment Submitted</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $mas_sub
                </div>
              </div>";
        $supplier = $item_data['supplier'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Supplier</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $supplier
                </div>
            </div>";
        $hdb_sup = $item_data['hdb_sup'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>HDB List of Approved Supplier</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $hdb_sup
                </div>
            </div>";
        $brand = $item_data['brand'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Manufacturer/Brand</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $brand
                </div>
              </div>";
        $model = $item_data['model'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Model / Type</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $model
                </div>
              </div>";
        $origin = $item_data['origin'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Country of Origin</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $origin
                </div>
              </div>";
        $comp_spec = $item_data['comp_spec'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Complaince With Specifications/standard</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $comp_spec
                </div>
              </div>";
        $tech_sub = $item_data['tech_sub'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Technical Brochures Submitted</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $tech_sub
                </div>
              </div>";
        $cert_sub = $item_data['cert_sub'];
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Test Certificate Submitted</label>
                <div class='col-sm-4 padding-lr5' style='text-align: left;'>
                    $cert_sub
                </div>
              </div>";
        if(count($attach_list)>0){
            $count = count($attach_list);
        }else{
            $count = 'N/A';
        }
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-5 offset-md-1 control-label padding-lr5' style='text-align: left'>Attachment(".$count.")</label>";
        if(count($attach_list)>0){
            echo "<div class='col-sm-2 padding-lr5' style='text-align: left;'><a onclick='zip(\"$check_id\",\"$step\")'>
                    <h3 style='cursor:pointer;color: #4a86e8'>Download Zip</h3>
                </a></div>
                <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                <a onclick='save_all(\"$check_id\",\"$step\")'>
                    <h3 style='cursor:pointer;color: #4a86e8'>Save As</h3>
                </a></div>";
        }
        echo "</div>";

        echo "<div class='row'  id='attach' >";
        foreach($attach_list as $i => $j){
            $file_path = $j['doc_path'];
            $doc_path = $j['doc_path'];
            $doc_name = $j['doc_name'];
            $doc_arr = explode('.',$j['doc_name']);
            if($doc_arr[1] == 'dwg' || $doc_arr[1] == 'zip' || $doc_arr[1] == 'rar'){
                echo "<div class='row col-12 ' style='margin-top: 8px;'><div class='col-sm-1 padding-lr5'></div><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'><img src='img/attach.png'>$doc_name</div><div class='col-sm-2 padding-lr5' style='text-align: left'><a  class='a_logo' onclick='download_one(\"$doc_path\",\"$doc_name\")'  style='cursor:pointer;'  title='Download'><i class='fa fa-fw fa-download'></i></a><a  class='a_logo' style='cursor:pointer;margin-left: 5px;' onclick='save_one(\"$check_id\",\"$doc_path\")'><i class='fa fa-fw fa-save' title='Save as'></i></a></div></div>";
            }else{
                echo "<div class='row col-12 ' style='margin-top: 8px;'><div class='col-sm-1 padding-lr5'></div><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'><img src='img/attach.png'>$doc_name</div><div class='col-sm-2 padding-lr5' style='text-align: left'><a  class='a_logo' onclick='previewdoc(\"$file_path\")' style='cursor:pointer' title='Preview'><i class='fa fa-fw fa-eye'></i></a><a  class='a_logo' onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer;margin-left: 5px;' title='Download'><i class='fa fa-fw fa-download'></i></a><a class='a_logo' onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer;margin-left: 5px;' title='Save as'><i class='fa fa-fw fa-save'></i></a></div></div>";
            }
        }
        echo "</div>";

        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-4 offset-md-1 control-label padding-lr5' style='text-align: left'>Message</label>
              </div>

              <div class='row' style='margin-top: 8px;'>
                <div class='col-sm-10 offset-md-1 padding-lr5'>
                    <textarea rows='10' id='message' name = 'rf[message]' style='width:100%' disabled>$remark</textarea>
                </div>
              </div>
        </div>";
        echo "<div class='row' style='height:30px;'></div>";
    }else if($n['status'] == '3'){
        $apply_time = Utils::DateToEn($n['record_time']);
        echo "<div class='panel panel-success' style='background-color: #F2F9FA;'>
                <div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>
                    <i class='fa fa-fw fa-user' ></i>$deal_user_name
                </label>
                <label for='program_name' class='col-sm-2 control-label padding-lr5' style='text-align: left'>To*</label>
                <div class='col-sm-3 padding-lr5' style='text-align: left'>";
        $to_user = '';
        foreach($rf_user_list as $i => $j){
            if($j['type'] == '1'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $user_name = $user_model->user_name;
                $to_user.=$user_name.' ';
            }
        }
        echo $to_user;
        echo "</div><div class='col-sm-4 padding-lr5' style=''>
                        <span class='badge bg-default' style='float: right;color: #333;background-color: $color_list[$deal_type]'>$type_list[$deal_type]</span><br><span style='float: right;'>$apply_time</span>
                    </div>
                </div>";

        echo "<div class='row' style='margin-top: 8px;' >
                <label for='program_name' class='col-sm-2 offset-md-3 control-label padding-lr5' style='text-align: left'>Cc</label>
                <div class='col-sm-7 padding-lr5' style='text-align: left'>";
        $cc_user = '';
        foreach($rf_user_list as $i => $j){
            if($j['type'] == '2'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $user_name = $user_model->user_name;
                $cc_user.=$user_name.' ';
            }
        }
        echo $cc_user;
        echo "</div></div>";

        $params = $n['params'];
        if($params != '') {
            $params_arr = json_decode($params, true);
            if(array_key_exists('rvo',$params_arr)){
                echo "<div class='row' style='margin-top: 8px;'>
                        <label for='program_name' class='col-sm-2 offset-md-3 control-label padding-lr5' style='text-align: left'>RVO</label>
                        <div class='col-sm-3 padding-lr5' style='text-align: left'>";
                if($params_arr['rvo'] == '1'){
                    echo "<input type='radio'  style='padding-left: 4px;' checked disabled > Approve
                          <input type='radio' style='padding-left: 4px;' disabled > Reject
                          <input type='radio'  style='padding-left: 4px;' disabled > NA";
                }else if($params_arr['rvo'] == '2'){
                    echo "<input type='radio'  style='padding-left: 4px;' disabled > Approve
                          <input type='radio'  style='padding-left: 4px;' checked disabled > Reject
                          <input type='radio'  style='padding-left: 4px;' disabled > NA";
                }else{
                    echo "<input type='radio'  style='padding-left: 4px;' disabled > Approve
                          <input type='radio'  style='padding-left: 4px;' disabled > Reject
                          <input type='radio'  style='padding-left: 4px;' checked disabled > NA";
                }
                echo "</div></div>";
            }
        }

        if(count($attach_list)>0){
            $count = count($attach_list);
        }else{
            $count = 'N/A';
        }
        echo "<div class='row' style='margin-top: 8px;'>
                <label for='program_name' class='col-sm-2 offset-md-3 control-label padding-lr5' style='text-align: left'>Attachment(".$count.")</label>";

        if(count($attach_list)>0){
            echo "<div class='col-sm-2 padding-lr5' style='text-align: left;'><a onclick='zip(\"$check_id\",\"$step\")'>
                    <h3 style='cursor:pointer;color: #4a86e8'>Download Zip</h3>
                </a></div>
                <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                <a onclick='save_all(\"$check_id\",\"$step\")'>
                    <h3 style='cursor:pointer;color: #4a86e8'>Save As</h3>
                </a></div>";
        }

        echo "</div>";

        echo "<div class='row'  id='attach' >";
        foreach($attach_list as $i => $j){
            $file_path = $j['doc_path'];
            $doc_path = $j['doc_path'];
            $doc_name = $j['doc_name'];
            $doc_arr = explode('.',$j['doc_name']);
            if($doc_arr[1] == 'dwg' || $doc_arr[1] == 'zip' || $doc_arr[1] == 'rar'){
                echo "<div class='row col-12 offset-md-1' style='margin-top: 8px;'><div class='col-sm-1 offset-md-1 padding-lr5'></div><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'><img src='img/attach.png'>$doc_name</div><div class='col-sm-2 padding-lr5' style='text-align: left'><a  class='a_logo' onclick='download_one(\"$doc_path\",\"$doc_name\")'  style='cursor:pointer;'  title='Download'><i class='fa fa-fw fa-download'></i></a><a  class='a_logo' style='cursor:pointer;margin-left: 5px;' onclick='save_one(\"$check_id\",\"$doc_path\")'><i class='fa fa-fw fa-save' title='Save as'></i></a></div></div>";
            }else{
                echo "<div class='row col-12 offset-md-1' style='margin-top: 8px;'><div class='col-sm-1 offset-md-1 padding-lr5'></div><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'><img src='img/attach.png'>$doc_name</div><div class='col-sm-2 padding-lr5' style='text-align: left'><a  class='a_logo' onclick='previewdoc(\"$file_path\")' style='cursor:pointer' title='Preview'><i class='fa fa-fw fa-eye'></i></a><a  class='a_logo' onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer;margin-left: 5px;' title='Download'><i class='fa fa-fw fa-download'></i></a><a class='a_logo' onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer;margin-left: 5px;' title='Save as'><i class='fa fa-fw fa-save'></i></a></div></div>";
            }
        }
        echo "</div>";

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
                            <label for='program_name' class='col-sm-2 offset-md-1 control-label padding-lr5' style='text-align: left'>
                                <i class='fa fa-fw fa-user' ></i>$deal_user_name
                            </label>
                            <label for='program_name' class='col-sm-2 control-label padding-lr5' style='text-align: left'>Attachment(".$count.")</label>";
        if(count($attach_list)>0){
            echo "<div class='col-sm-2 padding-lr5' style='text-align: left;'><a onclick='zip(\"$check_id\",\"$step\")'>
                    <h3 style='cursor:pointer;color: #4a86e8'>Download Zip</h3>
                </a></div>
                <div class='col-sm-2 padding-lr5' style='text-align: left;'>
                <a onclick='save_all(\"$check_id\",\"$step\")'>
                    <h3 style='cursor:pointer;color: #4a86e8'>Save As</h3>
                </a></div>";
        }

        echo "<div class='col-sm-3 padding-lr5' style='padding-top:7px;'><span class='badge bg-default' style='float: right;color: #333;background-color: $color_list[$deal_type]'>$type_list[$deal_type]</span><br><span style='float: right;'>$apply_time</span></div>
                        </div>";

        echo "<div class='row'  id='attach' >";
        foreach($attach_list as $i => $j){
            $file_path = $j['doc_path'];
            $doc_path = $j['doc_path'];
            $doc_name = $j['doc_name'];
            $doc_arr = explode('.',$j['doc_name']);
            if($doc_arr[1] == 'dwg' || $doc_arr[1] == 'zip' || $doc_arr[1] == 'rar'){
                echo "<div class='row col-12 offset-md-1' style='margin-top: 8px;'><div class='col-sm-1 offset-md-1 padding-lr5'></div><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'><img src='img/attach.png'>$doc_name</div><div class='col-sm-2 padding-lr5' style='text-align: left'><a  class='a_logo' onclick='download_one(\"$doc_path\",\"$doc_name\")'  style='cursor:pointer;'  title='Download'><i class='fa fa-fw fa-download'></i></a><a  class='a_logo' style='cursor:pointer;margin-left: 5px;' onclick='save_one(\"$check_id\",\"$doc_path\")'><i class='fa fa-fw fa-save' title='Save as'></i></a></div></div>";
            }else{
                echo "<div class='row col-12 offset-md-1' style='margin-top: 8px;'><div class='col-sm-1 offset-md-1 padding-lr5'></div><div class='col-sm-6 padding-lr5' style='text-align: left;padding-left: 0px;'><img src='img/attach.png'>$doc_name</div><div class='col-sm-2 padding-lr5' style='text-align: left'><a  class='a_logo' onclick='previewdoc(\"$file_path\")' style='cursor:pointer' title='Preview'><i class='fa fa-fw fa-eye'></i></a><a  class='a_logo' onclick='download_one(\"$doc_path\",\"$doc_name\")' style='cursor:pointer;margin-left: 5px;' title='Download'><i class='fa fa-fw fa-download'></i></a><a class='a_logo' onclick='save_one(\"$check_id\",\"$doc_path\")' style='cursor:pointer;margin-left: 5px;' title='Save as'><i class='fa fa-fw fa-save'></i></a></div></div>";
            }
        }
        echo "</div>";

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
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
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
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }

    }

    //取消
    function cancel (program_id,type) {
        window.location = "index.php?r=rf/rf/list&program_id="+program_id+"&type_id="+type;
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
    //转发
    function forward(check_id) {
        var program_id = $('#program_id').val();
        var type_id = $('#type_id').val();
        window.location = "index.php?r=rf/rf/forward&check_id="+check_id+"&program_id="+program_id+"&type="+type_id;
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
</script>
