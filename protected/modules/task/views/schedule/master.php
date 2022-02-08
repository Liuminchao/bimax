<style type="text/css">
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
        background-color: #ececec;
    }
</style>
<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
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
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=1" >PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }else if($pbu_tag == '2'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=1">PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }else if($pbu_tag == '3'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=1">PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <ul class="nav nav-tabs">
                    <?php
                    $block_list = ProgramBlockChart::locationBlockbyType($project_id,$pbu_tag);//$pbu_tag
                    foreach($block_list as $i => $j){
                        if($j == $block){
                            $tag = ' active ';
                        }else{
                            $tag = '';
                        }
                        ?>
                        <li class="nav-item"><a class="nav-link<?php echo $tag ?>" href="index.php?r=task/schedule/master&program_id=<?php echo $project_id ?>&block=<?php echo $j; ?>&pbu_tag=<?php echo $pbu_tag; ?>" ><?php echo $j ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <?php
                        $block_list = ProgramBlockChart::locationBlock($project_id,$pbu_tag);
                        foreach($block_list as $i => $j){
                            if($j == $block){
                                $tag = 'active ';
                    ?>
                                <div class="<?php echo $tag ?>tab-pane" id="<?php echo $j ?>">
                                    <?php
                                    /* @var $this RoleController */
                                    /* @var $model Role */
                                    /* @var $form CActiveForm */
                                    $form = $this->beginWidget('SimpleForm', array(
                                        'id' => 'form_'.$block,
                                        'enableAjaxSubmit' => true,
                                        'ajaxUpdateId' => 'content-body',
                                        'focus' => array($model, 'name'),
                                        'role' => 'form', //可省略
                                        'formClass' => 'form-horizontal', //可省略 表单对齐样式
                                    ));
                                    ?>
                                    <div class="row">
                                        <div class="col-2">
                                            <input type="hidden" value="<?php echo $block; ?>" id="block" name="Schedule[block]">
                                            <input type="hidden" value="<?php echo $project_id; ?>" id="block" name="Schedule[project_id]">
                                            <input type="hidden" value="<?php echo $pbu_tag; ?>" id="block" name="Schedule[pbu_tag]">
                                            <input type="hidden" value="" id="last_date" >
                                            <select class="form-control input-sm"  id="template_id" name="Schedule[template_id]" style="width: 100%;" onchange="change_template()">
                                                <?php
                                                $template_list = TaskTemplate::templateByProgram($project_id);
                                                if(count($template_list)>0){
                                                    foreach ($template_list as $x => $y) {
                                                        if($template_id == ''){
                                                            $template_id = $x;
                                                        }
                                                        if($template_id == $x){
                                                            echo "<option value='{$x}' selected>{$y}</option>";
                                                        }else{
                                                            echo "<option value='{$x}'>{$y}</option>";
                                                        }
                                                    }
                                                }
                                                $template_info = TaskScheduleTemplate::templateInfo($block,$project_id,$template_id);
                                                if(count($template_info)>0){
                                                    echo "<input type='hidden' value='1' id='load_tag' >";
                                                    $template_start = Utils::DateToEn($template_info[0]['template_start']);
                                                    $template_end = Utils::DateToEn($template_info[0]['template_end']);
                                                    $stage_start = Utils::DateToEn($template_info[0]['stage_start']);
                                                    $stage_end = Utils::DateToEn($template_info[0]['stage_end']);
                                                    $template_stage_id = $template_info[0]['stage_id'];
                                                    $level_from = $template_info[0]['level_from'];
                                                    $level_to = $template_info[0]['level_to'];
                                                    $avg_level = $template_info[0]['avg_level'];
                                                    $avg_zone = $template_info[0]['avg_zone'];
                                                    $adj_level = $template_info[0]['adj_level'];
                                                    $adj_zone = $template_info[0]['adj_zone'];
                                                }else{
                                                    echo "<input type='hidden' value='0' id='load_tag' >";
                                                    $template_start = '';
                                                    $template_end = '';
                                                    $stage_start = '';
                                                    $stage_end = '';
                                                    $template_stage_id = '';
                                                    $level_from = '';
                                                    $level_to = '';
                                                    $avg_level = '';
                                                    $avg_zone = '';
                                                    $adj_level = '';
                                                    $adj_zone = '';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-12" style="margin-left: -11px"><!--  模板开始结束时间  -->
                                            <div class="form-group">
                                                <label style="margin-left:10px" for="bca_service_date" class="col-2 control-label padding-lr5 label-rignt">Block Master Schedule Start:</label>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <div class="col-2 input-group date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#template_start" name="Schedule[template_start]" id="template_start" placeholder="" value="<?php echo isset($template_start)?$template_start:""; ?>" onchange="change_template_end()">
                                                    <div class="input-group-append" data-target="#template_start" data-toggle="datetimepicker">
                                                        <div class="input-group-text" style="height:34px"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <label for="work_pass_type" class="col-3 control-label padding-lr5 label-rignt" style="margin-top: 6px;">Block Master Schedule Finish:</label>
                                                <div class="col-2 input-group date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#template_end" name="Schedule[template_end]" id="template_end" placeholder="" value="<?php echo isset($template_end)?$template_end:""; ?>"  >
                                                    <div class="input-group-append" data-target="#template_end" data-toggle="datetimepicker">
                                                        <div class="input-group-text" style="height:34px"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="">
                                        <div class="col-12">
                                            <h3 class="form-header text-blue">Key Activities</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12" style="margin-left: -11px"><!--  入职时间  -->
                                            <div class="form-group">
                                                <label style="margin-left:10px" for="bca_service_date" class="col-2 control-label padding-lr5 label-rignt">Key Activity:</label>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <div class="col-2 input-group date" data-target-input="nearest">
                                                    <select class="form-control input-sm"  id="stage_id" name="Schedule[stage_id]" style="width: 100%;" >
                                                        <?php
                                                        $stage_list = TaskStage::queryStage($template_id);
                                                        if(count($stage_list)>0){
                                                            foreach ($stage_list as $stage_id => $stage_name) {
                                                                if(isset($stage_id)){
                                                                    if($template_stage_id == $stage_id){
                                                                        echo "<option value='{$stage_id}' selected>{$stage_name}</option>";
                                                                    }else{
                                                                        echo "<option value='{$stage_id}'>{$stage_name}</option>";
                                                                    }
                                                                }else{
                                                                    echo "<option value='{$stage_id}'>{$stage_name}</option>";
                                                                }

                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-12" style="margin-left: -11px"><!--  入职时间  -->
                                            <div class="form-group">
                                                <label style="margin-left:10px" for="bca_service_date" class="col-2 control-label padding-lr5 label-rignt">Key Activity Start:</label>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <div class="col-2 input-group date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#stage_start" name="Schedule[stage_start]" id="stage_start" placeholder=""  value="<?php echo isset($stage_start)?$stage_start:""; ?>"   onchange="getFloorZone()">
                                                    <div class="input-group-append" data-target="#stage_start" data-toggle="datetimepicker">
                                                        <div class="input-group-text" style="height:34px"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <label for="work_pass_type" class="col-3 control-label padding-lr5 label-rignt" style="margin-top: 6px;">Key Activity Finish:</label>
                                                <div class="col-2 input-group date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input" data-target="#stage_end" name="Schedule[stage_end]" id="stage_end" placeholder=""  value="<?php echo isset($stage_end)?$stage_end:""; ?>"  onchange="getFloorZone()" >
                                                    <div class="input-group-append" data-target="#stage_end" data-toggle="datetimepicker">
                                                        <div class="input-group-text" style="height:34px"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="">
                                        <div class="col-12">
                                            <h3 class="form-header text-blue">Cycle Time</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12" style="margin-left: -11px"><!--  入职时间  -->
                                            <div class="form-group">
                                                <label style="margin-left:10px" for="bca_service_date" class="col-3 control-label padding-lr5 label-rignt">Floor:</label>
                                                <div class="col-2 input-group date" data-target-input="nearest">
                                                    <input type="text" id="level_1" name="Schedule[level_from]" class="form-control input-sm" onchange="getFloorZone()" value="<?php echo isset($level_from)?$level_from:""; ?>">&nbsp;To&nbsp;<input type="text" id="level_2" name="Schedule[level_to]" class="form-control input-sm" onchange="getFloorZone()" value="<?php echo isset($level_to)?$level_to:""; ?>">
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <label for="bca_service_date" class="col-3 control-label padding-lr5 label-rignt">Average Zone Lag Time:</label>
                                                <input id="avg_part_lag" type="hidden" value="" name="Schedule[avg_part]"  value="<?php echo isset($avg_zone)?$avg_zone:""; ?>">
                                                <div class="col-2 input-group date" data-target-input="nearest" id="avg_part_lag_time">
                                                    <?php echo $avg_zone; ?>  days
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-12" style="margin-left: -11px"><!--  入职时间  -->
                                            <div class="form-group">
                                                <label style="margin-left:10px" for="bca_service_date" class="col-2 control-label padding-lr5 label-rignt">Zone No.:</label>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <div class="col-2 input-group date" data-target-input="nearest" id="last_zone">
                                                    <?php
                                                    $last_part = ProgramBlockChart::lastPart($project_id,$block);
                                                    $part_cnt = count($last_part);
                                                    $part_str = '';
                                                    foreach ($last_part as $i => $j){
                                                        $part_str.=$j['part'].',';
                                                    }
                                                    $part_str = substr($part_str,0,-1);
                                                    //                                                echo $last_part[$part_cnt-1]['part'];
                                                    echo $part_cnt;
                                                    ?>
                                                    <input type="hidden" id="last_part" value="<?php echo $last_part[0]['part'] ?>" >
                                                    <input type="hidden" id="part_cnt" value="<?php echo $part_cnt; ?>" >
                                                    <input type="hidden" id="part_str" value="<?php echo $part_str; ?>" >
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                                <label for="bca_service_date" class="col-3 control-label padding-lr5 label-rignt">Adjusted Zone Lag Time:</label>
                                                <div class="col-2 input-group date" data-target-input="nearest" id="adj_part_lag_time">
                                                    <input id="adj_part" type="text" class="form-control input-sm" value="<?php echo isset($adj_zone)?$adj_zone:""; ?>" name="Schedule[adj_part]" onchange="createSchedule()"> days
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-12" style="margin-left: -11px"><!--  入职时间  -->
                                            <div class="form-group">
                                                <label style="margin-left:10px" for="bca_service_date" class="col-3 control-label padding-lr5 label-rignt">Floor Zone:</label>
                                                <div class="col-3 input-group date" data-target-input="nearest" id="floor_zone">

                                                </div>
                                                <input type="hidden" id="level_part" value="">
                                                <label for="bca_service_date" class="col-3 control-label padding-lr5 label-rignt">Average Floor Cycle / Zone:</label>
                                                <input id="avg_level" type="hidden" value="" name="Schedule[avg_level]" value="<?php echo isset($avg_level)?$avg_level:""; ?>">
                                                <div class="col-2 input-group date" data-target-input="nearest" id="avg_cycle_level">
                                                    <?php echo $avg_level; ?>  days
                                                </div>
                                                <div class="col-1 input-group date" data-target-input="nearest">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-12" style="margin-left: -11px"><!--  入职时间  -->
                                            <div class="form-group">
                                                <label style="margin-left:10px" for="bca_service_date" class="col-3 control-label padding-lr5 label-rignt"></label>
                                                <div class="col-3 input-group date" data-target-input="nearest">

                                                </div>
                                                <label for="bca_service_date" class="col-3 control-label padding-lr5 label-rignt">Adjusted Floor Cycle / Zone:</label>
                                                <div class="col-2 input-group date" data-target-input="nearest" id="adj_cycle_level">
                                                    <input id="adj_level" type="text" class="form-control input-sm" value="<?php echo isset($adj_level)?$adj_level:""; ?>" name="Schedule[adj_level]"  onchange="createSchedule()"> days
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="">
                                        <div class="col-12">
                                            <h3 class="form-header text-blue">Schedule</h3>
                                        </div>
                                    </div>
                                    <div class="row" id="schedule">

                                    </div>
                                    <?php $this->endWidget(); ?>
                                    <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
                                        <div class="col-12" style="text-align: center">
                                            <button type="button" id="sbtn" class="btn btn-primary" onclick="save_schedule()">Save</button>
                                        </div>
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
    $(document).ready(function(){
        // functionvar current_date =  new Date(yyyy-mm-dd);
        // alert(current_date);
        $('#template_start').datetimepicker({
            format: 'DD MMM yyyy',
            // minDate: '2021-10-01',
            // maxDate: '2021-11-30'
        });
        // $("#template_start").on("change.datetimepicker", function (e) {
        //     if (e.oldDate !== e.date) {
        //         $('#stage_start').datetimepicker({
        //             format: 'DD MMM yyyy',
        //             minDate: '2021-10-01',
        //             maxDate: '2021-11-30'
        //         });
        //     }
        // })
        $('#template_end').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#stage_start').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#stage_end').datetimepicker({
            format: 'DD MMM yyyy'
        });
        getFloorZone();
    })

    var change_template_end = function(){
        var template_id = $('#template_id').val();
        var template_start = $('#template_start').val();
        var load_tag = $('#load_tag').val();
        $.ajax({
            url: "index.php?r=task/schedule/gettemplateend",
            data: {template_id: template_id, template_start: template_start},
            type: "POST",
            dataType: "json",
            success: function (res) {
                if(load_tag == '0'){
                    $('#template_end').val(res.template_end);
                }
            }
        });
        var template_start_ch = datetocn_new(template_start);
    }

    var change_stage_end = function(){
        var stage_id = $('#stage_id').val();
        var stage_start = $('#stage_start').val();
        var load_tag = $('#load_tag').val();
        $.ajax({
            url: "index.php?r=task/schedule/getstageend",
            data: {stage_id: stage_id, stage_start: stage_start},
            type: "POST",
            dataType: "json",
            success: function (res) {
                if(load_tag == '0'){
                    $('#stage_end').val(res.stage_end);
                }
            }
        })
    }

    var change_template = function(){
        var template_id = $('#template_id').val();
        var options=$("#template_id option:selected");
        alert(options.val());
        window.location = "index.php?r=task/schedule/master&program_id=<?php echo $project_id; ?>&block=<?php echo $block; ?>&pbu_tag=<?php echo $pbu_tag; ?>&template_id="+template_id;
    }

    var change_stage = function(){
        var stage_id = $('#stage_id').val();
        var template_id = $('#template_id').val();
        var template_start = $('#template_start').val();
        $.ajax({
            url: "index.php?r=task/schedule/getstageday",
            data: {template_id: template_id,stage_id:stage_id,template_start: template_start},
            type: "POST",
            dataType: "json",
            success: function (res) {
                $('#stage_start').val(res.stage_start);
                $('#stage_end').val(res.stage_end);
            }
        })
    }

    var getFloorZone = function(){
        var level_1 = $('#level_1').val();
        var level_2 = $('#level_2').val();
        if(level_1 != '' && level_2 != ''){
            var total_level = level_2 - level_1+1;
            var part_cnt = $('#part_cnt').val();
            var level_part_cnt = total_level * part_cnt;
            $('#level_part').val(level_part_cnt);
            $('#floor_zone').html(level_part_cnt);

            var stage_start = $('#stage_start').val();
            var stage_end = $('#stage_end').val();
            var stage_start = datetocn_new(stage_start);
            var stage_end = datetocn_new(stage_end);
            // 调用日期差方搜索法，求得参数日期与系统时间相差的天数
            var diff = DateDiff(stage_start,stage_end)-1;
            var avg_part_lag = Math.floor(diff/(level_part_cnt-1));
            $('#avg_part_lag').val(avg_part_lag);//avg_part_lag
            $('#avg_part_lag_time').html(avg_part_lag+' days');//avg_part_lag_time
            //Default = Rounddown [(Key Act Finish - Key Act Start) - Average Zone Lag x (no of Zone -1)] / (No of floor -1)
            var avg_cycle = Math.floor((diff-avg_part_lag*(part_cnt-1))/(total_level-1));
            $('#avg_level').val(avg_cycle);//avg_part_lag
            $('#avg_cycle_level').html(avg_cycle+' days');//avg_part_lag_time
            createSchedule();
        }
    }

    var createSchedule = function(){
        var template_id = $('#template_id').val();
        var select_stage_id = $('#stage_id').val();
        var stage_start = $('#stage_start').val();
        var part = $('#part_str').val();
        var part_list = part.split(',');
        console.log(part_list);
        var stage_data = '';
        $.ajax({
            url: "index.php?r=task/schedule/stagedetail",
            data: {template_id: template_id,stage_id:select_stage_id,stage_start:stage_start},
            type: "POST",
            dataType: "json",
            success: function (res) {
                stage_data = res;
                $('#schedule').empty();
                var level = $('#level_1').val();
                var level_2 = $('#level_2').val();
                var level_index = 0;
                var level_str = "<div class='col-2 col-sm-2'><div class='nav flex-column nav-tabs h-100' id='vert-tabs-tab' role='tablist' aria-orientation='vertical'>";
                for(level_2;level_2>=level;level_2--){
                    if(level_index == 0){
                        level_str+= "<a class='nav-link active' id='vert-tabs-"+level_2+"-tab' data-toggle='pill' href='#vert-tabs-"+level_2+"' role='tab' aria-controls='vert-tabs-home' aria-selected='true'>Level "+level_2+"</a>";
                    }else{
                        level_str+= "<a class='nav-link' id='vert-tabs-"+level_2+"-tab' data-toggle='pill' href='#vert-tabs-"+level_2+"' role='tab' aria-controls='vert-tabs-home' aria-selected='true'>Level "+level_2+"</a>";
                    }
                    level_index++;
                }
                level_str+= "</div></div>";
                var part_index = 0;
                var part_str = "<div class='col-10 col-sm-10'><div class='tab-content' id='vert-tabs-tabContent'>";
                var level_2 = $('#level_2').val();
                var last_level = $('#level_2').val();
                var avg_part_lag = Number($('#avg_part_lag').val());
                var adj_part = Number($('#adj_part').val());
                if(adj_part){
                    avg_part_lag = adj_part;
                }
                var adj_level = Number($('#adj_level').val());
                var avg_cycle_level = Number($('#avg_level').val());
                if(adj_level){
                    avg_cycle_level = adj_level;
                }
                for(level_2;level_2>=level;level_2--){
                    if(part_index == 0){
                        part_str+="<div class='tab-pane text-left fade show active' id='vert-tabs-"+level_2+"' role='tabpanel' aria-labelledby='vert-tabs-"+level_2+"-tab'>";
                    }else{
                        part_str+="<div class='tab-pane fade' id='vert-tabs-"+level_2+"' role='tabpanel' aria-labelledby='vert-tabs-"+level_2+"-tab'>";
                    }
                    part_str+="<table class='table table-head-fixed text-nowrap'>";
                    part_str+="<thead><tr><th>Activities</th>";
                    for(j = 0,len=part_list.length; j < len; j++) {
                        part_str+="<th>Plan End Date (Zone "+part_list[j]+")</th>";
                    }
                    part_str+="</tr></thead>";
                    part_str+="<tbody>";
                    $.each(stage_data, function (stage_id, stage_value) {
                        if(stage_value.id == select_stage_id){
                            part_str+= "<tr><td bgcolor='#ececec'>&nbsp;&nbsp;" + stage_value.name + "</td>";
                        }else{
                            part_str+= "<tr><td>&nbsp;&nbsp;" + stage_value.name + "</td>";
                        }
                        for(j = 0,len=part_list.length; j < len; j++) {
                            //是不是第一个part
                            if(j == 0){
                                //是不是第一层level
                                if(level_2 != level){
                                    var d1 = addDate(stage_value.end_date, (level_2-level)*avg_cycle_level);
                                }else{
                                    var d1 = Datetoen(stage_value.end_date);
                                }
                                part_str+="<td><input type='hidden' name='Set["+level_2+"]["+part_list[j]+"]["+stage_value.id+"_"+stage_value.type+"]' value='"+d1+"'>"+d1+"</td>";
                            }else{
                                //part间隔值是不是0
                                if(avg_part_lag == 0){
                                    var d = Datetoen(d1);
                                }else{
                                    var d = addDate(d1, j*avg_part_lag);
                                }
                                part_str+="<td><input type='hidden' name='Set["+level_2+"]["+part_list[j]+"]["+stage_value.id+"_"+stage_value.type+"]' value='"+d+"'>"+d+"</td>";
                            }
                            if(level_2 == last_level && j == len-1){
                                // alert(d);
                                $('#last_date').val(d);
                            }
                        }
                        part_str+="</tr>";
                    })
                    part_str+="</tbody>";
                    part_str+="</table>";
                    part_str+="</div>";
                    part_index++;
                }
                part_str+= "</div></div>";
                console.log(part_str);
                var str = level_str+part_str;
                $('#schedule').append(str);
            }
        })
    }

    function addDate(date,days) {
        if(days == undefined || days == ''){
            days = 1;
        }
        var date = new Date(date);
        date.setDate(date.getDate() + days);
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var Month = new Array();
        Month['01'] = 'Jan';
        Month['02'] = 'Feb';
        Month['03'] = 'Mar';
        Month['04'] = 'Apr';
        Month['05'] = 'May';
        Month['06'] = 'Jun';
        Month['07'] = 'Jul';
        Month['08'] = 'Aug';
        Month['09'] = 'Sep';
        Month['10'] = 'Oct';
        Month['11'] = 'Nov';
        Month['12'] = 'Dec';
        return getFormatDate(day)+' '+Month[getFormatDate(month)]+' '+date.getFullYear();
    }

    function Datetoen(date) {
        var date_list = date.split('-');
        var Month = new Array();
        Month['01'] = 'Jan';
        Month['02'] = 'Feb';
        Month['03'] = 'Mar';
        Month['04'] = 'Apr';
        Month['05'] = 'May';
        Month['06'] = 'Jun';
        Month['07'] = 'Jul';
        Month['08'] = 'Aug';
        Month['09'] = 'Sep';
        Month['10'] = 'Oct';
        Month['11'] = 'Nov';
        Month['12'] = 'Dec';
        return date_list[2]+' '+Month[date_list[1]]+' '+date_list[0];
    }

    function Datetoch(date) {
        var date_str = data.split(' ');
        var date = date_str[0];
        var month = date_str[1];
        var year = date_str[2];
        var Month = new Array();
        Month['Jan'] = '01';
        Month['Feb'] = '02';
        Month['Mar'] = '03';
        Month['Apr'] = '04';
        Month['May'] = '05';
        Month['Jun'] = '06';
        Month['Jul'] = '07';
        Month['Aug'] = '08';
        Month['Sep'] = '09';
        Month['Oct'] = '10';
        Month['Nov'] = '11';
        Month['Dec'] = '12';
        return year+' '+Month[month]+' '+date;
    }

    function getFormatDate(arg){
        if(arg == undefined || arg == ''){
            return '';
        }
        var re = arg + '';
        if(re.length < 2){
            re = '0'+re;
        }
        return re;
    }

    function datetocn_new (a1) {
        var Month = new Array();
        Month['Jan'] = '01';
        Month['Feb'] = '02';
        Month['Mar'] = '03';
        Month['Apr'] = '04';
        Month['May'] = '05';
        Month['Jun'] = '06';
        Month['Jul'] = '07';
        Month['Aug'] = '08';
        Month['Sep'] = '09';
        Month['Oct'] = '10';
        Month['Nov'] = '11';
        Month['Dec'] = '12';
        var year = a1.substring(7, 11);//23 Nov 2021
        var month = a1.substring(3 , 6);
        var day = a1.substring(0, 2);
        smonth = Month[month];
        date = year+"-"+smonth+"-"+day;
        return date;
    }

    // 给日期类对象添加日期差方法，返回日期与diff参数日期的时间差，单位为天
    function DateDiff(sDate1, sDate2) { //sDate1和sDate2是yyyy-MM-dd格式
        var dateStart = new Date(sDate1);
        var dateEnd = new Date(sDate2);
        var difValue = Math.floor((dateEnd - dateStart) / (1000 * 60 * 60 * 24));
        return difValue; //返回相差天数
    }
    //提交表单
    var save_schedule = function () {
        var last_date = $('#last_date').val();
        var last_date_ch = datetocn_new(last_date);
        var template_end = $('#template_end').val();
        var template_end_ch = datetocn_new(template_end);
        console.log(last_date_ch);
        console.log(template_end_ch);
        if(last_date_ch > template_end_ch){
            alert('The last Plan End Date cannot be greater than Block Master Schedule Finish Date after adjustment');
            return;
        }
        //var params = $('#form1').serialize();
        //alert("index.php?r=proj/task/tnew&" + params);
        var block = $('#block').val();
        $.ajax({
            data:$('#form_'+block).serialize(),
            url: "index.php?r=task/schedule/saveschedule",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                // change_stage();
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>
