
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <?php
                $q = $_REQUEST['q'];
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('+1 day')));
            ?>
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5" >
                    <select class="form-control input-sm" name="q[template_id]" id="template_id" style="width: 100%;">
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

                <div class="col-xs-2 padding-lr5" >
                    <select class="form-control input-sm" id="stage_id" name="q[stage_id]" style="width: 100%;">
                        <option value="">--Stage Name--</option>
                    </select>
                </div>

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

                <div class="col-xs-2 padding-lr5" style="width: 145px;">
                    <div class="input-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control input-sm tool-a-search" name="q[end_date]"
                                   value="<?php echo $endDate ?>"     id="q_end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="End Date"/>
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
//    $(function(){
//        QueryData();
//    });

    //Stage初始化
    function StageInit(stage) {
        return stage.html('<option value="">--Stage Name--</option>');
    }

    //模版 阶段  任务半联动
    $('#template_id').change(function(){

        var stageObj = $("#stage_id");
        var stageOpt = $("#stage_id option");
        StageInit(stageObj);

        $.ajax({
            type: "POST",
            url: "index.php?r=task/task/querystage",
            data: {template_id:$("#template_id").val()},
            dataType: "json",
            success: function(data){ //console.log(data);
                if (!data) {
                    return;
                }
                for (var o in data) {//console.log(o);
                    stageObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                }
            },
        });
    });

    var QueryData = function() {
        var first_tag = 'Carcass';
        var second_tag = 'Fitting out';
        var third_tag = 'Installed';
        var first_module = 'C';
        var second_module = 'B';
        var third_module = 'A';

        if(first_module != ''){
            var container = 'container_first';
            showdetail(container);
        }
//        if(second_module != ''){
//            var container = 'container_second';
//            showdetail2(second_module,second_tag,container);
//        }
//        if(third_module != ''){
//            var container = 'container_third';
//            showdetail2(third_module,third_tag,container);
//        }
    }
    var showdetail = function (container) {
//		alert(id);
//		alert(date);
        var arr = [];
        var category  = [];
        var cnt = [];
        var percent = [];
        var total = [];
        var y_max = 0;
        var tmp;
        var type = $('#stage_id option:selected').text();
        $.ajax({
            data:$('#_query_form').serialize(),
            url: "index.php?r=qa/statistic/databyday",
            type: "GET",
            dataType: "json",
            success: function(data) {
                var j = 0;
                if(data != null) {
                    $.each(data.result, function (i, field) {
                        var person=new Object();
//                        percent = Number(field.percent);
//                        person.percent=percent.toFixed(2);
                        person.percent=field.percent;
                        person.total=field.total;
                        person.name=field.block;
                        category[j] =person;
                        tmp = Number(field.cnt);
                        if(y_max < tmp){
                            y_max = tmp;
                        }
                        cnt[j] = Number(field.cnt); //强制转换为数字类型
                        percent[j] = field.percent;
                        total[j] = field.total;
                        j++;
                    });
                    console.log(category);
//                    for(i=0;i<=13;i++){
//                        cnt[i] = i;
//                    }
//                    var cnt = [0,0,10,4,10,1,0,5,0,0,5,1,2];
//                    alert(cnt[0]);
                    Highcharts.chart(container, {
                        chart: {
                            type: 'bar'
                        },
                        title: {
                            text: 'Total: '+data.total+' Cnt: '+data.count
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: category,
                            labels: { //设置横轴坐标的显示样式
                                formatter: function() {
                                    return this.value.name; //此处是核心   这里设置x轴显示的内容,Y轴同理设置yAxis的这个属性
                                },
//                                rotation: -45, //倾斜度
                                align: 'right'
                            },
                            crosshair: true,
                            min:0,
                            max:9
                        },
                        yAxis: {
                            min: 0,
                            max: y_max,
                            title: {
                                text: 'Total (cnt)'
                            }
                        },
                        scrollbar : {
                            enabled:true
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key.name}</span><table><tr><td style="color:{series.color};padding:0">Percent: </td>' +
                            '<td style="padding:0"><b>{point.key.percent}</b></td></tr>'+'<tr><td style="color:{series.color};padding:0">Total: </td>' +
                            '<td style="padding:0"><b>{point.key.total}</b></td></tr></table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        credits:{
                            enabled: false // 禁用版权信息
                        },
                        series: [
                            {
                                name: type,
                                data: cnt,
                                percent: percent,
                                total: total
                            }
                        ]
                    });
                }
            }
        });
    }

    var showdetail2 = function (module,tag,container) {
//		alert(id);
//		alert(date);
        var arr = [];
        var category  = [];
        var percent = [];
        var total = [];
        var cnt = [];
        var map = {};
        var y_max = 0;
        var tmp;
        var start_date = $("#q_start_date").val();
        var end_date = $("#q_start_date").val();
        var program_id = $("#program_id").val();
        $.ajax({
            url: "index.php?r=qa/statistic/databyday2&start_date="+start_date+"&end_date="+end_date+"&program_id="+program_id+"&module="+module,
            type: "GET",
            dataType: "json",
            success: function(data) {
                var j = 0;
                var n = 0;
                var big_arr = [];
                if(data != null) {
                    console.log(data.series);
                    console.log(data.x);
//                    var length = data.length;
//                    $.each(data, function (i, field) {
//                        console.log(field);
//                        $.each(field.stage, function (e, info) {
//                            if(!map[info.stage_id]){
////                                alert('进来了');
//                                //新的
//                                for(var o=0;o<length;o++){
//                                    cnt[o]= 0;
//                                }
//                                cnt[j] = info.cnt;
//                                big_arr.push({
//                                    id: info.stage_id,
//                                    name: info.stage_name,
//                                    data: cnt
//                                });
//                                map[info.stage_id] = info;
//                            }else{
//                                for(var x = 0; x < big_arr.length; x++){
//                                    var dj = big_arr[x];
//                                    if(dj.id == info.stage_id){
//                                        var no = info.cnt;
//                                        if(no != 0){
//                                            dj.data[j] = no;
//                                        }
//                                        break;
//                                    }
//                                }
//                            }
//                        })
//                        category[j] =field.block;
//                        j++;
//                    });
//                    console.log(big_arr);
//                    return false;
                    Highcharts.chart(container,{
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: tag
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: data.x,
                            crosshair: true,
                            title: {
                                text: 'Block'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: ''
                            }
                        },
                        credits: {//版权信息
                            enabled: false
                        },
                        tooltip: {
                            // head + 每个 point + footer 拼接成完整的 table
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y} </b></td></tr>',
                            footerFormat: '</table>',
//                            formatter:function(){
//                                console.log(this);
//                              return '<span style="font-size:10px">{point.key}</span><table>'+'<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
//                                  '<td style="padding:0"><b>{point.y} </b></td></tr>'+'<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
//                                  '<td style="padding:0"><b>{point.y} </b></td></tr>'+'</table>';
//                            },
                            shared: false,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                borderWidth: 0
                            },
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    allowOverlap: true // 允许数据标签重叠
                                }
                            }
                        },
                        series: data.series
                    });
                }
            }
        });
    }

</script>