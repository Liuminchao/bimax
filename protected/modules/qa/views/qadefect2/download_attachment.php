
    <?php
        if($drawing_id){
            $drawing_model = ProgramDrawing::model()->findByPk($drawing_id);
            $drawing_name = $drawing_model->drawing_name;
            $file = explode('.',$drawing_path);
            $file_type = $file[1];
            $file_name = $file[0];
            $path = $file_name.'_position.'.$file_type;
            $path = urlencode($path);
        }
    ?>
    <?php
        if($drawing_id){
    ?>
            <div class="row" style="margin-top: 8px;">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="working_life"
                               class="col-sm-12 control-label padding-lr5"><?php echo $drawing_name;?></label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-default" type='button' onclick="download_one('<?php echo $check_id; ?>')"><?php echo Yii::t('common', 'download');?></button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-default" type='button' onclick="preview_one('<?php echo $path ?>','<?php echo $check_id ?>')">Preview</button>
                </div>
            </div>
    <?php
        }
    ?>

<script type="text/javascript">
    //下载
    function download_one (check_id) {
        window.location = "index.php?r=qa/qadefect2/download&check_id="+check_id;
    }
    //预览
    function preview_one (path,check_id) {
        // formatUrl(path);
        path = encodeURIComponent(path);
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=qa/qadefect2/previewdoc&check_id="+check_id,"_blank");
        }else{
            window.open("index.php?r=qa/qadefect2/previewdoc&check_id="+check_id,"_blank");
        }
    }

    function getContextPath() {
        var pathName = document.location.pathname;
        var index = pathName.substr(1).indexOf("/");
        var result = pathName.substr(0,index+1);
        return result;
    }

    function formatUrl(url){
        url = url.replace(/&/g,"%26");
        url = url.replace(/#/g,"%23");
        var elemIF = document.createElement("iframe");
        elemIF.src = url;
        elemIF.style.display = "none";
        document.body.appendChild(elemIF);
        window.open(url);
    }
</script>
