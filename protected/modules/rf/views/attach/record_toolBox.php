<div class="row" style="margin-left: -20px;">
    <div class="col-xs-11">

    </div>
    <div class="col-xs-1">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemBack('<?php echo $program_id; ?>')">Back</button>
            </label>
        </div>
    </div>
</div>
<script type="application/javascript">
    //公司和类型半联动
    $('#program_id').change(function(){
        //alert($(this).val());

        var selObj = $("#type_id");
        var selOpt = $("#type_id option");

        if ($(this).val() == 0) {
            selOpt.remove();
            return;
        }
        $.ajax({
            type: "POST",
            url: "index.php?r=license/licensepdf/querytype",
            data: {program_id:$("#program_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                selOpt.remove();
                if (!data) {
                    return;
                }
                selObj.append("<option value=''>--<?php echo Yii::t('license_licensepdf', 'ptw_type'); ?>--</option>");
                for (var o in data) {//console.log(o);
                    selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
</script>