<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];
            
            ?>
            <form name="_query_form" id="_query_form" role="form">
                
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[program_id]" id="form_program_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('proj_report', 'report_program'); ?>--</option>
                        <?php
                        foreach ($program_list as $k => $name) {
                            echo "<option value='{$k}'>{$name}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <select class="form-control input-sm" name="q[role_id]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('proj_report', 'report_role'); ?>-</option>
                        <?php
                        foreach ($role_list as $k => $role) {
                            echo "<option value='{$k}'>{$role}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="col-xs-1 padding-lr5" style="width: 50px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]" 
                                   value="<?php echo $startDate; ?>" id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="操作开始日期"/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-1 padding-lr5" style="width: 50px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]" 
                                   value="<?php echo $endDate; ?>" id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="操作结束日期 "/>
                        </div>
                    </div>
                </div>
                
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
               <!--<a class="btn btn-primary btn-sm" href="javascript:itemExport()"><?php echo Yii::t('proj_report', 'export'); ?></a>-->
                                   
                <!--<a class="right" id="export"><strong onclick="itemExport();">导出Excel</strong></a>-->                    
               
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>