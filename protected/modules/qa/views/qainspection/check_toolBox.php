<div class="row" >
    <div class="col-9">

    </div>
    <div class="col-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemBack()"><?php echo Yii::t('common', 'button_back');?></button>
            </label>
        </div>
    </div>
</div>
<script type="application/javascript">

    //form初始化
    function FormInit(node) {
        return node.html('<option value="">--<?php echo Yii::t('comp_qa', 'form_type'); ?>--</option>');
    }

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
            url: "index.php?r=qa/qainspection/querytype",
            data: {program_id:$("#program_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);

                selOpt.remove();
                if (!data) {
                    return;
                }
                selObj.append("<option value=''>--<?php echo Yii::t('comp_routine', 'check_type'); ?>--</option>");
                for (var o in data) {//console.log(o);
                    selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
    //类型和表单类型半联动
    $('#type_id').change(function(){

        var formObj = $("#form_id");
        var formOpt = $("#form_id option");
        FormInit(formObj);

        $.ajax({
            type: "POST",
            url: "index.php?r=qa/qainspection/queryform",
            data: {type_id:$("#type_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                for (var o in data) {//console.log(o);
                    formObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });
    
    $(function () {
        $('#q_start_date').datetimepicker({
            format: 'DD MMM yyyy',
        });
        $('#q_end_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
    })

</script>