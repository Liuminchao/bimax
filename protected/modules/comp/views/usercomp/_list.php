<?php
$t->echo_grid_header();
$status_list = User::statusText();//状态
$status_css = User::statusCss();

if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['user_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        if ($tool) {
            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";
            $link .= "&nbsp;&nbsp;<a href='javascript:void(0)' onclick='itemLogout(\"{$row['user_id']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('common', 'delete')."</a>";
        } else {
            $link = Yii::t('common', 'no_action');
        }
        
       $t->echo_td($row['user_id']); 
       $t->echo_td($row['user_name']); 
       $t->echo_td($row['user_phone']); 
       
       $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
       $t->echo_td($status); //状态
       $t->echo_td($row['record_time']);
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
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

