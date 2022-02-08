<style>
    .pdfobject-container { height: 500px;}
    .pdfobject { border: 1px solid #666; }
</style>
<!--<div class="col-xs-12">-->
<!--    <div class="dataTables_filter" >-->
<!--        <label>-->
<!--            <button class="btn btn-primary btn-sm" onclick="back()">--><?php //echo Yii::t('electronic_contract', 'back'); ?><!--</button>-->
<!--        </label>-->
<!--    </div>-->
<!--</div>-->
<div id="example1"></div>
<script src="js/pdf/pdfobject.js"></script>
<script>
    //返回
    var back = function () {
        window.location = "index.php?r=document/company/list";
    }
    PDFObject.embed("<?php echo $doc_path;  ?>", "#example1");
</script>