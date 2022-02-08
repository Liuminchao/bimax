<!-- bootstrap 3.0.2 -->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>

<form id="form1">
    <?php
        $note_model = RfAttachNote::model()->findByPk($note_id);
    ?>
    <input type="hidden" id="file"  value="<?php echo $file; ?>">
    <input type="hidden" id="check_id"   value="<?php echo $note_model->check_id; ?>">
    <input type="hidden" id="attach_id"   value="<?php echo $note_model->attach_id; ?>">
    <div style="width: 55%;height:100%;float: left">
        <img src="<?php echo $pic; ?>"  width="700px" height="600px" />
    </div>

    <div style="width: 45%;height:100%;float: left">
        <div class="row" style="margin-top: 30px">
            <div class="form-group">
                <label for="type_name_en" class="col-sm-3 control-label padding-lr5">page:</label>
                <div class="col-sm-6 padding-lr5">
                    <input type="text" class="form-control" name="AttachNot[pagenumber]"  value="<?php echo $note_model->page; ?>" readonly>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 30px">
            <div class="form-group">
                <label for="type_name_en" class="col-sm-3 control-label padding-lr5">Remark:</label>
                <div class="col-sm-6 padding-lr5">
                    <textarea rows="25" name="AttachNot[remark]" cols="50" readonly><?php echo $note_model->remark; ?></textarea>
                </div>
            </div>
        </div>
</form>
<div class="row" style="margin-top: 30px">
    <button type="button" class="btn btn-default btn-lg" style="margin-left: 30px" onclick="back();">Back</button>
</div>
<script src="js/jquery.1.7.min.js"></script>
<script>
    //取消
    function back () {
        var check_id = $('#check_id').val();
        var attach_id = $('#attach_id').val();
        var file = $('#file').val();
        window.location = "index.php?r=rf/rf/preview&file="+file+"&check_id="+check_id+"&attach_id="+attach_id+"&tag=<?php echo $tag ?>";
    }
</script>
