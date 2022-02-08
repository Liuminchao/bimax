<?php

/**
 * 封装adminLTE-table-dataTable
 * @author yangtl
 */
class DataGrid
{

    private $newrow = true;
    private $newrow_attr = array();
    private $rows = 0;
    private $headers = array();
    private $i = 0;
    private $ti = 0;
    public $tid;
    public $url;
    public $updateDom;

    public function __construct($tid)
    {
        $this->tid = $tid;
    }

    public function set_url($url)
    {
        $this->url = $url;
    }
    /**
     * 设置标题
     * @param <string> $title 标题名称
     * @param <integer> $width 宽
     * @param <string> $align  标题对齐方式
     * @param <string> $order  排序字段名
     */
    public function set_header($title, $width = "", $align="", $order="")
    {
        if(is_numeric($width)){//如果是数值则默认为 px;
            $width=$width."px";
        }
        $this->headers[$this->i++] = array(
            "title" => $title,
            "width" => $width,
            "align" => $align,
            "order" => $order,
        );
    }

    /**
     * 设置标题
     * @param <string> $title 标题名称
     * @param <integer> $width 宽
     * @param <string> $align  标题对齐方式
     * @param <string> $order  排序字段名
     */
    public function set_compound_header($title, $width = "", $align="", $order="",$colspan="",$rowspan="")
    {
//        var_dump(111);
        if(is_numeric($width)){//如果是数值则默认为 px;
            $width=$width."px";
        }
        $this->headers[$this->i++] = array(
            "title" => $title,
            "width" => $width,
            "align" => $align,
            "order" => $order,
            "colspan"=> $colspan,
            "rowspan"=> $rowspan
        );
    }

    /**
     * 输出双层标题  dataTable
     * @param <string> $width
     * @param <string> $style
     *
     */
    public function echo_compound_header($width = '100%', $style='')
    {
//        var_dump(222);
        echo <<<EOF
    		<!-- DATA TABLES -->
        	<link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    		<table class="table table-striped table-bordered  dataTable" id="{$this->tid}" aria-describedby="example2_info">
				<thead>
EOF;
        echo "<tr role='row'>";
        if (count($this->headers) > 0)
            foreach ($this->headers as $i => $header){
                if($header["colspan"] == ''|| $header["rowspan"] == ''){
                    $title = $header['title'];
                    $sort_class = "";
                    $onclick = "";
                    if($header['order']!= ''){
                        $q_order = $_REQUEST['q_order'];
                        //结尾~标识升序,否则为降序
                        if (substr($q_order, -1) == '~'){
                            $q_order = substr($_REQUEST['q_order'], 0, -1);
                            $direction = 'up';
                        } else{
                            $direction = 'down';
                        }
                        if($q_order==$header['order']){   //正按此列排序
                            if($direction=='up'){
                                $sort_class="sorting_asc";
                                $q_order_new = $q_order;
                            }else {
                                $sort_class="sorting_desc";
                                $q_order_new = $q_order.'~';
                            }
                        }else{
                            $sort_class = "sorting";
                            $q_order_new = $header['order'];
                        }
                        $onclick = ' onclick="'. $this->tid . ".order='" . $q_order_new . "';" . $this->tid . '.refresh();"';
                    }
                    if($header["align"]){
                        $style_align = " text-align:".$header["align"].";";
                    }else{
                        $style_align = "";
                    }
                    if($header["width"]){
                        $style_width = " width:".$header["width"].';';
                        $th_width = "width=".$header["width"];
                    }else {
                        $style_width = "";
                        $th_width = "";
                    }


                    $style = "style='{$style_width} {$style_align}'";

                    if($header["colspan"]) {
                        $colspan = $header["colspan"];
                        echo "<th role='columnheader' tabindex='0' aria-controls='{$this -> tid}' colspan='{$colspan}' class='{$sort_class}' {$onclick} {$style} {$th_width}>{$title}</th>";
                    }
                    if($header["rowspan"]) {
                        $rowspan = $header["rowspan"];
                        echo "<th role='columnheader' tabindex='0' aria-controls='{$this -> tid}' rowspan='{$rowspan}' class='{$sort_class}' {$onclick} {$style} {$th_width}>{$title}</th>";
                    }
                }

            }

        echo "</tr>";
        echo "<tr role='row'>";
        if (count($this->headers) > 0)
            foreach ($this->headers as $i => $header){
                if(!$header["colspan"] && !$header["rowspan"]) {
                    $title = $header['title'];
                    $sort_class = "";
                    $onclick = "";
                    if ($header['order'] != '') {
                        $q_order = $_REQUEST['q_order'];
                        //结尾~标识升序,否则为降序
                        if (substr($q_order, -1) == '~') {
                            $q_order = substr($_REQUEST['q_order'], 0, -1);
                            $direction = 'up';
                        } else {
                            $direction = 'down';
                        }
                        if ($q_order == $header['order']) {   //正按此列排序
                            if ($direction == 'up') {
                                $sort_class = "sorting_asc";
                                $q_order_new = $q_order;
                            } else {
                                $sort_class = "sorting_desc";
                                $q_order_new = $q_order . '~';
                            }
                        } else {
                            $sort_class = "sorting";
                            $q_order_new = $header['order'];
                        }
                        $onclick = ' onclick="' . $this->tid . ".order='" . $q_order_new . "';" . $this->tid . '.refresh();"';
                    }
                    if ($header["align"]) {
                        $style_align = " text-align:" . $header["align"] . ";";
                    } else {
                        $style_align = "";
                    }
                    if ($header["width"]) {
                        $style_width = " width:" . $header["width"] . ';';
                        $th_width = "width=" . $header["width"];
                    } else {
                        $style_width = "";
                        $th_width = "";
                    }


                    $style = "style='{$style_width} {$style_align}'";


                    echo "<th role='columnheader' tabindex='0' aria-controls='{$this -> tid}' class='{$sort_class}' {$onclick} {$style} {$th_width}>{$title}</th>";

                }
            }

        echo "</tr></thead>";
        echo "<tbody role='alert' aria-live='polite' aria-relevant='all'>";
    }


