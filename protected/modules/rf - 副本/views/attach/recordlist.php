<script type="text/javascript">
    //查询
    var itemQuery = function() {
        var start_date = $("#q_start_date").val();
        var end_date = $("#q_end_date").val();
        if(start_date != '' && end_date == ''){
            alert('<?php echo Yii::t('license_licensepdf','time_period') ?>');
        }
        if(start_date == '' && end_date != ''){
            alert('<?php echo Yii::t('license_licensepdf','time_period') ?>');
        }
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
    //添加记录
    var itemAdd = function(program_id) {
        var modal = new TBModal();
        modal.title = 'My Drawings';
        modal.url = "index.php?r=rf/rf/selecttype&program_id="+program_id;
        modal.modal();
    }
    //编辑
    var itemEdit = function(check_id) {
        window.location = "index.php?r=rf/rf/editchat&check_id="+check_id;
    }
    //预览
    var itemPreview = function (check_id) {
        window.location = "index.php?r=rf/rf/info&check_id="+check_id;
    }
   //下载
    var itemDownload = function(id) {
        window.location = "index.php?r=rf/rf/downloadpdf&check_id="+id;
    }
    //返回
    var itemBack = function(id) {
        window.location = "index.php?r=rf/attach/list&program_id="+id;
    }
    //添加附件
    var itemAttachment = function(id) {
        var modal = new TBModal();
//        modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = 'Synchronous Attachment';
        modal.url = "index.php?r=rf/rf/attachment&check_id="+id;
        modal.modal();
    }
    //详情
    var itemDetail= function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/detail&apply_id="+id+"&app_id="+app_id;
    }

</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('record_toolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionRecordGrid($attach_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
