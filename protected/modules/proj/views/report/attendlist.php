<script type="text/javascript">
    //查询
    var itemQuery = function () {//alert($('#form_program_id').val());
        if ( $('#form_program_id').val() == '') {
            alert('<?php echo Yii::t('proj_report', 'alert_program_id'); ?>');
            return;
        }
        
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
   var itemExport = function () {
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
        var act = document.getElementById("export");
        act.href = "index.php?r=proj/report/export"+url;
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('attend_toolBox', array('program_list'=>$program_list, 'role_list'=>$role_list)); ?>
                    <div id="datagrid"><?php $this->actionAttendGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>