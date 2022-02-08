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
        $resetpwd_link = "<a href='javascript:void(0)' onclick='itemResetPwd(\"{$row['contractor_id']}\")'><i class=\"fa fa-fw fa-key\"></i>" . Yii::t('common', 'reset_pwd') . "</a>&nbsp;";
        $logout_link = "<a href='javascript:void(0)' onclick='itemLogout(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'delete') . "</a>";
        //$post_link = "<a href='javascript:void(0)' onclick='itemLogout(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-user\"></i>" . Yii::t('common', 'post') . "</a>";
        $article_link = "<a href='javascript:void(0)' onclick='itemContract(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-certificate\"></i>" . Yii::t('common', 'electronic contract') . "</a>";
        $statistic_link = "<a href='javascript:void(0)' onclick='itemStatistic(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-list-alt\"></i>" . Yii::t('comp_statistics', 'contentHeader_day') . "</a>";
        $operator_link = "<a href='javascript:void(0)' onclick='itemOperator(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-user\"></i>" . Yii::t('common', 'operator') . "</a>";
        $dms_link = "<a href='javascript:void(0)' onclick='itemDms(\"{$row['contractor_id']}\",\"{$row['contractor_name']}\")'><i class=\"fa fa-fw fa-file\"></i>Dms</a>";

        $t->echo_td($row['contractor_id']);
        $t->echo_td($row['contractor_name']);
        $t->echo_td($row['contractor_type']);
        $t->echo_td($row['company_sn']);
        $t->echo_td($row['link_person']);
        $t->echo_td($row['link_phone']);

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
       //$t->echo_td(substr($row['record_time'],0,10));
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)));
		if($row['status'] == Contractor::STATUS_NORMAL){
            $link = "<table><tr><td style='white-space: nowrap'>$edit_link</td><td style='white-space: nowrap'>$resetpwd_link</td></tr><tr><td style='white-space: nowrap'>$logout_link</td><td style='white-space: nowrap'>$article_link</td></tr><tr><td style='white-space: nowrap'>$statistic_link</td><td style='white-space: nowrap'>$dms_link</td></tr></table>";
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

