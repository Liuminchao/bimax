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
    var itemUpload = function(id) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('comp_document', 'upload'); ?>";
        modal.url = "index.php?r=rf/attach/upload&program_id="+id;
        modal.modal();
    }
    //图纸发布
    var itemPublish = function (id,program_id) {
//        var modal = new TBModal();
//        modal.title = "Publish Document";
//        modal.url = "index.php?r=rf/attach/publish&attach_id="+id;
//        modal.modal();
//        window.location = "index.php?r=rf/attach/publish&attach_id="+id;
        $.ajax({
            data: {attach_id: id, program_id: program_id},
            url: "index.php?r=rf/attach/publish",
            type: "POST",
            dataType: "json",
            success: function (data) {
                alert("<?php echo Yii::t('common', 'success_set'); ?>");
                window.location = "index.php?r=rf/attach/list&program_id="+program_id;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }
    //图纸记录
    var itemRecord = function (id,program_id) {
        window.location = "index.php?r=rf/attach/recordlist&attach_id="+id+"&program_id="+program_id;
    }
    //创建RFA
    var itemAdd = function(id){

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var attach_id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += attach_id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('Please select document.');
            return false;
        }
        if(tag != ''){
            tag = tag.substr(0,tag.length-1);
        }else{
            tag = 0;
        }
        window.location = "index.php?r=rf/rf/addrfachat&program_id="+id+"&tag="+tag;

    }
    //电子合约删除
    var itemDelete = function (path,id,name) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete_1'); ?>'+name+'<?php echo Yii::t('common', 'confirm_delete_2'); ?>')) {
            return;
        }
        $.ajax({
            data: {doc_id: id, doc_path: path,confirm: 1},
            url: "index.php?r=rf/attach/delete",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    window.location = "index.php?r=document/platform/list&id="+id;
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
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>