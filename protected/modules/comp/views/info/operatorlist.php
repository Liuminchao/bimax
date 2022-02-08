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
    //返回
    var back = function (id,name) {
        window.location = "index.php?r=comp/info/list";
    }
    //上传
    var itemSet = function (operator_id,contractor_id,contractor_name) {
        window.location = "index.php?r=comp/info/setapp&operator_id="+operator_id+'&contractor_id='+contractor_id+'&contractor_name='+contractor_name;
    }
    //添加操作员
    var add = function(id,name) {
        window.location = "index.php?r=comp/info/addoperator&id="+id+'&name='+name;
    }
    //项目组成员
    var itemPro = function (operator_id,company_id,company_name) {
        window.location = "index.php?r=comp/info/authoritylist&operator_id=" + operator_id+"&contractor_id="+company_id+"&company_name="+company_name;
    }
    //删除操作员
    var itemDelete = function (id,con_id,con_name) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete_1'); ?><?php echo Yii::t('common', 'confirm_delete_2'); ?>')) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=comp/info/deleteoperator",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    window.location = "index.php?r=comp/info/operatorlist&id="+con_id+"&name="+con_name;
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
        <div class="card card-info ">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper " id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('operator_toolBox',array('id' => $id,'name' => $name)); ?>
                    <div id="datagrid"><?php $this->actionOperatorGrid($id,$name); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>