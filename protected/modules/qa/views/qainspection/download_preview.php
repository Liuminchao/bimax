

<?php if(count($form_data_list) > 0){ ?>
    <?php foreach($form_data_list as $k => $list){
        ?>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="working_life"
                        class="col-sm-6 control-label padding-lr5"><?php echo $list['form_title'];?> PDF</label>
                    <div class="col-sm-3 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="downloadreport('<?php echo $check_id ?>','<?php echo $list['data_id'] ?>','1')">Preview</button>
                    </div>
                    <div class="col-sm-3 padding-lr5">
                        <button class="btn btn-default" type='button' onclick="downloadreport('<?php echo $check_id ?>','<?php echo $list['data_id'] ?>','2')"><?php echo Yii::t('common', 'download');?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } ?>
<script type="text/javascript">
    var downloadreport =  function(check_id,data_id,tag){
        // window.location = "index.php?r=qa/qainspection/qaexport&check_id="+check_id+"&data_id="+data_id;
        window.open("index.php?r=qa/qainspection/qaexport&check_id="+check_id+"&data_id="+data_id+"&tag="+tag);
    }
    var downloadexcel =  function(id){
        // window.open("index.php?r=qa/qainspection/downloadpdf&check_id="+id);
        window.location = "index.php?r=qa/qainspection/downloadpdf&check_id="+id;
    }
</script>
