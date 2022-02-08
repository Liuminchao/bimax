<?php
if ($msg) {
    $class = Utils::getMessageType($msg ['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          /*{$this->gridId}.refresh();*/
          </script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'focus' => array(
        $model,
        'name'
    ),
    'role' => 'form', // 可省略
    'formClass' => 'form-horizontal', // 可省略 表单对齐样式
    'autoValidation' => false
));
?>
<div class="box-body">
    <div >
        <input type="hidden" id="user_id" name="ProgramUser[user_id]" value="<?php echo "$user_id"; ?>"/>
        <input type="hidden" id="program_id" name="ProgramUser[program_id]" value="<?php echo "$program_id"; ?>"/>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue">Building Project Manpower</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12"><!--  Building Project Manpower  -->
            <div class="form-group">
                <label for="work_pass_type"
                       class="col-sm-3 control-label padding-lr5">Trades</label>
                <div class="input-group col-sm-7 padding-lr5">
                    <div class="col-sm-6 padding-lr5">
                        <?php
                        $type = 1;
                        $build_team_list = EpssRole::teamListByType($type);
                        echo $form->activeDropDownList($model, 'build_team_id',$build_team_list ,array('id' => 'build_team_id', 'class' => 'form-control', 'check-type' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-5 padding-lr5">
                        <?php
                        $build_role_list = array('EP0001'=>'N/A');
                        if($model->build_team_id)
                            $build_role_list = EpssRole::roleListByTeam($model->build_team_id);

                        echo $form->activeDropDownList($model, 'build_role_id', $build_role_list ,array('id' => 'build_role_id', 'class' => 'form-control'));
                        ?>

                    </div>
                    <span id="valierr" class="help-block" style="color:#FF9966">*</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue">Rail Project Manpower</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"><!--  Building Project Manpower  -->
            <div class="form-group">
                <label for="work_pass_type"
                       class="col-sm-3 control-label padding-lr5">Trades</label>
                <div class="input-group col-sm-7 padding-lr5">
                    <div class="col-sm-6 padding-lr5">
                        <?php
                        $type = 2;
                        $rail_team_list = EpssRole::teamListByType($type);
                        echo $form->activeDropDownList($model, 'rail_team_id',$rail_team_list ,array('id' => 'rail_team_id', 'class' => 'form-control', 'check-type' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-5 padding-lr5">
                        <?php
                        $rail_role_list = array('EP0057'=>'N/A');
                        if($model->rail_team_id)
                            $rail_role_list = EpssRole::roleListByTeam($model->rail_team_id);

                        echo $form->activeDropDownList($model, 'rail_role_id', $rail_role_list ,array('id' => 'rail_role_id', 'class' => 'form-control'));
                        ?>

                    </div>
                    <span id="valierr" class="help-block" style="color:#FF9966">*</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue">Road Project Manpower</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"><!--  Building Project Manpower  -->
            <div class="form-group">
                <label for="work_pass_type"
                       class="col-sm-3 control-label padding-lr5">Trades</label>
                <div class="input-group col-sm-7 padding-lr5">
                    <div class="col-sm-6 padding-lr5">
                        <?php
                        $type = 3;
                        $road_team_list = EpssRole::teamListByType($type);
                        echo $form->activeDropDownList($model, 'road_team_id',$road_team_list ,array('id' => 'road_team_id', 'class' => 'form-control', 'check-type' => 'required'));
                        ?>
                    </div>
                    <div class="col-sm-5 padding-lr5">
                        <?php
                        $road_role_list = array('EP0159'=>'N/A');
                        if($model->road_team_id)
                            $road_role_list = EpssRole::roleListByTeam($model->road_team_id);

                        echo $form->activeDropDownList($model, 'road_role_id', $road_role_list ,array('id' => 'road_role_id', 'class' => 'form-control'));
                        ?>

                    </div>
                    <span id="valierr" class="help-block" style="color:#FF9966">*</span>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="submit"  id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back(<?php echo $program_id ?>);"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">

    jQuery(document).ready(function () {

        //处理角色及角色分组
        $('#build_team_id').change(function(){
            //alert($(this).val());

            var buildObj = $("#build_role_id");
            var buildOpt = $("#build_role_id option");

            if ($(this).val() == 0) {
                buildOpt.remove();
                return;
            }
            $.ajax({
                type: "POST",
                url: "index.php?r=proj/assignuser/queryteam",
                data: {teamid:$("#build_team_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);

                    buildOpt.remove();
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        buildObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });

        $('#rail_team_id').change(function(){
            //alert($(this).val());

            var railObj = $("#rail_role_id");
            var railOpt = $("#rail_role_id option");

            if ($(this).val() == 0) {
                railOpt.remove();
                return;
            }
            $.ajax({
                type: "POST",
                url: "index.php?r=proj/assignuser/queryteam",
                data: {teamid:$("#rail_team_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);

                    railOpt.remove();
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        railObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });

        $('#road_team_id').change(function(){
            //alert($(this).val());

            var roadObj = $("#road_role_id");
            var roadOpt = $("#road_role_id option");

            if ($(this).val() == 0) {
                roadOpt.remove();
                return;
            }
            $.ajax({
                type: "POST",
                url: "index.php?r=proj/assignuser/queryteam",
                data: {teamid:$("#road_team_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);

                    roadOpt.remove();
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        roadObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });
    });
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/assignuser/authoritylist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
        //window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
</script>




