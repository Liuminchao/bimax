<?php
/**
 * Created by PhpStorm.
 * User: minchao
 * Date: 2017-02-04
 * Time: 11:30
 */
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */

if (Yii::app()->user->hasFlash('success')) {
    $msg['msg'] = Yii::t('common','success_insert');
    $msg['status'] = 1;
    $msg['refresh'] = true;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          ";
}
if (Yii::app()->user->hasFlash('error')) {
    $msg['status'] = -1;
    $msg['msg'] = Yii::t('common','error_insert');
    $msg['refresh'] = false;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
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
<div class="box-body">
    <?php foreach($location_list as $local => $list){
        foreach($list as $cnt => $re){
            if(!is_numeric($cnt)) {?>
                <input type="hidden" name="ProgramRegion[<?php echo $local; ?>][block]" value="<?php echo $re; ?>">
            <?php }}} ?>
    <?php foreach ($mc_regionlist as $block => $mc_region):?>

        <div class="row">
            <label for="team_name" class="col-sm-1 control-label "><?php echo $location_block[$block]['block']; ?>
            </label>
        </div>

        <div class="row">
            <div class="form-group">

                <div class="col-sm-9 ">

                    <label class="checkbox-inline select_all_label" id="label_<?php echo $block;?>"  style="margin-left:10px;">
                        <input type="checkbox" value="">&nbsp;<?php echo Yii::t('common', 'select_all');?>
                    </label>

                    <?php foreach ($mc_region as $cnt => $region): ?>
                        <label class="checkbox-inline checkbox_option label_<?php echo $block;?>" style="margin-left:10px;">
                            <?php if($sc_regionlist != null && array_key_exists($block,$sc_regionlist) ) {
                                $array[0]=$region;
                                $result = array_intersect($sc_regionlist[$block],$array);
                                if(!empty($result)){
                                    ?>
                                    <input id="select"  checked="checked"  type="checkbox" name="ProgramRegion[<?php echo $block; ?>][]" value="<?php echo $region; ?>"  >&nbsp;<?php echo $region; ?>
                                <?php }else{ ?>
                                    <input id="select"   type="checkbox" name="ProgramRegion[<?php echo $block; ?>][]" value="<?php echo $region; ?>"  >&nbsp;<?php echo $region; ?>
                                <?php } ?>
                            <?php }else{ ?>
                                <input id="select"   type="checkbox" name="ProgramRegion[<?php echo $block; ?>][]" value="<?php echo $region; ?>"  >&nbsp;<?php echo $region; ?>
                            <?php } ?>
                        </label>

                    <?php endforeach;   ?>

                </div>

            </div>
        </div>

    <?php endforeach;   ?>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back(<?php echo $program_id ?>);"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    jQuery(document).ready(function () {

        //全选功能
        $('.select_all_label').find('ins').addClass('select_all_ins');//给全选按钮的选框ins增加class

        function select_all(obj){
            var id = obj.attr('id'); //alert(id);
            if ($(obj).find('div').attr('aria-checked') == 'true'){//alert('true');
                $('.'+id).find('div').attr('aria-checked', 'true');
                $('.'+id).find('div').addClass('checked');
                $('.'+id).find("input").prop('checked',true);

            }
            if ($(obj).find('div').attr('aria-checked') == 'false'){//alert('false');
                $('.'+id).find('div').attr('aria-checked', 'false');
                $('.'+id).find("div[class='icheckbox_minimal checked']").removeClass('checked');
                //$('.'+id).find('input').removeAttr('checked');
                $('.'+id).find('input').prop('checked',false);
            }
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
