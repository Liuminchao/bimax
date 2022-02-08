<script type="text/javascript">
    //查询
    var itemQuery = function () {
//        alert(123);
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

//        window.location = "index.php?r=comp/staff/new";
        var title = '1';
        window.location = "index.php?r=comp/staff/tabs&mode=insert&title="+title;
    }
    //标签(添加)
    var itemTabs = function () {
        window.location = "index.php?r=comp/staff/test";
    }
    //批量导入
    var itemImport = function () {
        var modal=new TBModal();
        modal.title="<?php echo Yii::t('comp_staff','Batch import');?>";
        modal.url="./index.php?r=comp/upload/view";
        modal.modal();
    }
    //批量导出
    var itemExport = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += user_id + '|';
                }
            }
        })
        if(tag != ''){
            tag = tag.substr(0,tag.length-1);
        }else{
            tag = 0;
        }

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value + '&q[tag]=' +tag;
        }
        window.location = "index.php?r=comp/upload/export" + url;
    }
    //修改
    var itemEdit = function (id) {
        var title = '2';
        window.location = "index.php?r=comp/staff/tabs&user_id=" + id+"&mode=edit&title="+title;
    }
    //借调
    var itemLoaned = function (id) {
        window.location = "index.php?r=comp/staff/loane&id=" + id;
    }
    //查询行业资质
    var itemPhoto = function (id) {
        window.location = "index.php?r=comp/staff/attachlist&user_id="+id;
    }
    //加入白名单
    var itemWhite = function (id,name) {
//        if (!confirm('<?php echo Yii::t('common','confirm_white');?>'+'<?php echo Yii::t('common', 'confirm_white_1'); ?>' + name + '<?php echo Yii::t('common', 'confirm_white_2'); ?>')) {
//            return;
//        }
        var modal=new TBModal();
        modal.title='<?php echo Yii::t('comp_staff','White_list_type');?>';
        modal.url="./index.php?r=comp/staff/whitelist&id="+id+'&name='+name;
        modal.modal();
    }
    //二维码图片
    var itemQrcode = function(id,qrcode) {
        window.location = "index.php?r=comp/staff/qrcode&user_id="+id+"qrcode_path="+qrcode_path;
    }
    //统计信息
    var itemStatistics = function(id) {
        window.location = "index.php?r=comp/staff/statistics&user_id="+id;
    }
    //加入DMS
    var itemDms = function (id) {
        $.ajax({
            data: {id: id},
            url: "index.php?r=rf/dms/newuser",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.code == '100'){
                    alert('Set successful');
                }else{
                    alert(data.message);
                }
            }
        })
    }
    //注销
    var itemLogout = function (id, name) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_logout_1'); ?>' + name + '<?php echo Yii::t('common', 'confirm_logout_2'); ?>')) {
            return;
        }
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=comp/staff/logout",
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

    var getDetail = function (obj, id) {
        if (detailobj[id]) {
            showDetail(obj, detailobj[id], true);
            return;
        }
        var detail = "";
        
        $.ajax({
            data: {id: id},
            url: "index.php?r=comp/staff/detail",
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
