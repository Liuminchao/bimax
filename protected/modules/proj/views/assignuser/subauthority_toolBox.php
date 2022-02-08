<div class="row" >
    <div class="col-10">
        <div class="dataTables_length">
            <form class="form-inline"  name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id; ?>">
                
                <div class="form-group  padding-lr5" style="width: 200px;">
                    <input class="form-control input-sm" type="text" name="q[user_name]" style="width: 100%;" placeholder="<?php echo Yii::t('comp_staff', 'User_name'); ?>" value="">
                </div>
                <div class="form-group  padding-lr5" style="width: 160px;">
                    <select class="form-control input-sm" name="q[status]" style="width:100%">
                        <option value="">--<?php echo Yii::t('proj_project_user','status');?>--</option>
                        <?php
                        $status_list = ProgramUser::statusSubText(); //状态text
                        //var_dump($teamlist);
                        foreach ($status_list as $type_no => $status) {
                            echo "<option value='".$type_no."'";
                            if ($args['status'] == $type_no) {
                                echo " selected";
                            }
                            echo ">".$status."</option>";
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
    
    <div class="col-2">
        <div class="dataTables_filter" >
            <label style="margin-left: 10px;">
                <button type="button" class="btn btn-default btn-sm" style="margin-left: 10px" onclick="javascript:back();">Back</button>

            <?php if($rows) {
                    echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExport(\"{$program_id}\")'>" . Yii::t('proj_project_user', 'export') . "</button>";
                }
            ?>

            </label>
        </div>
    </div>
</div>


<script type="text/javascript">
    //返回
    
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['proj/assignuser/subauthoritylist']; ?>";
    }
    
</script>