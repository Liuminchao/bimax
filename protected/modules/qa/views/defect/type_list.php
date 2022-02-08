<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = ProgramLocation::statusText(); //状态
    $status_css = ProgramLocation::statusCss();
    $_SESSION['defect_type_page'] = $curpage;
//    $tool = true;
//    //$tool = false;验证权限
//    if (Yii::app()->user->checkAccess('mchtm')) {
//        $tool = true;
//    }
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['type_id']}');");

        $edit_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['type_id']}\",\"{$row['project_id']}\")' title=\" " . Yii::t('common', 'edit') . "\"><i class=\"fa fa-fw fa-edit\"></i></a>&nbsp;";
        $del_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDel(\"{$row['type_id']}\",\"{$row['project_id']}\")' title=\" " . Yii::t('common', 'delete') . "\"><i class=\"fa fa-fw fa-times\"></i></a>&nbsp;";
        $user_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemUser(\"{$row['type_id']}\",\"{$row['project_id']}\")' title=\"In Charge\"><i class=\"fa fa-fw fa-user\"></i></a>&nbsp;";

        $link = $edit_link . $del_link;

//        $t->echo_td($tag_list[$row['type']]);
//        $t->echo_td($row['value']);
//        $t->echo_td($row['type_1']);
//        $t->echo_td($row['blocktype']);
        $t->echo_td($row['type_2']);
        $t->echo_td($row['type_3']);
        $user_list = explode(',',$row['user_id']);
        $user = '';
        $contractor = '';
        foreach ($user_list as $i => $user_id){
            $user_model = Staff::model()->findByPk($user_id);
            $user_contractor_id = $user_model->contractor_id;
            $user_contractor = Contractor::model()->findByPk($user_contractor_id);
            $contractor_name = $user_contractor->contractor_name;
            $user.= $contractor_name .' : '. $user_model->user_name.'<br/>';
        }
//        $t->echo_td($user);
//        $t->echo_td($row['location']);
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        //$t->echo_td($row['record_time']); //record_time
        $t->echo_td($link,'center'); //操作
        $t->end_row();
    }
}

$t->echo_grid_floor();
$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-5">
        <div class="dataTables_info" id="example2_info">
<?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-7">
        <div class="dataTables_paginate paging_bootstrap">
<?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    function itemUser(id,project_id) {
        window.location = "index.php?r=qa/defect/edituser&id="+id+"&project_id="+project_id;
    }


</script>

