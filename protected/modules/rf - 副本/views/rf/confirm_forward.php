<div class="container-fluid" >
    <div class="row" style="margin-top: 8px;margin-bottom: 50px;display:inline-block;text-align: center">
        <div class="col-sm-12 offset-md-10 control-label padding-lr5" style="text-align: left">
            <h2 >I want to forward a</h2>
        </div>
    </div>
    <div class="row" style="margin-top: 8px;margin-bottom: 50px; ">
        <input type="hidden" id="program_id" value="<?php echo $program_id ?>">
        <input type="hidden" id="type_id" value="<?php echo $type_id ?>">
        <input type="hidden" id="check_id" value="<?php echo $check_id ?>">
        <div class="col-sm-4 offset-md-2 control-label padding-lr5" style="text-align: left">
            <button id="draft_btn" type="button" class="btn btn-primary" style="background-color: #4682b4" onclick="confirm_forward('1')" >Combined RFA</button>
        </div>
        <div class="col-sm-4 control-label padding-lr5" style="text-align: left">
            <button id="save_btn" type="button" class="btn btn-primary" style="background-color: #4682b4;float: right" onclick="confirm_forward('2')" >Separated RFA</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    //转发
    function confirm_forward(forward_status) {
        var program_id = $('#program_id').val();
        var type_id = $('#type_id').val();
        var check_id = $('#check_id').val();
        window.location = "index.php?r=rf/rf/forward&check_id="+check_id+"&program_id="+program_id+"&type="+type_id+"&forward_status="+forward_status;
    }
</script>