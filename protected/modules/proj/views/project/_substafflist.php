<?php
$t->echo_grid_header();
$status_list = Staff::statusText();//状态
$status_css = Staff::statusCss();
if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    $roleList = Role::roleList();
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['user_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";//编辑
        $loane_link = "<a href='javascript:void(0)' onclick='itemLoaned(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('comp_staff', 'loane')."</a>";    //借调
        $certificate_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemPhoto(\"{$row['user_id']}\")' title=\" ".Yii::t('comp_staff', 'Qualification Certificate')." \"><i class=\"fa fa-fw fa-certificate\"></i></a>";//资质照片
        $logout_link = "<a href='javascript:void(0)' onclick='itemLogout(\"{$row['user_id']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('common', 'delete')."</a>";    //注销
        $white_link = "<a href='javascript:void(0)' onclick='itemWhite(\"{$row['user_id']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-plus\"></i>".Yii::t('comp_staff','White_list_type')."</a>";  //加入白名单
        $qrcode_link = "<a href='javascript:void(0)' onclick='itemQrcode(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-qrcode\"></i>".Yii::t('comp_staff','qr_code')."</a>";  //二维码
        $statistics_link = "<a href='javascript:void(0)' onclick='itemStatistics(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-list-alt\"></i>".Yii::t('dboard','Menu Statistics')."</a>";  //统计信息
        $link = "";
        if ($row['status'] == Staff::STATUS_NORMAL AND $row['loaned_status'] == 0) { //状态正常 and 是归属企业

            $link = $certificate_link;
//            $link .="&nbsp;<a href='javascript:void(0)' onclick='itemWhite(\"{$row['user_id']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-plus\"></i>".Yii::t('comp_staff','White_list_type')."</a>";  //加入白名单
            if ($row['loaned_status'] == Staff::LOANED_STATUS_NO){  //不在借调中
                $link = $certificate_link;
            }
        }
        else if($row['status'] == Staff::STATUS_NORMAL AND $row['loaned_status'] == 1){
            $link = $certificate_link;
        }else if($row['status'] == Staff::STATUS_NORMAL AND $row['loaned_status'] == 2){
            $link = "";
        }

        $t->echo_td($row['user_id'],'center');
        $t->echo_td($row['user_name'],'center');
        $t->echo_td("<div class='td_userphone'>".$row['user_phone'].'</div>','center');
        $t->echo_td("<div class='td_workno'>".$row['work_no'].'</div>','center');
        $t->echo_td($row['work_pass_type'],'center');
        $t->echo_td($row['nation_type'],'center');
        $t->echo_td($roleList[$row['role_id']],'center');
        $t->echo_td(Staff::LoanedStatus($row['loaned_status']),'center');
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status,'center'); //状态
        //$t->echo_td($row['record_time']);
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)),'center');
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
    <div class="col-6">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
<!--            --><?php //if($rows){ ?>
<!---->
<!--                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport();">--><?php //echo Yii::t('comp_staff', 'Staff_batch_export');?><!--</strong></a>-->
<!---->
<!--            --><?php //} ?>
        </div>
    </div>
    <div class="col-6">
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

