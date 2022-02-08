<div class="container-fluid" >
<!--    <div class="row" style="margin-top: 8px;margin-bottom: 50px;display:inline-block;text-align: center">-->
<!--        <div class="col-sm-12 offset-md-10 control-label padding-lr5" style="text-align: left">-->
<!--            <h2 >I want to forward a</h2>-->
<!--        </div>-->
<!--    </div>-->
    <div class="row" style="margin-top: 8px;margin-bottom: 10px; ">
        <input type="hidden" id="program_id" value="<?php echo $project_id ?>">
        <input type="hidden" id="pbu_tag" value="<?php echo $pbu_tag ?>">
        <div class="col-sm-4 control-label padding-lr5" style="text-align: left">
            <button id="draft_btn" type="button" class="btn btn-primary" style="background-color: #4682b4" onclick="manual('<?php echo $project_id ?>','<?php echo $pbu_tag ?>')" >Manual Entry</button>
        </div>
        <div class="col-sm-4 control-label padding-lr5" style="text-align: center">
            <button id="draft_btn" type="button" class="btn btn-primary" style="background-color: #4682b4" onclick="itemAdd('<?php echo $project_id ?>','<?php echo $pbu_tag ?>')" >Upload Excel</button>
        </div>
        <div class="col-sm-4 control-label padding-lr5" style="text-align: left">
            <?php
                $pro_model = Program::model()->findByPk($project_id);
                $root_proid = $pro_model->root_proid;
                $app_module = ProgramApp::myModuleList($root_proid);
                $is_lite = $app_module[0]['is_lite'];
                if($is_lite == '0'){
            ?>
                    <button id="save_btn" type="button" class="btn btn-primary" style="background-color: #4682b4;float: right" onclick="bim('<?php echo $project_id ?>','<?php echo $pbu_tag ?>')" >BIM Model</button>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    //手动设置
    function manual(id,pbu_tag) {
        var modal = new TBModal();
        if(pbu_tag == '1'){
            modal.title = "Manual Allocate Pbu Type";
        }else if(pbu_tag == '2'){
            modal.title = "Manual Allocate PPVC Type";
        }else if(pbu_tag == '3'){
            modal.title = "Manual Allocate Precast Type";
        }
        modal.url = "index.php?r=task/pbu/manual&project_id=" + id+"&pbu_tag="+pbu_tag;
        modal.modal();
        itemQuery();
    }
    //从bim 获取
    var bim = function (id,pbu_tag) {
        var modal = new TBModal();
        modal.title = "BIM Model";
        modal.url = "index.php?r=task/pbu/bim&project_id=" + id+"&pbu_tag="+pbu_tag;
        modal.modal();
        itemQuery();
    }
    var itemAdd = function (id,pbu_tag) {
        window.location = "index.php?r=task/model/view&program_id="+id+"&pbu_tag="+pbu_tag;
    }
</script>