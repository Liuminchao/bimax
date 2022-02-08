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

        $preview_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemPreview(\"{$row['aptitude_photo']}\",\"{$row['aptitude_id']}\")' title=\" ".Yii::t('electronic_contract', 'preview')."\"><i class=\"fa fa-fw fa-eye\"></i></a>&nbsp;";
        $delete_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDelete(\"{$row['aptitude_id']}\",\"{$row['user_id']}\")' title=\" ".Yii::t('electronic_contract', 'delete')."\"><i class=\"fa fa-fw fa-times\"></i></a>&nbsp;";
        $download_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownload(\"{$row['aptitude_photo']}\")' title=\" ".Yii::t('electronic_contract', 'download')."\"><i class=\"fa fa-fw fa-download\"></i></a>&nbsp;";
        $edit_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['aptitude_id']}\",\"{$row['user_id']}\")' title=\" ".Yii::t('common', 'edit')."\"><i class=\"fa fa-fw fa-edit\"></i></a>&nbsp;";//编辑
        if($type == 'mc'){
            $link = "$download_link$delete_link$edit_link";
        }else{
            $link = "$download_link";
        }

//        $t->echo_td($row['doc_id']);
        if($row['aptitude_type'] == 'pdf'){
//            $t->echo_td("<a href='javascript:void(0)' onclick='itemPreview(\"{$row['doc_path']}\",\"{$row['doc_id']}\")'>" . $row['doc_name'] . "</a>");
            $t->echo_td("<a class='a_logo' href='index.php?r=comp/staff/preview&aptitude_photo={$row['aptitude_photo']}' target='_blank'><i class=\"fa fa-fw fa-eye\"></i>" . $row['aptitude_content']. "</a>",'center');
        }else{
            $t->echo_td("<a class='a_logo' href='javascript:void(0)' onclick='window.open(\"{$row['aptitude_photo']}\",\"_blank\")'><i class=\"fa fa-fw fa-eye\"></i>" . $row['aptitude_content']. "</a>",'center');
        }
        if($row['aptitude_use'] == 0){
            $t->echo_td("<img id=\"{$row['aptitude_id']}\" class='no_selected' src='img/star.png' onclick='set(\"{$row['aptitude_id']}\")'>",'center');
        }else{
            $t->echo_td("<img id=\"{$row['aptitude_id']}\" class='selected' src='img/star_select.png' onclick='set(\"{$row['aptitude_id']}\")'>",'center');
        }
        $t->echo_td($certificateList[$row['certificate_type']],'center');
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
        $t->echo_td(Utils::DateToEn($row['permit_startdate']),'center');
        $t->echo_td(Utils::DateToEn($row['permit_enddate']),'center');
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)),'center');
//        $t->echo_td($row['file_tag']);
        $t->echo_td($link,'center'); //操作
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-9">
        <div class="dataTables_paginate paging_simple_numbers">
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
