<style type="text/css">
    .omit{
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
</style>

<!-- COLOR PALETTE -->
<div class="card card-info card-outline">

    <?php
    //$t->echo_grid_header();
    if (is_array($rows)) {
        $j = 1;

        $tool = true;
        //$tool = false;验证权限
        if (Yii::app()->user->checkAccess('mchtm')) {
            $tool = true;
        }
        $status_list = Program::statusText(); //状态text
        $status_css = Program::statusCss(); //状态css
        $compList = Contractor::compAllList(); //所有承包商
        $operator_id = Yii::app()->user->id;
        $authority_list = OperatorProject::authorityList($operator_id);
        $row = $rows[0];

        //判断项目权限
        $value = $authority_list[$row['program_id']];
        $program_id = $row['program_id'];
        $program_name = $row['program_name'];
        $num = ($curpage - 1) * $this->pageSize + $j++;
        if(strpos($row['program_name'],"'")){
            $row['program_name'] = str_replace("'","&apos;",$row['program_name']);//html对特殊字符转义
        }
        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['program_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
        $del_link = "<a href='javascript:void(0)' onclick='itemDel(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'delete1') . "</a>&nbsp;";
        $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-gear\"></i>" . Yii::t('proj_project', 'STATUS_STOP') . "</a>&nbsp;";
        $ptype = Yii::app()->session['project_type'];
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $link = '';
        /*Header*/
        echo "<div class='card-header'>";

        if ($row['status'] == Program::STATUS_NORMAL) {
            if($value == '0'){
                if($row['default_program_type'] == 1){
                    $link.="<div class='row'>
                                <div class='col-9'>
                                    <h3 class='card-title h3-middel'>$program_name</h3>
                                    $status
                                </div>
                                <div class='col-3'>

                                </div>
                            </div>";
                }
            }else if($value == '1'){
                if($row['default_program_type'] == 1){
                    $link.="<div class='row'>
                                <div class='col-9'>
                                    <h3 class='card-title h3-middel'>$program_name</h3>
                                    $status
                                </div>
                                <div class='col-3'>
                                    
                                </div>
                            </div>";
                }else{
                    if($row['contractor_id'] == Yii::app()->user->contractor_id && $row['add_conid'] != Yii::app()->user->contractor_id){   //指向本公司的项目
                        $link.="<div class='row'>
                                    <div class='col-9'>
                                        <h3 class='card-title h3-middel'>$program_name</h3>
                                        $status 
                                    </div>
                                </div>";
                    }
                    if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']== 1){   //本公司建立的项目
                        $link.="<div class='row'>
                                    <div class='col-9' style='display : inline'>
                                        <h3 class='card-title h3-middel'>$program_name $status</h3>
                                    </div>
                                    <div class='col-3' style='text-align: right'>    
                                        
                                    </div>
                                </div>";
                    }
                    if($row['add_conid'] == Yii::app()->user->contractor_id && $row['node_level']!= 1){   //本公司建立的项目(分包项目)
                        $link.="<div class='row'>
                                    <div class='col-9'>
                                        <h3 class='card-title h3-middel'>$program_name</h3>
                                        $status
                                    </div>
                                    <div class='col-3'>    
                                        
                                    </div>
                                </div>";
                    }
                }
            }
        }
        echo $link;
        /* Header End */
        echo "</div>";
        /* body */
        echo "<div class='card-body'>";
        if($row['father_proid'] == 0){ //总包项目
            $project_type = '<span style="color:blue">'.Yii::t('dboard', 'Menu Project MC').'</span>';
        }
        else{//分包项目
            $project_type = '<span style="color:red">'.Yii::t('dboard', 'Menu Project SC').'</span>';
        }

        $record_time = substr(Utils::DateToEn($row['record_time']),0,11);
        echo "<div class='row' style='margin-top: 10px;'>
                <div class='col-6'>
                    <div class='form-group' >
                        <label  class='col-sm-4 control-label padding-lr5'>Project Type</label>
                        <div class='col-sm-6 padding-lr5' style=''>
                            $project_type
                        </div>
                    </div>
                </div>
                <div class='col-6'>
                    <div class='form-group'>
                        <label  class='col-sm-4 control-label padding-lr5'>Created On</label>
                        <div class='col-sm-6 padding-lr5' style=''>
                            $record_time
                        </div>
                    </div>
                </div>
              </div>";

        $main_name = $compList[$row['main_conid']];
        $program_id = $row['program_id'];
        echo "<div class='row' style='margin-top: 10px;'>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label  class='col-sm-4 control-label padding-lr5'>Main Coin</label>
                        <div class='col-sm-6 padding-lr5' style=''>
                            $main_name
                        </div>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label  class='col-sm-4 control-label padding-lr5'>No.</label>
                        <div class='col-sm-6 padding-lr5' style=''>
                            $program_id
                        </div>
                    </div>
                </div>
              </div>";

        $child_cnt = $row['child_cnt'];

        echo "<div class='row' style='margin-top: 10px;'>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label  class='col-sm-4 control-label padding-lr5'>No. of Sub-Con</label>
                        <div class='col-sm-6 padding-lr5' style=''>
                            $child_cnt
                        </div>
                    </div>
                </div>
              </div>
              </div>";
    }
    ?>
    <!-- /.card-body -->
</div>

<div class="row">
    <div class="col-md-6">
        <!-- PIE CHART -->
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="far fa-chart-bar"></i>
                    RFA
                </h3>

                <div class="card-tools">

                </div>
            </div>
            <div class="card-body"  id="pie-chart" style="height: 300px; position: relative;">

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col (LEFT) -->
    <div class="col-md-6">
        <!-- LINE CHART -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="far fa-chart-bar"></i>
                    Fabrication Overview(All blocks)
                </h3>

                <div class="card-tools">

                </div>
            </div>
            <div class="card-body" id="mix" style="height: 300px; position: relative;">
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col (RIGHT) -->
</div>

<!--<div class="row">-->
<!--    <div class="col-12">-->
<!--        <div class="card card-info">-->
<!--            <div class="card-header">-->
<!--                <h3 class="card-title">-->
<!--                    <i class="far fa-chart-bar"></i>-->
<!--                    Fabrication Overview(All blocks)-->
<!--                </h3>-->
<!---->
<!--                <div class="card-tools">-->
<!---->
<!--                </div>-->
<!--            </div>-->
<!--            <!-- /.card-header -->
<!--            <div class="card-body">-->
<!--                <div class="form-group">-->
<!--                    <div class="col-3">-->
<!--                        <div class="chart" id="first-pie-chart" style="height: 300px; position: relative;"></div>-->
<!--                    </div>-->
<!--                    <div class="col-3">-->
<!--                        <div class="chart" id="second-pie-chart" style="height: 300px; position: relative;"></div>-->
<!--                    </div>-->
<!--                    <div class="col-3">-->
<!--                        <div class="chart" id="third-pie-chart" style="height: 300px; position: relative;"></div>-->
<!--                    </div>-->
<!--                    <div class="col-3">-->
<!--                        <div class="chart" id="fourth-pie-chart" style="height: 300px; position: relative;"></div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <!-- /.card-body -->
<!--        </div>-->
<!--        <!-- /.card -->
<!--    </div>-->
<!--    <!-- /.col -->
<!--</div>-->

<script src="js/jquery.flot.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script src="js/highstock.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        /*
         * DONUT CHART
         * -----------
         */
        var placeholder = $("#pie-chart");
        placeholder.unbind();
        var data = [],
            series = Math.floor(Math.random() * 6) + 3;

        for (var i = 0; i < series; i++) {
            data[i] = {
                label: "项目记录" + (i + 1),
                data: Math.floor(Math.random() * 100) + 1
            }
        }

        var arr = [];


        $.getJSON("index.php?r=rf/rf/allbyproject&id=<?php echo $program_id; ?>", function (result) {
            var j = 0;
            $.each(result, function(i, field){
                arr[j] = {
                    label: field.status,
                    data: field.cnt
                }
                j++;
            });
            if(arr.length != 0){
                $.plot(placeholder, arr, {
                    series: {
                        pie: {
                            show: true,
                            combine: {
                                color: "#999",
                                threshold: 0.01
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                });
            }
        });

        changedetail();
    })
    async function changedetail() {
        await mixdetail();
        // await piedetail('first-pie-chart','C');
        // await piedetail('second-pie-chart','B');
        // await piedetail('third-pie-chart','A');
        // await piedetail('fourth-pie-chart','0');
    }
    async function mixdetail() {
        var date = $('#date').val();
        $.ajax({
            data: {date: date,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=task/statistic/databymix",
            type: "GET",
            dataType: "json",
            success: function(data) {
                var j = 0;
                if(data != null) {
                    var result = data.result;
                    Highcharts.chart('mix', {
                        title: {
                            text: ''
                        },
                        xAxis: {
                            categories: ['Carcass', 'Fitting out', 'Site', 'Installed']
                        },
                        credits: {
                            enabled: false
                        },
                        plotOptions: {
                            series: {
                                stacking: 'normal'
                            }
                        },
                        labels: {
                            // items: [{
                            //     html: '水果消耗',
                            //     style: {
                            //         left: '100px',
                            //         top: '18px',
                            //         color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                            //     }
                            // }]
                        },
                        series: [{
                            type: 'column',
                            name: 'Actual',
                            data: result.actual
                        },{
                            type: 'spline',
                            name: 'Plan',
                            data: result.plan,
                            marker: {
                                lineWidth: 2,
                                lineColor: Highcharts.getOptions().colors[3],
                                fillColor: 'white'
                            }
                        }]
                    });
                }
            }
        });
    }

    async function piedetail (module,type) {
        var date = $('#date').val();
        $.ajax({
            data: {type: type,date: date,program_id: <?php echo $program_id; ?>},
            url: "index.php?r=task/statistic/databypie",
            type: "GET",
            dataType: "json",
            success: function(data) {
                var j = 0;
                if(data != null) {
                    Highcharts.chart(module, {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: data.title
                        },
                        tooltip: {
                            pointFormat: '{point.y},<b>{point.percentage:.1f}%</b>'
                        },
                        colors:data.color,
                        credits: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: false
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: '',
                            colorByPoint: true,
                            data: [{
                                name: 'Completed',
                                y: data.completed,
                                sliced: true,
                                selected: true
                            }, {
                                name: 'Pending',
                                y: data.pending
                            }]
                        }]
                    });
                }
            }
        });
    }
</script>