    /**
     * 输出标题  dataTable
     * @param <string> $width
     * @param <string> $style
     *
     */
    public function echo_grid_header($width = '100%', $style='')
    {
        echo <<<EOF
    		<!-- DATA TABLES -->
        	<link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    		<table class="table table-striped table-bordered dataTable" id="{$this->tid}" aria-describedby="example2_info">
				<thead>
					<tr role="row">
EOF;

        if (count($this->headers) > 0)
            foreach ($this->headers as $i => $header){
                $title = $header['title'];
                $sort_class = "";
                $onclick = "";
                if($header['order']!= ''){
                    $q_order = $_REQUEST['q_order'];
                    //结尾~标识升序,否则为降序
                    if (substr($q_order, -1) == '~'){
                        $q_order = substr($_REQUEST['q_order'], 0, -1);
                        $direction = 'up';
                    } else{
                        $direction = 'down';
                    }
                    if($q_order==$header['order']){   //正按此列排序
                        if($direction=='up'){
                            $sort_class="sorting_asc";
                            $q_order_new = $q_order;
                        }else {
                            $sort_class="sorting_desc";
                            $q_order_new = $q_order.'~';
                        }
                    }else{
                        $sort_class = "sorting";
                        $q_order_new = $header['order'];
                    }
                    $onclick = ' onclick="'. $this->tid . ".order='" . $q_order_new . "';" . $this->tid . '.refresh();"';
                }
                if($header["align"]) {
                    if ($header["align"] == 'none') {
                        $style_align = " display:" . $header["align"] . ";";
                    } else{
                        $style_align = " text-align:" . $header["align"] . ";";
                    }
                }else{
                    $style_align = "";
                }
                if($header["width"]){
                    $style_width = " width:".$header["width"].';';
                    $th_width = "width=".$header["width"];
                }else {
                    $style_width = "";
                    $th_width = "";
                }


                $style = "style='{$style_width} {$style_align}'";

                echo "<th role='columnheader' tabindex='0' aria-controls='{$this -> tid}' class='{$sort_class}' {$onclick} {$style} {$th_width}>{$title}</th>";

            }

        echo "</tr></thead>";
        echo "<tbody role='alert' aria-live='polite' aria-relevant='all'>";
    }

