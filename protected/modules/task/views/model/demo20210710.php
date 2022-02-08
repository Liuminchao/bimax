<!--<link href="css/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" />-->
<link href="css/select2/fselect.css" rel="stylesheet" type="text/css" />
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
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a href="#models" role="tab" data-toggle="tab"><img src="img/model/model.png" width="22px" height="22px"> Models</a></li>
                        <li class="nav-item"><a href="#status" role="tab" data-toggle="tab"><img src="img/model/status.png" width="22px" height="22px"> Status</a></li>
                        <li class="nav-item"><a href="#properties" role="tab" data-toggle="tab"><img src="img/model/property.png" width="22px" height="22px"> Properties</a></li>
                        <li class="nav-item"><a href="#tool" role="tab" data-toggle="tab"><img src="img/model/tool.png" width="22px" height="22px"> Tool</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="models">

                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="status">

                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="properties">

                        </div>
                        <!-- /.tab-pane -->
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="tool">

                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        </div>
    </div>

    <div class="row" style="margin-left: -20px;">
        <div class="col-xs-12">
            <div class="dataTables_length">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="active"><a href="#models" role="tab" data-toggle="tab"><img src="img/model/model.png" width="22px" height="22px"> Models</a></li>
                    <li role="presentation"><a href="#status" role="tab" data-toggle="tab"><img src="img/model/status.png" width="22px" height="22px"> Status</a></li>
                    <li role="presentation"><a href="#properties" role="tab" data-toggle="tab"><img src="img/model/property.png" width="22px" height="22px"> Properties</a></li>
                    <li role="presentation"><a href="#tool" role="tab" data-toggle="tab"><img src="img/model/tool.png" width="22px" height="22px"> Tool</a></li>
<!--                    <li role="presentation"><a href="#settings" role="tab" data-toggle="tab"><img src="img/model/set.png" width="22px" height="22px"> Material</a></li>-->
<!--                    <li role="presentation"><a href="#qr_settings" role="tab" data-toggle="tab"><img src="img/model/qr.png" width="22px" height="22px"> Qr Settings</a></li>-->
                </ul>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-xs-12" >
            <div class="tab-content" >
                <div class="tab-pane active" id="models">
                    <div class="dataTables_length">
                        <!--                                        <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">-->
                        <!--                                            -->
                        <!--                                        </div>-->
                        <div class="col-xs-2 padding-lr5" style="width: 190px;margin-top: 5px;">
                            <form name="_query_form" id="_query_form" role="form">
                                <input type="hidden" name= "q[program_id]" id="program_id" value="<?php echo $program_id ?>">
                                <input type="hidden" name= "q[model_id]" id="model_id">
                                <input type="hidden" name= "q[version]" id="version">
                                <!--                                                <select id="modellist" name="q[model]" class="form-control input-sm"  style="width: 100%;">-->
                                <!--                                                    <option>Models</option>-->
                                <!--                                                </select>-->
                            </form>
                            <select class="demo hidden" multiple="multiple" id="fselect" style="height: 30px;" >
                            </select>
                        </div>
                        <!--                                        <div class="col-xs-2 padding-lr5" style="width: 90px;margin-top: 5px;">-->
                        <!--                                            <select id="open_tag" class="form-control input-sm" style="width: 100%;">-->
                        <!--                                                <option value="1">Single</option>-->
                        <!--                                                <option value="2">Multiple</option>-->
                        <!--                                                <option value="3">All</option>-->
                        <!--                                            </select>-->
                        <!--                                        </div>-->
                        <div class="col-xs-2 padding-lr5" style="width: 90px;margin-top: 5px;">
                            <select id="setLeftMouseOperation" class="form-control input-sm" style="width: 100%;">
                            </select>
                        </div>
                        <!--                                        <div class="col-xs-2 padding-lr5" style="width: 90px;margin-top: 5px;">-->
                        <!--                                            <select id="open_type" class="form-control input-sm" style="width: 100%;">-->
                        <!--                                                <option value="">-Open Type-</option>-->
                        <!--                                                <option value="1">Open All Params</option>-->
                        <!--                                            </select>-->
                        <!--                                        </div>-->
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <!-- 打开模型 -->
                            <button class="btn btn-default" id="openModelData">Open</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <!-- 关闭模型 -->
                            <button class="btn btn-default" id="closeModelDatas">Close</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <!-- 关闭模型 -->
                            <button class="btn btn-default" id="revertHomePosition">Reset</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                            <button id="qr_code" class="btn btn-default" onclick="allcode()">Qr Code</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <button id="level" class="btn btn-default" onclick="tree()">Level</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
                            <button id="component" class="btn btn-default" onclick="component_tree()">Component</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
                            <button id="component" class="btn btn-default" onclick="revit_tree()">Revit Info</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="status">
                    <div class="dataTables_length">
                        <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
                            <select class="form-control input-sm"  id="template_id" style="width: 100%;">
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
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <button class="btn btn-default" onclick="selectqrchecklist()" type="button">Tasks</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <button class="btn btn-default" onclick="selectstatuslist()" type="button">Status</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <button class="btn btn-default" onclick="pbuexcel()" type="button">Excel</button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 120px;margin-top: 5px;">
                            <input id="alpha"  type="text" style="width: 100%;height: 30px" placeholder="Alpha">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="properties">
                    <div class="dataTables_length">
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
                                <option value="0x00">Entity Uuid</option>
                                <option value="0x02">Entity Id</option>
                                <option value="0x04">Entity Property</option>
                                <option value="0x10">Entity Floor</option>
                            </select>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                            <input id="detail" name="q[detail]" type="text" style="width: 100%;height: 30px" placeholder="detail">
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                            <button id="save" class="btn btn-default" ><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></button>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 100px;margin-top: 5px;">
                            <button class="btn btn-default" onclick="selectqrcode()" type="button">QR-CODE</button>
                        </div>
