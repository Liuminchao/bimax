<?php
$t->echo_grid_header();
$data = $rows['block'];
$total = $rows['total'];

if (count($data)>0) {
    $j = 1;
    $total_sum = 0;
    for($total_sum;$total_sum<=$total;$total_sum++){
        $name = '';
        foreach ($data as $block => $row) {
//        if(stristr("Hello world!","Blk");){
//        if(strpos($row['block'],'Blk')!==false){
            $t->begin_row("onclick", "getDetail(this,'{$i}');");
            if($name != $row[$total_sum]['name']){
                $name = $row[$total_sum]['name'];
                $t->echo_td($row[$total_sum]['name']);
            }
            $t->echo_td($row[$total_sum]['cnt']);
        }
        $t->end_row();
    }
}
$t->echo_grid_floor();

//$pager = new CPagination($cnt);
//$pager->pageSize = $this->pageSize;
//$pager->itemCount = $cnt;
?>


<script type="text/javascript">
    //详情
    var itemDetail = function (id) {
        window.location = "index.php?r=task/task/method&id=" + id;
    }
</script>

