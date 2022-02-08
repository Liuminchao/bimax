<style>
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
<!--<script src="js/AdminLTE/app.js"></script>-->
<script src="js/pdf/pdfobject.js"></script>
<script src="js/jquery-2.1.1.min.js"></script>
<script>
    var height = document.body.clientHeight;
    $( ".pdfobject-container" ).css( "height", height );
    //返回
    //    var back = function () {
    //        history.back(-1);
    //        window.location.reload();
    //    }
    PDFObject.embed("<?php echo $certificate_photo;  ?>", "#example1");
</script>