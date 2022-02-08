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
    //下载设备入场表
    var itemDownload = function(program_id,primary_id){
        window.location = "index.php?r=proj/assignuser/downloadequipment&program_id="+program_id+"&primary_id="+primary_id;
    }
    //查询设备证书
    var itemPhoto = function (id,primary_id) {
        window.open("index.php?r=device/equipment/attachlist&device_id="+id+"&primary_id="+primary_id);
    }
    //添加
    var itemAdd = function () {
        window.location = "index.php?r=device/equipment/new";
    }
    //修改
    var itemEdit = function (id) {
        window.location = "index.php?r=device/equipment/edit&device_id=" + id;
    }
    //注销
    var itemLogout = function (id, name) {
        //alert("1234");
        if (!confirm('<?php echo Yii::t('common', 'confirm_logout_1'); ?>' + name + '<?php echo Yii::t('common', 'confirm_logout_2'); ?>')) {
            return;
        }
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=device/equipment/logout",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_logout'); ?>");
                    itemQuery();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_logout'); ?>");
                    alert(data.msg);
                }
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
    
    //删除设备
    var itemDelete = function (program_id,primary_id) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete_3'); ?>')) {
            return;
        }
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {program_id: program_id,primary_id:primary_id, confirm: 1},
            url: "index.php?r=proj/assignuser/deletedevice",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_delete'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
    
    //设置出场设备
    var itemLeave = function (program_id,primary_id) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_leave_3'); ?>')) {
            return;
        }
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);

        $.ajax({
            data: {program_id: program_id,primary_id:primary_id, confirm: 1},
            url: "index.php?r=proj/assignuser/leavedevice",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_remove'); ?>");
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_remove'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
    //设置入场设备不可见
    var itemInvisible = function (program_id,primary_id) {

        if (!confirm('Confirm to deactivate this equipment?')) {
            return;
        }
        // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);

        $.ajax({
            data: {program_id: program_id,primary_id:primary_id, confirm: 1},
            url: "index.php?r=proj/assignuser/invisibledevice",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_set'); ?>");
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_set'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
    //设置入场设备可见
    var itemVisible = function (program_id,primary_id) {

        if (!confirm('Confirm to reactivate this equipment?')) {
            return;
        }
        // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);

        $.ajax({
            data: {program_id: program_id,primary_id:primary_id, confirm: 1},
            url: "index.php?r=proj/assignuser/visibledevice",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_set'); ?>");
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_set'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
    //提交入场申请
    var itemEntrance = function (list,program_id) {
    //alert(list);
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {list: list,program_id: program_id,confirm: 1},
            url: "index.php?r=proj/assignuser/entrancedevice",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_submit'); ?>");
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_submit'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
</script>

<div class="row">
    <div class="col-xs-12">
        
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('device_toolBox',array('program_id' => $program_id)); ?>
                    <br>
                    <div id="datagrid"><?php $this->actionDeviceGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
