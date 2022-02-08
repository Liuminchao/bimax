<?php
$t->echo_grid_header();
$status_list = Contractor::statusText(); //状态
$status_css = Contractor::statusCss();

if (is_array($rows)) {
    $j = 1;

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['contractor_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['contractor_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
        $resetpwd_link = "<a href='javascript:void(0)' onclick='itemResetPwd(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-key\"></i>" . Yii::t('common', 'reset_pwd') . "</a>&nbsp;";
        $logout_link = "<a href='javascript:void(0)' onclick='itemLogout(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'delete') . "</a>";

        //$t->echo_td($row['contractor_id']);
        $t->echo_td($row['contractor_name']);
        $t->echo_td($row['link_person']);
        $t->echo_td($row['link_phone']);

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        $t->echo_td($row['record_time']);
		if($row['status'] == Contractor::STATUS_NORMAL){
            $link = $edit_link.$resetpwd_link.$logout_link;
        }else{
            $link = '';//Yii::t('common', 'no_action');
        }
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

