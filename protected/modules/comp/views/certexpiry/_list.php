<?php
$t->echo_grid_header();
$status_list = Certexpiry::statusText();//状态
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
        if($type_id == 'S'){
            $t->echo_td($row['info']['user_name'],'center');
        }else{
            $t->echo_td($row['info']['device_name'],'center');
        }
        $t->echo_td($row['contractor_name'],'center');
        if (Yii::app()->language == 'zh_CN'){
            $t->echo_td($row['info']['permit_enddate'],'center');
        }else{
            $t->echo_td($row['info']['certificate_name_en'],'center');
        }
        $t->echo_td(Utils::DateToEn($row['info']['permit_enddate']),'center');
        $t->echo_td($status_list[$row['info']['ispast']],'center');
//       $t->echo_td($link); //操作
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

