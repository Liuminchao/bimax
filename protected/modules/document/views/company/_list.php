<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $status_list = Meeting::statusText(); //状态text
    $status_css = Meeting::statusCss(); //状态css
    $tag_list = DocumentLabel::tagList();//标签
//    var_dump($tag_list);
//    exit;
    $program_list =  Program::programAllList();
    foreach ($rows as $i => $row) {
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $preview_link = "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['doc_path']}\",\"{$row['doc_id']}\")'><i class=\"fa fa-fw fa-eye\"></i>" . Yii::t('electronic_contract', 'preview') . "</a>&nbsp;";
        $delete_link = "<a href='javascript:void(0)' onclick='itemDelete(\"{$row['doc_path']}\",\"{$row['doc_id']}\",\"{$row['doc_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('electronic_contract', 'delete') . "</a>";
        $download_link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['doc_path']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('electronic_contract', 'download') . "</a>";
        $link = "<table><tr><td style='white-space: nowrap'>$download_link</td><td style='white-space: nowrap'>$delete_link</td></tr></table>";
//        $t->echo_td($row['doc_id']);
        if($row['doc_type'] == 'pdf'){
//            $t->echo_td("<a href='javascript:void(0)' onclick='itemPreview(\"{$row['doc_path']}\",\"{$row['doc_id']}\")'>" . $row['doc_name'] . "</a>");
//            "index.php?r=document/company/preview&doc_path="+path+"&doc_id="+id
            $t->echo_td("<a href='index.php?r=document/company/preview&doc_path={$row['doc_path']}' target='_blank'>" . $row['doc_name'] . "</a>",'center');
        }else{
            $t->echo_td("<a href='javascript:void(0)' onclick='window.open(\"{$row['doc_path']}\",\"_blank\")'>" . $row['doc_name'] . "</a>",'center');
        }
        if($row['doc_use'] == 0){
            $t->echo_td("<img id=\"{$row['doc_id']}\" class='no_selected' src='img/star.png' onclick='set(\"{$row['doc_id']}\")'>",'center');
        }else{
            $t->echo_td("<img id=\"{$row['doc_id']}\" class='selected' src='img/star_select.png' onclick='set(\"{$row['doc_id']}\")'>",'center');
        }
//        $t->echo_td($row['label_id']);
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
        $t->echo_td('<a href="#" class="editable editable-click tags" data-type="select"  data-pk="1"  data-url="index.php?r=document/company/settags&doc_id='.$row['doc_id'].'" data-title="Select Tag" >'.$tag_list[$row['label_id']].'</a>','center');
        $t->echo_td($row['record_time'],'center');
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
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/select2_new.js"></script>
<script type="text/javascript" src="js/bootstrap-editable.js"></script>
<script type="text/javascript">
    //    $('#tags').editable({
    //        inputclass: 'input-large',
    //        select2: {
    ////            tags: [{id: 'PTW', text: 'PTW'},{id: 'TBM', text: 'TBM'},{id: 'RA/SWP', text: 'RA/SWP'}],
    //            tags:function () {
    //                var result = [];
    ////                    var result = [{value:1,text:'PTW'},{value:1,text:'TBM'},{value:1,text:'RA'}];
    //                $.ajax({
    //                    url: 'index.php?r=document/platform/source',
    //                    async: false,
    //                    type: "get",
    //                    dataType: 'json',
    //                    success: function (data, status) {
    //                        $.each(data, function (key, value) {
    //                            result.push({ id: value.id, text: value.name });
    //                        });
    //                    }
    //                });
    //                return result; } ,
    //            width: '200px',
    ////            data: [{id: 0, text: 'PTW'},{id: 1, text: 'TBM'},{id: 2, text: 'RA/SWP'}],
    //            multiple: true
    //        }
    //    });
    $('.tags').editable({
        ajaxOptions: {
            dataType: 'json' //assuming json response
        },
        pk: 1,
        validate: function(newValue) {
            if($.trim(newValue) == '') {
                return '请选择一个标签';
            }
        },
        source: function () {
            var result = [];
            $.ajax({
                url: 'index.php?r=document/company/source',
                async: false,
                type: "get",
                dataType: 'json',
                success: function (data, status) {
                    $.each(data, function (key, value) {
                        result.push({ value: value.id, text: value.name });
                    });
                }
            });
            return result; } ,
        success: function(response, newValue) {
            //$('#ra_role').editable('option', 'source', sources[newValue]);
            //$('#ra_role').editable('setValue', null);
            //return '设置成功';
            //showTime(response.refresh);
            //return response.msg;
        },
        error: function(response, newValue) {
            if(response.status === 500) {
                return 'Service unavailable. Please try later.';
            }else{
                return '未知错误';
            }
        },
    });
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
            data: {doc_id: doc_id,doc_use:doc_use},
            url: "index.php?r=document/company/setused",
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
