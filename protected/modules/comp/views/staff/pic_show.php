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
    <input type="hidden" id="user_id"  value="<?php echo "$user_id"; ?>"/>
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
        var user_id = document.getElementById('user_id').value;
        //alert(task_id);
        window.location = "index.php?r=comp/staff/attachlist&user_id="+user_id;
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
