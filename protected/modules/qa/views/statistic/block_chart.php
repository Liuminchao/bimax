<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
            $q = $_REQUEST['q'];
            $startDate = Utils::DateToEn(date('Y-m-d'));
            ?>
            <form name="_query_form" id="_query_form" role="form">

                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="hidden" id="program_id" name="q[program_id]" value="<?php echo $program_id ?>">
                            <input type="text" class="form-control input-sm tool-a-search" name="q[start_date]"
                                   value="<?php echo $startDate ?>"    id="q_start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="Start Date"/>
                        </div>
                    </div>
                </div>

                <a class="tool-a-search" href="javascript:QueryData();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
                <!--<a class="btn btn-primary btn-sm" href="javascript:itemExport()"><?php echo Yii::t('proj_report', 'export'); ?></a>-->
            </form>
        </div>
    </div>
</div>
<div id="container_first" style="min-width: 500px; height: 550px; margin: 0 auto"></div>
<!--<div id="container_second" style="min-width: 500px; height: 350px; margin: 0 auto"></div>-->
<!--<div id="container_third" style="min-width: 500px; height: 350px; margin: 0 auto"></div>-->

<script src="js/highstock.js" type="text/javascript"></script>
<script type="text/javascript">
    var QueryData = function() {
        var container = 'container_first';
        showdetail(container);
    }
    var showdetail = function () {
        var date = $('#q_start_date').val();
        var program_id = $('#program_id').val();
        $.ajax({
            data: {program_id: program_id,date: date},
            url: "index.php?r=qa/statistic/blockdata",
            type: "GET",
            dataType: "json",
            success: function(data) {
                var j = 0;
                if(data != null) {
                    Highcharts.chart('container_first', {
                        title: {
                            text: 'Block Statistics'
                        },
                        xAxis: {
                            categories: data.block
                        },
                        credits: {
                            enabled: false
                        },
                        scrollbar : {
                            enabled:true
                        },
                        // plotOptions: {
                        //     series: {
                        //         stacking: 'null'
                        //     }
                        // },
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
                            name: 'actual',
                            data: data.actual
                        },{
                            type: 'spline',
                            name: 'Plan',
                            data: data.plan,
                            marker: {
                                lineWidth: 2,
                                lineColor: Highcharts.getOptions().colors[3],
                                fillColor: 'white'
                            }
                        },]
                    });
                }
            }
        });
    }

</script>