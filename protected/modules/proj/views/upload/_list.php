
<?php 

$t->echo_grid_header();
?>
    
<?php 
if($rows){
        foreach($rows as $k=>$row){
             
            if($row['task_attach']) {
                $j++;
                ?>
                <div class="column">
                    <div class="col-sm-6 col-md-3 <?php echo $j;?>">
                        <div style="cursor:pointer;text-align: center; height: 260px; width:200px; margin-bottom:15px;" class="img-thumbnail  <?php echo $j;?>">
                            <?php 
                                $pos = strpos($row['task_attach'],"attachment");
                                $new_img_url = substr($row['task_attach'], $pos);
                            ?>
                            <img src="<?php echo $new_img_url  ?>"  onclick="window.open(this.src,'_blank')"    id="attach" height="220px"  width="180px" />
                            <div style="position:absolute; z-index: 99;left:180px; top:0px">
                                <img  src="img/delete.png" onclick="delcfm('<?php echo $row['task_attach'] ?>','<?php echo $task_id ?>','<?php echo $program_id ?>')">
                            </div>
                                    <div class="caption">
                                        <p><?php echo $row['attach_content']  ?></p>
                                    </div>
                        </div>
                    </div>
                </div>
<?php 
            
            }
            $t->end_row();
        }
   }
        ?>

<?php 
$t->echo_grid_floor();
$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>
<div id="attendImg" class="popDiv">
    <div class="popDiv_top">
            <div class="popDiv_body"><img id="attendPhoto" src="" width="340"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#attendImg").hide();
    </script>
</div>

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
//照片显示方法 
//     $(function() {
//            $(document.body).mouseover(function (e) {
//                if (e.target.tagName == "IMG") {
//                    id = e.target.id;
//                    //alert(id);
//                    src=document.getElementById(id).src;
//                    setImg(this,src);
//                    $("#attendImg").show();
//                }
//            });
//        });  
//    $('.1').mouseout (function(){
//        $("#attendImg").hide();
//    });
//    
//    $('.1').mousemove (function(){
//        img_url = $(this).attr("src");
//        setImg(this,img_url);
//        $("#attendImg").show();
//     });
    function delcfm(str,task_id,program_id){
        //window.location = "index.php?r=proj/upload/delete&str=" + str+"&task_id="+task_id;
        $.ajax({
            data: {str: str, confirm: 1,task_id: task_id},
            url: "index.php?r=proj/upload/delete",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    window.location = "index.php?r=proj/upload/attachlist&program_id=" + program_id+"&task_id="+task_id;
                } else {
                    //alert("<?php echo Yii::t('common', 'error_logout'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
    function setImg(obj,src){
        var h;
        //src=document.getElementById("attach").src;
        //alert(src);
        $("#attendPhoto").attr("src",src);
        h=$("#attendImg").innerHeight();
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attendImg").css('top', ($(obj).position().top-h+450)+"px");
        $("#attendImg").css('left', ($(obj).position().left+150)+"px");
    }
    </script>