<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                    <input type="hidden" name="q[worker_type]" value="1">
                <div style="padding-left: 5px">
                    <input type="text" id="user_name" class="form-control input-sm" style="width: 90px" name="q[user_name]" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>">
                </div>
                <div style="margin-top: -30px;margin-left: 105px">
                    <input type="text" id="user_phone" class="form-control input-sm" style="width: 90px" name="q[user_phone]" placeholder="<?php echo Yii::t('comp_staff', 'User_phone'); ?>">
                </div>                
                <div style="margin-top: -30px;margin-left: 205px">
                    <input type="text" id="work_no" class="form-control input-sm" style="width: 90px" name="q[work_no]" placeholder="<?php echo Yii::t('comp_staff', 'Work_no'); ?>">
                </div>

                <div style="margin-top: -30px;margin-left: 305px;width:110px">
                    <select class="form-control input-sm" name="q[work_pass_type]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'Work_pass_type'); ?>-</option>
                        <?php
                            $WorkPassType = Staff::WorkPassType();
                            //var_dump($teamlist);
                            foreach ($WorkPassType as $typeid => $typename) {
                                echo "<option value='{$typeid}'>{$typename}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div style="margin-top: -30px;margin-left: 425px;width:110px">
                    <select class="form-control input-sm" name="q[nation_type]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'Nation_type'); ?>-</option>
                        <?php
                            $NationType = Staff::NationType();
                            //var_dump($teamlist);
                            foreach ($NationType as $typeid => $typename) {
                                echo "<option value='{$typeid}'>{$typename}</option>";
                            }
                        ?>
                    </select>
                </div>

                <div style="margin-top: -30px;margin-left: 545px;width:110px">
                    <select class="form-control input-sm" name="q[loane_type]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'loane_type'); ?>-</option>                        <option value="1"><?php echo Yii::t('comp_staff', 'loane_type_1'); ?></option>
                        <option value="2"><?php echo Yii::t('comp_staff', 'loane_type_2'); ?></option>
                    </select>
                </div>

                <div style="margin-top: -30px;margin-left: 665px;width:110px">
                    <select class="form-control input-sm" name="q[category]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'category'); ?>-</option>
                        <?php
                        $category = Staff::Category();
                        //var_dump($teamlist);
                        foreach ($category as $typeid => $typename) {
                            echo "<option value='{$typeid}'>{$typename}</option>";
                        }
                        ?>
                    </select>
                </div>
<!--                <div style="margin-top: -30px;margin-left: 665px;width:110px">
                    <select class="form-control input-sm" name="q[white_list_type]" style="width: 110%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'White_list_type'); ?>-</option>
                        <option value="1"><?php echo Yii::t('comp_staff', 'White_list_join'); ?></option>
                    </select>
                </div>-->
                
                <!--                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                                    <select class="form-control input-sm" name="q[status]" style="width: 100%;">
                                        <option value="">-<?php echo Yii::t('comp_staff', 'Status'); ?>-</option>
                <?php
                $status_list = Staff::statusText();
                foreach ($status_list as $k => $source) {
                    echo "<option value='{$k}'>{$source}</option>";
                }
                ?>
                                    </select>
                                </div>-->
                <div style="margin-top: -30px;margin-left: 785px;width:110px">
                    <select class="form-control input-sm" name="q[role_id]" style="width: 100%;">
                        <option value="">-<?php echo Yii::t('comp_staff', 'Role_id'); ?>-</option>
                        <?php
                        $category = Role::roleList();
                        //var_dump($teamlist);
                        foreach ($category as $typeid => $typename) {
                            echo "<option value='{$typeid}'>{$typename}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div style="margin-top: -30px;margin-left: 905px;width:110px">
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <?php if (Yii::app()->user->getState('operator_role') == '00'){ ?>
                    <button class="btn btn-primary btn-sm" onclick="itemImport()"><?php echo Yii::t('comp_staff', 'Batch import'); ?></button>
                    <button class="btn btn-primary btn-sm" onclick="itemAdd()"><?php echo Yii::t('comp_staff', 'Add Staff'); ?></button>
                <?php } ?>
            </label>
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