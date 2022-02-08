<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div> 
<ul id="myTab" class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" href="#home" data-toggle="tab">
            <?php echo Yii::t('sys_operator','Base Info');?></a></li>
    <li class="nav-item"><a class="nav-link" href="#ios" data-toggle="tab"><?php echo Yii::t('sys_operator','Login Info');?></a></li>
<?php //if($model->operator_type<>00){ ?><!-- -->
<!--   <li><a href="#face" data-toggle="tab">--><?php //echo Yii::t('sys_operator','Face Login Info');?><!--</a></li>-->
<?php //} ?>
</ul>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade show active" id="home">
        <br/>
        <?php $this->renderPartial('_pform', array('model' => $model, 'msg' => $msg, '_mode_' => 'pupdate')); ?>
    </div>
    <div class="tab-pane fade" id="ios">
        <br/>
        <?php $this->renderPartial('_passwd', array('model' => $model,'id'=>$id, 'msg' => $msg, '_mode_' => 'pwd')); ?>
    </div>
<!--    <div class="tab-pane fade" id="face" >-->
<!--        <br/>-->
<!--        --><?php //$this->renderPartial('_fpasswd', array('rs' => $rs, 'model' => $model, 'msg' => $msg, '_mode_' => 'fpwd'));?>
<!--    </div>-->
     
</div>