<?php
$t->echo_grid_header();
$domain = RevitModel::domainText();
$categor = RevitModel::categoryText();
if (is_array($rows)) {
    foreach ($rows as $i => $row) {
        $num = ($curpage - 1) * $this->pageSize + $j++;
//        $t->echo_td($num,'center'); //Apply
        $t->echo_td($row['name'],'center');//Company Name
        $t->echo_td($row['uuid'],'center'); //title
        $t->echo_td($row['entityId'],'center');//Company Name
        $t->echo_td($row['floor'],'center'); //title
        $t->echo_td($domain[$row['domain']],'center');//Company Name
        $t->echo_td($categor[$row['category']],'center'); //title
        $t->end_row();
    }

}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-5">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-xs-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

