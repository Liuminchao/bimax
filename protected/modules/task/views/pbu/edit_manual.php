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
<div class="row">
    <div class="col-12">
        <div class="card card-info ">
            <div class="row" style="margin-top: 10px;margin-bottom: 20px;">
                <div class="col-12">
                    <div class="form-group">
                        <?php if($pbu_tag == '2'){ ?>
                            <label for="user_name" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px">PPVC Type</label>
                        <?php }else if($pbu_tag == '1'){ ?>
                            <label for="user_name" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px">PBU Type</label>
                        <?php }else if($pbu_tag == '3'){ ?>
                            <label for="user_name" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px">Precast Type</label>
                        <?php } ?>
                        <div class="col-4 padding-lr5">
                            <input type="hidden" id="project_id" name="project" value="<?php echo $project_id; ?>">
                            <select class="form-control input-sm" name="pbu_type" id="pbu_type" onchange="check('<?php echo $project_id; ?>','<?php echo $pbu_tag ?>')">
                                <?php
                                $pbu_type_list = ProgramBlockChart::locationPbutype($project_id,$pbu_tag);
                                if(count($pbu_type_list)>0){
                                    foreach ($pbu_type_list as $i => $j){
                                        $pbu_type_1 = $j['pbu_type'];
                                        if($pbu_type == $pbu_type_1){
                                            echo "<option value='$pbu_type' selected>$pbu_type</option>";
                                        }else{
                                            echo "<option value='$pbu_type_1'>$pbu_type_1</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <label for="user_name" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px">Rename</label>
                        <div class="col-4 padding-lr5">
                            <input class="form-control input-sm" id="re_pbu_type" name="re_pbu_type" type="text">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-2" style="text-align: center">
                    <label>Block</label>
                </div>
                <div class="col-2" style="text-align: center">
                    <label>Level</label>
                </div>
                <div class="col-8" style="text-align: center">
                    <label>Unit</label>
                </div>
            </div>
            <?php
            $all_list = ProgramBlockChart::detailAllList($project_id,$pbu_tag);
            if(count($all_list)>0){
                $block_bak = '';
                foreach($all_list as $block => $detail_list){
                    foreach($detail_list['level'] as $level => $level_array){
                        echo "<div class='row' style='margin-top: 10px;'>";
                        if($block_bak != $block){
                            $block_bak = $block;
                            echo "<div class='col-2' style='text-align: center'><label>$block</label></div>";
                        }else{
                            echo "<div class='col-2' style='text-align: center'><label></label></div>";
                        }
                        echo "<div class='col-2' style='text-align: center'><label>$level</label></div>";
                        echo "<div class='col-8' style='text-align: center'>";
                        $class = 'block_'.$block.'_'.'level_'.$level;
                        echo "<label class='checkbox-inline select_all_label' id='$class'  style='margin-left:10px;'>
                            <input type='checkbox' >&nbsp;All
                          </label>";
                        foreach($level_array as $x => $y){
//                            $id = $y['id'];
                            $pbu_type_self = $y['pbu_type'];
                            $id = $block.'|'.$level.'|'.$x;
                            if($pbu_type_self){
                                if(strpos($pbu_type_self,$pbu_type) !== false){
                                    echo "<label class='checkbox-inline checkbox_option $class' style='margin-left:10px;'><input name='Unit[]' type='checkbox' value='$id' checked>$x</label>";
                                }else{
                                    echo "<label class='checkbox-inline checkbox_option $class' style='margin-left:10px;'><input name='Unit[]' type='checkbox' value='$id' disabled>$x</label>";
                                }
                            }else{
                                echo "<label class='checkbox-inline checkbox_option $class' style='margin-left:10px;'><input name='Unit[]' type='checkbox' value='$id' disabled>$x</label>";
                            }
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "<hr/>";
                }
            }
            ?>

            <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
                <div class="col-12" style="text-align: center">
                    <button type="button" id="sbtn" class="btn btn-primary" onclick="re_pbutype()">Done</button>
                    <button type="button" class="btn btn-default" style="margin-left: 10px;" onclick="back();">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    var re_pbutype = function () {
        url = "index.php?r=task/pbu/repbutype";
        var re_pbu_type = $('#re_pbu_type').val();
        if(re_pbu_type == ''){
            alert('Please input rename');
            return;
        }
        $.ajax({
            data:$('#form1').serialize(),
            url: url,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                window.location = "index.php?r=task/model/pbulist&program_id=<?php echo $project_id; ?>";
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    var back = function(){
        //window.location = "index.php?r=task/pbu/list&program_id=<?php //echo $project_id; ?>//&block=<?php //echo $block; ?>//";
        window.location = "index.php?r=task/pbu/pbulist&program_id=<?php echo $project_id; ?>";
    }
    var check = function(id,pbu_tag){
        var pbu_type = $('#pbu_type').val();
        var modal = new TBModal();
        modal.title = "Edit Pbu Type";
        modal.url = "index.php?r=task/pbu/editallocate&project_id=" + id+"&pbu_type="+pbu_type+"&pbu_tag="+pbu_tag;
        modal.modal();
        // itemQuery();
    }

    jQuery(document).ready(function () {

        //全选功能
        $('.select_all_label').find('ins').addClass('select_all_ins');//给全选按钮的选框ins增加class
        function select_all(obj){
            var id = obj.attr('id');
            // alert(id);
            // alert($(obj).find("input:checkbox").prop('checked'));
            if($(obj).find("input:checkbox").prop('checked') == true){
                // alert("勾选");
                $('.'+id).find("input:not(:disabled)").prop('checked',true);
            }
            if($(obj).find("input:checkbox").prop('checked') == false){
                // alert('取消');
                $('.'+id).find('input:not(:disabled)').prop('checked',false);
            }
            // option_count();
        }

        $('.select_all_label').click(function(){
            select_all($(this));
        });

        $('.select_all_ins').click(function(){
            obj = $(this).closest('.select_all_label');
            select_all(obj);
        });
        //全选功能结束

    })
</script>