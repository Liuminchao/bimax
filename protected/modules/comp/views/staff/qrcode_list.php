<?php
/**
 * Created by PhpStorm.
 * User: minchao
 * Date: 2017-05-02
 * Time: 14:17
 */
//echo "<h1>PHP QR Code</h1><hr/>";
header('Content-Type:text/html; charset=utf-8;');
//set it to writable location, a place for temp generated PNG files
//$PNG_TEMP_DIR = './img/staff/';

//ofcourse we need rights to create temp dir

//display generated file
$file_name = $PNG_TEMP_DIR.$user_id.'.png';
echo '<img src="'.$file_name.'" /><hr/>';

//config form
echo '<form >
        <!--Data:&nbsp;<input name="data" value="'.(isset($_REQUEST['data'])?htmlspecialchars($_REQUEST['data']):'PHP QR Code :)').'" />&nbsp;-->
        ECC:&nbsp;<select name="level">
            <option value="L"'.(($errorCorrectionLevel=='L')?' selected':'').'>L - smallest</option>
            <option value="M"'.(($errorCorrectionLevel=='M')?' selected':'').'>M</option>
            <option value="Q"'.(($errorCorrectionLevel=='Q')?' selected':'').'>Q</option>
            <option value="H"'.(($errorCorrectionLevel=='H')?' selected':'').'>H - best</option>
        </select>&nbsp;
        Size:&nbsp;<select name="size">';

for($i=1;$i<=10;$i++)
    echo '<option value="'.$i.'"'.(($matrixPointSize==$i)?' selected':'').'>'.$i.'</option>';

echo '</select><hr/>&nbsp;
<br/>

<div class="row">
        <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <!--<button type="submit" id="sbtn" class="btn btn-primary btn-lg" >Save</button>-->
            <button type="button" class="btn btn-default btn-lg"
                style="margin-left: 10px" onclick="back();">Back</button>
        </div>
        </div>
</div></form><hr/>';
// benchmark
//QRtools::timeBenchmark();
?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
</script>