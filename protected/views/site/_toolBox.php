<!-- Content Header (Page header) -->
<div class="content-header" style="padding-left: 0px;">
    <div class="container-fluid" style="padding-left: 0px;">
        <div class="row mb-2">
            <div class="col-sm-9">
                <h1 class="m-0">Project List</h1>
            </div><!-- /.col -->
            <?php
            $operator_type = Yii::app()->user->getState('operator_type');
            $operator_role = Yii::app()->user->getState('operator_role');
            if($operator_type == '01' && $operator_role == '00'):?>
                <div class="col-sm-3" style="text-align: right">

                </div>
            <?php endif;?>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
<!--            <form name="_query_form" id="_query_form" role="form">-->
<!--                <div class="col-xs-2 padding-lr5" style="width:200px">-->
<!--                    <input type="text" class="form-control input-sm" name="q[project_name]" placeholder="--><?php //echo Yii::t('proj_project', 'project_name'); ?><!--">-->
<!--                </div>-->
<!--<!--                <div class="col-xs-2 padding-lr5" >-->
<!--                    <input type="text" class="form-control input-sm" name="q[contractor_id]" placeholder="--><?php //echo Yii::t('proj_project', 'contractor_id'); ?><!--">-->
<!--                </div>-->
<!--                <div class="col-xs-2 padding-lr5" style="width: 120px;">-->
<!--                    <select class="form-control input-sm" name="q[status]" style="width: 100%;">-->
<!--                        <option value="">---><?php //echo Yii::t('proj_project', 'status'); ?><!---</option>-->
<!--                        --><?php
//                        $status_list = Program::statusText();
//                        foreach ($status_list as $k => $source) {
//                            echo "<option value='{$k}'>{$source}</option>";
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--                <!--<div class="col-xs-2 padding-lr5" style="width: 110px;">-->
<!--                    <select class="form-control input-sm" name="q[project_type]" style="width: 100%;">-->
<!--                        <option value="">---><?php //echo Yii::t('proj_project', 'company_type'); ?><!---</option>-->
<!--                        --><?php
//                        $status_list = Program::typeText();
//                        foreach ($status_list as $k => $source) {
//                            echo "<option value='{$k}'>{$source}</option>";
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!---->
<!--                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> --><?php //echo Yii::t('common', 'search');?><!--</a>-->
<!--            </form>-->
        </div>
    </div>

</div>
