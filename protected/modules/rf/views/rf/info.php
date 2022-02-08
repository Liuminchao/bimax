<style type="text/css">

    input.task_attach{
        position:absolute;
        right:0px;
        top:0px;
        opacity:0;
        filter:alpha(opacity=0);
        cursor:pointer;
        width:276px;
        height:36px;
        overflow: hidden;
    }
    .rows_wrap {
        position: relative;
    }
    .rows_wrap .rows_title {
        padding-left: 6px;
    }
    .rows_wrap .rows_content {
        float: right;
        padding-bottom: 30px;
        position: relative;
        width: 841px;
        padding-left: 5px;
        left:-230px;
    }
    /*.wrap>div {*/
        /*opacity: 0;*/
        /*padding: 10px;*/
    /*}*/

    @media screen and (max-width:640px) {
        button {
            width: 100%;
            margin: 2% auto;
            padding: 10px;
        }
    }
    .content{
        background:#C8D7D2
    }
</style>
<link rel="stylesheet" href="css/jquery.tag-editor.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
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
$upload_url = Yii::app()->params['upload_url'];//上传地址
?>

<div class="box-body">

    <div class="page-header">
        <small>Send</small>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Type*</label>
            <?php if($list_list[0]['type'] == '1' ){ ?>
                <span class="col-xs-6 col-sm-4" style="padding-top:8px">RFI</span>
            <?php }else{ ?>
                <span class="col-xs-6 col-sm-4" style="padding-top:8px">RFA</span>
            <?php } ?>
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Created on:</label>
            <span class="col-xs-6 col-sm-4" style="padding-top:8px"><?php echo Utils::DateToEn($list_list[0]['record_time']); ?></span>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label padding-lr5">Ref No: </label>
            <div class="col-sm-3 padding-lr5">
                <span class="col-md-12" style="padding-top:8px"><?php echo $list_list[0]['check_id']; ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label padding-lr5">To*</label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                    $to_user = RfUser::userList($list_list[0]['check_id'],$step,'1');
                    $to_usr_str = '';
                    foreach($to_user as $x => $y){
                        $to_usr_str.=$y['user_name'].' ';
                    }
                ?>
                <span class="col-md-12" style="padding-top:8px"><?php echo $to_usr_str; ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label padding-lr5">Cc</label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                    $cc_user = RfUser::userList($list_list[0]['check_id'],$step,'2');
                    $cc_usr_str = '';
                    foreach($cc_user as $i => $j){
                        $cc_usr_str.=$j['user_name'].' ';
                    }
                ?>
                <span class="col-md-12" style="padding-top:8px"><?php echo $cc_usr_str; ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Deadline</label>
            <div class="col-sm-3 padding-lr5">
                <span class="col-md-12" style="padding-top:8px"><?php echo Utils::DateToEn($list_list[0]['valid_time']); ?></span>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="form-group">
            <label for="title" class="col-sm-2 control-label padding-lr5">Subject</label>
            <div class="col-sm-3 padding-lr5">
                <span class="col-md-12" style="padding-top:8px"><?php echo $list_list[0]['subject']; ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Discipline</label>
            <div class="col-sm-3 padding-lr5">
                <span class="col-md-12" style="padding-top:8px"><?php echo $list_list[0]['discipline']; ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Attribute</label>
            <div class="col-sm-3 padding-lr5">
                <?php
                $attribute = explode(',',$list_list[0]['attribute']);
                foreach($attribute as $m => $n){
                    ?>
                    <span class="label label-info"><?php echo $n; ?></span>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row" >
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Attachment</label>
            <div class="col-sm-6 padding-lr5">
                <table id="attachment">
                    <?php
                    $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                    $attachment = RfAttachment::dealListBystep($list_list[0]['check_id'],$step);
                    if($attachment){
                        foreach($attachment as $i => $j){
                            ?>
                            <tr><td ><img  src='img/attach.png' ></td> <td ><?php echo $j['doc_name'] ?></td><td ><button type='button' onclick='preview("<?php echo $j['doc_path'] ?>")'>Preview</button></td><td  ><button type='button' onclick='download("<?php echo $j['doc_path'] ?>","<?php echo $j['doc_name'] ?>")'>Download</button></td></tr>
                        <?php }
                    } ?>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Viewpoin & component</label>
            <div class="col-sm-6 padding-lr5" id="J-sd-demo">
                <table >
                    <?php
                    $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                    $view_list = RfModelView::dealList($list_list[0]['check_id'],$step);
                    $model_view_id = $view_list[0]['model_id'];
                    $model_view = $view_list[0]['view'];
                    $component_list = RfModelComponent::dealList($list_list[0]['check_id'],$step);
                    $model_component_id = $component_list[0]['model_id'];
                    $model_uuid = $component_list[0]['uuid'];
                    ?>
                    <tr><td ><img  src="img/pic_min.png" ></td> <td   align="left">Viewpoint</td> <td  ><button type="button"  onclick="show_view('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $step; ?>')">Preview</button></td> <td  ></td></tr>
                    <tr><td ><img  src="img/component_min.png" ></td> <td   align="left">Component Name</td> <td  ><button type="button" onclick="show_component('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $step; ?>')">Preview</button></td> <td  ></td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="worker_type" class="col-sm-2 control-label padding-lr5">Message</label>
            <div class="col-sm-3 padding-lr5">
                <?php
                    $step = RfDetail::stepByType($list_list[0]['check_id'],'1');
                    $detail_list = RfDetail::dealListByStep($list_list[0]['check_id'],$step);
                ?>
                <span class="col-md-12" style="padding-top:8px"><?php echo $detail_list[0]['remark']; ?></span>
            </div>
        </div>
    </div>


    <?php
        $detail = RfDetail::dealList($list_list[0]['check_id']);
        foreach($detail as $i => $j) {
             if($j['deal_type'] != '0' &&  $j['deal_type'] != '1' &&  $j['deal_type'] != '7' &&  $j['deal_type'] != '8') {
                ?>
                 <div class="page-header">
                     <small><?php  echo RfDetail::statusText($j['deal_type']); ?></small>
                 </div>
                <?php
                $to_user = RfUser::userList($list_list[0]['check_id'],$j['step'],'1');
                $to_usr_str = '';
                foreach($to_user as $x => $y){
                    $to_usr_str.=$y['user_name'].' ';
                }
                if($to_usr_str){
                ?>
                <div class="row">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label padding-lr5">To*</label>
                        <div class="col-sm-3 padding-lr5">
                            <span class="col-md-12" style="padding-top:8px"><?php echo $to_usr_str; ?></span>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php
                $cc_user = RfUser::userList($list_list[0]['check_id'],$j['step'],'2');
                $cc_usr_str = '';
                foreach($cc_user as $i => $o){
                    $cc_usr_str.=$o['user_name'].' ';
                }
                if($cc_user_str){
                ?>
                <div class="row">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label padding-lr5">Cc</label>
                        <div class="col-sm-3 padding-lr5">
                            <span class="col-md-12" style="padding-top:8px"><?php echo $cc_usr_str; ?></span>
                        </div>
                    </div>
                </div>
                <?php } ?>

                 <div class="row">
                     <div class="form-group">
                         <label for="worker_type" class="col-sm-2 control-label padding-lr5">Attachment</label>
                         <div class="col-sm-6 padding-lr5">
                             <table id="attachment">
                                 <?php
                                 $attachment = RfAttachment::dealListBystep($list_list[0]['check_id'],$j['step']);
                                 foreach($attachment as $i => $t){
                                     ?>
                                     <tr><td ><img  src='img/attach.png' ></td> <td ><?php echo $t['doc_name'] ?></td><td ><button type='button' onclick='preview("<?php echo $t['doc_path'] ?>")'>Preview</button></td><td  ><button type='button' onclick='download("<?php echo $t['doc_path'] ?>","<?php echo $t['doc_name'] ?>")'>Download</button></td></tr>
                                 <?php } ?>
                             </table>
                         </div>
                     </div>
                 </div>

                <div class="row">
                    <div class="form-group">
                        <label for="worker_type" class="col-sm-2 control-label padding-lr5">Viewpoin & component</label>
                        <div class="col-sm-6 padding-lr5" id="J-sd-demo">
                            <table >
                                <?php
                                $view_list = RfModelView::dealList($list_list[0]['check_id'],$j['step']);
                                $model_view_id = $view_list[0]['model_id'];
                                $model_view = $view_list[0]['view'];
                                $component_list = RfModelComponent::dealList($list_list[0]['check_id'],$step);
                                $model_component_id = $component_list[0]['model_id'];
                                $model_uuid = $component_list[0]['uuid'];
                                ?>
                                <tr><td ><img  src="img/pic_min.png" ></td> <td   align="left">Viewpoint</td> <td  ><button type="button"  onclick="show_view('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $j['step']; ?>')">Preview</button></td> <td  ></td></tr>
                                <tr><td ><img  src="img/component_min.png" ></td> <td   align="left">Component Name</td> <td  ><button type="button" onclick="show_component('<?php  echo $list_list[0]['check_id']; ?>','<?php  echo $j['step']; ?>')">Preview</button></td> <td  ></td></tr>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if($j['remark']){ ?>
                     <div class="row">
                         <div class="form-group">
                             <label for="worker_type" class="col-sm-2 control-label padding-lr5">Message</label>
                             <div class="col-sm-3 padding-lr5">
                                 <span class="col-md-12" style="padding-top:8px"><?php echo $j['remark']; ?></span>
                             </div>
                         </div>
                     </div>
                    <?php } ?>
                <?php } ?>
            <?php if($j['deal_type'] == '7' || $j['deal_type'] == '8'){ ?>
                <div class="page-header">
                    <small><?php  echo RfDetail::statusText($j['deal_type']); ?></small>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="worker_type" class="col-sm-2 control-label padding-lr5">Operator</label>
                        <div class="col-sm-3 padding-lr5">
                            <span class="col-md-12" style="padding-top:8px"><?php $info = Staff::userByPhone($operator_id); echo $j['user_id']; ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
</div>

<?php if($list_list[0]['status'] == '2' || $list_list[0]['status'] == '3' || $list_list[0]['status'] == '4'){ ?>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" id="sbtn" class="btn btn-primary"
                    style="margin-left: 5px; background: #44ACB7" onclick="cancel('<?php echo $list_list[0]['program_id']; ?>');">Back</button>
        </div>
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" id="sbtn" class="btn btn-primary"
                    style="margin-left: 5px; background: #44ACB7" onclick="cancel('<?php echo $list_list[0]['program_id']; ?>');">Back</button>
            <?php if($list_list[0]['status'] == '0' || $list_list[0]['status'] == '1' ){ ?>
            <?php
            $operator_id = Yii::app()->user->id;
            if($operator_id == $list_list[0]['add_user'] && $list_list[0]['type'] == '2'){
                ?>
                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 25px; background: #44ACB7" onclick="withdraw('<?php echo $list_list[0]['check_id']; ?>');">Whithdraw</button>
            <?php } ?>
            <?php
            if($operator_id == $list_list[0]['add_user'] && $list_list[0]['type'] == '1'){
                ?>
                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 45px; background: #44ACB7" onclick="closelist('<?php echo $list_list[0]['check_id']; ?>');">Close</button>

                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 65px; background: #44ACB7" onclick="withdraw('<?php echo $list_list[0]['check_id']; ?>');">Whithdraw</button>
            <?php } ?>
            <?php
            $operator_id = Yii::app()->user->id;
            $authority = RfList::permissionsInfo($list_list[0]['check_id'],$operator_id);
            if($authority['tag'] == '1'){
                ?>
                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 25px; background: #44ACB7" onclick="forward('<?php echo $list_list[0]['check_id']; ?>');">Forward</button>
                <button type="button" class="btn btn-primary"
                        style="margin-left: 45px; background: #44ACB7" onclick="reply('<?php echo $list_list[0]['check_id']; ?>');">Reply</button>
            <?php }else if($authority['tag'] == '2'){ ?>
                <button type="button" id="sbtn" class="btn btn-primary"
                        style="margin-left: 25px; background: #44ACB7" onclick="forward('<?php echo $list_list[0]['check_id']; ?>');">Forward</button>
                <button type="button" class="btn btn-primary"
                        style="margin-left: 45px; background: #44ACB7" onclick="reject('<?php echo $list_list[0]['check_id']; ?>');">Reject</button>
                <button type="button" class="btn btn-primary"
                        style="margin-left: 65px; background: #44ACB7" onclick="approve('<?php echo $list_list[0]['check_id']; ?>');">Approve</button>
                <button type="button" class="btn btn-primary"
                        style="margin-left: 85px; background: #44ACB7" onclick="approve_comment('<?php echo $list_list[0]['check_id']; ?>');">Approve with Comment</button>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>

<?php $this->endWidget(); ?>

<!--<iframe id="attendIframe" style="margin: 0 auto;" name="attendIframe"  frameborder="0" class="iframe_r" src="http://localhost/ctmgr/index.php?r=rf/rfi/demo" style="height:200px;;width:100%; background-color:#fff;"></iframe>-->

<script src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/javascript">
    $(function() {

    });
    window.onload = function () {

    }
    //预览
    function preview (path) {
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&file="+path,"_blank");
        }else{
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }
    }
    //下载
    function download (path,name) {
        window.location = "index.php?r=rf/rf/download&doc_path="+path+"&doc_name="+name;
    }
    function show_component(check_id,step) {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 580;
        diag.Title = "Model Component";
        diag.URL = "showcomponent&step="+step+"&check_id="+check_id;
        diag.show();
    }

    function show_view(check_id,step) {
        var diag = new Dialog();
        diag.Width = 800;
        diag.Height = 580;
        diag.Title = "Model View";
        diag.URL = "showview&step="+step+"&check_id="+check_id;
        diag.show();
    }
    //转发
    function forward (check_id) {
        window.location = "index.php?r=rf/rf/forward&check_id="+check_id;
    }
    //取消
    function cancel (program_id) {
        window.location = "index.php?r=rf/rf/list&program_id="+program_id;
    }
    //回复
    function reply (check_id) {
        window.location = "index.php?r=rf/rf/reply&check_id="+check_id;
    }
    //拒绝
    function reject (check_id) {
        window.location = "index.php?r=rf/rf/reject&check_id="+check_id;
    }
    //批准
    function approve (check_id) {
        if (!confirm('Proceed to approve?')) {
            return;
        }
        window.location = "index.php?r=rf/rf/approve&check_id="+check_id;
    }
    //批准(带评论)
    function approve_comment (check_id) {
        window.location = "index.php?r=rf/rf/approvecomment&check_id="+check_id;
    }

    //撤销
    function withdraw(check_id) {
        $.ajax({
            data:{check_id:check_id},
            url: "index.php?r=rf/rf/withdraw",
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
    //关闭
    function closelist(check_id) {
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