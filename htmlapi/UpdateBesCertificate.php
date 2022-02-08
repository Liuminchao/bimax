<?php
    $data = $_POST;
//    print_r($data);
//    print_r(22222222);
//    exit;
    $mysql_conf = array(
        'host'    => 'rm-gs51693z4l4s7l46p.mysql.singapore.rds.aliyuncs.com',
        'db'      => 'cmsdb2',
        'db_user' => 'cmsdb',
        'db_pwd'  => 'cmsdb@2015',
    );

//    $mysql_conf = array(
//        'host'    => 'localhost',
//        'db'      => 'cmsdb',
//        'db_user' => 'root',
//        'db_pwd'  => 'root',
//    );

    $mysqli = @new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
    if ($mysqli->connect_errno) {
        die("could not connect to the database:\n" . $mysqli->connect_error);//诊断连接错误
    }
    $mysqli->query("set names 'utf8';");//编码转化
    $select_db = $mysqli->select_db($mysql_conf['db']);
    if (!$select_db) {
        die("could not connect to the db:\n" .  $mysqli->error);
    }

//    //检测work_no
    $query  = "select * from bac_staff where work_no = '".$data['work_no']."' ";
    $result = $mysqli->query($query);
//    print_r($result);
//    print_r($query);
//    exit;
    if($result) {
        $row=mysqli_fetch_assoc($result);
//        var_dump($row);
//        exit;
        $name = substr($data['aptitude_src'],27);
        $conid = $row['contractor_id'];
        $upload_path = '/opt/www-nginx/web/filebase/data/staff/' .$conid;
        $upload_file = $upload_path.'/'.$name;
//            var_dump($name);exit;
        //创建目录
        if($upload_path == ''){
            return false;
        }
        if(!file_exists($upload_path))
        {
            umask(0000);
            @mkdir($upload_path, 0777, true);
        }
        //移动文件到指定目录下
//        var_dump($data['aptitude_src']);
//        var_dump($upload_file);
        if (rename($data['aptitude_src'],$upload_file)) {
//            var_dump(111111);
//            var_dump($upload_file);
//            exit;
            $r['src'] = substr($upload_file,18);
            $r['status'] = 1;
            $r['refresh'] = true;
            $name = substr($r['src'],25);
            $src = '/opt/www-nginx/web'.$r['src'];
            $file_name = explode('.',$name);
            $size = filesize($src)/1024;
            $aptitude_size = sprintf('%.2f',$size);
            $aptitude_type = $file_name[1];
            $update = "insert into bac_aptitude set aptitude_name ='".$data['aptitude_name']."',user_id ='".$row['user_id']."',contractor_id ='".$row['contractor_id']."', 
            certificate_type ='".$data['aptitude_type']."',aptitude_photo ='".$r['src']."',aptitude_content ='".$data['aptitude_content']."',aptitude_size ='".$aptitude_size."',
            aptitude_type ='".$aptitude_type."',aptitude_use ='0',permit_startdate ='".$data['permit_startdate']."',permit_enddate ='".$data['permit_enddate']."' ";
            $mysqli->query($update);
            $id = mysqli_insert_id($mysqli);
//            var_dump($id);
//            var_dump($mysqli->error);
            $rs['success']['msg'] = "Save Success";
            $rs['success']['status'] = 1;
            $rs['success']['refresh'] = true;
            $rs['success']['id'] = $id;
            $rs['success']['src'] = $r['src'];
            print_r(json_encode($rs));
        }else{
//            var_dump(222222);
//            exit;
            $rs['error']['warning'] = "Error moving";
            $rs['error']['status'] = -1;
            $rs['error']['refresh'] = false;
            print_r(json_encode($rs));
        }
    }else{
//        var_dump(333333);
//        exit;
        $rs['error']['warning'] = "Can't find this staff in CMS";
        $rs['error']['status'] = -1;
        $rs['error']['refresh'] = false;
        print_r(json_encode($rs));
    }


