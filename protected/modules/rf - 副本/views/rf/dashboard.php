<style type="text/css">
    .program {
        font-size: 21px;
        font-weight: bold;
    }
    .open-font{
        font-size: 22px;
        color:#FFa500;
    }
    .on-going-font{
        font-size: 22px;
        color:#0000FF;
    }
    .closed-font{
        font-size: 22px;
        color:#008000;
    }
    .wip-overdue-font{
        font-size: 22px;
        color:#FF0000;
    }
</style>
<input id="program_id" type="hidden" value="<?php echo $program_id; ?>">
<div class="row" style="padding-top: 8px;">
    <div class="col-md-6">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="form-header ">RFI (Overall)</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="height: 400px;padding-top: 50px;" >
                            <div class="row">
                                <div class="col-md-6 " >
                                    <p style="font-size: 22px">Total</p><br><p id="rfi_total" style="font-size: 22px"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 " >
                                    <p style="font-size: 22px">Open</p><br><p id="rfi_open" class="open-font"></p>
                                </div>
                                <div class="col-md-6 ">
                                    <p style="font-size: 22px">On-going</p><br><p id="rfi_ongoing" class="on-going-font"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <p style="font-size: 22px">Closed</p><br><p id="rfi_closed" class="closed-font"></p>
                                </div>
                                <div class="col-md-6 ">
                                    <p style="font-size: 22px">WIP-Overdue</p><br><p id="rfi_overdue" class="wip-overdue-font"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart" id="rfi_status" style="height: 400px; position: relative;">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <h3 class="form-header ">RFI-Discipline</h3>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control input-sm" name="q[rfi_dis_type]" id="rfi_dis_type" style="width: 100%;"  onchange="itemDiscipline('1')">
                                <option value="0">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart" id="rfi_discipline" style=" position: relative;"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="form-header ">RFI-Group</h3>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control input-sm" name="q[rfi_group_dis]" id="rfi_group_dis" style="width: 100%;"  onchange="itemGroup('1')">
                                <option value="0" selected>All</option>
                                <option value="1">Structural</option>
                                <option value="2">Architecture</option>
                                <option value="3">MEP</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control input-sm" name="q[rfi_group_type]" id="rfi_group_type" style="width: 100%;" onchange="itemGroup('1')">
                                <option value="0">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart" id="rfi_group" style=" position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="form-header ">RFA (Overall)</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="height: 400px;padding-top: 50px;" >
                            <div class="row">
                                <div class="col-md-6 " >
                                    <p style="font-size: 22px">Total</p><br><p id="rfa_total" style="font-size: 22px"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 " >
                                    <p style="font-size: 22px">Open</p><br><p id="rfa_open" class="open-font"></p>
                                </div>
                                <div class="col-md-6 ">
                                    <p style="font-size: 22px">On-going</p><br><p id="rfa_ongoing" class="on-going-font"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <p style="font-size: 22px">Closed</p><br><p id="rfa_closed" class="closed-font"></p>
                                </div>
                                <div class="col-md-6 ">
                                    <p style="font-size: 22px">WIP-Overdue</p><br><p id="rfa_overdue" class="wip-overdue-font"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart" id="rfa_status" style="height: 400px; position: relative;">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <h3 class="form-header ">RFA-Discipline</h3>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control input-sm" name="q[rfa_dis_type]" id="rfa_dis_type" style="width: 100%;" onchange="itemDiscipline('2')">
                                <option value="0" selected>All</option>
                                <option value="RF00003">RFA-Dwg</option>
                                <option value="RF00002">RFA-M&E</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart" id="rfa_discipline" style=" position: relative;"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="form-header ">RFA-Group</h3>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control input-sm" name="q[rfa_group_type]" id="rfa_group_type" style="width: 100%;" onchange="itemGroup('2')">
                                <option value="0" selected>All</option>
                                <option value="RF00003">RFA-Dwg</option>
                                <option value="RF00002">RFA-M&E</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control input-sm" name="q[rfa_group_dis]" id="rfa_group_dis" style="width: 100%;"  onchange="itemGroup('2')">
                                <option value="0" selected>All</option>
                                <option value="1">Structural</option>
                                <option value="2">Architecture</option>
                                <option value="3">MEP</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart" id="rfa_group" style=" position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

</div>

