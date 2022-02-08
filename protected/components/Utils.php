<?php

class Utils {

    public static function hx2bin($str) {

        $len = strlen($str);
        $nstr = "";
        for ($i = 0; $i < $len; $i+=2) {
            $num = sscanf(substr($str, $i, 2), "%x");
            $nstr.=chr($num[0]);
        }
        return $nstr;
    }

    private static function to64($v, $n) {
        $ITOA64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        $ret = "";
        while (($n - 1) >= 0) {
            $n--;
            $ret .= $ITOA64[$v & 0x3f];
            $v = $v >> 6;
        }

        return $ret;
    }

    public static function MonthLast($last = -1, $fm = 'Y-m-d') {
        
    }

    public static function md5crypt($pw, $salt, $magic = "") {

        $MAGIC = "$1$";

        if ($magic == "")
            $magic = $MAGIC;

        $slist = explode("$", $salt);
        if ($slist[0] == "1")
            $salt = $slist[1];
        $salt = substr($salt, 0, 8);

        $ctx = $pw . $magic . $salt;

        $final = self::hx2bin(md5($pw . $salt . $pw));

        for ($i = strlen($pw); $i > 0; $i-=16) {
            if ($i > 16)
                $ctx .= substr($final, 0, 16);
            else
                $ctx .= substr($final, 0, $i);
        }

        $i = strlen($pw);
        while ($i > 0) {
            if ($i & 1)
                $ctx .= chr(0);
            else
                $ctx .= $pw[0];
            $i = $i >> 1;
        }

        $final = self::hx2bin(md5($ctx));

        # this is really stupid and takes too long

        for ($i = 0; $i < 1000; $i++) {
            $ctx1 = "";
            if ($i & 1)
                $ctx1 .= $pw;
            else
                $ctx1 .= substr($final, 0, 16);
            if ($i % 3)
                $ctx1 .= $salt;
            if ($i % 7)
                $ctx1 .= $pw;
            if ($i & 1)
                $ctx1 .= substr($final, 0, 16);
            else
                $ctx1 .= $pw;
            $final = self::hx2bin(md5($ctx1));
        }

        $passwd = "";

        $passwd .= self::to64(( (ord($final[0]) << 16) | (ord($final[6]) << 8) | (ord($final[12]))), 4);
        $passwd .= self::to64(( (ord($final[1]) << 16) | (ord($final[7]) << 8) | (ord($final[13]))), 4);
        $passwd .= self::to64(( (ord($final[2]) << 16) | (ord($final[8]) << 8) | (ord($final[14]))), 4);
        $passwd .= self::to64(( (ord($final[3]) << 16) | (ord($final[9]) << 8) | (ord($final[15]))), 4);
        $passwd .= self::to64(( (ord($final[4]) << 16) | (ord($final[10]) << 8) | (ord($final[5]))), 4);
        $passwd .= self::to64(ord($final[11]), 2);

        return "$magic$salt\$$passwd";
    }

    /**
     * 产生action的权限配置文件
     * @param <string> 模块名称, 为空表示app下Controller
     */
    public static function getControllersActions($module = '') {

        if ($module == '') {
            //Yii::import('application.controllers.*');
            $controllerPath = Yii::app()->basePath . '/controllers';
            $path = get_include_path();
            set_include_path($controllerPath . PATH_SEPARATOR . $path);
        } else {
            //Yii::import('application.modules.'.$module.'.controllers.*');
            $controllerPath = Yii::app()->basePath . '/modules/' . $module . '/controllers';
            $path = get_include_path();
            set_include_path($controllerPath . PATH_SEPARATOR . $path);
        }
        $a = array();


        $d = @dir($controllerPath);
        if (false === $d)
            return array();
        while (false !== ($entry = @$d->read()))
            if ($entry != '..' && $entry != '.' && substr($entry, -14) == 'Controller.php') {
                //echo $entry,'<br/>';
                $controller = substr($entry, 0, strlen($entry) - 4);
                //echo $controller,'<br/>';
                $class = new ReflectionClass($controller);
                $methods = $class->getMethods();
                foreach ($methods as $method) {
                    //var_dump($method);
                    if ($method->class == $controller && substr($method->name, 0, 6) == 'action') {
                        //echo $method->name,'<br>';
                        $a[] = strtolower(substr($controller, 0, strlen($controller) - 10) . '/' . substr($method->name, 6));
                    }
                }
            }
        $d->close();
        return $a;
    }

