<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/javascript">
    //查询
    var itemQuery = function () {
        var objs = document.getElementById("query_form").elements;
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

    var itemAdd = function (id) {
        window.location = "index.php?r=proj/project/subnew&ptype=<?php echo Yii::app()->session['project_type'];?>&father_proid="+id;
    }
    //项目组成员
    var itemTeam = function (id,name) {
        window.location = "index.php?r=proj/assignuser/subauthoritylist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
    //项目组设备
    var itemDevice = function (id,name) {
        window.location = "index.php?r=proj/assignuser/subdevicelist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id+"&name="+name;
    }
    //修改
    var itemEdit = function (id) {
        window.location = "index.php?r=proj/project/subedit&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
    //分包商查看设备
    var itemSubDevice = function (con_id,pro_id,tag) {
        window.location = "index.php?r=proj/project/subdevicelist&contractor_id=" + con_id + "&program_id=" + pro_id +"&tag=" +tag;
    }
    //分包商查看人员
    var itemSubStaff = function (con_id,pro_id,tag) {
        window.location = "index.php?r=proj/project/substafflist&contractor_id=" + con_id + "&root_proid=" + pro_id +"&tag=" +tag;
    }
    var itemWorkforce = function (program_id) {
        var diag = new Dialog();
        diag.Width = 930;
        diag.Height = 980;
        diag.Title = "Manpower Sync";
        diag.URL = "syncworkforce&program_id="+program_id;
        diag.show();
    }
    //启用
    var itemStart = function (id, name) {

        if (!confirm("<?php echo Yii::t('common', 'confirm_start_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_start_2'); ?>")) {
            return;
        }
       // alert("index.php?r=proj/project/start");

        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/project/start",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_start'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_start'); ?>");
                }
            }
        });
    }
    //停用
    var itemStop = function (id, name,con_name) {
        if (!confirm("<?php echo Yii::t('proj_project', 'sc_confirm_stop_1') ?>" + con_name + "<?php echo Yii::t('proj_project', 'sc_confirm_stop_2'); ?>"+ name + "<?php echo Yii::t('proj_project', 'sc_confirm_stop_3'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/project/stop",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_stop'); ?>");
                    itemQuery();
                } else {
                    alert(data.msg);
                }
            }
        });
    }
    //删除
    var itemDel = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_delete_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_delete_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/project/delete",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    itemQuery();
                } else {
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

    var getDetail = function (obj, id) {
        if (detailobj[id]) {
            showDetail(obj, detailobj[id], true);
            return;
        }
        var detail = "";
        $.ajax({
            data: {id: id},
            url: "index.php?r=proj/project/detail",
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


<!-- Main content -->

<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_subtoolBox', array('args'=>$args, 'father_model'=>$father_model,'father_proid'=>$father_proid)); ?>
                    <div id="datagrid"><?php $this->actionSubGrid($father_proid); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>