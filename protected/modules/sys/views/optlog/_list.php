<?php
$t->echo_grid_header();



if (is_array($rows)) {
   
    $moduleRs = OperatorLog::moduleDesc();
    $optCodes = OperatorLog::statusDesc(); //操作结果
    $optCss = OperatorLog::statusCss(); //操作结果css

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['log_sn']}');");
        $t->echo_td($row['log_sn']); //Log Sn
        $t->echo_td($moduleRs[$row['module_id']]); //Module
        $t->echo_td($row['operator_name'] . '(' . $row['operator_id'] . ')'); //Operator Name
        $t->echo_td($row['opt_field']); //Opt Field
        $opt_desc = '<span class="label ' . $optCss[$row['opt_result']] . '">' . $optCodes[$row['opt_result']] . '</span>';
        $t->echo_td($opt_desc); //操作结果
        $t->echo_td($row['opt_host_ip']);
        //$t->echo_td($row['opt_time']);
        $t->echo_td(Utils::DateToEn($row['opt_time']));
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
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

