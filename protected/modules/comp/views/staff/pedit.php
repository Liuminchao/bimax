<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>


<ul id="myTab" class="nav nav-tabs">
    <li ><a href="#base" >
            <?php echo Yii::t('comp_staff','Base Info');?></a></li>
    <li ><a href="#personal" ><?php echo Yii::t('comp_staff','Personal Info');?></a></li>
    <li ><a href="#passport" ><?php echo Yii::t('comp_staff','passport');?></a></li>
    <li ><a href="#bca" ><?php echo Yii::t('comp_staff','bca');?></a></li>
    <li><a href="#csoc" ><?php echo Yii::t('comp_staff','csoc');?></a></li>
    <li><a href="#ins" ><?php echo Yii::t('comp_staff','ins');?></a></li>
</ul>
<div id="hide">
    <input id="hid" type="hidden" name="satff" value="<?php echo $id;?>">
    <input id="tag" type="hidden" name="satff" value="<?php echo $tag;?>">
</div>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="base">
        <br/>
        <?php 
        //var_dump($msg);
        if($_mode_ == "insert"){    
            $this->renderPartial('_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'insert','user_id' => $user_id,'msg' => $msg,'roleList' => $roleList, 'myRoleList' => $myRoleList));
        }else{
            $this->renderPartial('_form', array('model' => $model, 'infomodel' => $infomodel, '_mode_' => 'edit','user_id' => $user_id,'msg' => $msg,'roleList' => $roleList, 'myRoleList' => $myRoleList));
        }?>
    </div>
    <div class="tab-pane fade" id="personal">
        <br/>
            <?php 
            if($_mode_ == "insert"){
                $this->renderPartial('_perform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'insert','msg' => $per_msg));
            }else{
                $this->renderPartial('_perform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'edit','msg' => $per_msg));
            }
            ?>
    </div>
    <div class="tab-pane fade" id="passport">
        <br/>
            <?php 
            if($_mode_ == "insert"){
                $this->renderPartial('_passform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'insert','msg' => $pass_msg));
            }else{
                $this->renderPartial('_passform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'edit','msg' => $pass_msg));
            }
            ?>
    </div>
     <div class="tab-pane fade" id="bca">
        <br/>
            <?php 
            if($_mode_ == "insert"){
                $this->renderPartial('_bcaform', array('model' =>$model,'infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'insert','msg' => $bca_msg));
            }else{
                $this->renderPartial('_bcaform', array('model' =>$model,'infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'edit','msg' => $bca_msg));
            }
            ?>
    </div>
    <div class="tab-pane fade" id="csoc">
        <br/>
            <?php 
            if($_mode_ == "insert"){
                $this->renderPartial('_csocform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'insert','msg' => $csoc_msg));
            }else{
                $this->renderPartial('_csocform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'edit','msg' => $csoc_msg));
            }
            ?>
    </div>
    <div class="tab-pane fade" id="ins">
        <br/>
            <?php 
            if($_mode_ == "insert"){
                $this->renderPartial('_insform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'insert','msg' => $ins_msg));
            }else{
                $this->renderPartial('_insform', array('infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => 'edit','msg' => $ins_msg));
            }
            ?>
    </div>
</div>
<script type="text/javascript">
    
      $(function () { 
        $(".nav-tabs a:first").tab('show');//初始化显示哪个tab 
        val = $("#tag")[0].value;
        if(val == 'per'){
            $('#myTab li:eq(1) a').tab('show');
        }else if(val == 'pass'){
            $('#myTab li:eq(2) a').tab('show');
        }else if(val == 'bca'){
            $('#myTab li:eq(3) a').tab('show');
        }else if(val == 'csoc'){
            $('#myTab li:eq(4) a').tab('show');
        }else if(val == 'ins'){
            $('#myTab li:eq(5) a').tab('show');
        }
        $('#myTab a').click(function (e) {
//          alert(1111111);
          e.preventDefault();//阻止a链接的跳转行为 
          $(this).tab('show');//显示当前选中的链接及关联的content 
        }) 
      }) 
</script>