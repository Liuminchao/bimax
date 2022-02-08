<script type="text/javascript">
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
    //上传
    var itemUpload = function() {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('comp_document', 'upload'); ?>";
        modal.url = "index.php?r=document/company/upload";
        modal.modal();
    }
    //电子合约预览
    var itemPreview = function (path,id) {
        window.location = "index.php?r=document/company/preview&doc_path="+path+"&doc_id="+id;
    }
    //电子合约下载
    var itemDownload = function (path) {
        window.location = "index.php?r=document/company/download&doc_path="+path;
    }
    //电子合约删除
    var itemDelete = function (path,id,name) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete_1'); ?>'+name+'<?php echo Yii::t('common', 'confirm_delete_2'); ?>')) {
            return;
        }
        $.ajax({
            data: {doc_id: id, doc_path: path,confirm: 1},
            url: "index.php?r=document/company/delete",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    window.location = "index.php?r=document/company/list&id="+id;
                }
                else{
                    alert("<?php echo Yii::t('common', 'error_delete'); ?>");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox'); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>