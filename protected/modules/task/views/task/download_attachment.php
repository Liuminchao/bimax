
<?php if(count($documen_list) > 0){ ?>
    <?php foreach($documen_list as $k => $list){
        ?>
        <div class="row" style="margin-top: 8px;">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                           class="col-sm-6 control-label padding-lr5"><?php echo $list['doc_name'].'.'.$list['doc_type'];?></label>
                    <div class="col-sm-6 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="download_one('<?php echo $list['doc_name']; ?>','<?php echo $list['doc_path'] ?>')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script type="text/javascript">
    //下载
    function download_one (doc_name,doc_path) {
        window.location = "index.php?r=rf/rf/download&doc_path="+doc_path+"&doc_name="+doc_name;
    }
</script>
