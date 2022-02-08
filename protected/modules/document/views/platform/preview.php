<style>
    .pdfobject-container { height: 100%;width: 100%}
    .pdfobject { border: 1px solid #666; }
    .rf_pdf { width: 60%; }
</style>
<!--<div class="col-xs-12">-->
<!--    <div class="dataTables_filter" >-->
<!--        <label>-->
<!--            <button class="btn btn-primary btn-sm" onclick="back()">--><?php //echo Yii::t('electronic_contract', 'back'); ?><!--</button>-->
<!--        </label>-->
<!--    </div>-->
<!--</div>-->
<div id="example1" class="rf_pdf"></div>
<script src="js/AdminLTE/app.js"></script>
<script src="js/pdf/pdfobject.js"></script>
<script>
    //返回
    var back = function () {
        window.location = "index.php?r=document/platform/list";
    }
    PDFObject.embed("<?php echo $doc_path;  ?>", "#example1");
</script>