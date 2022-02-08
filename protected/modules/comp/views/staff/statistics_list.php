


<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    var query = function (id) {
        var user_id = $("#user_id").val();
//        window.location = "index.php?r=comp/staff/selfgrid&program_id="+id+"&user_id="+user_id;
        var url = '';

        url += '&user_id=' + user_id + '&program_id=' + id;

        <?php echo $this->gridId; ?>.condition = url;
        <?php echo $this->gridId; ?>.refresh();
    }
</script>