<?php
$program_id = $_REQUEST['program_id'];
$version_id = $_REQUEST['version_id'];
//创建实例对象，连接数据库
$pdo = new PDO('mysql:host=rm-gs51693z4l4s7l46p.mysql.singapore.rds.aliyuncs.com;dbname=cmsdb2','cmsdb','cmsdb@2015');
$sql = "SELECT * FROM progress_plan where project_id = :project_id and version_id = :version_id";
//创建预处理对象
$stmt = $pdo->prepare($sql);
//bindParam:绑定一个参数到指定的变量名（类似于占位符）
$stmt->bindParam(':project_id',$program_id,PDO::PARAM_STR);
$stmt->bindParam(':version_id',$version_id,PDO::PARAM_STR);
if (!$stmt->execute()) {
    print_r($stmt->errorInfo());
    exit();
}
$data_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$x = 0;
$y = 1;
$rs = array();
foreach($data_list as $i => $j){
    $rs[$x]['authorityId'] = (int)$y;
    $rs[$x]['plan_id'] = $j['plan_id'];
    $rs[$x]['authorityName'] = $j['plan_name'];
    $rs[$x]['orderNumber'] = (int)$y;
    $rs[$x]['menuUrl'] = null;
    $rs[$x]['menuIcon'] = null;
    $rs[$x]['createTime'] = $j['plan_start'].'  '.$j['plan_finish'];
    $rs[$x]['authority'] = null;
    $rs[$x]['checked'] = 0;
    $rs[$x]['updateTime'] = $j['record_time'];
    $rs[$x]['isMenu'] = 0;
    $rs[$x]['parentId'] = $j['father_plan'];
    $rs[$x]['open'] = true;
    $x++;
    $y++;
}
$r['code'] = 0;
$r['msg'] = 'Success';
$r['count'] = $x;
$r['data'] = $rs;
print_r(json_encode($r));