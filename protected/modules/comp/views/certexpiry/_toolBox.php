<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                <div style="margin-left: 10px;width:110px">
                    <select class="form-control input-sm" name="q[type_id]" style="width: 100%;">
                        <option value='S' selected>Personnel</option>
                        <option value='E'>Equipment</option>
                    </select>
                </div>

                <div style="margin-top: -30px;margin-left: 130px;width:110px">
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>

</div>
<script type="text/javascript">
    document.getElementById("user_phone").onkeyup = function() {
        var str=(this.value).replace(/[^\d]/g, "");
        var maxlen = 11;
        if (str.length < maxlen) {
            maxlen = str.length;
        }
        var temp = "";
        for (var i = 0; i < maxlen; i++) {
            temp = temp + str.substring(i, i + 1);
            if (i != 0 && (i + 1) % 4 == 0 ) {
                temp = temp + " ";
            }
        }
        this.value=temp;
    }
    
    //证件号格式
    document.getElementById("work_no").onkeyup = function(evt) {
        evt = (evt) ? evt : ((window.event) ? window.event : "");  
        var key = evt.keyCode?evt.keyCode:evt.which;
        if ( key != 8 ){
            var str=(this.value).replace(/[^\d||-]/g, "");
            var maxlen = 9;
            if (str.length < maxlen) {
                maxlen = str.length;
            }
            var temp = "";
            for (var i = 0; i < maxlen; i++) {
                temp = temp + str.substring(i, i + 1);
                if (i==0 ||(i + 1)==5) {
                    temp = temp + " ";
                }
            }
            this.value=temp;
        }
    }    
</script>