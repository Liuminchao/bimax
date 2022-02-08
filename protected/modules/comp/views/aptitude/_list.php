<?php
$t->echo_grid_header();
$status_list = Staff::statusText();//状态
$status_css = Staff::statusCss();
if (is_array($rows)) {
    $j = 1;
//    $cnt = 0;
    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    $roleList = Role::roleList();
    foreach ($rows as $i => $row) {
//        $cnt++;
        $t->begin_row("onclick", "getDetail(this,'{$row['user_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $t->echo_td($row['user_id']); 
        $t->echo_td($row['user_name']); 
        $t->echo_td("<div class='td_userphone'>".$row['user_phone'].'</div>'); 
        $t->echo_td("<div class='td_workno'>".$row['work_no'].'</div>');
        $t->echo_td($row['work_pass_type']);
        $t->echo_td($roleList[$row['role_id']]);
        if($info == 'bca'){
            $t->echo_td(BCA);
            $t->echo_td($row['bca_issue_date']);
            $t->echo_td($row['bca_expire_date']);
        }else if($info == 'pass'){
            $t->echo_td(Yii::t('comp_aptitude', 'Passport'));
            $t->echo_td($row['ppt_issue_date']);
            $t->echo_td($row['ppt_expire_date']);
        }else if($info == 'csoc'){
            $t->echo_td( Yii::t('comp_staff', 'csoc'));
            $t->echo_td($row['csoc_issue_date']);
            $t->echo_td($row['csoc_expire_date']);
        }else if($info == 'ins_scy'){
            $t->echo_td(Yii::t('comp_staff', 'Ins_scy'));
            $t->echo_td($row['ins_scy_issue_date']);
            $t->echo_td($row['ins_scy_expire_date']);
        }else if($info == 'ins_med'){
            $t->echo_td( Yii::t('comp_staff', 'Ins_med'));
            $t->echo_td($row['ins_med_issue_date']);
            $t->echo_td($row['ins_med_expire_date']);
        }else if($info == 'ins_adt'){
            $t->echo_td( Yii::t('comp_staff', 'Ins_adt'));
            $t->echo_td($row['ins_adt_issue_date']);
            $t->echo_td($row['ins_adt_expire_date']);
        }
        $t->echo_td($row['day']);
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
//       $t->echo_td($status); //状态
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
<script type="text/javascript">
jQuery(document).ready(function () {
        $('.td_userphone').each(function(){
            v = $(this).text();
            v1 = see(v);
            $(this).text(v1);
        });
        $('.td_workno').each(function(){
            b = $(this).text();
            b1 = play(b);
            $(this).text(b1);
        });
    });
</script>

