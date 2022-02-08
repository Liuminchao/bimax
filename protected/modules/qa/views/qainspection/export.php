    <form name="_query_form" id="_query_form" role="form">  
        <?php
            $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
            $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
        ?>
        <div class="box-body">
            <div class="row">
                <div class="form-group">
                    <label for="start_date" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('pay_payroll','start_date'); ?></label>
                        <div class="input-group col-sm-6">
                            <div class="input-group col-sm-6">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div> 
                                    <input id="start_date" class="form-control b_date_allowance" type="text" name="Export[start_date]" check-type="" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})">
                            </div>
                        <div style="margin-top: -35px;margin-left: 240px">
                            <span id="msg_start_date" class="help-block" style="display:none"></span>
                        </div>
                        </div>
                </div>
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="end_date" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('pay_payroll','end_date'); ?></label>
                        <div class="input-group col-sm-6">
                            <div class="input-group col-sm-6">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div> 
                                    <input id="end_date" class="form-control b_date_allowance" type="text" name="Export[end_date]" check-type="" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})">
                            </div>
                            <div style="margin-top: -35px;margin-left: 240px">
                                <span id="msg_end_date" class="help-block" style="display:none"></span>
                            </div>
                        </div>  
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-10">
                        <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="itemExport();"><?php echo Yii::t('proj_report', 'export'); ?></button>
                    </div>
                </div>
            </div>
        </div>     
    </form>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>
<script type="text/javascript">
    function itemExport() {
        var start_date = document.getElementById("start_date").value;
        var end_date = document.getElementById("end_date").value;
        if(start_date == '' || end_date == ''){
            alert('请选择日期');
            return false;
        }
        window.location = "index.php?r=wsh/wshinspection/exportpdf&start_date="+start_date+'&end_date='+end_date;
    }
</script>