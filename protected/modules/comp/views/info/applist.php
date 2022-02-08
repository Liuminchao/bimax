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
    var itemAdd = function (id) {
        window.location = "index.php?r=comp/info/newapp&company_id="+id;
    }
    //返回
    var itemBack = function () {
        window.location = "index.php?r=comp/info/list";
    }
    //修改
    var itemEdit = function (company_id, app_id) {
        window.location = "index.php?r=comp/info/editapp&company_id=" + company_id +"&app_id="+app_id;
    }

    //注销
    var itemLogout = function (company_id, app_id) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_logout_1'); ?>' + name + '<?php echo Yii::t('common', 'confirm_logout_2'); ?>')) {
            return;
        }
        //alert("index.php?r=comp/info/logout&confirm=1&id="+id);
        $.ajax({
            data: {company_id: company_id,app_id: app_id, confirm: 1},
            url: "index.php?r=comp/info/logoutapp",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_logout'); ?>");
                    itemQuery();
                }
                else{
                    alert("<?php echo Yii::t('common', 'error_logout'); ?>");
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
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('app_toolBox',array('company_id'=>$company_id)); ?>
                    <div id="datagrid"><?php $this->actionAppGrid($company_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>