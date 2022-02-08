<div class="row" >
    <div class="col-12">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="row" >
                    <input type="hidden" id="project_id" name="q[project_id]"  value="<?php echo $program_id; ?>">
                    <input type="hidden" id="pbu_tag" name="q[pbu_tag]"  value="<?php echo $pbu_tag; ?>">
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 180px;">
                        <select class="form-control input-sm" name="q[template_id]"  id="template_id" style="width: 100%;">
                            <option value="">--Template Name--</option>
                            <?php
                                $template_list = TaskTemplate::templateByProgram($program_id);
                                if(count($template_list)>0){
                                    foreach ($template_list as $template_id => $template_name) {
                                        $selected = '';
                                        if($args['template_id'] == $template_id){
                                            $selected = 'selected';
                                        }
                                        echo "<option value='{$template_id}'  $selected>{$template_name}</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 160px;">
                        <select class="form-control input-sm" id="stage_id" name="q[stage_id]" style="width: 100%;">
                            <option value="">--Stage Name--</option>
                            <?php
                                if($args['template_id']){
                                    $stage_list = TaskStage::queryStage($args['template_id']);
                                    if(count($stage_list)>0){
                                        foreach ($stage_list as $stage_id => $stage_name) {
                                            $selected = '';
                                            if($args['stage_id'] == $stage_id){
                                                $selected = 'selected';
                                            }
                                            echo "<option value='{$stage_id}'  $selected>{$stage_name}</option>";
                                        }
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 160px;">
                        <select id="modellist" class="form-control input-sm" name="q[modellist]" style="width: 100%;">
                            <option value="0">No Model</option>
                        </select>
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 120px;">
                        <select id="block" class="form-control input-sm" name="q[block]" style="width: 100%;">
                            <option value="">--Block--</option>
                            <?php
                                $block_list = RevitComponent::blockByModel('0',$program_id,$pbu_tag);
                                foreach($block_list as $i => $j) {
                                    $selected = '';
                                    if($args['block'] == $j){
                                        $selected = 'selected';
                                    }
                                ?>
                                <option value="<?php echo $j ?>"  <?php echo $selected ?>><?php echo $j ?></option>
                                <?php
                                }
                            ?>
                        </select>
                    </div>
                            <div class="form-group padding-lr5" style="padding-bottom:5px;width: 120px;">
                                <select id="level" class="form-control input-sm" name="q[level]" style="width: 100%;">
                                    <option value="">--Level--</option>
                                    <?php
                                    $level_list = RevitComponent::levelByModel('0',$program_id,$pbu_tag);
                                    foreach($level_list as $i => $j) {
                                        $selected = '';
                                        if($args['level'] == $j){
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?php echo $j ?>"   <?php echo $selected ?>><?php echo $j ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group padding-lr5" style="padding-bottom:5px;width: 120px;">
                                <select id="part" class="form-control input-sm" name="q[part]" style="width: 100%;">
                                    <option value="">--Part--</option>
                                    <?php
                                    $part_list = RevitComponent::partByModel('0',$program_id,$pbu_tag);
                                    foreach($part_list as $i => $j) {
                                        $selected = '';
                                        if($args['part'] == $j){
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?php echo $j ?>"   <?php echo $selected ?>><?php echo $j ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group padding-lr5" style="padding-bottom:5px;width: 140px;">
                                <select id="property" class="form-control input-sm" name="q[property]" style="width: 100%;">
                                    <option value="">--Property--</option>
                                    <option value="model_id" <?php if($args['property'] == 'model_id') echo "selected"; ?>>Model Id</option>
                                    <option value="pbu_id" <?php if($args['property'] == 'pbu_id') echo "selected"; ?>>GUID</option>
                                    <option value="block" <?php if($args['property'] == 'block') echo "selected"; ?>>Block</option>
                                    <option value="level" <?php if($args['property'] == 'level') echo "selected"; ?>>Level</option>
                                    <option value="unit_nos" <?php if($args['property'] == 'unit_nos') echo "selected"; ?>>Unit No.</option>
                                    <option value="part" <?php if($args['property'] == 'part') echo "selected"; ?>>Zone</option>
                                    <option value="unit_type" <?php if($args['property'] == 'unit_type') echo "selected"; ?>>Unit Type</option>
                                    <option value="serial_number" <?php if($args['property'] == 'serial_number') echo "selected"; ?>>Serial No</option>
                                    <option value="pbu_type" <?php if($args['property'] == 'pbu_type') echo "selected"; ?>>
                                        <?php if($pbu_tag == '1'){ ?>
                                            PBU Type
                                        <?php }else if($pbu_tag == '2'){ ?>
                                            PPVC Type
                                        <?php }else if($pbu_tag == '3'){ ?>
                                            Precast Type
                                        <?php } ?>
                                    </option>
                                    <option value="pbu_name" <?php if($args['property'] == 'pbu_name') echo "selected"; ?>>QR Code ID</option>
                                    <option value="module_type" <?php if($args['property'] == 'module_type') echo "selected"; ?>>Module Type</option>
                                    <option value="precast_plant" <?php if($args['property'] == 'precast_plant') echo "selected"; ?>>Other</option>
                                </select>
                            </div>
                            <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                                <input type="text" class="form-control input-sm" name="q[property_name]" placeholder="Property Name" style="width: 100%;"   value="<?php echo array_key_exists('property_name',$args)?$args['property_name']:""; ?>">
                            </div>
                            <a class="tool-a-search" style="" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                        </div>
                    </form>
            </div>
    </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var program_id = $('#project_id').val();
        var pbu_tag = $('#pbu_tag').val();
        var formData = new FormData();
        let modeldata = new Map();
        formData.append("project_id",program_id);
        $.ajax({
            url: "index.php?r=rf/rf/modellist",
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,         // 告诉jQuery不要去处理发送的数据
            contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
            beforeSend: function () {

            },
            success: function(data){
                $.each(data, function (name, value) {
                    let temp = {};
                    temp._id = value['model_id'];
                    temp._version = value['version'];
                    temp._name = value['model_name'];
                    modeldata.set(value['model_name'], temp);
                })
                // let modelarray = await data.getWINDDataQuerier().getAllModelParameterS();
                let model_name = $('#model_name').val();
                console.log(modeldata);
                let modellistUI = document.getElementById("modellist");
                let select_index = 0;
                modeldata.forEach((model, name) => {
//                    console.log(model);
                    //new Option(text,value)
                    id_version = model._id+'_'+model._version;
                    var model_id = model._id;
                    if(program_id == '1453'){
                        if(pbu_tag == '1' && name.indexOf("PBU") != -1){
                            if(id_version == '<?php echo $args['modellist']; ?>'){
                                select_index++;
                            }
                            modellistUI.add(new Option(name,id_version));
                        }else if(pbu_tag == '3' && name.indexOf("Precast") != -1){
                            if(id_version == '<?php echo $args['modellist']; ?>'){
                                select_index++;
                            }
                            modellistUI.add(new Option(name,id_version));
                        }else if(pbu_tag == '2' && name.indexOf("PBU") == -1 && name.indexOf("Precast") == -1){
                            if(id_version == '<?php echo $args['modellist']; ?>'){
                                select_index++;
                            }
                            modellistUI.add(new Option(name,id_version));
                        }
                    }else{
                        if(id_version == '<?php echo $args['modellist']; ?>'){
                            select_index++;
                        }
                        modellistUI.add(new Option(name,id_version));
                    }
                });
                if(select_index != 0){
                    modellistUI.options[select_index].selected = true;
                }else{
                    modellistUI.options[0].selected = true;//默认选中第一个
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                //alert(textStatus);
            },
        });

        //模版 阶段  任务半联动
        $('#template_id').change(function(){

            var stageObj = $("#stage_id");
            var stageOpt = $("#stage_id option");
            StageInit(stageObj);

            $.ajax({
                type: "POST",
                url: "index.php?r=task/task/querystage",
                data: {template_id:$("#template_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        stageObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });
    })
    //Block初始化
    function BlockInit(block) {
        return block.html('<option value="">--Block--</option>');
    }
    //Level初始化
    function LevelInit(level) {
        return level.html('<option value="">--Level--</option>');
    }
    //Part初始化
    function PartInit(part) {
        return part.html('<option value="">--Part--</option>');
    }
    //模版 阶段  任务半联动
    $('#modellist').change(function(){
        var pbu_tag = $('#pbu_tag').val();
        var blockObj = $("#block");
        var blockOpt = $("#block option");
        var levelObj = $("#level");
        var levelOpt = $("#level option");
        var partObj = $("#part");
        var partOpt = $("#part option");
        var program_id = $('#project_id').val();
        $.ajax({
            type: "POST",
            url: "index.php?r=task/model/queryblock",
            data: {modellist:$("#modellist").val(),program_id:program_id,pbu_tag:pbu_tag},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                BlockInit(blockObj);
                for (var o in data) {//console.log(o);
                    if(data[o] == '<?php echo $args['block']; ?>'){
                        blockObj.append("<option value='"+data[o]+"' selected>"+data[o]+"</option>");
                    }else{
                        blockObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
                    }
                }
            },
        });
        $.ajax({
            type: "POST",
            url: "index.php?r=task/model/querylevel",
            data: {modellist:$("#modellist").val(),program_id:program_id,pbu_tag:pbu_tag},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                LevelInit(levelObj);
                for (var o in data) {//console.log(o);
                    if(data[o] == '<?php echo $args['level']; ?>'){
                        levelObj.append("<option value='"+data[o]+"' selected>"+data[o]+"</option>");
                    }else{
                        levelObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
                    }
                }
            },
        });
        $.ajax({
            type: "POST",
            url: "index.php?r=task/model/querypart",
            data: {modellist:$("#modellist").val(),program_id:program_id,pbu_tag:pbu_tag},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                PartInit(partObj);
                for (var o in data) {//console.log(o);
                    if(data[o] == '<?php echo $args['part']; ?>'){
                        partObj.append("<option value='"+data[o]+"' selected>"+data[o]+"</option>");
                    }else{
                        partObj.append("<option value='"+data[o]+"'>"+data[o]+"</option>");
                    }
                }
            },
        });
    });
    //Stage初始化
    function StageInit(stage) {
        return stage.html('<option value="">--Stage Name--</option>');
    }

</script>