<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>


<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="index.php?r=document/platform/show">公司级文档</a></li>
    <li ><a href="index.php?r=document/platform/pertabs">系统级文档</a></li>
    <li ><a href="index.php?r=document/platform/passtabs" >应用级文档</a></li>
</ul>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="ins">
        <br/>
        <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
            <?php $this->renderPartial('_toolBox'); ?>
            <div id="datagrid"><?php $this->actionGrid(); ?></div>
        </div>
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
<script src="js/jquery.1.7.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/AdminLTE/app.js"></script>
<script type="text/javascript">

</script>