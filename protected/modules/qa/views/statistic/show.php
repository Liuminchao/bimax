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
    #block_chart{
        overflow: hidden;
    }
    .legend_item {
        height: 50%;
        display: -ms-flexbox;
        display: flex;
        margin-left: 1rem;
    }
    .one_bottom_legends {
        height: 40px;
        -ms-flex-pack: center;
        justify-content: center;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }
    .legend_item, .one_bottom_legends {
        -ms-flex-align: center;
        align-items: center;
    }
    .legend_icon {
        width: 1rem;
        height: 1rem;
    }
    .legend_text {
        margin-left: .2rem;
        font-size: 1rem;
        color: #000000;
    }
    .one_bottom, .one_bottom_legends {
        width: 100%;
        display: -ms-flexbox;
        display: flex;
    }
    .one_bottom {
        height: 42%;
        -ms-flex-direction: column;
        flex-direction: column;
    }
    table {
        clear: both;
        margin-top: 6px !important;
        margin-bottom: 6px !important;
        max-width: none !important;
    }
</style>
<script type="text/javascript">
    $(function(){
        var template_id = $('#column_template_id').val();
        $('#template_id_2').val(template_id);
        $('#template_id_3').val(template_id);
        itemQuery_3();
        itemQuery_4();
        itemQuery_5();
    });
    //查询
    var itemQuery_3 = function () {
        var objs = document.getElementById("_query_form_3").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        <?php echo $this->gridId; ?>.condition = url;
        <?php echo $this->gridId; ?>.refresh();
    }
    //返回
    var back = function (project_id,template_id,stage_id) {
        window.location = "index.php?r=task/template/stagelist&id="+template_id+"&project_id="+project_id;
    }
    //批量导出
    var itemExport = function (project_id,template_id) {
//        var objs = document.getElementById("_query_form").elements;
//        var i = 0;
//        var cnt = objs.length;
//        var obj;
//        var url = '';
//
//        for (i = 0; i < cnt; i++) {
//            obj = objs.item(i);
//            url += '&' + obj.name + '=' + obj.value + '&q[tag]=' +tag;
//        }
        window.location = "index.php?r=qa/statistic/blockexport&program_id=" + project_id+"&template_id="+template_id;
    }
    //查询
    var itemQuery_4 = function () {
        var objs = document.getElementById("_query_form_4").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';
        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            console.log(obj);
            url += '&' + obj.name + '=' + obj.value;
        }
        <?php echo $this->gridId_1; ?>.condition = url;
        <?php echo $this->gridId_1; ?>.refresh();
    }
    //查询
    var itemQuery_5 = function () {
        var objs = document.getElementById("_query_form_5").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';
        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            console.log(obj);
            url += '&' + obj.name + '=' + obj.value;
        }
        <?php echo $this->gridId_2; ?>.condition = url;
        <?php echo $this->gridId_2; ?>.refresh();
    }
    //查询
    var itemQuery_6 = function () {
        var objs = document.getElementById("_query_form_6").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';
        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            console.log(obj);
            url += '&' + obj.name + '=' + obj.value;
        }
        <?php echo $this->gridId_3; ?>.condition = url;
        <?php echo $this->gridId_3; ?>.refresh();
    }
    //批量导出
    var itemExport_1 = function (project_id,template_id) {
        // var objs = document.getElementById("_query_form_1").elements;
        // var i = 0;
        // var cnt = objs.length;
        // var obj;
        // var url = '';
        //
        // for (i = 0; i < cnt; i++) {
        //     obj = objs.item(i);
        //     url += '&' + obj.name + '=' + obj.value;
        // }
        window.location = "index.php?r=qa/statistic/dateexport&program_id=" + project_id+"&template_id="+template_id;
    }
</script>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header p-2" >
                <div class="row">
                    <div class="col-9" style="text-align: right;margin-bottom: 0px;">
                        <ul class="nav nav-pills" role="tablist" id="myTab">
                            <?php
                                $pro_model = Program::model()->findByPk($program_id);
                                $root_proid = $pro_model->root_proid;
                                $app_module = ProgramApp::myModuleList($root_proid);
                                $is_lite = $app_module[0]['is_lite'];
                                if($is_lite == '0'){
                            ?>
                                    <li role="presentation" class="nav-item"><a class="nav-link active" href="#block_chart" role="tab" data-toggle="tab"> Block Chart</a></li>
                            <?php } ?>
                            <li role="presentation" class="nav-item"><a class="nav-link" href="#general" role="tab" data-toggle="tab"> General</a></li>
                        </ul>
                    </div>
                    <div class="col-3" style="text-align: right;padding-right:20px;line-height: 37px;">
