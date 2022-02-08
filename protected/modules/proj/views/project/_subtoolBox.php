<div class="row">
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="query_form" id="query_form" role="form">
                        <div class="row" >
                            <input type="hidden" name="q[father_proid]" value="<?php echo $father_proid;    ?>">

                            <div class="form-group padding-lr5" style="width:250px">
                                <input type="text" class="form-control input-sm" name="q[subcon_name]" placeholder="<?php echo Yii::t('proj_project', 'sub_contractor_name'); ?>" style="width: 100%" value="<?php echo $args['subcon_name']; ?>">
                            </div>
                            <!--                <div class="col-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[contractor_id]" placeholder="<?php echo Yii::t('proj_project', 'contractor_id'); ?>">
                </div>-->
                            <div class="form-group padding-lr5" style="width: 160px;">
                                <select class="form-control input-sm" name="q[status]" style="width: 100%;">
                                    <option value="">--<?php echo Yii::t('proj_project', 'status'); ?>--</option>
                                    <?php
                                    $status_list = Program::statusText();
                                    foreach ($status_list as $k => $source) {
                                        echo "<option value='".$k."'";
                                        if ($args['status'] == $k) {
                                            echo " selected";
                                        }
                                        echo ">".$source."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <a class="tool-a-search padding-lr5" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                        </div>
                    </form>
            </div>
    </div>
    <div class="col-3">
            <!--<label  class="padding-lr5 float-sm-right">
                <button class="btn btn-primary btn-sm" onclick="back('<?php echo $father_proid; ?>')"><?php echo Yii::t('common', 'button_back');  ?></button>
            </label>-->
            <label  class="padding-lr5 float-sm-right">
                <?php if($father_model->status == Program::STATUS_NORMAL):  ?>
                    <?php
                    $operator_id = Yii::app()->user->id;
                    $authority_list = OperatorProject::authorityList($operator_id);
                    $value = $authority_list[$father_proid];
                    if($value == '1') {
                        ?>
                        <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $father_proid; ?>')"><?php echo Yii::t('proj_project', 'Add Sub Proj');   ?></button>
                    <?php } ?>
                <?php endif ?>
            </label>
    </div>
</div>

<script type="text/javascript">
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/project/list&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id="+id;
    }


</script>