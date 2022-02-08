<?php
$t->echo_grid_header();
$app_list = App::appList();
$status_list = CompanyApp::statusText(); //状态
$status_css = CompanyApp::statusCss();

if (is_array($rows)) {
    $j = 1;

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['company_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['company_id']}\",\"{$row['app_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
        $logout_link = "<a href='javascript:void(0)' onclick='itemLogout(\"{$row['company_id']}\",\"{$row['app_id']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'delete') . "</a>";

        $t->echo_td($row['app_id']);
        $t->echo_td($app_list[$row['app_id']]);
        $t->echo_td(Utils::DateToEn($row['open_time']));
        $t->echo_td(Utils::DateToEn($row['close_time']));

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
       //$t->echo_td(substr($row['record_time'],0,10));
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)));

        $link = "<table><tr><td style='white-space: nowrap'>$edit_link</td><td style='white-space: nowrap'>$logout_link</td></tr><tr><td style='white-space: nowrap'>$function_link</td></tr></table>";

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

