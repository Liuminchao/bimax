
<?php
/* @var $this RoleController */
/* @var $model Role */
/* @var $form CActiveForm */
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => true,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
?>
<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
<input type="hidden" name="blockchart[program_id]" id="program_id" value="<?php echo $project_id ?>"/>
<!--<input type="hidden" name="blockchart[pbu_tag]" id="pbu_tag" value="--><?php //echo $pbu_tag ?><!--"/>-->
<?php
    $block_list = ProgramBlockChart::locationBlock($project_id,$pbu_tag);
    if(count($block_list)>0){
        $block = $block_list[0];
        $block_list = ProgramBlockChart::locationBlock($project_id,$pbu_tag);
        $level_list = ProgramBlockChart::locationLevel($project_id,$block);
        $unit_list = ProgramBlockChart::locationUnit($project_id,$block,$pbu_tag);
        $level_cnt = count($level_list);
        $unit_cnt = count($unit_list);
    }
?>
<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" id="area-body">
                <div class='row' style='margin-top: 10px;'>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="col-1">

                            </div>
                            <div class="col-3" style="text-align: right;">
                                <a class='a_logo' style='margin-left: 6px' onclick='Add()'><i class='fa fa-fw fa-plus'></i></a> Blk No.
                            </div>
                            <div class="col-7" id="block" style="text-align: left;">
                                <?php
                                    if(count($block_list)>0){
                                        foreach ($block_list as $block_index => $block){
                                            echo "<input   class='form-control input-sm' style='margin-left: 13px;margin-bottom: 10px;width: 120px;display: inline' name='blockchart[block][]'  value='$block' placeholder='Block' type='text'><a href='#' class='remove'><img style='margin-left: 3px;' width='16' src='img/delete.png' /></a>";
                                        }
                                    }else{
                                        echo "<input   class='form-control input-sm' style='margin-left: 13px;margin-bottom: 10px;width: 120px;display: inline' name='blockchart[block][]'  value='' placeholder='Block' type='text'><a href='#' class='remove'><img style='margin-left: 3px;' width='16' src='img/delete.png' /></a>";
                                    }
                                ?>

                            </div>
                            <div class="col-1">

                            </div>
                        </div>
                    </div>
                </div>

                <div class='row' style='margin-top: 10px;'>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="col-1">

                            </div>
                            <div class="col-3" style="text-align: right;">
                                Total Level
                            </div>
                            <div class="col-2" style="text-align: left;">
                                <?php
                                    if(count($block_list)>0){
                                        echo "<input  class='form-control input-sm' style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='blockchart[level]'  value='$level_cnt' placeholder='Level' type='text'>";
                                    }else{
                                        echo "<input  class='form-control input-sm' style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='blockchart[level]'  value='' placeholder='Level' type='text'>";
                                    }
                                ?>

                            </div>
                            <div class="col-5" style="">
                                Indicate Max. No.
                            </div>
                            <div class="col-1">

                            </div>
                        </div>
                    </div>
                </div>

                <div class='row' style='margin-top: 10px;'>
                    <div class="col-12">
                        <div class="form-group">
                            <div class="col-1">

                            </div>
                            <div class="col-3" style="text-align: right;">
                                Total Dwelling<br>Unit per Level
                            </div>
                            <div class="col-2" style="text-align: left;">
                                <?php
                                if(count($block_list)>0){
                                    echo "<input   class='form-control input-sm' style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='blockchart[unit]'  value='$unit_cnt' placeholder='Unit' type='text'>";
                                }else{
                                    echo "<input   class='form-control input-sm' style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='blockchart[unit]'  value='' placeholder='Unit' type='text'>";
                                }
                                ?>
                            </div>
                            <div class="col-5" style="">
                                Indicate Max. No.
                            </div>
                            <div class="col-1">

                            </div>
                        </div>
                    </div>

                </div>

<!--                <div class='row' style='margin-top: 10px;'>-->
<!--                    <div class="col-12">-->
<!--                        <div class="form-group">-->
<!--                            <div class="col-1">-->
<!---->
<!--                            </div>-->
<!--                            <div class="col-3" style="text-align: right;">-->
<!--                                Category-->
<!--                            </div>-->
<!--                            <div class="col-2" style="text-align: left;">-->
<!--                                <select style="margin-left: 13px;width: 120px;" id="pbu_tag" class='form-control input-sm'>-->
<!--                                    echo "<option value='1' selected>PBU</option>";-->
<!--                                    echo "<option value='2'>PPVC</option>";-->
<!--                                    echo "<option value='3'>Precast</option>";-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                </div>-->
            </div>
            <div class="row" style="margin-top: 10px;margin-bottom: 10px">
                <div class='col-12' style="text-align: center">
                    <button type="button" class="btn btn-primary"  onclick="save();">Save</button>
<!--                    <button type="button" class="btn btn-primary"  onclick="back();">Back</button>-->
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click",".removeclass", function(e){ //user click on remove text
            $(this).parent('div').remove(); //remove text box
        })
        $("body").on("click",".remove", function(e){ //user click on remove text
            $(this).prev().remove();//remove text box
            $(this).remove();
        })
    })
    var Add = function () {
        var html = "<input class='form-control input-sm' style='margin-left: 13px;margin-bottom: 10px;width: 120px;display: inline'  name='blockchart[block][]'  value='' placeholder='Block'  type='text'><a href='#' class='remove'><img style='margin-left: 3px;' width='16' src='img/delete.png' /></a>";
        $("#block").append(html);
    }
    var back = function(){
        var pbu_tag = $('#pbu_tag').val();
        window.location = "index.php?r=task/pbu/list&program_id=<?php echo $project_id; ?>";
    }
    var save = function () {
        $.ajax({
            data:$('#form1').serialize(),                 //将表单数据序列化，格式为name=value
            url: "index.php?r=task/pbu/setblock",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    alert('<?php echo Yii::t('common','success_submit'); ?>');
                    window.location = "index.php?r=task/pbu/list&program_id=<?php echo $project_id; ?>";
                }else{
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html(data.msg);
                    $('#msgbox').show();
                }
            },
            error: function () {
//                $('#msgbox').addClass('alert-danger fa-ban');
//                $('#msginfo').html('系统错误');
//                $('#msgbox').show();
                alert('System Error!');
            }
        });
    }
</script>