    /**
     * 产生action的权限配置文件
     * @param <string> 模块名称, 为空表示app下Controller
     */
    public static function getIndexControllersActions($module = '') {

        if ($module == '') {
            //Yii::import('application.controllers.*');
            $controllerPath = Yii::app()->basePath . '/index/controllers';
            $path = get_include_path();
            set_include_path($controllerPath . PATH_SEPARATOR . $path);
        } else {
            //Yii::import('application.modules.'.$module.'.controllers.*');
            $controllerPath = Yii::app()->basePath . '/index/modules/' . $module . '/controllers';
            $path = get_include_path();
            set_include_path($controllerPath . PATH_SEPARATOR . $path);
        }
        $a = array();


        $d = @dir($controllerPath);
        if (false === $d)
            return array();
        while (false !== ($entry = @$d->read()))
            if ($entry != '..' && $entry != '.' && substr($entry, -14) == 'Controller.php') {
                //echo $entry,'<br/>';
                $controller = substr($entry, 0, strlen($entry) - 4);
                //echo $controller,'<br/>';
                $class = new ReflectionClass($controller);
                $methods = $class->getMethods();
                foreach ($methods as $method) {
                    //var_dump($method);
                    if ($method->class == $controller && substr($method->name, 0, 6) == 'action') {
                        //echo $method->name,'<br>';
                        $a[] = strtolower(substr($controller, 0, strlen($controller) - 10) . '/' . substr($method->name, 6));
                    }
                }
            }
        $d->close();
        return $a;
    }

    public function mb_explode($separator, $string) {
        mb_regex_encoding('UTF-8');
        return mb_split('[' . $separator . ']', $string);
    }

    public function is_startwith($string, $start) {
        return substr($string, 0, strlen($start)) == $start;
    }

    public static function getMicrotime() {
        list($usec, $sec) = explode(' ', microtime());
        return ((float) $usec + (float) $sec);
    }

