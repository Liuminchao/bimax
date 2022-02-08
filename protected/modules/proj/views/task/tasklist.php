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
        return;
    }
    
    //添加一级任务
    var itemAdd = function (id) {
        //alert(id);
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('task', 'smallHeader New'); ?>";
        modal.url = "index.php?r=proj/task/new&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + id;
        modal.modal();
        //window.location = "index.php?r=proj/task/new&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + id;
    }
    
    //添加子任务
    var itemAddSubtask = function (program_id,task_id) {
        //alert(id);
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('task', 'smallHeader Subtask New'); ?>";
        modal.url = "index.php?r=proj/task/newsubtask&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + program_id+'&task_id='+task_id;
        modal.modal();
        //window.location = "index.php?r=proj/task/new&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + id;
    }
    //修改一级任务
    var itemEdit = function (program_id,task_id,father_taskid) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('task', 'smallHeader Edit'); ?>";
        modal.url = "index.php?r=proj/task/edit&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + program_id+"&task_id="+task_id+"&father_taskid="+father_taskid;
        modal.modal();
    }
    
    
    //修改子任务
    var itemEditSubtask = function (program_id,task_id,father_taskid) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('task', 'smallHeader Subtask Edit'); ?>";
        modal.url = "index.php?r=proj/task/editsubtask&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id=" + program_id+"&task_id="+task_id+"&father_taskid="+father_taskid;
        modal.modal();
    }
    //设置任务成员
    var itemSet = function (program_id,task_id) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('task', 'smallHeader Set'); ?>";
        modal.url = "index.php?r=proj/task/set&program_id=" + program_id+"&task_id="+task_id;
        modal.modal(); 
    }
    
    //删除
    var itemDel = function (father_taskid, task_id, name) {
        if(father_taskid==0){
            if (!confirm(name+"<?php echo Yii::t('task', 'delete_father_task'); ?>")) {
            return;
            }
        }else{
            if (!confirm("<?php echo Yii::t('common', 'confirm_delete_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_delete_2'); ?>")) {
            return;
        }
        }
        
        $.ajax({
            data: {father_taskid: father_taskid, task_id: task_id, confirm: 1},
            url: "index.php?r=proj/task/delete",
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
    
    //导出报表
    var itemExport =function(){
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';
        //var order = '';
        //var page = document.getElementsByClassName("page selected")[0];

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        //if( typeof page == "undefined" ) {
            //page = 1;
       // }else{
           // page = page.innerText;
       // }
        //var act = document.getElementById("export");
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('task', 'smallHeader Export'); ?>";
        modal.url = "index.php?r=proj/export/texport"+url;
        modal.modal();
       // act.href = "index.php?r=proj/export/export"+url;
    }
    //查询附件列表
    var itemAttach = function (program_id,task_id) {
        window.location = "index.php?r=proj/upload/attachlist&program_id="+program_id+'&task_id='+task_id;
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
            url: "index.php?r=proj/task/detail",
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
                    <?php $this->renderPartial('_tasktoolBox', array('father_model'=>$father_model,'program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>