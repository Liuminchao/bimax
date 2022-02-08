<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = ProgressPlan::statusText(); //状态
    $status_css = ProgressPlan::statusCss();

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['plan_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $t->echo_td($row['plan_id']);
        $t->echo_td($row['plan_name']);
        $t->echo_td($row['plan_start']);
        $t->echo_td($row['plan_finish']);
        $t->echo_td($row['duration_days']);
        $t->echo_td($row['level']);
        $t->echo_td($row['record_time']);
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
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

