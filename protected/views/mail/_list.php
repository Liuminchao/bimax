<?php
$t->echo_grid_header();
$status_list = MailType::statusText();//状态
$status_css = MailType::statusCss();
$device_list = DeviceType::deviceList();
if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
  }
    $roleList = Role::roleList();
    $current_date = date('Y-m-d');
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['user_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

       $t->echo_td($row['user_name']);
       $t->echo_td($row['program_name']);
       $t->echo_td($row['contractor_name']);
       $t->echo_td($row['certificate_name_en']);
//       $t->echo_td(Utils::DateToEn($row['permit_startdate']));
       $t->echo_td(Utils::DateToEn($row['permit_enddate']));
       if($row['permit_enddate']<$current_date){
           $status = '2';
       }else{
           $status = '1';
       }
       $status = '<span class="label ' . $status_css[$status] . '">' . $status_list[$status] . '</span>';
       $t->echo_td($status,'center'); //状态
       //$t->echo_td($row['record_time']);
       $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-6" style="text-align: left">
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

