<style type="text/css">
    .format1{
        list-style:none; padding:0px; margin:0px; width:220px; float: left;
    }
    .format2{ width:50%; display:inline-block; float: left; padding-left: 0}
    #example2 td:nth-child(2){
        display: none;
    }
</style>
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
        $certificate_link = "<a  href='javascript:void(0)' onclick='itemPhoto(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('comp_staff', 'Qualification Certificate')."</a>";//资质照片
        $logout_link = "<a href='javascript:void(0)' onclick='itemLogout(\"{$row['user_id']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('common', 'delete')."</a>";    //注销
        $white_link = "<a href='javascript:void(0)' onclick='itemWhite(\"{$row['user_id']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-plus\"></i>".Yii::t('comp_staff','White_list_type')."</a>";  //加入白名单
        $qrcode_link = "<a href='javascript:void(0)' onclick='itemQrcode(\"{$row['user_id']}\",\"{$row['qrcode']}\")'><i class=\"fa fa-fw fa-qrcode\"></i>".Yii::t('comp_staff','qr_code')."</a>";  //二维码
        $statistics_link = "<a href='javascript:void(0)' onclick='itemStatistics(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-list-alt\"></i>".Yii::t('dboard','Menu Statistics')."</a>";  //统计信息
        $dms_link = "<a href='javascript:void(0)' onclick='itemDms(\"{$row['user_id']}\")'><i class=\"fa fa-fw fa-list-alt\"></i>DMS</a>";  //dms
        $link = "";
        if ($row['status'] == Staff::STATUS_NORMAL AND $row['loaned_status'] == 0) { //状态正常 and 是归属企业
            
            $link = "<ul class='format1'><li class='format2'>$edit_link</li><li class='format2'>$loane_link</li><li class='format2'>$certificate_link</li></ul>";
//            $link .="&nbsp;<a href='javascript:void(0)' onclick='itemWhite(\"{$row['user_id']}\",\"{$row['user_name']}\")'><i class=\"fa fa-fw fa-plus\"></i>".Yii::t('comp_staff','White_list_type')."</a>";  //加入白名单
            if ($row['loaned_status'] == Staff::LOANED_STATUS_NO){  //不在借调中
                $link = "<ul class='format1'><li class='format2'>$edit_link</li><li class='format2'>$loane_link</li><li class='format2'>$logout_link</li><li class='format2'>$statistics_link</li></ul>";
            }
        }
        else if($row['status'] == Staff::STATUS_NORMAL AND $row['loaned_status'] == 1){
          $contractor_id = Yii::app()->user->contractor_id;
          if($row['contractor_id'] != $row['original_conid'] and $row['contractor_id'] == $contractor_id){
              $link = "<ul class='format1'><li class='format2'>$loane_link</li></ul>";
          }
          if($row['contractor_id'] != $row['original_conid'] and $row['original_conid'] == $contractor_id){
              $link = "<ul class='format1'><li class='format2'>$edit_link</li><li class='format2'>$loane_link</li><li class='format2'>$logout_link</li><li class='format2'>$statistics_link</li><li class='format2'>$certificate_link</li></ul>";
          }
            
       }
       $t->echo_td($row['user_id'],'center');
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
    <div class="col-xs-6">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
            <?php if($rows){ ?>
            
                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport();"><?php echo Yii::t('comp_staff', 'Staff_batch_export');?></strong></a>
            
            <?php } ?>
        </div>
    </div>
    <div class="col-xs-6">
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

    function initTableCheckbox() {
        var $thr = $('#example2 thead tr');
        var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
        /*将全选/反选复选框添加到表头最前，即增加一列*/
        $thr.prepend($checkAllTh);
        /*“全选/反选”复选框*/
        var $checkAll = $thr.find('input');
        $checkAll.click(function (event) {
            /*将所有行的选中状态设成全选框的选中状态*/
            $tbr.find('input').prop('checked', $(this).prop('checked'));
            /*并调整所有选中行的CSS样式*/
            if ($(this).prop('checked')) {
                $tbr.find('input').parent().parent().addClass('warning');
            } else {
                $tbr.find('input').parent().parent().removeClass('warning');
            }
            /*阻止向上冒泡，以防再次触发点击操作*/
            event.stopPropagation();
        });
        /*点击全选框所在单元格时也触发全选框的点击操作*/
        $thr.click(function () {
            $(this).find('input').click();
        });
        var $tbr = $('#example2 tbody tr');
        var $checkItemTd = $('<td><input type="checkbox" name="checkItem" /></td>');
        /*每一行都在最前面插入一个选中复选框的单元格*/
        $tbr.prepend($checkItemTd);
        /*点击每一行的选中复选框时*/
        $tbr.find('input').click(function (event) {
            /*调整选中行的CSS样式*/
            $(this).parent().parent().toggleClass('warning');
            /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
            $checkAll.prop('checked', $tbr.find('input:checked').length == $tbr.length ? true : false);
            /*阻止向上冒泡，以防再次触发点击操作*/
            event.stopPropagation();
        });
        /*点击每一行时也触发该行的选中操作*/
        $tbr.click(function () {
            $(this).find('input').click();
        });
    }

    initTableCheckbox();
    });
</script>

