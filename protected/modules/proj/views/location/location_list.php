<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = ProgramLocation::statusText(); //状态
    $status_css = ProgramLocation::statusCss();
    $tag_list = ProgramLocation::TagList();

//    $tool = true;
//    //$tool = false;验证权限
//    if (Yii::app()->user->checkAccess('mchtm')) {
//        $tool = true;
//    }

    foreach ($rows as $i => $row) {
        $t->begin_row("onclick", "getDetail(this,'{$row['id']}');");

        $edit_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['id']}\",\"{$row['project_id']}\")' title＝\" ".Yii::t('common', 'edit'). "\"><i class=\"fa fa-fw fa-edit\"></i></a>&nbsp;";
        $del_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDel(\"{$row['id']}\",\"{$row['project_id']}\")'><i class=\"fa fa-fw fa-times\" title=\" " . Yii::t('common', 'delete') . "\"></i></a>&nbsp;";
        $doc_link_1 = "<a class='a_logo' href='javascript:void(0)' onclick='itemDoc(\"{$row['id']}\",\"{$row['project_id']}\")' title=\"Upload Floor Plan\"><i class=\"fa fa-fw fa-upload\"></i></a>";
        $doc_link_2 = "<a class='b_logo' href='javascript:void(0)' onclick='itemDoc(\"{$row['id']}\",\"{$row['project_id']}\")' title=\"Upload Floor Plan\"><i class=\"fa fa-fw fa-upload\"></i></a>";

        $link_1 = $edit_link . $del_link;
        if($row['doc_id']){
            $link_2 = $doc_link_1;
        }else{
            $link_2 = $doc_link_2;
        }


//        $t->echo_td($tag_list[$row['type']]);
//        $t->echo_td($row['value']);
        $t->echo_td($row['block']);
//        $t->echo_td($row['blocktype']);
        $t->echo_td($row['secondary_region']);
        $t->echo_td($row['unit']);
        if($row['type'] == '0'){
            $t->echo_td('Non-typical Level');
        }else if($row['type'] == '1'){
            $t->echo_td('Typical Level');
        }else if($row['type'] == '2'){
            $t->echo_td('NA');
        }
//        $t->echo_td($row['doc_name']);
//        $t->echo_td($row['location']);
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
//        $t->echo_td($status); //状态
        //$t->echo_td($row['record_time']); //record_time
        $t->echo_td($link_2,'center'); //图纸
        $t->echo_td($link_1,'center'); //编辑，删除

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
    function itemDoc(id,project_id) {
        sessionStorage.setItem('block_id', id);
        var modal = new TBModal();
        modal.title = "Drawings";
        modal.url = "index.php?r=proj/location/uploadfile&id="+id+"&project_id="+project_id;
        modal.modal();
    // alert('123');
    // var diag = new Dialog();
    // diag.Width = 930;
    // diag.Height = 980;
    // diag.Title = "DMS";
    // diag.URL = "showview";
    // diag.show();
    }

    window.addEventListener('message', function (messageEvent) {
        var data = messageEvent.data;
        console.info('message from child:', data);
        var obj = eval('(' + data + ')');
        console.info(obj.file_list[0].file_id);
        var block_id = sessionStorage.getItem('block_id');
        $.ajax({
            data: {id: block_id, doc_id: obj.file_list[0].file_id, doc_name: obj.file_list[0].file_name,confirm: 1},
            url: "index.php?r=proj/location/setdoc",
            dataType: "json",
            type: "POST",
            success: function (data) {
                alert('set success');
                $("#modal-close").click();
                window.location = "index.php?r=proj/location/locationlist&project_id=<?php echo $project_id;?>";
            }
        });
    }, false);
</script>

