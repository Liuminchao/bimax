<!--<link href="css/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" />-->
<link href="css/select2/fselect.css" rel="stylesheet" type="text/css" />
<link href="css/glyphicon.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .level_tab td:nth-child(2){
        display: none;
    }
    .level_tab th{
        text-align: center;
    }
    /*#status_tab td:nth-child(2){*/
    /*    display: none;*/
    /*}*/
    /*#status_tab td:nth-child(3){*/
    /*    display: none;*/
    /*}*/
    #status_tab th{
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#models" role="tab" data-toggle="tab"><img src="img/model/model.png" width="22px" height="22px"> Models</a></li>
                    <li class="nav-item"><a class="nav-link" href="#status" role="tab" data-toggle="tab"><img src="img/model/status.png" width="22px" height="22px"> Status</a></li>
                    <?php
                        $operator_id = Yii::app()->user->id;
                        $operator_list = array(
                            '111222' => '111222',
                            '222333' => '222333',
                            '333444' => '333444',
                            '444555' => '444555',
                            '555666' => '555666'
                        );
                        $url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                        if( preg_match_all('/test/', $url, $rs) ){
                    ?>
                            <li class="nav-item"><a class="nav-link" href="#properties" role="tab" data-toggle="tab"><img src="img/model/property.png" width="22px" height="22px"> Properties</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tool" role="tab" data-toggle="tab"><img src="img/model/tool.png" width="22px" height="22px"> Tool</a></li>
                    <?php
                        }else{
                            if(array_key_exists($operator_id,$operator_list)){
                    ?>
                                <li class="nav-item"><a class="nav-link" href="#properties" role="tab" data-toggle="tab"><img src="img/model/property.png" width="22px" height="22px"> Properties</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tool" role="tab" data-toggle="tab"><img src="img/model/tool.png" width="22px" height="22px"> Tool</a></li>
                    <?php
                            }
                        }
                    ?>

                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="models">
                        <div class="row">
                            <div>
                                <form name="_query_form" id="_query_form" role="form">
                                    <input type="hidden" name= "q[program_id]" id="program_id" value="<?php echo $program_id ?>">
                                    <input type="hidden" name= "q[model_id]" id="model_id">
                                    <input type="hidden" name= "q[version]" id="version">
                                    <input type="hidden" id="text_tag">
                                    <a id="open_sidebar" class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" style="display: none">
                                        <i class="fas fa-th-large"></i>
                                    </a>
                                </form>
                                <select class="demo hidden" multiple="multiple" id="fselect" style="height: 30px;" >
                                </select>
                            </div>
<!--                            <div style="padding-left: 8px;">-->
<!--                                <select id="setLeftMouseOperation" class="form-control input-sm" style="width: 100%;">-->
<!--                                </select>-->
<!--                            </div>-->
                            <div style="padding-left: 8px;">
                                <!-- 打开模型 -->
                                <button class="btn btn-default" id="openModelData">Open</button>
                            </div>
