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
                        $args = array(
                             'contractor_id' => Yii::app()->user->contractor_id,
                             //'project_type'  =>  'MC',
                         );//var_dump($args);
                        $program_list = Program::McProgramList($args);
                        foreach ($program_list as $k => $name) {
                            echo "<option value='{$k}'>{$name}</option>";
                        }
                        ?>
                    </select>
                </div>
                 <?php
                //$startDate = date('Y-m-01', strtotime(date("Y-m-d")));
                //$endDate = date('Y-m-d', strtotime("$startDate +1 month -1 day"));
                //$startDate = date("Y-m-01");
                //$endDate = date("Y-m-d");
                $startDate = Utils::DateToEn(date("Y-m-01"));
                $endDate = Utils::DateToEn(date("Y-m-d"));
                ?>
                <div class="col-xs-1 padding-lr5" style="width: 50px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
               <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]" 
                                   value="<?php echo $startDate; ?>" id="q_start_date"  onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})"  placeholder="操作开始日期"/>
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
            
       <!--     <span id="wdate">
           <input type="text" name="q[start_date]" id="q_start_date" value="<?php echo $startDate; ?>" size="15"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
           <input type="text" name="q[end_date]" id="q_end_date" value="<?php echo $endDate; ?>" size="15"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
            </span>
       -->
                <!--<div class="col-xs-2 padding-lr5" >
                    <select class="form-control input-sm" name="q[record_status]" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('sys_attend','card_result');?>--</option>
                        <?php
                        $attendRs = ProjectAttend::getRusult();
                        foreach ($attendRs as $value => $name) {
                            echo "<option value=" . $value . ">" . $name . "</option>";
                        }
                        ?>
                    </select>
                </div>-->
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                
            </form>
        </div>
    </div>
    
</div>
<script type="text/javascript" src="js/JQdate/WdatePicker.js"></script>
