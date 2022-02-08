<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = Role::statusText(); //状态
    $status_css = Role::statusCss();

//    $tool = true;
//    //$tool = false;验证权限
//    if (Yii::app()->user->checkAccess('mchtm')) {
//        $tool = true;
//    }
    
    foreach ($rows as $i => $row) {

        //$t->begin_row("onclick", "getDetail(this,'{$row['role_id']}');");

        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['role_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common','edit')."</a>&nbsp;";
        $start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['role_id']}\",\"{$row['role_name']}\")'><i class=\"fa fa-fw fa-check\"></i>".Yii::t('common','start')."</a>&nbsp;";
        $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['role_id']}\",\"{$row['role_name']}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('common','stop')."</a>&nbsp;";
        
        if($row['status'] == Role::STATUS_NORMAL){
            $link = $stop_link;
        }
        else if($row['status'] == Role::STATUS_STOP){
            $link = $edit_link.$start_link;
        }
        //$t->echo_td($type_list[$row['contractor_type']]); //contractor_type
        //$t->echo_td($row['role_id']); //role_id
        if(Yii::app()->language == 'zh_CN'){
            $t->echo_td($row['role_name']); //role_name
            $t->echo_td($row['team_name']); //team_name
        }
        else if(Yii::app()->language == 'en_US'){
             $t->echo_td($row['role_name_en']); //role_name_en
             $t->echo_td($row['team_name_en']); //team_name_en
        }
        
       
        $t->echo_td($row['sort_id']); //order
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        //$t->echo_td($row['record_time']); //record_time

        //$t->echo_td($link); //操作
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

