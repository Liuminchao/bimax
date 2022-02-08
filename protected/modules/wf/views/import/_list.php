<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = ProgressPlanVersion::statusText(); //状态
    $status_css = ProgressPlanVersion::statusCss();

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['version_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
//        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['role_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
//        $start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['role_id']}\",\"{$row['role_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";
//        $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['form_id']}\",\"{$row['form_name_en']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'stop') . "</a>&nbsp;";
        $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['version_id']}\",\"{$program_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>Detail</a>&nbsp;";

        if ($row['status'] == QaChecklist::STATUS_NORMAL) {
            $link = $detail_link;
        }
        $t->echo_td($row['version_id']);
        $t->echo_td($row['version']);
        $t->echo_td($row['version_name']);
        $t->echo_td($row['record_time']);
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        $t->echo_td($link);
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

