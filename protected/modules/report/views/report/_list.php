<?php
$t->echo_grid_header();
$status_list = Report::statusText();//状态
$status_css = Report::statusCss();
$company_list = Contractor::compAllList();//承包商公司列表
$program_list =  Program::programAllList();
if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
  }
    $roleList = Role::roleList();
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        
        if ($row['status'] == Device::STATUS_NORMAL ) { //状态正常
            
            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common', 'edit')."</a>";    //编辑
            $link .= "&nbsp;<a href='javascript:void(0)' onclick='itemLogout(\"{$row['primary_id']}\",\"{$row['device_name']}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('common', 'delete')."</a>";    //借调
            $link .= "&nbsp;<a href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a>";    //资质照片
            $link .= "&nbsp;<a href='javascript:void(0)' onclick='itemStatistics(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-list-alt\"></i>".Yii::t('dboard','Menu Statistics')."</a>";  //统计信息
        }
        else {
            $link = '';
        }

       $t->echo_td($num,'center');
       $t->echo_td($program_list[$row['program_id']]);
       $t->echo_td(Utils::MonthToEn($row['report_date']));
//       $t->echo_td(Utils::DateToEn($row['permit_enddate']));
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

                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport();"><?php echo Yii::t('device', 'Equipment_batch_export');?></strong></a>

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
    });
</script>

