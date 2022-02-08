
<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;


    $status_list = TaskTemplate::statusText(); //状态
    $status_css = TaskTemplate::statusCss();
    $type_list = TaskTemplate::typeList();
    $pro_model = Program::model()->findByPk($project_id);
    $root_proid = $pro_model->root_proid;
    $root_model = Program::model()->findByPk($root_proid);
    $user_phone = Yii::app()->user->id;
    $user = Staff::userByPhone($user_phone);
    $user_model = Staff::model()->findByPk($user[0]['user_id']);
    $user_contractor_id = $user_model->contractor_id;
    $contractor_id = $root_model->contractor_id;
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['group_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
    
        $stop_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemStop(\"{$row['group_id']}\",\"{$row['group_name']}\")' title=\"Delete\"><i class=\"fa fa-fw fa-times\"></i></a>&nbsp;";
        $detail_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDetail(\"{$row['group_id']}\",\"{$project_id}\")' title=\" ". Yii::t('sys_workflow', 'detail') . "\"><i class=\"fa fa-fw fa-list-alt\"></i></a>&nbsp;";

        if ($row['status'] == RfGroup::STATUS_NORMAL) {
            if($user_contractor_id == $contractor_id){
                $link =  $detail_link.$stop_link;
            }else{
                $link =  $detail_link;
            }
        }
        $t->echo_td($row['group_id']); //Id
//        $t->echo_td($type_list[$row['clt_type']]);
        $t->echo_td($row['group_name']); //Template Name
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        $t->echo_td(Utils::DateToEn($row['record_time'])); //Record Time
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
    <div class="col-5">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    //详情
    var itemDetail = function (id,project_id) {
        window.location = "index.php?r=rf/group/method&id=" + id+"&project_id="+project_id;
    }
</script>