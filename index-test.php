<?php
$tr=array('0'=>'123123123','1'=>'123123123');
$cd=json_decode('{"0":{"h_id":"14600188","h_type":"0"},"1":{"h_id":"14600188","h_type":"0"}}');
//echo count($cd);
if(is_object($cd)){
    $ar=(array)$cd;
    var_dump($ar['0']);
}
var_dump($tr);