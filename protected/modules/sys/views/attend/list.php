<script type="text/javascript">
    //验证是否跨月查询
    function verifyDate() {
        var start_date = document.getElementById("q_start_date").value;
        var end_date = document.getElementById("q_end_date").value;
        var new_start_date = start_date.substr(0, 7);
        var new_end_date = end_date.substr(0, 7);
        if (new_start_date == new_end_date) {
            return true;
        } else {
            alert('<?php echo Yii::t('sys_optlog','tip_month'); ?>');
            return false;
        }

    }
    
    //查询
    var itemQuery = function() {
         if (verifyDate() == false)
            return;
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