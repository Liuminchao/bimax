<?php
$t->echo_nogrid_header();

if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    $block_user = TaskBlockPerson::queryUser($args);
    foreach ($rows as $i => $row) {
        $user_id = $row['user_id'];
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $user_model = Staff::model()->findByPk($row['user_id']);
        $user_name = $user_model->user_name;
        if(array_key_exists($row['user_id'],$block_user)){
            $t->echo_td($user_name);
            if($block_user[$row['user_id']]['web_view'] == '1'){
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][web_view]\" checked>","center");
            }else{
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][web_view]\">","center");
            }
            if($block_user[$row['user_id']]['web_edit'] == '1'){
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][web_edit]\" checked>","center");
            }else{
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][web_edit]\">","center");
            }
            if($block_user[$row['user_id']]['app'] == '1'){
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][app]\" checked>","center");
            }else{
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][app]\">","center");
            }
        }else{
            if($args['block']){
                $t->echo_td($user_name);
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][web_view]\">","center");
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][web_edit]\">","center");
                $t->echo_td("<input type=\"checkbox\" name=\"Person[$user_id][app]\">","center");
            }
        }
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-3">
        <div class="dataTables_info" id="example2_info">
<!--            --><?php //echo Yii::t('common', 'page_total'); ?><!-- --><?php //echo $cnt; ?><!-- --><?php //echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

<div class="row " style="margin-top: 10px;margin-bottom: 5px;">
    <div class="col-12" style="text-align: center">
        <button type="button" id="sbtn" class="btn btn-primary" onclick="save_person()">Save</button>
    </div>
</div>
<script type="text/javascript">

    //提交表单
    var save_person = function () {

        //var params = $('#form1').serialize();
        //alert("index.php?r=proj/task/tnew&" + params);
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=task/schedule/savepersonincharge",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>