    public static function json_decode_nice($json, $assoc = FALSE) {
        $json = '"' . $json . '"';
        $json = str_replace(array("\n", "\r"), "", $json);
        $json = preg_replace('/([{,])(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
        return json_decode($json, $assoc);
    }
    /**
     * 工作日期格式转换
     */
    public static function WorkDateToEn($time) {
        if($time == ''){
            return $time;
        }
        $w = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec');
        $strmonth = substr($time, 5, 1);
        if($strmonth == 0){
             $dt = substr($time,6,1)-1;
//             var_dump($dt);
        }else{
             $dt = substr($time,5,2)-1;
        }
        $month = $w[$dt];
        //var_dump($month);
        $year = substr($time,0,4);
        $day = substr($time,8,2);
        
        $daytime = $day .' '.$month .' '.$year;
        

        return $daytime;
    }
    /**
     * 日期格式转换 2021-12-10
     */
    public static function DateToEn($time){
        if($time == ''){
            return $time;
        }
        $w = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec');
        $strmonth = substr($time, 5, 1);
        if($strmonth == 0){
             $dt = substr($time,6,1)-1;
             //var_dump($dt);
        }else{
             $dt = substr($time,5,2)-1;
        }
        $month = $w[$dt];
        //var_dump($month);
        $year = substr($time, 0, 4);
        $day = substr($time,8 , 2);
        $time_ = substr($time,11,8);
        if($time_ !=''){
            $daytime = $day .' '.$month .' '.$year .' '.$time_;
        }else{
            $daytime = $day .' '.$month .' '.$year;
        }
        
        return $daytime;
    }
    /*
     * 10/12/2021 转换 2021-12-10
     */
    public static function ChangeToCn($time){
        if($time == ''){
            return $time;
        }
        $time_list = explode('/',$time);
        $day = sprintf("%02d", $time_list[0]);
        $month = sprintf("%02d", $time_list[1]);
        $year = $time_list[2];
        $daytime = $year .'-'.$month.'-'.$day;
        return $daytime;
    }
    /*
     * 月份转换
     */
    public static function MonthToCn($time){
        if($time==''){
            return $time;
        }
        $w = array(Jan => "01",
            Feb => "02",
            Mar => "03",
            Apr => "04",
            May => "05",
            Jun => "06",
            Jul => "07",
            Aug => "08",
            Sep => "09",
            Oct => "10",
            Nov => "11",
            Dec => "12");
        $year = substr($time, 4, 4);
        $strmonth = substr($time,0, 3);
        $month = $w[$strmonth];

        $date = $year.'-'.$month;

        //var_dump($date);

        return $date;
    }
    /**
     * 日期格式转换
     */
    public static function DateToCn($args){

        if($args==''){
            return $args;
        }
        $w = array(Jan => "01",
        Feb => "02",
        Mar => "03",
        Apr => "04",
        May => "05",
        Jun => "06",
        Jul => "07",
        Aug => "08",
        Sep => "09",
        Oct => "10",
        Nov => "11",
        Dec => "12");
        $year = substr($args, 7, 4);
        $strmonth = substr($args,3 , 3);
        $day = substr($args, 0, 2);
        $month = $w[$strmonth];
        if($day == ''){
            $date = $year.'-'.$month;
        }else{
            $date = $year.'-'.$month.'-'.$day;
        }
        //var_dump($date);
        
        return $date;
    }
    /**
     * 日期格式转换
     */
    public static function DateMonthYear($args){

        if($args==''){
            return $args;
        }

        $year = substr($args, 0, 4);
        $month = substr($args,5 , 2);
        $day = substr($args, 8, 2);

        if($day == ''){
            $date = $year.'-'.$month;
        }else{
            $date = $day.'-'.$month.'-'.$year;
        }
        //var_dump($date);

        return $date;
    }
    /**
     * 月份转换
     * 
     */
    public static function MonthToEn($time){
        if($time == ''){
            return $time;
        }
        $w = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec');
        $strmonth = substr($time, 5, 1);
        if($strmonth == 0){
            $dt = substr($time,6,1)-1;
            //var_dump($dt);
        }else{
            $dt = substr($time,5,2)-1;
        }
        $month = $w[$dt];
        //var_dump($month);
        $year = substr($time, 0, 4);

        $daytime = $month .' '.$year;

        return $daytime;
    }
    public static function chinese_week($time = 0) {
        $w = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
        if ($time == 0)
            $time = time();
        return $w[date('w', $time)];
    }

    public static function getMessageType($type = false) {
        $ar = array(
            '1' => array('alert-success', 'fa-check'),
            '-1' => array('alert-danger', 'fa-ban'),
            '2' => array('alert-info', 'fa-info'),
            '3' => array('alert-warning', 'fa-warning'),
        );
        return $type ? $ar[$type] : $ar;
    }

    public static function pictype($file)
    {
        /*$png_header = "/x89/x50/x4e/x47/x0d/x0a/x1a/x0a";
        $jpg_header = "/xff/xd8";*/
        $header = file_get_contents($file, 0 , NULL , 0 , 5 );
        //echo bin2hex($header);
        if($header{0}.$header{1} == "/x89/x50")
        {
            return 'png' ;
        }
        else if($header{0}.$header{1} ==  "/xff/xd8")
        {
            return 'jpeg' ;
        }
        else if($header{0}.$header{1}.$header{2} == "/x47/x49/x46")
        {
            if($header{4} == "/x37")
                return 'gif87' ;
            else if($header{4} == "/x39")
                return 'gif89' ;
        }
    }

    function _U2_Utf8_Gb($_C) {
        $_String = '';
        if ($_C < 0x80) {
            $_String .= $_C;
        } elseif ($_C < 0x800) {
            $_String .= chr(0xC0 | $_C >> 6);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x10000) {
            $_String .= chr(0xE0 | $_C >> 12);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        } elseif ($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C >> 18);
            $_String .= chr(0x80 | $_C >> 12 & 0x3F);
            $_String .= chr(0x80 | $_C >> 6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GB2312', $_String);
    }

    /**
     * 下载模板文件
     * @return <type>
     */
    public static function Download($file_path, $show_name, $extend = 'xml') {
//        $filename = trim($_REQUEST['filename']);
//        $showfilename = trim($_REQUEST['showfilename']);
//        $fileDir = Yii::app()->params['stu_template_path'] . $filename . '.xls';
        //文件不存在
        if (file_exists($file_path) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = urlencode($show_name);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        header('Content-Type: application/octet-stream');
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '.' . $extend . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $show_name . '.' . $extend . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $show_name . '.' . $extend . '"');
        }
        header('Content-Length:' . filesize($file_path));
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        ob_clean();
        flush();
        readfile($file_path);
    }

    /**
     * 截去数字前、后无效的零
     * @author yangtl
     */
    public static function getvalidNumber($number) {
        $numbers = explode('.', $number);
        $int = $numbers[0];
        $float = $numbers[1];
        if ($float) {
            $matches = array();
            $cnt = preg_match_all("/[1-9]/", $float, $matches, PREG_OFFSET_CAPTURE);
            if ($cnt > 0) {
                $last_pos = $matches[0][$cnt - 1][1];
                $float_valid = substr($float, 0, $last_pos + 1);
            }
        }

        $int_valid = intval($int);

        $number_valid = $float_valid ? $int_valid . "." . $float_valid : $int_valid;

        return strval($number_valid);
    }

    /**
     * 异步表单提交结果
     * @param $msg array eg:array('status'=>1,'msg'=>'成功')
     */
    public static function ajaxFormMsg($msg, $refreshgrid = '', $script = '') {
        $class = Utils::getMessageType($msg['status']);
        $msg = "<div class='alert " . $class[0] . " alert-dismissable'>
 			<i class='fa " . $class[1] . "'></i>
 			<button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
 			<b>提示：</b>" . $msg['msg'] . "</div>";
        if ($refreshgrid)
            $msg .= "<script type='text/javascript'>{$refreshgrid}.refresh();</script>";
        if ($script)
            $msg .= "<script type='text/javascript'>$script;</script>";
        echo $msg;
    }

    //字符串截取
    public static function mbSubstrHtml($name, $l = 12) {
        if (mb_strlen($name) > $l)
            $suf = "...";
        return "<span title='{$name}'>" . mb_substr($name, 0, $l, "UTF-8") . $suf . "</span>";
    }

    //字符串截取 
    public static function mbSubstrHtml2($name, $l = 12) {
        if (mb_strlen($name) > $l)
            $suf = "...";
        return "<span >" . mb_substr($name, 0, $l, "UTF-8") . $suf . "</span>";
    }

    /**
     * 验证手机号码
     */
    public static function validMobile($val) {
        $val = trim($val);
        $preg = "/^[1-9][0-9]{10}$/";
        if ($val == '' || preg_match($preg, $val))
            return true;
        else
            return false;
    }

    /**
     * 验证邮箱
     */
    public static function validEmail($val) {
        $val = trim($val);
        $preg = "/^[A-Za-z0-9]+([._\-\+]*[A-Za-z0-9]+)*@([A-Za-z0-9-]+\.)+[A-Za-z0-9]+$/";
        if ($val == '' || preg_match($preg, $val))
            return true;
        else
            return false;
    }

    /**
     * 获得两个日期相隔的天数
     * @param type $date1
     * @param type $date2
     * @return type
     */
    public static function days($date1, $date2) {
        $temp = strtotime($date1) - strtotime($date2);
        $days = $temp / (60 * 60 * 24)+1;
        return $days;
    }
    
    /**
* 将调试信息输出到日志文件
* @param string $var_name 自定义需要显示的变量名
* @param string or array $content 变量内容
*/
    public static function filedump($content, $var_name, $filename="php_debug.log")
    {
        $log_path = Yii::app()->params['debug_log_path'];
        if(!file_exists($log_path))
        {
            umask(0000);
            @mkdir($log_path, 0777, true);
        }
        if($filename == '')
            $filename = "php_debug.log";
            
        $file = $log_path.'/'.$filename;
        
        $fp = fopen($file, 'a');
        if($fp)
        {
            if($var_name <> '')
                $var_name = '$'.$var_name.' = ';
                
            if(is_string($content) || is_int($content) || is_float($content))  //字符串、整型、浮点类型
                $content = $var_name  . $content;
            if(is_bool($content))  //布尔类型
            {
                if($content)
                    $content = $var_name . "true";
                else
                    $content = $var_name . "false";
            }
            if(is_array($content))  //数组类型
                $content = arrayeval($var_name, $content);
            $time = date('Y-m-d H:i:s');
            $title = "Logged at $time , content is: \n\n";
            $split = "===============================================================================";
            fwrite($fp, $title);
            fwrite($fp, $content."\n\n");
            fwrite($fp, $split."\n\n");
        }
        fclose($fp);
    }

    //背景色数组
    public static function backgroundList(){
        $list = array('bg-red', 'bg-yellow','bg-blue', 'bg-green', 'bg-navy', 'bg-teal', 'bg-olive', 'bg-lime', 'bg-orange', 'bg-fuchsia', 'bg-purple', 'bg-maroon', 'bg-black', 'bg-gray', 'bg-black','bg-red','bg-yellow','bg-blue','bg-green','bg-navy','bg-teal','bg-olive','bg-red', 'bg-yellow','bg-blue','bg-green', 'bg-navy', 'bg-teal', 'bg-olive', 'bg-lime', 'bg-orange', 'bg-fuchsia', 'bg-purple', 'bg-maroon', 'bg-black', 'bg-gray', 'bg-black','bg-red','bg-yellow','bg-blue','bg-green','bg-navy','bg-teal','bg-olive'
        );
        return $list;
    }

    //计算两个日期差
    public static function getTimeDifference($startdate,$enddate) {
        if ($enddate!= 0 && $enddate!='') {
            $startdate=strtotime($startdate);
            $enddate=strtotime($enddate);

            $date=floor(($enddate-$startdate)/86400);
            $hour=floor(($enddate-$startdate)%86400/3600);
            $minute=floor(($enddate-$startdate)%86400%3600/60);

            $time_diff = $date.'D '.$hour.'Hrs '.$minute.'Mins';
            $time['day'] = $date;
            $time['hour'] = $hour;
            $time['mins'] = $minute;
            $time['time_diff'] = $time_diff;
            return $time;
        }else{
            $time['time_diff'] = 0;
            return $time;
        }
    }
    //根据两个日期得到天数差
    public static function diffBetweenTwoDays ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }

    /**
     * 生成某个范围内的随机时间
     * @param <type> $begintime  起始时间 格式为 Y-m-d H:i:s
     * @param <type> $endtime    结束时间 格式为 Y-m-d H:i:s
     * @param <type> $now         是否是时间戳 格式为 Boolean
     */
    public static function randomDate($begintime, $endtime="", $now = true) {
        $begin = strtotime($begintime);
        $end = $endtime == "" ? mktime() : strtotime($endtime);
        $timestamp = rand($begin, $end);
        // d($timestamp);
        return $now ? date("Y-m-d H:i:s", $timestamp) : $timestamp;
    }

}
