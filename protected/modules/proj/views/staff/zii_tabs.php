<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>


<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="index.php?r=proj/staff/tabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>" ><?php echo Yii::t('comp_staff','Base Info');?></a></li>
    <li ><a href="index.php?r=proj/staff/pertabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','Personal Info');?></a></li>
<!--    <li ><a href="index.php?r=comp/staff/passtabs&user_id=--><?php //echo $user_id; ?><!--&mode=--><?php //echo $_mode_; ?><!--&title=--><?php //echo $title; ?><!--">--><?php //echo Yii::t('comp_staff','passport');?><!--</a></li>-->
<!--    <li ><a href="index.php?r=comp/staff/bcatabs&user_id=--><?php //echo $user_id; ?><!--&mode=--><?php //echo $_mode_; ?><!--&title=--><?php //echo $title; ?><!--">--><?php //echo Yii::t('comp_staff','bca');?><!--</a></li>-->
<!--    <li><a href="index.php?r=comp/staff/csoctabs&user_id=--><?php //echo $user_id; ?><!--&mode=--><?php //echo $_mode_; ?><!--&title=--><?php //echo $title; ?><!--">--><?php //echo Yii::t('comp_staff','csoc');?><!--</a></li>-->
    <li><a href="index.php?r=proj/staff/instabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','ins');?></a></li>
<!--    <li ><a href="index.php?r=comp/staff/violtabs&user_id=--><?php //echo $user_id; ?><!--" >违规记录查询</a></li>-->
    <!--    <li><a href="#ind"><?php echo Yii::t('comp_staff','Industry Qualification');?></a></li>-->
</ul>
<div id="hide">
    <input id="hid" type="hidden" name="satff" value="<?php echo $id;?>">
</div>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="base">
        <br/>
        <?php

        if($_mode_ == "insert"){
            $this->renderPartial('_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'insert','user_id' => $user_id,'program_id'=>$program_id,'msg' => $msg,'roleList' => $roleList, 'myRoleList' => $myRoleList));
        }else{
            $this->renderPartial('_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'edit','user_id' => $user_id,'program_id'=>$program_id,'msg' => $msg,'roleList' => $roleList, 'myRoleList' => $myRoleList));
        }?>
    </div>
    <!--    <div class="tab-pane fade" id="ind">
        <br/>
            <?php
    //            if($_mode_ == "insert"){
    //                $this->renderPartial('_indform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'insert','msg' => $ins_msg));
    //            }else{
    //                $this->renderPartial('_indform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'edit','msg' => $ins_msg));
    //            }
    ?>
    </div>-->
</div>
<script type="text/javascript">

    $(function () {

    })
</script>