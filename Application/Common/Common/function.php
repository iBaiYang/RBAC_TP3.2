<?php
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    if(function_exists("mb_substr")){
            if ($suffix && strlen($str)>$length)
                return mb_substr($str, $start, $length, $charset)."...";
        else
                 return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
            if ($suffix && strlen($str)>$length)
                return iconv_substr($str,$start,$length,$charset)."...";
        else
                return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

function is_gb2312($str){
    for($i=0; $i<strlen($str); $i++) {
        $v = ord( $str[$i] );
        if( $v > 127) {
            if( ($v >= 228) && ($v <= 233) ){
                if( ($i+2) >= (strlen($str) - 1)) return true;  // not enough characters
                $v1 = ord( $str[$i+1] );
                $v2 = ord( $str[$i+2] );
                if( ($v1 >= 128) && ($v1 <=191) && ($v2 >=128) && ($v2 <= 191) ){// utf编码
                    return false;
                }else{
                    return true;
                }
            }
        }
    }
    return true;
}




/**
 * 随机密码
 * @param number $pw_length：密码长度
 * @return string：返回生成的密码
 */
function create_password($pw_length = 8){
    $randpwd = "";
    for ($i = 0; $i < $pw_length; $i++)
    {
        $randpwd .= chr(mt_rand(33, 126));//全部可见字符，数字、字母大小写、特殊符号
    }
    return $randpwd;
}

/**
 * 随机字符串
 * @param int $length 长度
 * @param int $numeric 类型(0：混合；1：纯数字)
 * @return string
 */
function random($length=6, $numeric = 0) {
     $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
     $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
     if($numeric) {
          $hash = '';
     } else {
          $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
          $length--;
     }
     $max = strlen($seed) - 1;
     for($i = 0; $i < $length; $i++) {
          $hash .= $seed{mt_rand(0, $max)};
     }
     return $hash;
}

/**
 * CURL
 * @param $url 请求地址
 * @param string $type 请求类型，GET或POST
 * @param string $data 请求内容
 * @param $Async 是否异步
 * @return mixed|string
 */
function curl ( $url, $type='GET', $data='', $Async=true )
{
    $ch = curl_init();
    // Asynchronous：异步请求
    if ( $Async ) {
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);  // 注意，毫秒超时一定要设置这个
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);  // 超时毫秒，小于500时不稳定，测试在600以上可以
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $info = curl_exec($ch);
    if ( curl_errno($ch) ) {
        return 'Errno'.curl_error($ch);
    }
    curl_close($ch);
    return $info;
}


/**
 * 文件写入
 * @access public
 * @param string $filename  文件名
 * @param string $content  文件内容
 * @return boolean
 */
function put ( $filename, $content, $type = '' )
{
    $dir  = dirname($filename);
    if ( !is_dir($dir) ) {
        mkdir($dir,0777,true);
    }
    if ( false === file_put_contents($filename,$content) ) {
        // 写入错误日志文件
    }
}