<!--                            <div style="padding-left: 8px;">-->
<!--                                <!-- 关闭模型 -->
<!--                                <button class="btn btn-default" id="closeModelDatas">Close</button>-->
<!--                            </div>-->
<!--                            <div style="padding-left: 8px;">-->
<!--                                <!-- 关闭模型 -->
<!--                                <button class="btn btn-default" id="revertHomePosition">Reset</button>-->
<!--                            </div>-->
                            <div style="padding-left: 8px;">
                                <button id="level" class="btn btn-default" onclick="tree()" >Level</button>
                            </div>
                            <div style="padding-left: 8px;">
                                <button id="component" class="btn btn-default" onclick="revit_tree()" >Revit Info</button>
                            </div>
                            <div style="padding-left: 8px;">
                                <button id="qr_code" class="btn btn-default" onclick="allcode()">QR Code</button>
                            </div>
                            <div style="padding-left: 8px;">
                                <button id="component" class="btn btn-default" onclick="component_tree()" >Component</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="status">
                        <div class="row">
                            <div >
                                <select class="form-control input-sm"  id="template_id" style="width: 100%;height: 38px;">
                                    <option value="">--Template Name--</option>
                                    <?php
                                    $template_list = TaskTemplate::templateByProgram($program_id);
                                    if(count($template_list)>0){
                                        foreach ($template_list as $template_id => $template_name) {
                                            echo "<option value='{$template_id}'>{$template_name}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div style="padding-left: 8px;">
                                <button class="btn btn-default" onclick="selectstatuslist()"  type="button">Status</button>
                            </div>
                            <div style="padding-left: 8px;">
                                <button class="btn btn-default" onclick="selectqrchecklist()" type="button"  data-slide="true" >Tasks</button>
                            </div>
<!--                            <div style="padding-left: 8px;">-->
<!--                                <button class="btn btn-default" onclick="pbuexcel()" type="button">Excel</button>-->
<!--                            </div>-->
<!--                            <div style="padding-left: 8px;">-->
<!--                                <input id="alpha" class="form-control input-sm"   type="text" style="width: 100%;" placeholder="Alpha">-->
<!--                            </div>-->
                        </div>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="properties">
                        <div class="row">
                            <div >
                                <select id="big_type" name="q[big_type]" class="form-control input-sm" style="width: 100%;height: 38px;">
                                    <option value="">--Type--</option>
                                    <option value="1">Entity</option>
                                    <option value="2">Entity Group</option>
                                </select>
                            </div>
                            <div style="padding-left: 8px;">
                                <select id="type" name="q[type]" class="form-control input-sm" style="width: 100%;height: 38px;">
                                    <option value="">--Entity Type--</option>
                                    <option value="0x01">Entity Name</option>
                                    <option value="0x00">Entity Uuid</option>
                                    <option value="0x02">Entity Id</option>
                                    <option value="0x04">Entity Property</option>
                                    <option value="0x10">Entity Floor</option>
                                </select>
                            </div>
                            <div style="padding-left: 8px;">
                                <input id="detail" class="form-control input-sm"    name="q[detail]" type="text" style="width: 100%;height: 38px;" placeholder="detail">
                            </div>
                            <div style="padding-left: 8px;">
                                <button id="save" class="btn btn-default" ><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></button>
                            </div>
                            <div style="padding-left: 8px;">
                                <button class="btn btn-default" onclick="selectqrcode()" type="button">QR-CODE</button>
                            </div>
<!--                            <div style="padding-left: 8px;">-->
<!--                                <button class="btn btn-default" onclick="setcomponent()" type="button">Add Properties</button>-->
<!--                            </div>-->
                            <div style="padding-left: 8px;">
                                <button class="btn btn-default" onclick="tabdemo()" type="button" >Export</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="tool">
                        <div class="row">
                            <div style="width: 30px;" >
                                <a onclick="solate()"><img src="img/model/select.png" width="16px" height="16px" alt="Solate"></a>
                            </div>
                            <div style="width: 30px;" >
                                <a onclick="hide()"><img src="img/model/hide.png" width="16px" height="16px" alt="Hide"></a>
                            </div>
<!--                            <div style="width: 30px;" >-->
<!--                                <a onclick="transfer()"><img src="img/model/transfer.png" width="16px" height="16px" alt="Transfer"></a>-->
<!--                            </div>-->
                            <div style="width: 30px;" >
                                <!-- 关闭模型 -->
                                <button class="btn btn-default" id="revertHomePosition">Reset</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
                <div class="row" style="min-height: inherit;">
                    <div id="WindJS" style="width:100%;min-height: inherit;position: relative;background: white;margin-top: 5px;">
                        <canvas id="View" class="js-rotate-05" style="height:800px;float: left;top:0;left:0;width:100%;"></canvas>
                        <!--                    <div id="info" style="min-height: inherit;height:100%;float: left;width:35%;padding-left: 13px;overflow-y: auto;z-index: 1031">-->
                        <!---->
                        <!--                    </div>-->
                    </div>
                    <div id="Settings_iframe" style="position: relative;background: white;margin-top: 5px;display: none;">

                    </div>
                </div>
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>


<!-- jQuery -->
<script src="js/WIND.js" type="text/javascript" ></script>
<script type="text/javascript">document.write('<script src="js/model_qr.js?v='+new Date().getTime()+'" type="text/javascript" ><\/script>');</script>
<script src="js/loading_model.js"></script>
<script type="text/javascript">
    $(function () {
        // var t = window.devicePixelRatio   // 获取下载的缩放 125% -> 1.25    150% -> 1.5
        // document.write('您的显示器分辨率为:\n' + screen.width + '*' + screen.height + ' pixels<br/>');
        // var w1cm = document.getElementById("hutia").offsetWidth, w = screen.width/w1cm, h = screen.height/w1cm, r = Math.round(Math.sqrt(w*w + h*h) / 2.54);
        // document.write('您的显示器尺寸为:\n' + (screen.width/w1cm).toFixed(1) + '*' + (screen.height/w1cm).toFixed(1) + ' cm, '+ r +'寸<br/>');
        // alert(t);
        // document.getElementById("content-header").style.display="none";//隐藏
        // $("#weatherType").selectpicker('refresh');

        $('#myTab a').click(function (e) {
            console.log($(this).context);
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
            var tab_text = $(this).context.text;
            console.log(tab_text);
            if(tab_text == ' Material'){
                var wind_div=document.getElementById("WindJS");
                wind_div.style.display='none';
                // var set_div=document.getElementById("Settings_iframe");
                // set_div.style.display='block';
            }
        })

    })


</script>