<script type="text/javascript">
    //查询
    var itemQuery = function () {
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
    //添加
    var itemAdd = function () {

        window.location = "index.php?r=comp/subcomp/new";
    }
    //修改
    var itemEdit = function (id) {
        window.location = "index.php?r=comp/subcomp/edit&id=" + id;
    }
    //删除
    var itemLogout = function (id, name) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_logout_1'); ?>' + name + '<?php echo Yii::t('common', 'confirm_logout_2'); ?>')) {
            return;
        }
        
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=comp/subcomp/logout",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_logout'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_logout'); ?>");
                }
            }
        });
    }
    //重置密码
    var itemResetPwd = function (id, name) {
        if (!confirm("<?php echo Yii::t('common','confirm_reset_1');?>" + name + "<?php echo Yii::t('common','confirm_reset_2');?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=comp/subcomp/resetpwd",
            dataType: "json",
            type: "POST",
            success: function (data) {
                alert(data.msg);
            }
        });
    }
    //折叠详细
    var showDetail = function (obj, desc, show) {
        $("#row_desc").remove();
        if (c_Note) {
            $(c_Note).removeClass("towfocus");
        }
        if (show && c_Note == obj) {
            c_Note = null;
            return;
        }
        $(obj).after("<tr id='row_desc' class='towfocus'><td colspan='" + obj.cells.length + "'>" + desc + "</td></tr>");
        c_Note = obj;
        $(c_Note).addClass("towfocus");
    }

    var c_Note = null;
    var detailobj = {};

    var getDetail = function (obj, id) {
        if (detailobj[id]) {
            showDetail(obj, detailobj[id], true);
            return;
        }
        var detail = "";
        
        $.ajax({
            data: {id: id},
            url: "index.php?r=comp/subcomp/detail",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                detail = "<?php echo Yii::t('common', 'loading'); ?>";
                showDetail(obj, detail, false);
            },
            success: function (data) {
                detail = data.detail;
                if (data.status) {
                    detailobj[id] = detail;
                }
                showDetail(obj, detail, false);
            }
        })
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