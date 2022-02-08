<style>
    .pdfobject-container { height: 100%;}
    .pdfobject { border: 1px solid #666; }
</style>
<!--<div class="col-xs-12">-->
<!--    <div class="dataTables_filter" >-->
<!--        <label>-->
<!--            <button class="btn btn-primary btn-sm" onclick="back()">--><?php //echo Yii::t('electronic_contract', 'back'); ?><!--</button>-->
<!--        </label>-->
<!--    </div>-->
<!--</div>-->
<div id="example1" ></div>
<script src="js/AdminLTE/app.js"></script>
<script src="js/pdf/pdfobject.js"></script>
<script>
    PDFObject.embed("<?php echo $file;  ?>", "#example1");
</script>