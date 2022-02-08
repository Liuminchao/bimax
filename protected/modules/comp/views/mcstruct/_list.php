<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

//    $tool = true;
//    //$tool = false;验证权限
//    if (Yii::app()->user->checkAccess('mchtm')) {
//        $tool = true;
//    }

    $status_list = ContractorStruct::statusText(); //状态
    $status_css = ContractorStruct::statusCss();

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        if ($tool) {
            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['id']}\")'><i class=\"fa fa-fw fa-edit\"></i>编辑</a>";
            //if (Yii::app()->user->id == $row['operator'])
            $link .= "&nbsp;&nbsp;<a href='javascript:void(0)' onclick='itemDelete(\"{$row['id']}\",\"{$row['name']}\")'><i class=\"fa fa-fw fa-times\"></i>删除</a>";
        } else {
            $link = '无操作';
        }

        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['team_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
        $start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['team_id']}\",\"{$row['team_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";
        $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['team_id']}\",\"{$row['team_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'stop') . "</a>&nbsp;";

        if ($row['status'] == ContractorStruct::STATUS_NORMAL) {
            $link = $stop_link;
        } else if ($row['status'] == ContractorStruct::STATUS_STOP) {
            $link = $edit_link . $start_link;
        }

        //$t->echo_td($row['contractor_id']); //Contractor
        $t->echo_td($row['team_id']); //Team
        $t->echo_td($row['team_name']); //Team Name
        $t->echo_td($row['link_people']); //Link People
        $t->echo_td($row['link_phone']); //Link Phone
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        //$t->echo_td($row['record_time']); //Record Time

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
            共 <?php echo $cnt; ?> 条
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

