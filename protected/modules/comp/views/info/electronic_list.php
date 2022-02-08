<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['contractor_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $file_path = $row['file_path'];
        $preview_link = "<a href='javascript:void(0)' onclick='itemPreview(\"{$file_path}\",\"{$row['contractor_id']}\")' ><i class=\"fa fa-fw fa-eye\"></i>" . Yii::t('electronic_contract', 'preview') . "</a>&nbsp;";
        $delete_link = "<a href='javascript:void(0)' onclick='itemDelete(\"{$row['file_path']}\",\"{$row['contractor_id']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('electronic_contract', 'delete') . "</a>";
        $download_link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['file_path']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('electronic_contract', 'download') . "</a>";
        
        
        $t->echo_td($row['title']);
        $t->echo_td($row['content']);
        $t->echo_td($row['start_date']);
        $t->echo_td($row['end_date']);
        //$t->echo_td(substr($row['record_time'],0,10));
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)));
        
        if (Yii::app()->user->getState('operator_type') == '00'){
            $link = "<table><tr><td style='white-space: nowrap'><a href='index.php?r=comp/info/preview&file_path={$row['file_path']}' target='_blank'><i class=\"fa fa-fw fa-eye\"></i>".Yii::t('electronic_contract', 'preview')."</a></td><td style='white-space: nowrap'>$delete_link</td></tr><tr><td style='white-space: nowrap'>$download_link</td></tr></table>";
        }else{
            $link = "<table><tr><td style='white-space: nowrap'><a href='index.php?r=comp/info/preview&file_path={$row['file_path']}' target='_blank'><i class=\"fa fa-fw fa-eye\"></i>".Yii::t('electronic_contract', 'preview')."</a></td><td style='white-space: nowrap'>$download_link</td></tr></table>";
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

