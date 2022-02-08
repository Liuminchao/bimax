<style type="text/css">
    #status_tab td:nth-child(2){
        display: none;
    }
    #status_tab td:nth-child(3){
        display: none;
    }
    #status_tab th{
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="box" style="margin-bottom: 5px;">
            <div class="box-body table-responsive" >
                <div role="grid"  class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <div class="row" style="margin-left: -20px;">
                        <div class="col-xs-6">
                            <div class="dataTables_length">
                                <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
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
                                <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
                                    <form name="_query_form" id="_query_form" role="form">
                                        <input type="hidden" name= "q[program_id]" id="program_id" value="<?php echo $program_id ?>">
                                        <input type="hidden" name= "q[model_id]" id="model_id">
                                        <input type="hidden" name= "q[version]" id="version">
                                        <select id="modellist" name="q[model]" class="form-control input-sm"  style="width: 100%;">
                                        </select>
                                    </form>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 90px;margin-top: 5px;">
                                    <select id="setLeftMouseOperation" class="form-control input-sm" style="width: 100%;">
                                    </select>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
                                    <select id="big_type" name="q[big_type]" class="form-control input-sm" style="width: 100%;">
                                        <option value="">--Type--</option>
                                        <option value="1">Entity</option>
                                        <option value="2">Entity Group</option>
                                    </select>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
                                    <select id="type" name="q[type]" class="form-control input-sm" style="width: 100%;">
                                        <option value="">--Entity Type--</option>
                                        <option value="0x01">Entity Name</option>
                                        <option value="0x02">Entity Id</option>
                                        <option value="0x04">Entity Property</option>
                                        <option value="0x10">Entity Floor</option>
                                    </select>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                                    <input id="detail" name="q[detail]" type="text" style="width: 100%;height: 30px" placeholder="detail">
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                                    <!-- 打开模型 -->
                                    <button class="btn btn-default" id="openModelData">Open</button>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                                    <!-- 关闭模型 -->
                                    <button class="btn btn-default" id="closeModelDatas">Close</button>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                                    <!-- 重置模型 -->
                                    <button class="btn btn-default" id="revertHomePosition">Reset</button>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                                    <button id="qr_code" class="btn btn-default" onclick="allcode()">Qr Code</button>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                                    <button id="qr_code" class="btn btn-default" onclick="solate()">Solate</button>
                                </div>
                                <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                                    <button id="qr_code" class="btn btn-default" onclick="hide()">Hide</button>
                                </div>
                                <button id="save" class="btn btn-default" style="margin-top: 5px;"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></button>
                            </div>
                        </div>
                        <div class="col-xs-6" style="padding-top: 13px;text-align: center">
                            <div class="dataTables_length">
                                <button class="btn btn-default" onclick="selectqrchecklist()" type="button">QA/QC</button>
                                <button class="btn btn-default" onclick="selectqrcode()" type="button">QR-CODE</button>
                                <button class="btn btn-default" onclick="selectstatuslist()" type="button">Status</button>
                                <button class="btn btn-default" onclick="setcomponent()" type="button">Set Pbu</button>
                                <button class="btn btn-default" onclick="exportcomponent()" type="button">Export Pbu</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div id="WindJS" style="position: relative;background: white;margin-top: 5px;">
            <canvas id="View" class="js-rotate-05" style="float: left;top:0;left:0;height:600px;width:50%;"></canvas>
            <div id="info" style="float: left;width:50%;padding-left: 13px;">

            </div>
        </div>
    </div>
</div>
<!--<div class="row"  style="margin-top: 5px;">-->
<!--    <div class="col-xs-12">-->
<!--        <button class="btn btn-default" onclick="selectqrchecklist()" type="button">QA/QC</button>-->
<!--        <button class="btn btn-default" onclick="selectqrcode()" type="button">QR-CODE</button>-->
<!--        <button class="btn btn-default" onclick="selectstatuslist()" type="button">Status</button>-->
<!--        <button class="btn btn-default" onclick="setcomponent()" type="button">Set Pbu</button>-->
<!--        <button class="btn btn-default" onclick="exportcomponent()" type="button">Export Pbu</button>-->
<!--        <div class="box">-->
<!--            <div class="box-body table-responsive">-->
<!--                <div role="grid" class="dataTables_wrapper form-inline" id="--><?php //echo $this->gridId; ?><!--_wrapper">-->
<!--                    <div id="datagrid">-->
<!--                        <ul id="treeDemo" class="ztree"></ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<script type="text/javascript" src="js/zTree_js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="js/WIND.js"></script>
<script type="text/javascript" src="js/model_qr.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">

//    window.onload=function (){
//        var o = document.getElementById("canvas");
//        var info = document.getElementById("info");
//        var w = o.clientWidth||o.offsetWidth;
//        var view = document.getElementById("View");
//        var view_width = view.clientWidth||view.offsetWidth;
//        var total_width = document.getElementById("WindJS").clientWidth;
//        var canvas_width = document.getElementById("View").clientWidth;
//        var info_width = total_width-view_width-5;
//        var width = canvas_width -w+5;
//        o.style.width = view_width+'px';
//        info.style.width = info_width+'px';
//    }
</script>