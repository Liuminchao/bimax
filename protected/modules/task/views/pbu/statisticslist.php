<script src="js/loading.js"></script>
<script type="text/javascript">
    $(function(){
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
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header p-0 border-bottom-0">
                <div class="row" style="margin-bottom: 8px;">
                    <div class="col-9" style="text-align: right;margin-bottom: 0px;">
                        <ul class="nav nav-pills" role="tablist" id="myTab">
                            <?php
                            if($pbu_tag == '1'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=1" >PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }else if($pbu_tag == '2'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=1">PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }else if($pbu_tag == '3'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=1">PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <?php
                    $block_list = ProgramBlockChart::locationBlockbyType($project_id,$pbu_tag);
                    foreach($block_list as $i => $j){
                        if($j == $block){
                            $tag = ' active ';
                        }else{
                            $tag = '';
                        }
                        ?>
                        <li class="nav-item"><a class="nav-link<?php echo $tag ?>" href="index.php?r=task/pbu/statisticslist&program_id=<?php echo $project_id ?>&block=<?php echo $j; ?>&pbu_tag=<?php echo $pbu_tag; ?>" ><?php echo $j ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('statistics_toolBox',array('program_id'=>$project_id,'args'=>$args,'block'=>$block,'pbu_tag'=>$pbu_tag)); ?>
                    <div id="datagrid"><?php $this->actionStatisticsGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
