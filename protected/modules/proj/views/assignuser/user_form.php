<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
if (Yii::app()->user->hasFlash('success')) {
    $tag = Yii::t('common', 'success_apply');
    $status = 1;
    $class = Utils::getMessageType($status);
    $button = Yii::t('common', 'button_back');
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$tag}
              <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='back(\"{$program_id}\");'>{$button}</button>
          </div>
          ";
}
if (Yii::app()->user->hasFlash('error')) {
    $tag = Yii::t('common', 'error_apply');
    $status = -1;
    $class = Utils::getMessageType($status);
    $button = Yii::t('common', 'button_back');
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$tag}
              <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='back(\"{$program_id}\");'>{$button}</button>
          </div>
          ";
}

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'program_id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
echo $form->activeHiddenField($model, 'program_id', array());
?>
<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title h3-middel text-blue">
                    <?php echo Yii::t('proj_project_user', 'Assign User'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php echo Yii::t('proj_project_user', 'already_select'); ?>:<input type="checkbox" id="user" disabled="disabled" checked="checked" name="User[]">&nbsp;&nbsp;&nbsp;&nbsp;
                </h3>
            </div>
            <div class="card-body">
                <?php foreach ((array)$staff_List['team'] as $team_id => $team_name): ?>
                    <div class="row">
                        <label for="team_name" class="col-sm-2 control-label padding-lr5"><?php echo $team_name; ?></label>
                    </div>

                    <?php foreach ((array)$staff_List['role'][$team_id] as $role_id => $role_name): ?>
                        <div class="row">
                            <label for="role_name" class="col-sm-3 control-label padding-lr5" style="text-align: right"><?php echo $role_name; ?></label>
                            <div class="col-sm-9 padding-lr5">

                                <label class="checkbox-inline select_all_label" id="label_<?php echo $role_id;?>"  style="margin-left:10px;">
                                    <input type="checkbox" value="">&nbsp;<?php echo Yii::t('common', 'select_all');?>
                                </label>

                                <?php $cnt = array('PRC'=>0, 'NTS'=>0);
                                foreach((array)$staff_List['staff'][$role_id] as $user_id => $user_name):  ?>
                                    <label class="checkbox-inline checkbox_option label_<?php echo $role_id;?>" style="margin-left:10px;">
                                        <?php if(array_key_exists($user_id, $select_List)){ ?>
                                            <input id="select"  checked="checked" disabled="disabled" type="checkbox" name="Program[sc_list][]" value="<?php echo $user_id; ?>"  nation_type="<?php echo $staff_List['nation'][$user_id]; ?>">&nbsp;<?php echo $user_id; ?> <?php echo $user_name; if($staff_List['nation'][$user_id]<>'') echo '('.$staff_List['nation'][$user_id].')';    ?>
                                        <?php }else{ ?>
                                            <input id="select"   type="checkbox" name="Program[sc_list][]" value="<?php echo $user_id; ?>"  nation_type="<?php echo $staff_List['nation'][$user_id]; ?>">&nbsp;<?php echo $user_id; ?> <?php echo $user_name; if($staff_List['nation'][$user_id]<>'') echo '('.$staff_List['nation'][$user_id].')';    ?>
                                        <?php } ?>
                                    </label>

                                <?php endforeach    ?>

                            </div>
                        </div>

                    <?php endforeach;   ?>

                <?php endforeach;   ?>

                <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4" style="text-align: center">
                        <button type="submit" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
                        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back(<?php echo $mc_program_id ?>);"><?php echo Yii::t('common', 'button_back'); ?></button>
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/AdminLTE/app.js" ></script>
<script type="text/javascript">
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/assignuser/authoritylist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=<?php echo $mc_program_id ?>";
        //window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
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
        
        
        //人数计算
        option_count(); // 初始化人数计算
        $('.checkbox_option').find('ins').addClass('checkbox_option');//给选框ins增加计算人数的class
        
        function option_count(){
            cnt_all = $('.checkbox_option').find("input:checkbox:checked").size();
            $('#count_all').html(cnt_all);
            cnt_prc = $('.checkbox_option').find("input:checkbox:checked[nation_type='PRC']").size();
            $('#count_prc').html(cnt_prc);
            cnt_nts = $('.checkbox_option').find("input:checkbox:checked[nation_type='NTS']").size();
            $('#count_nts').html(cnt_nts);
        }
        
        $('.checkbox_option').click(function(){
            option_count();
        });
        //人数计算结束
 })
</script>
