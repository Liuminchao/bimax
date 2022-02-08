<?php
/**
 *@Name line_basic.php
 *@Author Connor <caokang@foxmail.com>
 *@Copyright Copyright &copy;  2012 
 *@Since 2012-7-17
 */
foreach($userful['rows'] as $k=>$v){
	$x.="'".$k."',";
	if($unit=='%')$y.=number_format(($v*100),2).",";
	else $y.=$v.",";
}
$x=substr($x, 0,-1);
// echo $x;
$y=substr($y, 0,-1);
// print_r(($userful['lables']));
?>
<script type="text/javascript">
    var chart;
    $(document).ready(function() {
        var lables = eval(<?php echo $userful['lables'];?>);
        //for (i in lables){
        	//alert(lables[i].rate);
       // }
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'line',
                marginLeft:80,
                marginRight: <?php echo $mright?$mright:110;?>,
                marginBottom: <?php echo $mbottom?$mbottom:35;?>
            },
            title: {
                text: "<?php echo $title;?>",
                x: -20 //center
            },
            subtitle: {
                text: '<?php echo $subtitle;?>',
                x: -20
            },
            xAxis: {
                categories: [<?php echo $x;?>]
            },
            yAxis: {
                title: {
                    text: '<?php echo $ytitle;?>'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                       
                        return '<b>'+ this.series.name +'</b>'+<?php if(isset($userful['lables'])): ?>'（'+lables[this.x].rate+'/'+lables[this.x].all+'）'+<?php endif; ?>'<br/>'+
                        this.x +': '+ this.y +'<?php echo $unit;?>';
                        
                }
            },
            exporting:{enabled:false },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [
                     	{
                		name: "<?php echo $series;?>",
                		data: [<?php echo $y;?>]
              			}
              		]
        });
    });
</script>


<script src="<?php echo $this->jspath;?>/highcharts.js"></script>
<script src="<?php echo $this->jspath;?>/modules/exporting.js"></script>

<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

</body>

 