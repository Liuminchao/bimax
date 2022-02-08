<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $status_list = Program::statusText(); //状态text
    $status_css = Program::statusCss(); //状态css
    $compList = Contractor::compAllList(); //所有承包商

    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;

        $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-user\"></i>" . Yii::t('proj_project_user', 'Assign User') . "</a>";
        $t->echo_td($num); //序号
        //$t->echo_td($row['program_id']); //Program
        $t->echo_td($row['program_name']); //Program Name
        $t->echo_td($compList[$row['contractor_id']]); //Contractor
        //$t->echo_td('');
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        //$t->echo_td($row['record_time']); //Record Time

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