<!--                        <a class='a_logo' href="index.php?r=task/blockchart/list&project_id=--><?php //echo $program_id; ?><!--" ><img src="img/model/set.png" width="22px" height="22px"></a>-->
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <?php
                        if($is_lite == '0'){
                    ?>
                    <div class="tab-pane active" id="block_chart" >
                        <?php
                        //                    $pro_model = Program::model()->findByPk($program_id);
                        //                    $root_proid = $pro_model->root_proid;
                        //                    if($root_proid == '1627' || $root_proid == '2310'){
                        //    //            $url = "https://shell.cmstech.sg/hvblchart/index.html#/?proj_id=".$program_id;
                        //                        $url = "https://shell.cmstech.sg/blchart/index.html#/hv?proj_id=".$root_proid;
                        //                    }else{
                        //                        $url = "https://shell.cmstech.sg/blchart/index.html#/?proj_id=".$program_id;
                        //                    }
                        $url = "https://shell.cmstech.sg/blchart/index.html#/?proj_id=".$program_id;
                        ?>
                        <iframe id="attendIframe" name="attendIframe" frameborder="0" class="iframe_r" src="<?php echo $url; ?>" scrolling="auto" style="height:630px;width:100%;background-color:#fff;"></iframe>
                    </div>
                    <?php } ?>
                    <?php
                    if($is_lite == '0'){
                    ?>
                        <div class="tab-pane" id="general">
                            <form name="query_form" id="query_form" role="form">
                                <div class="row">
                                    <input id="project_id" name="q[program_id]" type="hidden" value="<?php echo $program_id; ?>">
                                    <div class="form-group padding-lr5" style="width:200px">
                                        <select class="form-control input-sm"  id="column_template_id" style="width: 100%;">
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
                                    <a class="tool-a-search" href="javascript:itemStatistic();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                                </div>
                            </form>
                            <div id="statistic" class="row" style="height: 450px; position: relative;">

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto;" >
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('block_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid" >
                                                    <?php $this->actionBlockGrid($program_id); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto">
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('date_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid_1"><?php $this->actionDateGrid($program_id); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto">
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('moduletype_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid_2"><?php $this->actionModuletypeGrid($program_id); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto">
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('taskcnt_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid_3"><?php $this->actionTaskcntGrid($program_id); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-info card-outline">
                                <div class="card-body">
                                    <form name="_query_form" id="_query_form_1" role="form">
                                        <div class="row" style="display: none">
                                            <input id="program_id" name="q[program_id]" type="hidden" value="<?php echo $program_id; ?>">
                                            <div class="form-group padding-lr5" style="width:200px">
                                                <select class="form-control input-sm" name="q[template_id]"  id="pie_template_id" style="width: 100%;">
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
                                            <a class="tool-a-search" href="javascript:itemMilestones();"><i class="fa fa-fw fa-search"></i> Search</a>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="one_bottom_legends" id="legends">
                                        </div>
                                    </div>

                                    <div  id="model">

                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php }else{ ?>
                        <div class="tab-pane active" id="general">
                            <form name="query_form" id="query_form" role="form">
                                <div class="row">
                                    <input id="project_id" name="q[program_id]" type="hidden" value="<?php echo $program_id; ?>">
                                    <div class="form-group padding-lr5" style="width:200px">
                                        <select class="form-control input-sm"  id="column_template_id" style="width: 100%;">
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
                                    <a class="tool-a-search" href="javascript:itemStatistic();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                                </div>
                            </form>
                            <div id="statistic" class="row" style="height: 450px; position: relative;">

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto;" >
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('block_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid" >
                                                    <?php $this->actionBlockGrid($program_id); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto">
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('date_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid_1"><?php $this->actionDateGrid($program_id); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto">
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('moduletype_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid_2"><?php $this->actionModuletypeGrid($program_id); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info card-outline">
                                        <div class="card-body" style="overflow-x: auto">
                                            <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                                                <?php $this->renderPartial('taskcnt_toolBox',array('program_id'=>$program_id)); ?>
                                                <div id="datagrid_3"><?php $this->actionTaskcntGrid($program_id); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-info card-outline">
                                <div class="card-body">
                                    <form name="_query_form" id="_query_form_1" role="form">
                                        <div class="row" style="display: none">
                                            <input id="program_id" name="q[program_id]" type="hidden" value="<?php echo $program_id; ?>">
                                            <div class="form-group padding-lr5" style="width:200px;display: none">
                                                <select class="form-control input-sm" name="q[template_id]"  id="pie_template_id" style="width: 100%;">
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
                                            <a class="tool-a-search" href="javascript:itemMilestones();"><i class="fa fa-fw fa-search"></i> Search</a>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="one_bottom_legends" id="legends">
                                        </div>
                                    </div>

                                    <div  id="model">

                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!--  横柱  -->
<script src="https://img.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    $(function () {
        // var t = window.devicePixelRatio   // 获取下载的缩放 125% -> 1.25    150% -> 1.5
        // document.write('您的显示器分辨率为:\n' + screen.width + '*' + screen.height + ' pixels<br/>');
        // var w1cm = document.getElementById("hutia").offsetWidth, w = screen.width/w1cm, h = screen.height/w1cm, r = Math.round(Math.sqrt(w*w + h*h) / 2.54);
        // document.write('您的显示器尺寸为:\n' + (screen.width/w1cm).toFixed(1) + '*' + (screen.height/w1cm).toFixed(1) + ' cm, '+ r +'寸<br/>');
        // alert(t);
        // document.getElementById("content-header").style.display="none";//隐藏
        // $("#weatherType").selectpicker('refresh');
        // $('#myTab a').click(function (e) {
        //     console.log($(this).context);
        //     e.preventDefault();//阻止a链接的跳转行为
        //     $(this).tab('show');//显示当前选中的链接及关联的content
        //     var tab_text = $(this).context.text;
        //     console.log(tab_text);
        // })

        itemStatistic();
        itemMilestones();
    })
    var itemMilestones = function() {
        var template_id = $('#pie_template_id').val();
        var project_id = $('#project_id').val();
        $.ajax({
            data: {template_id:template_id,project_id:project_id},
            url: "index.php?r=qa/statistic/milestones",
            dataType: "json",
            type: "POST",
            success: function (data) {
                if(data.cnt>0){
                    $('#model').empty();
                    var cnt = data.cnt;
                    var row = cnt/4+1;
                    var id_index = 0;
                    var pie_str = '';
                    for(var row_index=0;row_index<=row;row_index++){
                        pie_str+= "<div class='row'>";
                        for (var i=0; i<4; i++) {
                            id_index++;
                            var pie_id = 'pie_'+id_index;
                            pie_str+="<div class='col-sm-3' id=" + pie_id + " style='height: 200px;background-color:#ffffff'></div>";
                        }
                        pie_str+="</div>";
                    }
                }
                $('#model').append(pie_str);
                console.log(pie_str);
                var tag = 0;
                var model = data.model;
                var legend = "";
                $('#legends').empty();
                $.each(data.model, function (i, stage_list) {
                    if(i == 0){
                        $.each(stage_list, function (x, y) {
                            legend+="<div class='legend_item'><div class='legend_icon' style='background-color:"+y.color+" '></div><div class='legend_text'>"+y.name+"</div></div>";
                        })
                    }
                })
                $('#legends').append(legend);
                $.each(data.model, function (name, value) {
                    tag++;
                    console.log(value);
                    console.log(tag);
                    var chart = Highcharts.chart('pie_'+tag, {
                        chart: {
                            spacing : [0, 0 , 0, 0],
                            backgroundColor: '#f9f9f9'
                        },
                        title: {
                            floating:true,
                            text: data.block[name]
                        },
                        legend: {
                            layout: 'vertical',
                            backgroundColor: '#FFFFFF',
                            floating: true,
                            align: 'left',
                            x: 100,
                            verticalAlign: 'top',
                            y: 70
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    format: ' {point.y} ',
                                    style: {
                                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    },
                                    distance: '-20%',//调整数据显示位置
                                },
                                point: {
                                    events: {
                                        click: function(e) { // 同样的可以在点击事件里处理
                                            alert(e.point.name);
                                            alert(e.point.y + ' %');
                                        }
                                    }
                                },
                            }
                        },
                        series: [{
                            type: 'pie',
                            innerSize: '70%',
                            data: value
                        }],
                        credits: {
                            enabled:false
                        },

                        exporting: {
                            enabled:false
                        },
                    }, function(c) { // 图表初始化完毕后的会掉函数
                        // 环形图圆心
                        var centerY = c.series[0].center[1],
                            titleHeight = parseInt(c.title.styles.fontSize);
                        // 动态设置标题位置
                        c.setTitle({
                            y:centerY + titleHeight/2
                        });
                    });
                })
            }
        })
    }
    var itemStatistic = function () {
        var template_id = $('#column_template_id').val();
        $('#template_id_2').val(template_id);
        $('#template_id_3').val(template_id);
        $('#template_id_4').val(template_id);
        $('#pie_template_id').val(template_id);
        itemQuery_3();
        itemQuery_4();
        itemQuery_5();
        itemQuery_6();
        itemMilestones();
        var project_id = $('#project_id').val();
        $.ajax({
            data: {template_id:template_id,project_id:project_id},
            url: "index.php?r=qa/statistic/blockcolumn",
            dataType: "json",
            type: "POST",
            success: function (data) {
                Highcharts.chart('statistic', {
                    chart: {
                        type: 'column',
                        marginBottom:100
                    },
                    colors: data.color,
                    credits: { enabled: false },
                    exporting: { enabled:true },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: data.x
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        },
                        stackLabels: {  // 堆叠数据标签
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                            }
                        }
                    },
                    legend: {
                        align: 'center',
                        x: -20,
                        y: 10,
                        floating: true,
                        shadow: false,
                        verticalAlign: 'bottom',
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.x + '</b><br/>' +
                                this.series.name + ': ' + this.y + '<br/>' +
                                '总量: ' + this.point.stackTotal;
                        }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            pointWidth: 30,
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                style: {
                                    // 如果不需要数据标签阴影，可以将 textOutline 设置为 'none'
                                    textOutline: '1px 1px black'
                                }
                            }
                            // minPointLength : 1
                        },
                    },
                    series: data.y
                });
            }
        })
    }

</script>