    /**
     * 输出标题  dataTable
     * @param <string> $width
     * @param <string> $style
     *
     */
    public function echo_nogrid_header($width = '100%', $style='')
    {
        echo <<<EOF
    		<!-- DATA TABLES -->
        	<link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    		<table class="table" id="{$this->tid}" aria-describedby="example2_info">
				<thead>
					<tr role="row">
EOF;

        if (count($this->headers) > 0)
            foreach ($this->headers as $i => $header){
                $title = $header['title'];
                $sort_class = "";
                $onclick = "";
                if($header['order']!= ''){
                    $q_order = $_REQUEST['q_order'];
                    //结尾~标识升序,否则为降序
                    if (substr($q_order, -1) == '~'){
                        $q_order = substr($_REQUEST['q_order'], 0, -1);
                        $direction = 'up';
                    } else{
                        $direction = 'down';
                    }
                    if($q_order==$header['order']){   //正按此列排序
                        if($direction=='up'){
                            $sort_class="sorting_asc";
                            $q_order_new = $q_order;
                        }else {
                            $sort_class="sorting_desc";
                            $q_order_new = $q_order.'~';
                        }
                    }else{
                        $sort_class = "sorting";
                        $q_order_new = $header['order'];
                    }
                    $onclick = ' onclick="'. $this->tid . ".order='" . $q_order_new . "';" . $this->tid . '.refresh();"';
                }
                if($header["align"]) {
                    if ($header["align"] == 'none') {
                        $style_align = " display:" . $header["align"] . ";";
                    } else{
                        $style_align = " text-align:" . $header["align"] . ";";
                    }
                }else{
                    $style_align = "";
                }
                if($header["width"]){
                    $style_width = " width:".$header["width"].';';
                    $th_width = "width=".$header["width"];
                }else {
                    $style_width = "";
                    $th_width = "";
                }


                $style = "style='{$style_width} {$style_align}'";

                echo "<th role='columnheader' tabindex='0' aria-controls='{$this -> tid}' class='{$sort_class}' {$onclick} {$style} {$th_width}>{$title}</th>";

            }

        echo "</tr></thead>";
        echo "<tbody role='alert' aria-live='polite' aria-relevant='all'>";
    }

    /**
     * 输出标题  simple
     * @param <string> $width
     * @param <string> $style
     *
     */
    public function echo_simple_header($width = '100%', $style='')
    {
        echo <<<EOF
    		<!-- simple table -->
    		<table class="table table-striped " id="{$this->tid}">
				<thead>
					<tr >
EOF;

        if (count($this->headers) > 0)
            foreach ($this->headers as $i => $header){
                $title = $header['title'];
                $sort_class = "";
                $onclick = "";
                if($header['order']!= ''){
                    $q_order = $_REQUEST['q_order'];
                    //结尾~标识升序,否则为降序
                    if (substr($q_order, -1) == '~'){
                        $q_order = substr($_REQUEST['q_order'], 0, -1);
                        $direction = 'up';
                    } else{
                        $direction = 'down';
                    }
                    if($q_order==$header['order']){   //正按此列排序
                        if($direction=='up'){
                            $sort_class="sorting_asc";
                            $q_order_new = $q_order;
                        }else {
                            $sort_class="sorting_desc";
                            $q_order_new = $q_order.'~';
                        }
                    }else{
                        $sort_class = "sorting";
                        $q_order_new = $header['order'];
                    }
                    $onclick = ' onclick="'. $this->tid . ".order='" . $q_order_new . "';" . $this->tid . '.refresh();"';
                }
                if($header["align"]){
                    $style_align = " text-align:".$header["align"].";";
                }else{
                    $style_align = "";
                }
                if($header["width"]){
                    $style_width = " width:".$header["width"].';';
                    $th_width = "width=".$header["width"];
                }else {
                    $style_width = "";
                    $th_width = "";
                }

                $style = "style='{$style_width} {$style_align}'";

                echo "<th class='{$sort_class}' {$onclick} {$style} {$th_width}>{$title}</th>";
            }

        echo "</tr></thead>";
        echo "<tbody >";
    }

    /*
     * 设置tr属性（可选）
     * 两种形式的参数
     * 1.数组形式，可以传递多条设置
     * @param <array> $attr 数组
     * 2.单条设置的简化传递
	 * @param <string> $attr 名称
	 * @param <string> $value  值
     *
     * onClickDirect 参数传递整行点击跳转url
     */
    public function begin_row($attr, $value='')
    {
        if($value!='')
            $this->newrow_attr = array($attr => $value);
        else
            $this->newrow_attr = $attr;
    }

    public function echo_td($content = "&nbsp;", $align = "", $attr = "", $main_class = "")
    {

        if ($this->newrow == true)
        {
            $row_attr = '';
            $class = '';
            if(count($this->newrow_attr)>0)
            {
                foreach($this->newrow_attr as $rk => $rv)
                {
                    if($rk == 'onClickDirect')
                    {
                        $row_attr .= " onClick=\"window.location.href='{$rv}';\" ";
                        $class .=" cursor ";
                    }
                    elseif($rk == 'class')
                    {
                        $class .=" $rv ";
                    }
                    else
                    {
                        $row_attr .= " {$rk}=\"{$rv}\"  ";
                        if($rk=='onclick')
                        {
                            $class .=" cursor ";
                        }
                    }
                }
            }
            echo "<tr {$row_attr} class=\"".$class."\">";
            $this->newrow = false;
        }
        if ($content != 0 && $content == '')
            $content = '&nbsp;';

        $style = '';
        try{
            if($this->headers[$this->ti]){
                $width=$this->headers[$this->ti]['width'];
                if(!$align)
                    $align=$this->headers[$this->ti]['align'];
            }
            $this->ti++;
        }catch (Exception $e){

        }
        if($align != '')
            $style .= "  text-align:$align; ";
        if($width != ''){
            $style .= "  width:".$width.";";
        }

        $valign = 'top';
        $td_attr = '';
        $colspan = '';
        $tooltip = '';
        if($attr!='' && count($attr)>0)
        {
            foreach($attr as $k => $v)
            {
                if($k=='valign')
                    $valign = $v;
                elseif($k=='tooltip')
                    $tooltip .= " $v ";
                elseif($k=='style')
                    $style .= " $v ";
                elseif($k=='colspan')
                    $colspan = $v;
                $td_attr .= " {$k}=\"{$v}\"  ";
            }
        }
        echo '<td  valign="'.$valign.'" '.$td_attr;
        echo $colspan != '' ? "colspan=".$colspan : '';
        echo $main_class != '' ? "class=".$main_class : '';
        echo $style != '' ? " style=\"$style\" " : '';
        echo '>' . $content . '</td>';
    }



