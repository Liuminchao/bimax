<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = QaChecklist::statusText(); //状态
    $status_css = QaChecklist::statusCss();
    $type_list = QaCheckType::checkType();
    $form_type = QaChecklist::formType();

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['form_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
//        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['role_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
//        $start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['role_id']}\",\"{$row['role_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";

        $stop_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemStop(\"{$row['form_id']}\",\"{$row['form_name_en']}\")' title=\" ".Yii::t('common', 'delete')."\"><i class=\"fa fa-fw fa-times\"></i></a>";
        $detail_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDetail(\"{$row['form_id']}\",\"{$program_id}\",\"{$row['form_name_en']}\")' title='Details'><i class=\"fa fa-fw fa-eye\"></i></a>";
        $attachment_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownloadAttachment(\"{$row['form_id']}\")' title=\" ".Yii::t('comp_qa', 'attachment')."\"><i class=\"fa fa-fw fa-paperclip\"></i></a>";

        if ($row['status'] == QaChecklist::STATUS_NORMAL) {
            $link = $detail_link."&nbsp;".$attachment_link;
        }
        $t->echo_td($type_list[$row['type_id']],'center');
        $t->echo_td($row['form_id'],'center');
        $t->echo_td($row['form_name_en']);
        $t->echo_td($form_type[$row['form_type']],'center');
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status,'center'); //状态
        $t->echo_td($link,'center');
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
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    var itemDownloadAttachment = function (id){
        var modal = new TBModal();
        modal.title = 'Attachfile';
        modal.url = "index.php?r=qa/import/downloadpreview&form_id="+id;
        modal.modal();
    }
</script>