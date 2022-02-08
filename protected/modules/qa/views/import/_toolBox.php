<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <input type="hidden" name="q[project_id]" value="<?php echo $program_id; ?>">
                <div class="form-group " style="width: 160px;">
                    <select class="form-control input-sm" name="q[type_id]" style="width: 100%;">
                        <option value="">--Discipline--</option>
                        <?php
                            $type_list = QaCheckType::AllType();
                            $form_type = QaChecklist::formType();
                            $form_type_id = '';
                            $form_type_name = '';
                            foreach ($type_list as $val => $value){
                                if($form_type_id != $value['form_type']){
                                    $form_type_id = $value['form_type'];
                                    if($form_type_name != $form_type[$form_type_id]){
                                        $form_type_name = $form_type[$form_type_id];
                                        echo "<optgroup label='$form_type_name'>";
                                    }
                                }
                                echo "<option value='".$val."'";
                                if ($args['type_id'] == $val) {
                                    echo " selected";
                                }
                                echo ">".$value['type_name']."</option>";
                    
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group padding-lr5" style="width: 250px;">
                    <input type="text" class="form-control input-sm" name="q[form_name]" placeholder="Checklist Name" style="width: 100%" value="<?php echo $args['form_name']==''?'':$args['form_name']; ?>">
                </div>
                
                <div class="form-group padding-lr5" style="width:100px">
                    <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-3">

    </div>
</div>