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
        window.location = "index.php?r=proj/assignuser/new";
    }
    //修改
    var itemEdit = function (id) {
       window.location = "index.php?r=proj/assignuser/edit&id=" + id;
    }
    //删除
    var itemDelete = function (id, name) {
        if (!confirm("确认要删除:" + name + "吗？")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/assignuser/delete",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("删除成功！");
                    itemQuery();
                } else {
                    alert("删除失败！");
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
                    <?php $this->renderPartial('_toolBox'); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>