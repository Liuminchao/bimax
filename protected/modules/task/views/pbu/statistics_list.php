<style type="text/css">

</style>
<?php
$t->echo_grid_header();
if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = RevitComponent::statusText(); //状态
    $status_css = RevitComponent::statusCss();
    foreach ($rows as $level => $row) {
        $attr['style'] = 'display:none';
        $t->begin_row("onclick", "getDetail(this,'{$level}');");
        $t->echo_td($level);
        $t->echo_td($row['complete']);
        $t->echo_td($row['total']);
        $t->echo_td($row['balance']);
        $t->echo_td($row['percentage']);
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
        <div class="dataTables_info" id="projlist_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

    });
    //Allocate
    var itemAllocate = function (id,pbu_tag) {
        var modal = new TBModal();
        if(pbu_tag == '1'){
            modal.title = "Allocate PBU Type";
        }else if(pbu_tag == '2'){
            modal.title = "Allocate PPVC Type";
        }else if(pbu_tag == '3'){
            modal.title = "Allocate Precast Type";
        }
        modal.url = "index.php?r=task/pbu/allocate&project_id=" + id+"&pbu_tag="+pbu_tag;
        modal.modal();
        itemQuery();
    }
    var itemEditPbutype = function (id) {
        var modal = new TBModal();
        modal.title = "Edit Pbu Type";
        modal.url = "index.php?r=task/pbu/editallocate&project_id=" + id;
        modal.modal();
        itemQuery();
    }
    //更新构件 状态置为0
    var itemUpdate = function (project_id) {
        var tbodyObj = document.getElementById('projlist');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('Please select Pbu Info.');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
//        alert(tag);
        alert('Are you sure to upgrade to the component library?');//确认升级到构件库吗
        $.ajax({
            data: {tag:tag},
            url: "index.php?r=task/pbu/updatepbu",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_update'); ?>");
                    removecloud();//去遮罩
                    window.location = "index.php?r=task/pbu/pbulist&program_id=<?php echo $program_id; ?>";
                } else {
                    //alert("<?php echo Yii::t('common', 'error_update'); ?>");
                    alert(data.msg);
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>

