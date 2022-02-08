<div class="container-fluid" >
    <!--    <div class="row" style="margin-top: 8px;margin-bottom: 50px;display:inline-block;text-align: center">-->
    <!--        <div class="col-sm-12 offset-md-10 control-label padding-lr5" style="text-align: left">-->
    <!--            <h2 >I want to forward a</h2>-->
    <!--        </div>-->
    <!--    </div>-->
    <div class="row" style="margin-top: 8px;margin-bottom: 10px; ">
        <input type="hidden" id="program_id" value="<?php echo $project_id ?>">
        <div class="col-sm-4 offset-md-2 control-label padding-lr5" style="text-align: left">
            <button id="draft_btn" type="button" class="btn btn-primary" style="background-color: #4682b4" onclick="download('<?php echo $attach_file ?>','<?php echo $form_name ?>')" >Download</button>
        </div>
        <div class="col-sm-4 control-label padding-lr5" style="text-align: left">
            <button id="save_btn" type="button" class="btn btn-primary" style="background-color: #4682b4;float: right" onclick="preview('<?php echo $attach_file ?>')" >Preview</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    //下载
    function download (doc_path,doc_name) {
        window.location = "index.php?r=transmittal/trans/download&doc_path="+doc_path+"&doc_name="+doc_name;
    }
    //预览
    var preview = function (path) {
        window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
    }
</script>