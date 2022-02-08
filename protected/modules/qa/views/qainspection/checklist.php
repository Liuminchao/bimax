<script type="text/javascript">
    // $(function(){
    //     itemQuery();
    // });
    //查询
    var itemQuery = function() {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
<?php echo $this->gridId; ?>.condition = url;
<?php echo $this->gridId; ?>.refresh();
    }
    //查询
    var detailQuery = function() {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        <?php echo $this->gridId; ?>.condition = url;
        <?php echo $this->gridId; ?>.refresh();
    }
    //预览
    var itemPreview = function (id) {
        var modal = new TBModal();
        modal.title = "[Checklist]";
        modal.url = "index.php?r=routine/routineinspection/preview&check_id="+id;
        modal.modal();
    }
    //导出
    var itemPdf = function (id) {
        window.location = "index.php?r=qa/qainspection/downloadpdf&check_id="+id;
    }
   //下载
    var itemDownload = function(check_id) {
//        window.location = "index.php?r=qa/qainspection/qaexport&check_id="+id;
        var modal = new TBModal();
        modal.title = 'QA/QC Report';
        modal.url = "index.php?r=qa/qainspection/downloadpreview&check_id="+check_id;
        modal.modal();
    }
    //下载附件
    var itemDownloadAttachment = function(id) {
        var modal = new TBModal();
//        modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = 'QA';
        modal.url = "index.php?r=qa/qainspection/downloadattachment&check_id="+id;
        modal.modal();
    }
    //人员下载
    var itemStaff = function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/staffdownload&apply_id="+id+"&app_id="+app_id;
    }
    //详情
    var itemDetail= function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/detail&apply_id="+id+"&app_id="+app_id;
    }
    function itemBack () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['task/recordlist']; ?>";
    }
</script>

<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">

            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper " id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('check_toolBox',array('check_id'=>$check_id, 'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionCheckGrid($check_id); ?></div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>



