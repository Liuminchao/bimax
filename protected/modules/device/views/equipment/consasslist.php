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
   //下载
    var itemDownload = function(id,name) {
        window.location = "index.php?r=device/equipment/downloadpdf&apply_id="+id+'&app_id='+name;
    }
    //添加
    var itemAdd = function() {
        window.location = "index.php?r=device/equipment/consass";
    }
    //编辑
    var itemEdit = function(id) {
        window.location = "index.php?r=device/equipment/editconsass&id="+id;
    }
    //注销
    var itemDelete = function (id) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_delete'); ?>' )) {
            return;
        }
        // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=device/equipment/delconsass",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    itemQuery();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_delete'); ?>");
                    alert(data.msg);
                }
            }
        });
    }

    //批量导出
    var itemExport = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=device/equipment/exportconsass" + url;
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('consass_toolBox'); ?>
                    <div id="datagrid"><?php $this->actionConsassGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>