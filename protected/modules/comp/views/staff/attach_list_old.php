<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $tag_list = DocumentLabel::tagList();//标签
    $certificateList = CertificateType::certificateList();//证件类型
//    var_dump($tag_list);
//    exit;
    $program_list =  Program::programAllList();
    foreach ($rows as $i => $row) {
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $preview_link = "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['aptitude_photo']}\",\"{$row['aptitude_id']}\")'><i class=\"fa fa-fw fa-eye\"></i>" . Yii::t('electronic_contract', 'preview') . "</a>&nbsp;";
        $delete_link = "<a href='javascript:void(0)' onclick='itemDelete(\"{$row['aptitude_photo']}\",\"{$row['aptitude_id']}\",\"{$row['aptitude_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('electronic_contract', 'delete') . "</a>";
        $download_link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['aptitude_photo']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('electronic_contract', 'download') . "</a>";
        $link = "<table><tr><td style='white-space: nowrap'>$download_link</td><td style='white-space: nowrap'>$delete_link</td></tr></table>";
//        $t->echo_td($row['doc_id']);
        if($row['aptitude_type'] == 'pdf'){
//            $t->echo_td("<a href='javascript:void(0)' onclick='itemPreview(\"{$row['doc_path']}\",\"{$row['doc_id']}\")'>" . $row['doc_name'] . "</a>");
            $t->echo_td("<a href='index.php?r=comp/staff/preview&aptitude_photo={$row['aptitude_photo']}' target='_blank'><i class=\"fa fa-fw fa-eye\"></i>" . $row['aptitude_content']. "</a>");
        }else{
            $t->echo_td("<a href='javascript:void(0)' onclick='window.open(\"{$row['aptitude_photo']}\",\"_blank\")'><i class=\"fa fa-fw fa-eye\"></i>" . $row['aptitude_content']. "</a>");
        }
        if($row['aptitude_use'] == 0){
            $t->echo_td("<img id=\"{$row['aptitude_id']}\" class='no_selected' src='img/star.png' onclick='set(\"{$row['aptitude_id']}\")'>");
        }else{
            $t->echo_td("<img id=\"{$row['aptitude_id']}\" class='selected' src='img/star_select.png' onclick='set(\"{$row['aptitude_id']}\")'>");
        }
        $t->echo_td($certificateList[$row['certificate_type']]);
//        $label_name = '';
//        $label = explode(',',$row['label_id']);
//        foreach($label as $cnt => $id){
//            if($label_name == ''){
//                $label_name = $tag_list[$id];
//            }else{
//                $label_name.= ','.$tag_list[$id];
//            }
//        }
//        $t->echo_td('<a id="tags" class="editable editable-click" href="#" data-type="select2" data-pk="1" data-title="Enter tags" data-original-title="" title="" style="" data-url="index.php?r=document/platform/settags&doc_id='.$row['doc_id'].'">'.$label_name.'</a>');
        $t->echo_td($row['permit_startdate']);
        $t->echo_td($row['permit_enddate']);
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)));
//        $t->echo_td($row['file_tag']);
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
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/select2_new.js"></script>
<script type="text/javascript" src="js/bootstrap-editable.js"></script>
<script type="text/javascript">

    function set(doc_id){
        var calssname = document.getElementById(doc_id).className;
//    alert(calssname);
        var src = $("#"+doc_id)[0].src;
        if(calssname == 'no_selected'){
            var doc_use = 0;
        }else{
            var doc_use = 1;
        }
        $.ajax({
            data: {aptitude_id: doc_id,aptitude_use:doc_use},
            url: "index.php?r=comp/staff/setused",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if (doc_use == 0) {
                    $("#"+doc_id).attr('src','img/star_select.png');
                    $("#"+doc_id).attr('class','selected');
                } else {
                    $("#"+doc_id).attr('src','img/star.png');
                    $("#"+doc_id).attr('class','no_selected');
                }
            }
        });
    }
</script>
