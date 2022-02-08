<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;
    $status_list = ConsassAudit::statusText();//状态
    $status_css = ConsassAudit::statusCss();
    $company_list = Contractor::compAllList();//承包商公司列表
    $program_list =  Program::programAllList();
    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;
        if ($row['status'] == Device::STATUS_NORMAL ) { //状态正常

            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";    //编辑
            $link .= "&nbsp;<a href='javascript:void(0)' onclick='itemDelete(\"{$row['id']}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('common', 'delete')."</a>";   //删除
        }
        else {
            $link = '';
        }
        
        
        $t->echo_td($num,'center');
        $t->echo_td($program_list[$row['program_id']],'center');
        $t->echo_td($row['proposed_date'],'center');//Company Name
        $t->echo_td($row['actual_date'],'center');
        $t->echo_td($row['audit_company']);
        $t->echo_td($row['remarks']);
        $t->echo_td(Utils::DateToEn($row['record_time']),'center');
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status,'center'); //状态
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
            <?php if($rows){ ?>

                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport();"><?php echo Yii::t('comp_staff', 'Batch export');?></strong></a>

            <?php } ?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

