<style type="text/css">
    .none_input{
        border:0;​
    outline:medium;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <div class="row" style="margin-bottom: 8px;">
                    <div class="col-9" style="text-align: right;margin-bottom: 0px;">
                        <ul class="nav nav-pills" role="tablist" id="myTab">
                            <?php
                                if($pbu_tag == '1'){
                            ?>
                                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                    <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=1" >PBU</a></li>
                                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                            <?php
                                }else if($pbu_tag == '2'){
                            ?>
                                    <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=1">PBU</a></li>
                                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                            <?php
                                }else if($pbu_tag == '3'){
                            ?>
                                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=1">PBU</a></li>
                                    <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                    <div class="col-3" style="text-align: right;margin-bottom: 0px;">
                        <label class="padding-lr5 float-sm-right">

                        </label>
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
                        <li class="nav-item"><a class="nav-link<?php echo $tag ?>" href="index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&block=<?php echo $j; ?>&pbu_tag=<?php echo $pbu_tag; ?>" ><?php echo $j ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body" style="overflow-x: auto">
                <div class="tab-content">
                    <?php
                    $block_list = ProgramBlockChart::locationBlock($project_id,$pbu_tag);
                    foreach($block_list as $i => $j){
                        if($j == $block){
                            $tag = 'active ';
                            ?>
                            <div class="<?php echo $tag ?>tab-pane" id="<?php echo $j ?>">
                                <div class="row">
                                    <?php
                                        $data_list = ProgramBlockChart::detailPartList($project_id,$j,$select_stage_id,$pbu_tag);
                                        $detail_list = $data_list['data'];
                                        $total_cnt = $data_list['total_cnt'];
                                        $act_cnt = $data_list['act_cnt'];
                                        $unit_list = ProgramBlockChart::locationUnit($project_id,$j,$pbu_tag);
                                        $unit_cnt = ProgramBlockChart::pbuByUnit($project_id,$j,$pbu_tag);
                                    ?>
                                    <div class="col-2">
                                        <input type="hidden" id="project_id" value="<?php echo $project_id; ?>">
                                        <select class="form-control input-sm" name="q[template_id]"  id="template_id" style="width: 100%;">
                                            <option value="">--Template Name--</option>
                                            <?php
                                            $template_list = TaskTemplate::templateByProgram($project_id);
                                            $stage_model = TaskStage::model()->findByPk($select_stage_id);
                                            $select_template_id = $stage_model->template_id;
                                            if(count($template_list)>0){
                                                foreach ($template_list as $template_id => $template_name) {
                                                    $selected = '';
                                                    if($select_template_id == $template_id){
                                                        $selected = 'selected';
                                                    }
                                                    echo "<option value='{$template_id}'  $selected>{$template_name}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select class="form-control input-sm" id="stage_id" name="q[stage_id]" style="width: 100%;" onchange="changestage()">
                                            <option value="">--Stage Name--</option>
                                            <?php
                                            if($select_template_id){
                                                $stage_list = TaskStage::queryStage($select_template_id);
                                                if(count($stage_list)>0){
                                                    foreach ($stage_list as $stage_id => $stage_name) {
                                                        $selected = '';
                                                        if($select_stage_id == $stage_id){
                                                            $selected = 'selected';
                                                        }
                                                        echo "<option value='{$stage_id}'  $selected>{$stage_name}</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        Qty:<?php echo $act_cnt ?>/<?php echo $total_cnt ?>
                                    </div>
                                    <div class="col-5">
                                        <div class="padding-lr5 float-sm-right">
                                            <button class="btn btn-primary btn-sm" onclick="itemUnit('<?php echo $j ?>','<?php echo $project_id ?>','<?php echo $pbu_tag ?>')">Unit No.</button>
                                            <button class="btn btn-primary btn-sm" onclick="itemPart('<?php echo $j ?>','<?php echo $project_id ?>','<?php echo $pbu_tag ?>')">Part/Zone</button>
                                            <?php
                                                $operator_id = Yii::app()->user->id;
                                                $user = Staff::userByPhone($operator_id);
                                                $args['user_id'] = $user[0]['user_id'];
                                                $args['program_id'] = $project_id;
                                                $args['type'] = $pbu_tag;
                                                $args['block'] = $block;
                                                $operate_tag = TaskBlockPerson::querySelf($args);
                                                $set_tag = '0';
                                                if(!empty($operate_tag)){
                                                    $set_tag = $operate_tag['web_edit'];
                                                }
                                                if($set_tag =='1'){
                                            ?>
                                                    <button class="btn btn-primary btn-sm" onclick="itemEditDate('<?php echo $project_id ?>')">Actual Date</button>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <table class="table table-bordered dataTable" id="projlist" aria-describedby="example2_info">
                                        <tr align="center">
                                            <td>Level/Unit</td>
                                            <?php
                                            if(count($unit_list)>0){
                                                foreach($unit_list as $x => $y){
                                                    $unit_nos = $y['unit_nos'];
                                                    $cnt = $unit_cnt[$unit_nos]['cnt'];
                                                    echo "<td align='center' colspan='$cnt'>$unit_nos</td>";
                                                }
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                        if(count($detail_list)>0){
                                            foreach($detail_list as $level => $level_array){
                                                echo "<tr><td  align='center'>$level</td>";
                                                foreach($unit_list as $x => $y){
                                                    $unit_nos = $y['unit_nos'];
                                                    $pbu_list = $level_array[$unit_nos];
                                                    $cnt = $unit_cnt[$unit_nos]['cnt'];
                                                    $index =0;
                                                    if(count($pbu_list) > 0){
                                                        foreach ($pbu_list as $pbu_index => $pbu_info){
                                                            $part = $pbu_info['part'];
                                                            $pbu_id = $pbu_info['pbu_id'];
                                                            $pbu_type = $pbu_info['pbu_type'];
                                                            $end_date = $pbu_info['end_date'];
                                                            $end_status = $pbu_info['end_status'];
                                                            $color = $pbu_info['color'];
                                                            $plan_date = 'Plan Date: '.$pbu_info['plan_date'];
                                                            $td = "<td align='center' title='$plan_date' bgcolor='$color'>$part<br>$pbu_type";
                                                            if($end_date != ''){
                                                                $td.="<br><input id='$pbu_id' style='text-align:center;background-color:$color' class='form-control input-sm none_input act_date' type='text' value='$end_date' onchange='save_act(\"$pbu_id\",\"$end_date\",\"$pbu_tag\")'  disabled='disabled' >";
                                                            }
                                                            $td.="<a onclick='show_task(\"$pbu_id\",\"$project_id\",\"$select_stage_id\")'>$end_status</a>";
                                                            $td.="</td>";
                                                            echo $td;
                                                            $index++;
                                                        }
                                                    }
                                                    for($index;$index < $cnt;$index++){
                                                        echo "<td align='center'></td>";
                                                    }
                                                }
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        <?php }else{
                            $tag = '';
                        }
                        ?>
                        <?php
                    }
                    ?>
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
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
    var itemCreate = function (project_id,pbu_tag) {
        window.location = "index.php?r=task/pbu/create&project_id="+project_id+"&pbu_tag="+pbu_tag;
    }
    var itemEditDate = function (project_id) {
        $('.act_date').attr("disabled",false);
    }
    var changestage = function(){
        var stage_id = $('#stage_id').val();
        window.location = "index.php?r=task/pbu/list&program_id=<?php echo $project_id ?>&block=<?php echo $block ?>&pbu_tag=<?php echo $pbu_tag ?>&stage_id="+stage_id;
    }
    var itemUnit = function (block,project_id,pbu_tag) {
        var modal = new TBModal();
        modal.title = "Change Unit No.";
        modal.url = "index.php?r=task/pbu/changeunit&block=" + block+"&project_id="+project_id+"&pbu_tag="+pbu_tag;
        modal.modal();
    }
    var itemPart = function (block,project_id,pbu_tag) {
        var modal = new TBModal();
        modal.title = "Create Zone";
        modal.url = "index.php?r=task/pbu/changepart&block=" + block+"&project_id="+project_id+"&pbu_tag="+pbu_tag;
        modal.modal();
    }
    //Stage初始化
    function StageInit(stage) {
        return stage.html('<option value="">--Stage Name--</option>');
    }
    var save_act = function(pbu_id,bak_end_date,pbu_tag){
        var end_date = $('#'+pbu_id).val();
        var project_id = $('#project_id').val();
        var stage_id = $('#stage_id').val();
        $.ajax({
            type: "POST",
            url: "index.php?r=task/pbu/updateactdate",
            data: {pbu_id:pbu_id,bak_end_date:bak_end_date,end_date:end_date,stage_id:stage_id,project_id:project_id,pbu_tag:pbu_tag},
            dataType: "json",
            success: function(data){ //console.log(data);
                console.log('success');
            },
        });
    }
    var show_task = function(pbu_id,project_id,stage_id){
        console.log(pbu_id);
        console.log(project_id);
        console.log(stage_id);
        // $.ajax({
        //     type: "POST",
        //     url: "index.php?r=task/pbu/showtask",
        //     data: {pbu_id:pbu_id,stage_id:stage_id,project_id:project_id},
        //     dataType: "json",
        //     success: function(data){ //console.log(data);
        //         console.log('success');
        //     },
        // });
        var modal = new TBModal();
        modal.title = "Task";
        modal.url = "index.php?r=task/pbu/showtask&pbu_id="+pbu_id+"&project_id="+project_id+"&stage_id="+stage_id;
        modal.modal();
    }
</script>