    public function end_row()
    {
        $this->rows += 1;
        echo '</tr>', "\n";
        $this->newrow = true;
        $this->newrow_attr = array();
        $this->ti=0;
    }

    private function echo_grid_none_data()
    {
        $out = "";
        $out .= "\n<tr>";
        $out .= "\n<td colSpan=" . (count($this->headers)) . ">&nbsp;&nbsp;&nbsp;&nbsp;".Yii::t('common', 'no record')."</td>";
        $out .= "</tr>";
        echo $out;
    }

    private function end_tbody(){
        if ($this->rows == 0)
        {
            $this->echo_grid_none_data();
        }
        echo '  </tbody>', "\n";
    }

    /**
     * tfoot部分
     * @param $foot array eg:array(array('colspan'=>2,'title'=>'合计'),array('colspan'=>3,'title'=>'RMB20000.00'))
     */
    private function echo_tfoot($foot=array()){
        echo "<tfoot>";
        if(count($foot)>0){
            echo "<tr>";
            foreach ($foot as $row){
                $col = $row['colspan'];
                $title = $row['title'];
                echo "<th colspan='{$col}'>{$title} </th>";
            }
            echo "</tr>";
        }

        echo "</tfoot>";
    }

    public function echo_grid_floor($tfoot=array())
    {
        $this -> end_tbody();
        $this -> echo_tfoot($tfoot);
        echo '</table>', "\n";
        $page = intval($_REQUEST['page']);
        $condition = '';
        $order = $_REQUEST['q_order'];
        echo '
<script src="plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    //隔行变色
    if($("#'.$this->tid.'.dataTable").length>0)
    	$("#'.$this->tid.'.dataTable  > tbody > tr:even").css("background-color","#f3f4f5");
    //防止冒泡事件
    $("table a").bind("click", function (event) {
        event.stopPropagation();
    });
    $("table input").bind("click", function (event) {
        event.stopPropagation();
    });
    $("table label").bind("click", function (event) {
        event.stopPropagation();
    });
        		
    // 鼠标经过 数据表格的行
	$("#'.$this->tid.'").find("tbody").find("tr").hover(function() { 
		$(this).addClass("grid-tr-hover");}, function() {
		$(this).removeClass("grid-tr-hover");
	});
})

if( typeof ' . $this->tid . ' == "undefined" || ' . $this->tid . ' instanceof HTMLTableElement ) {
//		alert(' . $this->url . ');
//		alert(' . $this->tid . ' instanceof HTMLTableElement);
		var ' . $this->tid . ' = {};
		' . $this->tid . '.page = ' . $page . ';
		' . $this->tid . '.condition = "' . $condition . '";
		' . $this->tid . '.order = "' . $order . '";
		' . $this->tid . '.refresh = function(page) {
			if( typeof page == "undefined" ) page = this.page;
			url = "' . $this->url . '"+"&page="+page+"&"+' . $this->tid . '.condition+"&q_order="+' . $this->tid . '.order;
			url=encodeURI(url);
//			alert( '.$this->url .');
//			alert(url);
			jQuery.ajax({
			type: "get",
			url: url,
			beforeSend: function(XMLHttpRequest){
				displayLoadingLayer();
			},
			success: function(data, textStatus){
				jQuery("#' . $this->updateDom . '").html(data);
			},
			complete: function(XMLHttpRequest, textStatus){
				hideLoadingLayer();
			},
			error: function(){
				alert("'.Yii::t('common','The request failed').'");
			}
			});
		};
	}else{
//	alert(' . $this->tid . ' instanceof HTMLTableElement);
//	alert(' . $this->url . ');
	    ' . $this->tid . '.page = ' . $page . ';
	    ' . $this->tid . '.order = "' . $order . '";
	}
</script>
		';
    }

}




