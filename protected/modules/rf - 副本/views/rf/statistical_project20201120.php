<div class="row">

    <input id="program_id" name="q[program_id]" type="hidden" value="<?php echo $program_id; ?>">
    <div class="input-group has-error" style="float:left;">
        <div class="input-group">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            <?php
            $second_time = Utils::MonthToEn(date('Y-m'));
            ?>
            <input type="text" class="form-control input-sm tool-a-search" name="q[month]"
                   value="<?php echo $second_time ?>" id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'MMM yyyy'})" placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" width="100px"/>
        </div>
    </div>
    <a class="tool-a-search" style="padding-left:8px"  href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
</div>
<div class="row" style="padding-top: 8px;">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">RFA Activity</h3>
                <div class="box-body chart-responsive">
                    <div class="chart" id="first-chart" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">RFI Activity</h3>
                <div class="box-body chart-responsive">
                    <div class="chart" id="second-chart" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</div>
<div class="row" style="padding-top: 8px;">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">RFA Type</h3>
                <div class="box-body chart-responsive">
                    <div class="chart" id="third-chart" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">RFI Type</h3>
                <div class="box-body chart-responsive">
                    <div class="chart" id="fourth-chart" style="height: 300px; position: relative;"></div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</div>
<script src="https://img.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/oldie.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script src="https://img.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script type="text/javascript">

    var itemQuery = function () {
        itemQuery_first();
        itemQuery_second();
        itemQuery_third();
        itemQuery_fourth();
    }

    var itemQuery_first = function () {
        var id=$("#program_id").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
        var arr = [];
        var placeholder = $("#first-chart");
        var datasets = [];
        placeholder.unbind();
        $.getJSON("index.php?r=rf/rf/cntbyproject&id="+id+'&date='+date, function (result) {
            var j = 0;
            if(result != null){
                $.each(result.data, function (i, field) {

                    arr[j] = [
                        field.status,
                        Number(field.cnt)
                    ]
                    j++;
                });
                var dataset = [{
                    type: 'pie',
                    innerSize: '70%',
                    name: 'Status',
                    data:arr
                }];
                var chart = Highcharts.chart('first-chart', {
                    chart: {
                        spacing : [40, 0 , 40, 0]
                    },
                    title: {
                        floating:true,
                        text: 'Status'
                    },
                    colors: result.color,
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>:{point.percentage:.1f}%',
                                style: {
                                    color: result.color
                                }
                            },
                            point: {
                                events: {
                                    mouseOver: function(e) {  // ?????????????????????????????????
                                        // ?????????????????????API ?????????https://api.hcharts.cn/highcharts#Chart.setTitle
//                                        alert(e.target);
                                        chart.setTitle({
                                            text: e.target.name+ '\t'+ e.target.y
                                        });
                                    }
                                    //,
                                    // click: function(e) { // ???????????????????????????????????????
                                    //     chart.setTitle({
                                    //         text: e.point.name+ '\t'+ e.point.y + ' %'
                                    //     });
                                    // }
                                }
                            },
                        }
                    },
                    series: dataset
                }, function(c) { // ???????????????????????????????????????
                    // ???????????????
                    var centerY = c.series[0].center[1],
                        titleHeight = parseInt(c.title.styles.fontSize);
                    // ????????????????????????
                    c.setTitle({
                        y:centerY + titleHeight/2
                    });
                });
            }else{
                placeholder.empty();
            }

        });
    }

    var itemQuery_second = function () {
        var id=$("#program_id").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
        var arr = [];
        var placeholder = $("#second-chart");
        var datasets = [];
        placeholder.unbind();
        $.getJSON("index.php?r=rf/rf/cntbyproject2&id="+id+'&date='+date, function (result) {
            var j = 0;
            if(result != null){
                $.each(result.data, function (i, field) {

                    arr[j] = [
                        field.status,
                        Number(field.cnt)
                    ]
                    j++;
                });
                var dataset = [{
                    type: 'pie',
                    innerSize: '70%',
                    name: 'Status',
                    data:arr
                }];
                var chart = Highcharts.chart('second-chart', {
                    chart: {
                        spacing : [40, 0 , 40, 0]
                    },
                    title: {
                        floating:true,
                        text: 'Status'
                    },
                    colors: result.color,
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>:{point.percentage:.1f}%',
                                style: {
                                    color: result.color
                                }
                            },
                            point: {
                                events: {
                                    mouseOver: function(e) {  // ?????????????????????????????????
                                        // ?????????????????????API ?????????https://api.hcharts.cn/highcharts#Chart.setTitle
//                                        alert(e.target);
                                        chart.setTitle({
                                            text: e.target.name+ '\t'+ e.target.y
                                        });
                                    }
                                    //,
                                    // click: function(e) { // ???????????????????????????????????????
                                    //     chart.setTitle({
                                    //         text: e.point.name+ '\t'+ e.point.y + ' %'
                                    //     });
                                    // }
                                }
                            },
                        }
                    },
                    series: dataset
                }, function(c) { // ???????????????????????????????????????
                    // ???????????????
                    var centerY = c.series[0].center[1],
                        titleHeight = parseInt(c.title.styles.fontSize);
                    // ????????????????????????
                    c.setTitle({
                        y:centerY + titleHeight/2
                    });
                });
            }else{
                placeholder.empty();
            }

        });
    }

    var itemQuery_third = function () {
        var id=$("#program_id").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
        var arr = [];
        var placeholder = $("#second-chart");
        var datasets = [];
        placeholder.unbind();
        $.getJSON("index.php?r=rf/rf/typebyproject&id="+id+'&date='+date, function (result) {
            var chart = Highcharts.chart('third-chart', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                colors: result.color,
                xAxis: {
                    categories: result.x
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'RFA'
                    }
                },
                legend: {
                    /* ????????????????????????
                     * ????????????????????????????????????????????????????????????
                     * yAxis.reversedStacks = false ????????????????????????
                     */
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                series: result.y
            });
        })
    }

    var itemQuery_fourth = function () {
        var id=$("#program_id").val();
        if(id == ''){
            alert('<?php echo Yii::t('common','select_program'); ?>');
            return;
        }
        var date = $("#q_start_date").val();
        var arr = [];
        var placeholder = $("#second-chart");
        var datasets = [];
        placeholder.unbind();
        $.getJSON("index.php?r=rf/rf/typebyproject2&id="+id+'&date='+date, function (result) {
            var chart = Highcharts.chart('fourth-chart', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                colors: result.color,
                xAxis: {
                    categories: result.x
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'RFA'
                    }
                },
                legend: {
                    /* ????????????????????????
                     * ????????????????????????????????????????????????????????????
                     * yAxis.reversedStacks = false ????????????????????????
                     */
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                series: result.y
            });
        })
    }

    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center;padding:2px;color:black;'>" +  Math.round(series.percent) + "%</div>";
    }
    // function labelFormatter(label, series) {
    //     return "<div style='font-size:8pt; text-align:center; padding:22px; color:black;'>" +  Math.round(series.percent) + "%</div>";
    // }
</script>
