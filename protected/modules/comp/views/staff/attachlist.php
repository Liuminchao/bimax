<script type="text/javascript">
    //返回
    var back = function () {
        <?php
            if($type == 'sc'){
        ?>
            window.location.href = document.referrer;//返回上一页并刷新
        <?php }else{ ?>
            window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        <?Php } ?>
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    //上传
    var itemUpload = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }

//        var modal = new TBModal();
//        modal.title = "<?php //echo Yii::t('proj_project_user', 'smallHeader Upload'); ?>//";
//        modal.url = "index.php?r=comp/staff/upload"+url;
        window.location = "index.php?r=comp/staff/upload"+url;
//        modal.modal();
    }

    //编辑证书
    var itemEdit = function (id,user_id) {
        window.location = "index.php?r=comp/staff/displayupload&aptitude_id="+id+'&user_id='+user_id;
    }

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

    //证件预览
    var itemPreview = function (path,id) {
        window.location = "index.php?r=comp/staff/preview&aptitude_photo="+path+"&aptitude_id="+id;
    }
    //证件下载
    var itemDownload = function (path) {
        window.location = "index.php?r=comp/staff/download&aptitude_photo="+path;
    }
    var back = function(){
        window.location = "./?<?php echo Yii::app()->session['list_url']['proj/project/substafflist']; ?>";
    }
    //证件删除
    var itemDelete = function (id,user_id) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete'); ?>')) {
            return;
        }
        $.ajax({
            data: {id: id,confirm: 1},
            url: "index.php?r=comp/staff/delete",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    window.location = "index.php?r=comp/staff/attachlist&user_id="+user_id;
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
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('attach_toolBox', array('user_id'=>$user_id,'type'=>$type)); ?>
                    <div id="datagrid"><?php $this->actionAttachGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>