<style type="text/css">
    .omit{
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    .progress_bar .pro-bar {
        background: hsl(0, 0%, 97%);
        box-shadow: 0 1px 2px hsla(0, 0%, 0%, 0.1) inset;
        height:4px;
        margin-bottom: 12px;
        margin-top: 50px;
        position: relative;
    }
    .progress_bar .progress_bar_title{
        /*color: hsl(218, 4%, 50%);*/
        color: #8B8378;
        font-size: 15px;
        font-weight: 300;
        position: relative;
        top: -28px;
        z-index: 1;
    }
    .progress_bar .progress_number{
        float: right;
        margin-top: -24px;
    }
    .progress_bar .progress-bar-inner {
        background-color: hsl(0, 0%, 88%);
        display: block;
        width: 0;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        transition: width 1s linear 0s;
        animation: animate-positive 2s;
    }
    .progress_bar .progress-bar-inner:before {
        content: "";
        background-color: hsl(0, 0%, 100%);
        border-radius: 50%;
        width: 4px;
        height: 4px;
        position: absolute;
        right: 1px;
        top: 0;
        z-index: 1;
    }
    .progress_bar .progress-bar-inner:after {
        content: "";
        width: 14px;
        height: 14px;
        background-color: inherit;
        border-radius: 50%;
        position: absolute;
        right: -4px;
        top: -5px;
    }
    @-webkit-keyframes animate-positive{
        0% { width: 0%; }
    }
    @keyframes animate-positive{
        0% { width: 0%; }
    }
    span.counter { display: block; margin: 15px auto; font-size: 48px; font-family: Arial; font-weight: bold; }
    </style>

<?php
if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    $status_list = Program::statusText(); //状态text
    $status_css = Program::statusCss(); //状态css
    $compList = Contractor::compAllList(); //所有发展商
    $background_list = Utils::backgroundList();

//        for($i=1;$i<=9;$i++) {
    foreach ($rows as $i => $row) {
?>
            <?php
                if(($j % 4)== 1){
                    echo '<div class="row">';
                }
            ?>
            <div class="col-lg-3 col-6">
                <div class="small-box <?php echo $background_list[$i]; ?>" >
                    <div class="inner">
                        <span class="counter"><h3> <?php
                        $cnt_list = ProgramUser::AllCntList($row['program_id']);//公司下各项目入场人数
                        if($cnt_list[$row['program_id']]['cnt']){
                            echo $cnt_list[$row['program_id']]['cnt'];
                        }else{
                            echo '0';
                        }
                        ?>  </h3></span>
                        <p><?php echo Yii::t('proj_project','in_staffs') ?></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-home"></i>
                    </div>
                    <?php if($row['father_proid']<>0){ ?>
                        <a class="small-box-footer" href="#" onclick="itemMenu('<?php echo $row['program_id'] ?>','SC')">
                    <?php }else{ ?>
                        <a class="small-box-footer" href="#" onclick="itemMenu('<?php echo $row['program_id'] ?>','MC')">
                    <?php } ?>
                    <?php
                        $p_model = Program::model()->findByPk($row['program_id']);
                        $root_proid = $p_model->root_proid;
                        $app_info = ProgramApp::myModuleList($root_proid);
                        $is_lite = '';
                        if($app_info[0]['is_lite'] == '1'){
                            $is_lite = '(Lite)';
                        }
                        if(strlen($row['program_name']) > 30){
                            echo $is_lite.substr($row['program_name'],0,30).'...';
                        }else{
                            echo $is_lite.$row['program_name'];
                        }
                    ?>
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

<?php

        if(($j % 4)== 0){
            echo '</div>';
        }
        $j++;
        }

    }
?>


<!--<div class="row">-->
<!--    <div class="col-xs-3">-->
<!--        <div class="dataTables_info" id="example2_info">-->
<!--            --><?php //echo Yii::t('common', 'page_total'); ?><!-- --><?php //echo $new_cnt; ?><!-- --><?php //echo Yii::t('common', 'page_cnt'); ?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-xs-9">-->
<!--        <div class="dataTables_paginate paging_bootstrap">-->
<!--            --><?php //$this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<!--<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=lS14hFRnEXU07GKiVDqNL4is&s=1"></script>-->
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhcLgIHv_6VXLS9Kyt4GTTPlsZF_srA4o&libraries=places&callback=initMap" async defer></script>-->
<script type="text/javascript">

     function display() {
         $(".display_none").attr("style", "display:block;");
     };

</script>
