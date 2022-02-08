<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = Role::statusText(); //状态
    $status_css = Role::statusCss();

//    $tool = true;
//    //$tool = false;验证权限
//    if (Yii::app()->user->checkAccess('mchtm')) {
//        $tool = true;
//    }

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['id']}');");

        $delete_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDelete(\"{$row['id']}\")'><i class=\"fa fa-fw fa-times\" title=\" ".Yii::t('common', 'delete')."\"></i></a>&nbsp;";

        $link = $delete_link;
        $t->echo_td($row['block']);
        $t->echo_td($row['type']);
        $t->echo_td($row['s_val']);
        $t->echo_td($row['t_val']);
        $t->echo_td($row['record_time']); //record_time
        $t->echo_td($link); //操作
        $t->end_row();
    }
}

$t->echo_grid_floor();
$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
<?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
<?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