<!--                        <div class="col-xs-2 padding-lr5" style="width: 60px;margin-top: 5px;">-->
<!--                            <button class="btn btn-default" onclick="pbuinfo()" type="button">Info</button>-->
<!--                        </div>-->
                        <div class="col-xs-2 padding-lr5" style="width: 135px;margin-top: 5px;">
                            <button class="btn btn-default" onclick="setcomponent()" type="button">Add Properties</button>
                        </div>
                        <!--                                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">-->
                        <!--                                            <button class="btn btn-default" onclick="exportcomponent()" type="button">Export</button>-->
                        <!--                                        </div>-->
                        <!--                                        <div class="col-xs-2 padding-lr5" style="width: 130px;margin-top: 5px;">-->
                        <!--                                            <button class="btn btn-default" onclick="exporttask()" type="button">Export Task</button>-->
                        <!--                                        </div>-->
                        <div class="col-xs-2 padding-lr5" style="width: 130px;margin-top: 5px;">
                            <button class="btn btn-default" onclick="tabdemo()" type="button">Export</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tool">
                    <div class="dataTables_length">
                        <div class="col-xs-2 padding-lr5" style="width: 60px;margin-top: 5px;">
                            <a onclick="solate()"><img src="img/model/select.png" width="16px" height="16px" alt="Solate"></a>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 60px;margin-top: 5px;">
                            <a onclick="hide()"><img src="img/model/hide.png" width="16px" height="16px" alt="Hide"></a>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 60px;margin-top: 5px;">
                            <a onclick="transfer()"><img src="img/model/transfer.png" width="16px" height="16px" alt="Transfer"></a>
                        </div>
                        <div class="col-xs-2 padding-lr5" style="width: 80px;margin-top: 5px;">
                            <!-- 关闭模型 -->
                            <button class="btn btn-default" id="revertHomePosition">Reset</button>
                        </div>
                    </div>
                </div>
<!--                <div class="tab-pane" id="settings" style="min-height: inherit;">-->
<!--                    <iframe id="attendIframe" name="attendIframe" frameborder="0" class="iframe_r" src="--><?php //echo "?r=task/model/list&program_id=".$program_id;?><!--" style="height: 600px;width:100%; background-color:#fff;"></iframe>-->
<!--                </div>-->
<!--                <div class="tab-pane" id="qr_settings" style="height:650px;">-->
<!--                    <iframe id="attendIframe" name="attendIframe" frameborder="0" class="iframe_r" src="--><?php //echo "?r=task/model/newqr&program_id=".$program_id;?><!--" style="height:100%;width:100%; background-color:#fff;"></iframe>-->
<!--                </div>-->
            </div>
        </div>
    </div>
    <div class="row" style="min-height: inherit;">
        <div id="WindJS" style="min-height: inherit;position: relative;background: white;margin-top: 5px;">
            <canvas id="View" class="js-rotate-05" style="height:800px;float: left;top:0;left:0;width:65%;"></canvas>
            <div id="info" style="min-height: inherit;height:100%;float: left;width:35%;padding-left: 13px;overflow-y: auto">

            </div>
        </div>
        <div id="Settings_iframe" style="position: relative;background: white;margin-top: 5px;display: none">

        </div>
    </div>

