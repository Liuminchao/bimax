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
            alert('<?php echo Yii::t('sys_optlog','tip_month');?>');
            return false;
        }

    }
    
    //查询
    var itemQuery = function() {
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

    //参数设置
    var itemParams = function (program_id) {
        var modal = new TBModal();
        modal.title = '<?php echo Yii::t('proj_project','params');?>';
        modal.url = "index.php?r=sys/main/setparams&program_id="+program_id;
        modal.modal();
        // window.location = "index.php?r=proj/project/applist&program_id="+program_id;
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
            url: "index.php?r=sys/optlog/detail",
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