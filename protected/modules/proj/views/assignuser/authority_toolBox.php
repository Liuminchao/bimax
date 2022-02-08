<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                
                <div class="form-group padding-lr5" style="width: 200px">
                    <input class="form-control input-sm" type="text" name="q[user_name]" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>" value="<?php echo array_key_exists('user_name',$args)?$args['user_name']:""; ?>"  style="width: 100%">
                </div>
                <div class="form-group padding-lr5" style="width: 200px">
                    <select class="form-control input-sm" name="q[status]"  style="width: 100%">
                        <option value="">-<?php echo Yii::t('proj_project_user','status');?>-</option>
                        <?php
                        $status_list = ProgramUser::statusText(); //状态text
                        //var_dump($teamlist);
                        foreach ($status_list as $type_no => $status) {
                            if($args['status'] == $type_no){
                                echo "<option value='{$type_no}' selected>{$status}</option>";
                            }else{
                                echo "<option value='{$type_no}'>{$status}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group padding-lr5" style="width: 200px">
                    <select class="form-control input-sm" name="q[con_id]"  style="width: 100%">
                        <option value="">-Company-</option>
                        <?php
                        $program_list = Program::Mc_ScProgramList($program_id);
                        $company_list = Contractor::compAllList();//承包商公司列表
                        //var_dump($teamlist);
//                        foreach ($program_list as $type_no => $status) {
//                            echo "<option value='{$type_no}'>{$status}</option>";
//                        }
                        foreach($program_list as $i => $j){
                            $contractor_id = $j['contractor_id'];
                            $company_name = $company_list[$j['contractor_id']];
                            if($args['con_id'] == $contractor_id){
                                echo "<option value='{$contractor_id}' selected>{$company_name}</option>";
                            }else{
                                echo "<option value='{$contractor_id}'>{$company_name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group padding-lr5" style="width:100px">
                    <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-3">
        <div  class="padding-lr5 float-sm-right">
            <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id ?>')">Add Person</button>
            <button class="btn btn-primary btn-sm" onclick="itemWorkforceSync('<?php echo $program_id; ?>')">User Sync</button>
        </div>
    </div>
</div>