<!--<script type="text/javascript" src="js/bootstrap-select/bootstrap-select.min.js"></script>-->
<!--<script src="js/browser.js" type="text/javascript" ></script>-->
<!--<script src="js/browser-polyfill.js" type="text/javascript" ></script>-->
<script type="text/javascript" src="js/select2/fselect.js"></script>
<script src="js/WIND.js" type="text/javascript" ></script>
<script type="text/javascript">document.write('<script src="js/model_qr.js?v='+new Date().getTime()+'" type="text/javascript" ><\/script>');</script>
<script src="js/loading.js"></script>
<script src="js/bootstrap-treeview.js"></script>
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
<!--<script type="text/javascript">-->
<!--    // 判断pc浏览器是否缩放，若返回100则为默认无缩放，如果大于100则是放大，否则缩小-->
<!--    function detectZoom (){-->
<!--        var ratio = 0,-->
<!--            screen = window.screen,-->
<!--            ua = navigator.userAgent.toLowerCase();-->
<!---->
<!--        if (window.devicePixelRatio !== undefined) {-->
<!--            ratio = window.devicePixelRatio;-->
<!--        }-->
<!--        else if (~ua.indexOf('msie')) {-->
<!--            if (screen.deviceXDPI && screen.logicalXDPI) {-->
<!--                ratio = screen.deviceXDPI / screen.logicalXDPI;-->
<!--            }-->
<!--        }-->
<!--        else if (window.outerWidth !== undefined && window.innerWidth !== undefined) {-->
<!--            ratio = window.outerWidth / window.innerWidth;-->
<!--        }-->
<!---->
<!--        if (ratio){-->
<!--            ratio = Math.round(ratio * 100);-->
<!--        }-->
<!---->
<!--        return ratio;-->
<!--    };-->
<!--    //window.onresize 事件可用于检测页面是否触发了放大或缩小。-->
<!--    $(function(){-->
<!--        //alert(detectZoom())-->
<!--    })-->
<!--    $(window).on('resize',function(){-->
<!--        isScale();-->
<!--    });-->
<!--    //判断PC端浏览器缩放比例不是100%时的情况-->
<!--    function isScale(){-->
<!--        var rate = detectZoom();-->
<!--        if(rate != 100){-->
<!--            //如何让页面的缩放比例自动为100,'transform':'scale(1,1)'没有用，又无法自动条用键盘事件，目前只能提示让用户如果想使用100%的比例手动去触发按ctrl+0-->
<!--            console.log(rate);-->
<!--            alert('当前页面不是100%显示，请按键盘ctrl+0恢复100%显示标准，以防页面显示错乱！')-->
<!--            //var t = window.devicePixelRatio   // 获取下载的缩放 125% -> 1.25    150% -> 1.5-->
<!---->
<!--        }-->
<!--    }-->
<!---->
<!--    //阻止pc端浏览器缩放js代码-->
<!--    //由于浏览器菜单栏属于系统软件权限，没发控制，我们着手解决ctrl/cammond + +/- 或 Windows下ctrl + 滚轮 缩放页面的情况，只能通过js来控制了-->
<!--    // jqeury version-->
<!--    $(document).ready(function () {-->
<!--        // chrome 浏览器直接加上下面这个样式就行了，但是ff不识别-->
<!--        $('body').css('zoom', 'reset');-->
<!--        $(document).keydown(function (event) {-->
<!--            //event.metaKey mac的command键-->
<!--            if ((event.ctrlKey === true || event.metaKey === true)&& (event.which === 61 || event.which === 107 || event.which === 173 || event.which === 109 || event.which === 187  || event.which === 189)){-->
<!--                event.preventDefault();-->
<!--            }-->
<!--        });-->
<!--        $(window).bind('mousewheel DOMMouseScroll', function (event) {-->
<!--            if (event.ctrlKey === true || event.metaKey) {-->
<!--                event.preventDefault();-->
<!--            }-->
<!--        });-->
<!---->
<!--    });-->
<!--</script>-->