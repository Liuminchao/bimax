<?php
$t->echo_grid_header();
//$type_list = Operator::typeText();//操作员类型
$status_list = Operator::statusText();//状态
$status_css = Operator::statusCss();

if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['operator_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        
        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['operator_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>&nbsp;";
        $rp_link = "<a href='javascript:void(0)' onclick='itemResetPwd(\"{$row['operator_id']}\",\"{$row['name']}\")'><i class=\"fa fa-fw fa-key\"></i>".Yii::t('common','reset_pwd')."</a>&nbsp;";
        $logout_link = "<a href='javascript:void(0)' onclick='itemLogout(\"{$row['operator_id']}\",\"{$row['name']}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('common', 'logout')."</a>";
        
        $t->echo_td($row['operator_id']); //Operator
        $t->echo_td($row['name']); //Name
        $t->echo_td($row['phone']); //Phone
        $t->echo_td($row['email']); //Email
        //$t->echo_td($type_list[$row['operator_type']]);//操作员类型
        //$t->echo_td($row['reg_time']); //Reg Time
        $t->echo_td(Utils::DateToEn($row['reg_time']));
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        //$t->echo_td($row['record_time']); //Record Time
        if($row['status'] == Operator::STATUS_NORMAL){
            $link = $edit_link.$rp_link.$logout_link;
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
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

