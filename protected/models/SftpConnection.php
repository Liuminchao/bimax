<?php
class SftpConnection extends CActiveRecord
{
//    private $connection;
//    private $sftp;
//
//    public function __construct($host, $port=22)
//    {
//    $this->connection = @ssh2_connect($host, $port);
//    if (! $this->connection)
//    throw new Exception("Could not connect to $host on port $port.");
//    }

    public static function uploadFile($program_id, $module, $local_file)
    {
        $srcFile = "./Main.pdf";
        $full_dir = "/data/sftp/";
        $dstFile = "Main.pdf";
        $config = array("host"=>"118.190.244.53","user"=>"sftp","port"=>"22","passwd"=>"sftp@2019!");
        $conn = ssh2_connect($config['host'], $config['port']);
        if (!ssh2_auth_password($conn, $config['user'], $config['passwd'])) {
            die('sftp 连接失败');
        }else{
            $content = 'Connect Time :'.date("Y-m-d H:i:s")."\r\n";
            self::writeLog($content);
        }
        if(file_exists("./Main.pdf")){
            ssh2_scp_send($conn, $srcFile, $full_dir.$dstFile,0644);
        }else{
            var_dump('找不到');
            exit;
        }
        $content = 'Send :'.date("Y-m-d H:i:s").$full_dir.$srcFile."\r\n";
        self::writeLog($content);
        echo "over";
//        $user="sftp";
//        $pass="sftp@2019!";
//        $connection=ssh2_connect('118.190.244.53',22);
//        ssh2_auth_password($connection,$user,$pass);
//        $cmd="ps aux";
//        $ret=ssh2_exec($connection,$cmd);
//        stream_set_blocking($ret, true);
//        echo (stream_get_contents($ret));
    }
    public static function writeLog($content){
//        $content = date("Y-m-d H:i:s")."\r\n";
        $years = date('Y-m');
        //设置路径目录信息
        $url = '/opt/www-nginx/web/filebase/tmp/'.date('Ymd').'_sftp_log.txt';
        $dir_name=dirname($url);
        //目录不存在就创建
        if(!file_exists($dir_name))
        {
            //iconv防止中文名乱码
            $res = mkdir(iconv("UTF-8", "GBK", $dir_name),0777,true);
        }
        $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建
        fwrite($fp,$content);//写入文件
        fclose($fp);//关闭资源通道
    }
}