<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/style.css">
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm"  onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </label>
        </div>
    </div>
</div>
<div >
    <input type="hidden" id="device_id"  value="<?php echo "$device_id"; ?>"/>
    <input type="hidden" id="type_no"  value="<?php echo "$type_no"; ?>"/>
    <input type="hidden" id="primary_id"  value="<?php echo "$primary_id"; ?>"/>
</div>
<div id="slider">
    <ul class="slides clearfix">
        <?php
        $arg = explode("|",$pic_str);
        foreach($arg as $cnt => $src){
            echo '<li><img class="responsive" src="'.$src.'"></li>';
        }
        ?>
    </ul>
    <ul class="controls">
        <li><img src="img/prev.png" alt="previous"></li>
        <li><img src="img/next.png" alt="next"></li>
    </ul>
<!--    <ul class="pagination">-->
<!--        <li class="active"></li>-->
<!--        <li></li>-->
<!--        <li></li>-->
<!--        <li></li>-->
<!--    </ul>-->
</div>
<script src="js/easySlider.js"></script>
<script type="text/javascript">
    //返回
    var back = function () {
        var device_id = document.getElementById('device_id').value;
        var type_no = document.getElementById('type_no').value;
        var primary_id = document.getElementById('primary_id').value;
        //alert(task_id);
        window.location = "index.php?r=device/equipment/attachlist&device_id="+device_id+"&type_no="+type_no+"&primary_id="+primary_id;
    }
    $(function() {
        $("#slider").easySlider( {
            slideSpeed: 500,
            paginationSpacing: "15px",
            paginationDiameter: "12px",
            paginationPositionFromBottom: "20px",
            slidesClass: ".slides",
            controlsClass: ".controls",
            paginationClass: ".pagination"
        });
    });
</script>
