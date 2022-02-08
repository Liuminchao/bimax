<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" >
                    <input type="text" class="form-control input-sm" name="q[program_name]" placeholder="<?php echo Yii::t('proj_project', 'program_name');?>">
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 180px;">
                    <select class="form-control input-sm" name="q[contractor_id]" id="contractor_id" style="width: 100%;">
                        <option value="">--<?php echo Yii::t('license_licensepdf', 'main_contractor'); ?>--</option>
                        <?php
                        $maincomp_list = Contractor::mcCompList();
                        if($maincomp_list) {
                            foreach ($maincomp_list as $k => $name) {
                                echo "<option value='{$k}'>{$name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">

    </div>
</div>