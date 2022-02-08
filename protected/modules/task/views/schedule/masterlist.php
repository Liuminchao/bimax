<script src="js/loading.js"></script>
<script type="text/javascript">
    $(function(){
        itemQuery_1();
        itemQuery();
    });
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

    //查询
    var itemQuery_1 = function () {
        var objs = document.getElementById("_query_form_2").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';
        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            console.log(obj);
            url += '&' + obj.name + '=' + obj.value;
        }
        <?php echo $this->gridId_1; ?>.condition = url;
        <?php echo $this->gridId_1; ?>.refresh();
    }

    //启用
    var itemStart = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_start_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_start_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=comp/role/start",
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
    var itemStop = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_stop_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_stop_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=qa/import/stop",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_stop'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_stop'); ?>");
                }
            }
        });
    }

    //修改
    var itemCreate = function (id) {
        var modal = new TBModal();
        modal.title = "Create";
        modal.url = "index.php?r=task/model/create&id=" + id;
        modal.modal();
    }

    //批量导出
    var itemExportQr = function (program_id) {
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
                    var id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += id + '|';
                    i++;
                }
            }
        })
        if(tag != ''){
            tag = tag.substr(0,tag.length-1);
        }else{
            alert('Please select Pbu Info.');
            return false;
        }
        addcloud();
        ajaxReadData(tag,i,0,program_id);
    }
    var per_read_cnt = 20;
    /*
    * 加载数据
    */
    var ajaxReadData = function (tag, rowcnt, startrow, program_id){
        jQuery.ajax({
            data: {tag:tag,startrow: startrow, per_read_cnt:per_read_cnt, program_id:program_id},
            type: 'post',
            url: './index.php?r=task/model/createqrpdf',
            dataType: 'json',
            success: function (data, textStatus) {
                if (rowcnt > startrow) {
                    ajaxReadData(tag,rowcnt, startrow+per_read_cnt, program_id);
                }else{
                    clearCache();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(XMLHttpRequest);
                alert(textStatus);
                alert(errorThrown);
            },
        });
        return false;
    }
    /*
    * 清除缓存，下载压缩包
    */
    var clearCache = function(){//alert('aa');
        removecloud();
        window.location = "index.php?r=task/model/downloadqrzip";
    }
</script>

<div class="row">
    <div class="col-6">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('masterone_toolBox',array('program_id'=>$program_id,'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionMasterOneGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('mastertwo_toolBox',array('program_id'=>$program_id,'args'=>$args)); ?>
                    <div id="datagrid_1"><?php $this->actionMasterTwoGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