<script src="js/jquery.flot.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.min.js" type="text/javascript"></script>
<!--  横柱  -->
<script src="https://img.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        itemStatus('1');
        itemStatus('2');
        itemGroup('1');
        itemGroup('2');
        itemDiscipline('1');
        itemDiscipline('2');
    })
    //status
    var itemStatus = function (type) {
        var project_id = $('#program_id').val();
        $.ajax({
            data: {project_id: project_id, type: type},
            url: "index.php?r=rf/statistic/querystatus",
            dataType: "json",
            type: "POST",
            success: function (data) {
                console.log(data);
                if(type == '1'){
                    var type_id = 'rfi_status';
                    var type_tag = 'rfi';
                }
                if(type == '2'){
                    var type_id = 'rfa_status';
                    var type_tag = 'rfa';
                }
                var open_cnt = 0;
                var total_cnt = 0;
                var ongoing_cnt = 0;
                var closed_cnt = 0;
                var overdue_cnt = 0;
                $.each(data.result, function (name, value) {
                    console.log(value);
                    var status_obj = {};
                    if(value.status == '0'){
                        ongoing_cnt = value.count;
                        open_cnt+=Number(ongoing_cnt);
                        total_cnt+=Number(ongoing_cnt);
                    }
                    if(value.status == '1'){
                        closed_cnt = value.count;
                        total_cnt+=Number(closed_cnt);
                    }
                    if(value.status == '2'){
                        overdue_cnt = value.count;
                        open_cnt+=Number(overdue_cnt);
                        total_cnt+=Number(overdue_cnt);
                    }
                })
                $('#'+type_tag+'_ongoing').html(ongoing_cnt);
                $('#'+type_tag+'_closed').html(closed_cnt);
                $('#'+type_tag+'_overdue').html(overdue_cnt);
                $('#'+type_tag+'_open').html(open_cnt);
                $('#'+type_tag+'_total').html(total_cnt);
                var open_rate = open_cnt/total_cnt;
                var open_rate = open_rate.toFixed(3)*100;
                var closed_rate = closed_cnt/total_cnt;
                var closed_rate = closed_rate.toFixed(3)*100;
                var status_arr = [
                    {name:'Open',y: open_rate, color:"#FFa500"},
                    {name:'Closed',y:closed_rate,color:"#008000"}
                ];
                var chart = Highcharts.chart(type_id, {
                    chart: {
                        spacing : [0, 0 , 0, 0],
                    },
                    title: {
                        floating:true,
                        text: ''
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
                                format: ' {point.percentage:.1f} %',
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
                        data: status_arr
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
            }
        });
    }

    //group
    var itemGroup = function (type) {
        var project_id = $('#program_id').val();
        if(type == '1'){
            var major_id = $('#rfi_group_dis').val();
            var form_id = $('#rfi_group_type').val();
        }
        if(type == '2'){
            var major_id = $('#rfa_group_dis').val();
            var form_id = $('#rfa_group_type').val();
        }
        $.ajax({
            data: {project_id: project_id, type: type, major_id:major_id, form_id:form_id},
            url: "index.php?r=rf/statistic/querygroup",
            dataType: "json",
            type: "POST",
            success: function (data) {
                console.log(data);
                if(type == '1'){
                    var type_id = 'rfi_group';
                    var type_tag = 'rfi';
                }
                if(type == '2'){
                    var type_id = 'rfa_group';
                    var type_tag = 'rfa';
                }
                var chart = Highcharts.chart(type_id, {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: '',
                        // align: 'left',
                        // x: 70
                    },
                    xAxis: {
                        categories: data.y
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        },
                        labels: {
                            enabled: false
                        },
                        stackLabels: {
                            enabled: true,
                            allowOverlap: true,
                            formatter: function(){
                                if(this.total==0){
                                    return "";
                                }else{
                                    return this.total;
                                }
                            }
                        }
                    },
                    legend: {
                        /* 图例显示顺序反转
                         * 这是因为堆叠的顺序默认是反转的，可以设置
                         * yAxis.reversedStacks = false 来达到类似的效果
                         */
                        reversed: true
                    },
                    plotOptions: {
                        series: {
                            pointPadding: 0.2,
                            stacking: 'normal',
                            cursor: 'pointer',
                            events: {
                                click: function (event) {
                                    // alert(
                                    //     this.name + ' 被点击了\n' +
                                    //     '最近点：' + event.point.category + '\n' +
                                    //     'Alt 键: ' + event.altKey + '\n' +
                                    //     'Ctrl 键: ' + event.ctrlKey + '\n' +
                                    //     'Meta 键（win 键）： ' + event.metaKey + '\n' +
                                    //     'Shift 键：' + event.shiftKey
                                    // );
                                    if(this.name == 'WIP-Overdue'){
                                        var status = '2';
                                    }
                                    if(this.name == 'Closed'){
                                        var status = '1';
                                    }
                                    if(this.name == 'On-going'){
                                        var status = '0';
                                    }

                                    window.location = "index.php?r=rf/rf/list&program_id="+project_id+"&type_id="+type+"&q[status]="+status+"&q[group_name]="+encodeURIComponent(event.point.category)+"&q[form_id]="+form_id+"&q[discipline]="+major_id;
                                }
                            },
                            dataLabels: {
                                enabled: true, //设置显示对应y的值
                                inside:  true,
                            },
                            showInLegend: false
                        },
                    },
                    series: data.x,
                    credits: {
                        enabled:false
                    },

                    exporting: {
                        enabled:false
                    },
                });
            }
        })
    }

    //discipline
    var itemDiscipline = function (type) {
        var project_id = $('#program_id').val();
        if(type == '1'){
            var form_id = $('#rfi_dis_type').val();
        }
        if(type == '2'){
            var form_id = $('#rfa_dis_type').val();
        }
        $.ajax({
            data: {project_id: project_id, type: type, form_id:form_id},
            url: "index.php?r=rf/statistic/querydiscipline",
            dataType: "json",
            type: "POST",
            success: function (data) {
                console.log(data);
                if(type == '1'){
                    var type_id = 'rfi_discipline';
                    var type_tag = 'rfi';
                }
                if(type == '2'){
                    var type_id = 'rfa_discipline';
                    var type_tag = 'rfa';
                }

                var chart = Highcharts.chart(type_id, {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: '',
                        // align: 'left',
                        // x: 70
                    },
                    xAxis: {
                        categories: data.y
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        },
                        labels: {
                            enabled: false
                        },
                        stackLabels: {
                            enabled: true,
                            allowOverlap: true,
                            formatter: function(){
                                if(this.total==0){
                                    return "";
                                }else{
                                    return this.total;
                                }
                            }
                        }
                    },
                    legend: {
                        /* 图例显示顺序反转
                         * 这是因为堆叠的顺序默认是反转的，可以设置
                         * yAxis.reversedStacks = false 来达到类似的效果
                         */
                        reversed: true
                    },
                    plotOptions: {
                        series: {
                            stacking: 'normal',
                            cursor: 'pointer',
                            pointPadding: 0.2,
                            events: {
                                click: function (event) {
                                    // alert(
                                    //     this.name + ' 被点击了\n' +
                                    //     '最近点：' + event.point.category + '\n' +
                                    //     'Alt 键: ' + event.altKey + '\n' +
                                    //     'Ctrl 键: ' + event.ctrlKey + '\n' +
                                    //     'Meta 键（win 键）： ' + event.metaKey + '\n' +
                                    //     'Shift 键：' + event.shiftKey
                                    // );
                                    if(this.name == 'WIP-Overdue'){
                                        var status = '2';
                                    }
                                    if(this.name == 'Closed'){
                                        var status = '1';
                                    }
                                    if(this.name == 'On-going'){
                                        var status = '0';
                                    }
                                    // alert(event.point.category);
                                    if(event.point.category == 'Structural'){
                                        var discipline = '1';
                                    }
                                    if(event.point.category == 'Architecture'){
                                        var discipline = '2';
                                    }
                                    if(event.point.category == 'M&E'){
                                        var discipline = '3';
                                    }
                                    window.location = "index.php?r=rf/rf/list&program_id="+project_id+"&type_id="+type+"&q[status]="+status+"&q[discipline]="+discipline+"&q[form_id]="+form_id;
                                }
                            },
                            dataLabels: {
                                enabled: true, //设置显示对应y的值
                                inside:  true,
                            },
                            showInLegend: false
                        },
                    },
                    series: data.x,
                    credits: {
                        enabled:false
                    },

                    exporting: {
                        enabled:false
                    },
                });
            }
        })
    }

</script>
