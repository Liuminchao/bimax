<script type="text/javascript">
    //返回
    var back = function (id,name) {
        window.location = "index.php?r=comp/info/list";
    }
    //上传
    var upload = function (id,name) {
//        var modal = new TBModal();
//        modal.title = "<?php echo Yii::t('electronic_contract', 'upload_contract'); ?>";
//        modal.url = "index.php?r=comp/info/uploadview&id="+id+"&name="+name;
//        modal.modal();
        window.location = "index.php?r=comp/info/uploadview&id="+id+"&name="+name;
    }
    //电子合约预览
    var itemPreview = function (path,id) {
        window.location = "index.php?r=comp/info/preview&file_path="+path+"&id="+id;
    }
    //电子合约下载
    var itemDownload = function (path) {
        window.location = "index.php?r=comp/info/download&path="+path;
    }
    //电子合约删除
    var itemDelete = function (path,id) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete_1'); ?><?php echo Yii::t('common', 'confirm_delete_2'); ?>')) {
            return;
        }
        $.ajax({
            data: {id: id, file_path: path,confirm: 1},
            url: "index.php?r=comp/info/delete",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    window.location = "index.php?r=comp/info/electroniclist&id="+id;
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
                    <?php $this->renderPartial('electronic_toolBox',array('id' => $id,'name' => $name)); ?>
                    <div id="datagrid"><?php $this->actionElectronicGrid($id,$name); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>