
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['device/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    var query = function (id) {
        var device_id = $("#device_id").val();
//        window.location = "index.php?r=comp/staff/selfgrid&program_id="+id+"&user_id="+user_id;
        var url = '';

        url += '&device_id=' + device_id + '&program_id=' + id;

        <?php echo $this->gridId; ?>.condition = url;
        <?php echo $this->gridId; ?>.refresh();
    }
</script>