<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($model, 'name'),
    'autoValidation' => true,
    "action" => "javascript:formSubmit1()",
));


?>
<div class="container">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div>
        <input type="hidden" id="tag" name="pbu[id]" value="<?php echo $tag ?>">
        <input type="hidden" id="pbu_tag"  value="<?php echo $pbu_tag ?>">
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-3 control-label  label-rignt padding-lr5">Property</label>
        <div class="input-group col-7 padding-lr5">
            <select name="pbu[type]" id="type" class="form-control input-sm" style="width: 100%" onchange="change_type()">
                <option value="">--Property--</option>
                <option value="block">Block</option>
                <option value="level">Level</option>
                <option value="part">Part/Zone</option>
                <option value="unit_nos">Unit No</option>
                <option value="unit_type">Unit Type</option>
                <?php if($pbu_tag == '1'){ ?>
                    <option value="pbu_type">PBU Type</option>
                <?php }else if($pbu_tag == '2'){ ?>
                    <option value="pbu_type">PPVC Type</option>
                <?php }else if($pbu_tag == '3'){ ?>
                    <option value="pbu_type">Precast Type</option>
                <?php } ?>
                <option value="module_type">Module Type</option>
            </select>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-3 control-label  label-rignt padding-lr5">Original Info:</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control input-sm tool-a-search" name="pbu[original]"
               id="original"    value=""    placeholder="Original Info" readonly/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-3 control-label  label-rignt padding-lr5">Replace with:</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control input-sm tool-a-search" name="pbu[replace]"
               id="replace"    value=""    placeholder="Replace with"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;text-align: center">
        <div class="col-12">
            <button id="btn" type="button" onclick="save_replace()" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">

    var n = 4;
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
        $('#divMain').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }

    var change_type = function () {
        var type = $('#type').val();
        var id = $('#tag').val();
        $.ajax({
            url: "index.php?r=task/model/getoriginal",
            data: {id: id,type: type},
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == '-1'){
                    alert('The selected component has different property values');
                    $("#btn").attr("disabled", true);
                }
                if(data.status == '1'){
                    $('#original').val(data.value);
                    $("#btn").attr("disabled", false);
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

    //提交表单
    var save_replace = function () {
        var type = $('#type').val();
        var id = $('#tag').val();
        var replace = $('#replace').val();
        $.ajax({
            url: "index.php?r=task/model/setoriginal",
            data: {id: id,type: type,replace:replace},
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                //window.location = "index.php?ctc/handover/recordlist&apply_id=<?php //echo $apply_id ?>//";
                location.reload();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

</script>