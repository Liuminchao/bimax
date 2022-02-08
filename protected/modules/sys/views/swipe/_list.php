<?php

/*if($error_msg <> ''){
	echo "<br/><div class='alert alert-danger alert-dismissable'>
              <i class='fa fa-ban'></i>
              <b>" . Yii::t('common', 'tip') . "：</b>{$error_msg}
          </div>";
}*/


$t->echo_grid_header();



if (is_array($rows)) {
   
    //$moduleRs = OperatorLog::moduleDesc();
    //$optCodes = OperatorLog::statusDesc(); //操作结果
    //$optCss = OperatorLog::statusCss(); //操作结果css

    
//    var_dump($program_list);
    foreach ($rows as $i => $row) {
        
        $t->echo_td($program_list[$row['device_id']]==''?$row['device_id']:$program_list[$row['device_id']]); //考勤项目
       // $t->echo_td($contractor_list[$row['org_id']]==''?$row['org_id']:$contractor_list[$row['org_id']]); //承包商
       // $t->echo_td($row['user_name']); //人
       // $t->echo_td($row['user_isdn']); //手机号
       // $t->echo_td($row['card_time']);//考勤时间
        $t->echo_td(Utils::DateToEn($row['card_time']));
        $rs = $attend_result[$row['record_status']];
        if($row['record_status'] != '00' and $row['error_msg'] != '')
            $rs .= ':'.$row['error_msg'];
            
        $t->echo_td($rs);//考勤结果
        if($row['record_status'] != '00'){
            $t->echo_td("<a href='javascript:void(0);' class='img_class' img_url='".$row['img_url']."'><img width='30' src='index.php?r=sys/swipe/viewImg&img_url=".$row['img_url']."'/></a>");
        }else{
            $t->echo_td('');
        }
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.img_class').mouseout (function(){
        $("#attendImg").hide();
    });
    
    $('.img_class').mousemove (function(){
        img_url = $(this).attr("img_url");
        setImg(img_url,this);
        $("#attendImg").show();
     });

    function setImg(img_url,obj){
        var src,h;
        src='index.php?r=sys/swipe/viewImg&img_url='+img_url;//alert(src);
        $("#attendPhoto").attr("src",src);
        h=$("#attendImg").innerHeight();
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attendImg").css('top', ($(obj).position().top-h+253)+"px");
        $("#attendImg").css('left', ($(obj).position().left-350)+"px");
    }
</script>

<div id="attendImg" class="popDiv">
	<div class="popDiv_top">
            <div class="popDiv_body"><img id="attendPhoto" src="" width="320"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    
	<script type="text/javascript">
        $("#attendImg").hide();
    </script>
    
</div>