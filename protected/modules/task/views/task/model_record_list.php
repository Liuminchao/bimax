

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <div class="row" style="margin-left: -20px;">
                        <div class="col-xs-11">
                            <div class="dataTables_length">
                                <div class="col-xs-2 padding-lr5" >
                                    <select class="form-control input-sm"  id="template_id" style="width: 100%;">
                                        <option value="">--Template Name--</option>
                                        <?php
                                        $template_list = TaskTemplate::templateByProgram($program_id);
                                        foreach ($template_list as $template_id => $template_name) {
                                            echo "<option value='{$template_id}'>{$template_name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 150px;">
                                    <select id="modellist" class="form-control input-sm"  style="width: 100%;">
                                    </select>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 90px;">
                                    <select id="setLeftMouseOperation" class="form-control input-sm" style="width: 100%;">
                                    </select>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 80px;">
                                    <!-- 打开模型 -->
                                    <button id="openModelData">Open</button>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 80px;">
                                    <!-- 关闭模型 -->
                                    <button id="closeModelDatas">Close</button>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 80px;">
                                    <!-- 关闭模型 -->
                                    <button id="revertHomePosition">Reset</button>
                                </div>
                                <!--                                <div class="col-xs-2 padding-lr5" style="width: 80px;">-->
                                <!--                                    <button id="save">Save</button>-->
                                <!--                                </div>-->
                                <form name="_query_form" id="_query_form" role="form">
                                    <input type="hidden" name= "q[template_id]" id="template" value="">
                                    <input type="hidden" name= "q[uuid]" id="uuid" value="">
                                    <input type="hidden" name= "q[program_id]" id="program_id" value="<?php echo $program_id ?>">
                                    <input type="hidden" name= "q[model_id]" id="model_id" value="">
                                    <input type="hidden" name= "q[version]" id="version" value="">
                                </form>
<!--                                <button id="save" class="tool-a-search"><i class="fa fa-fw fa-search"></i> --><?php //echo Yii::t('common', 'search'); ?><!--</button>-->
                            </div>
                        </div>
                    </div>
                    <div id="WindJS" style="position: relative;background: white;margin-top: 5px;">
                        <canvas id="View" class="js-rotate-05" style="top:0;left:0;height:400px;width:60%;"></canvas>
                    </div>
                    <div id="datagrid">
                        <?php $this->actionRecordUuidGrid($program_id); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/WIND.js"></script>
<script type="text/javascript" src="js/task_model.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    $(function(){

    });
</script>