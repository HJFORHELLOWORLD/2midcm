<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function is_ajax() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;
    }
    return false;
}

function skin() {
	return get_cookie('skin') ? get_cookie('skin') : 'green';
}

function skin_url($uri='') {
    if (substr($uri,0,4)=='http') {
		return $uri;
	} else {
	    $ci = &get_instance();
		$config = $ci->config->config;
		return base_url($config['skin_url'].$uri);
	}
}

function md6($str,$key='phpci') {
	$str = md5(md5($str.$key));
	return $str;
}

function token($str='') {
    $ci = &get_instance();
	if (!$str) {
		$data['token'] = md5(time().uniqid());
		set_cookie('token',$data['token'],120000);
		return $data['token'];
	} else {
	    $post   = $ci->input->get_post('token');
		$token  = get_cookie('token');
		if (isset($token) && isset($post) && $post == $token) {
		    set_cookie('token','',120000);
			return true;
		}
		return false;
	}
}

function alert($str,$url='') {
    $str = $str ? 'alert("'.$str.'");' : '';
    $url = $url ? 'location.href="'.$url.'";' : 'history.go(-1);';
	die('<script>'.$str.$url.'</script>');
}

function str_enhtml($str) {
	if (!is_array($str)) return addslashes(htmlspecialchars(trim($str)));
	foreach ($str as $key=>$val) {
		$str[$key] = str_enhtml($val);
	}
	return $str;
}

function str_nohtml($str) {
	if (!is_array($str)) return stripslashes(htmlspecialchars_decode(trim($str)));
	foreach ($str as $key=>$val) {
		$str[$key] = str_nohtml($val);
	}
	return $str;
} 

if (!function_exists('str_quote')) {
	function str_quote($str) {
		$str = explode(',',$str);
		foreach($str as $v) {
			$arr[] = "'$v'";
		}
		return isset($arr) ? join(',',$arr) :'';
	}
}

function str_check($t0, $t1) {
	if (strlen($t0)<1) return false;   
	switch($t1){
		case 'en':$t2 = '/^[a-zA-Z]+$/'; break;   
		case 'cn':$t2 = '/[\u4e00-\u9fa5]+/u'; break;    
		case 'int':$t2 = '/^[0-9]*$/'; break;        
		case 'price':$t2 = '/^\d+(\.\d+)?$/'; break;  
		case 'username':$t2 = '/^[a-zA-Z0-9_]{5,20}$/'; break;   
		case 'password':$t2 = '/^[a-zA-Z0-9_]{6,16}$/'; break;   
		case 'email':$t2 = '/^[\w\-\.]+@[a-zA-Z0-9]+\.(([a-zA-Z0-9]{2,4})|([a-zA-Z0-9]{2,4}\.[a-zA-Z]{2,4}))$/'; break;      
		case 'tel':$t2 = '/^((\(\+?\d{2,3}\))|(\+?\d{2,3}\-))?(\(0?\d{2,3}\)|0?\d{2,3}-)?[1-9]\d{4,7}(\-\d{1,4})?$/'; break; 
		case 'mobile':$t2 = '/^(\+?\d{2,3})?0?1(3\d|5\d|8\d)\d{8}$/'; break; 
		case 'idcard':$t2 = '/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/'; break; 
		case 'qq':$t2 = '/^[1-9]\d{4,15}$/'; break; 
		case 'url':$t2 = '/^(http|https|ftp):\/\/[a-zA-Z0-9]+\.[a-zA-Z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\'\'])*$/'; break; 
		case 'ip':$t2 = '/^((25[0-5]|2[0-4]\d|(1\d|[1-9])?\d)\.){3}(25[0-5]|2[0-4]\d|(1\d|[1-9])?\d)$/'; break; 
		case 'file':$t2 = '/^[a-zA-Z0-9]{1,50}$/'; break;    
		case 'zipcode':$t2 = '/^\d{6}$/'; break;        
		case 'filename':$t2 = '/^[a-zA-Z0-9]{1,50}$/'; break;       
		case 'date':$t2 = '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'; break;  
		case 'time':$t2 = '/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/'; break; 
		case 'utf8':$t2 = '%^(?:
						[\x09\x0A\x0D\x20-\x7E] # ASCII
						| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
						| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
						| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
						| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
						| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
						| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
						| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
						)*$%xs'; break;                                   
		default:$t2 = ''; break;      
	}
	$pour = @preg_match($t2, $t0);   
	if ($pour) {  
		return $t0;  
	} else {  
		return false;   
	}  
}

function str_num2rmb ($num) {
    $c1 = "零壹贰叁肆伍陆柒捌玖";
    $c2 = "分角元拾佰仟万拾佰仟亿";
    $num = round($num, 2);
    $num = $num * 100;
    if (strlen($num) > 10) {
        return "oh,sorry,the number is too long!";
    }
    $i = 0;
    $c = "";
    while (1) {
        if ($i == 0) {
            $n = substr($num, strlen($num)-1, 1);
        } else {
            $n = $num % 10;
        }
        $p1 = substr($c1, 3 * $n, 3);
        $p2 = substr($c2, 3 * $i, 3);
        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
            $c = $p1 . $p2 . $c;
        } else {
            $c = $p1 . $c;
        }
        $i = $i + 1;
        $num = $num / 10;
        $num = (int)$num;
        if ($num == 0) {
            break;
        }
    }
    $j = 0;
    $slen = strlen($c);
    while ($j < $slen) {
        $m = substr($c, $j, 6);
        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
            $left = substr($c, 0, $j);
            $right = substr($c, $j + 3);
            $c = $left . $right;
            $j = $j-3;
            $slen = $slen-3;
        }
        $j = $j + 3;
    }
    if (substr($c, strlen($c)-3, 3) == '零') {
        $c = substr($c, 0, strlen($c)-3);
    } // if there is a '0' on the end , chop it out
    return $c . "整";
}

function str_money($num,$f=2){
	return number_format($num, $f,'.',',');
}

function str_cut($str, $length, $start=0 ,$f='...', $charset="utf-8") {
    if (function_exists("mb_substr")) {
        $slice = mb_substr($str, $start, $length, $charset);
    } elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
    } else {
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312']  = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']     = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']    = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join('',array_slice($match[0], $start, $length));
    }
    return strlen($str) > $length ? $slice.$f : $slice;
}

function str_random($len,$chars='ABCDEFJHIJKMNOPQRSTUVWSYZ'){
	$str = '';
	$max = strlen($chars) - 1;
	for ($i=0;$i<$len;$i++) {
		$str .= $chars[mt_rand(0,$max)];
	}
	return $str;
}


function str_no($str='') {
	return $str.date("YmdHis").rand(0,9);
}

function dir_add($dir,$mode=0777){
    if (is_dir($dir) || @mkdir($dir,$mode)) return true;
    if (!dir_add(dirname($dir),$mode)) return false;
    return @mkdir($dir,$mode);
}

function dir_del($dir) {
    $dir = str_replace('\\', '/', $dir);
	if (substr($dir, -1) != '/') $dir = $dir.'/';
	if (!is_dir($dir)) return false;
	$list = glob($dir.'*');
	foreach($list as $v) {
		is_dir($v) ? dir_del($v) : @unlink($v);
	}
    return @rmdir($dir);
}

function sys_xls($name){
//    ob_start();
//    set_time_limit(0);
    header("<meta http-equiv=\"content-type\" content=\"text/html;charset=uft-8\">");
    header("Content-Type: application/vnd.ms-excel");
    header("Expires:0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $filename = urlencode($name);
    $filename = str_replace("+", "%20", $filename);
    header('Content-Type: application/octet-stream');
	if (preg_match("/MSIE/", $ua)) {
		header('Content-Disposition: attachment; filename="' .$filename. '"');
	} else if (preg_match("/Firefox/", $ua)) {
		header('Content-Disposition: attachment; filename*="utf8\'\'' . $name. '"');
	} else {
		header('Content-Disposition: attachment; filename="' .$name. '"');
	}
}

if (!function_exists('array_column')) {
    function array_column(array $array, $columnKey, $indexKey = null) {
        $result = array();
        foreach ($array as $subArray) {
            if (!is_array($subArray)) {
                continue;
            } elseif (is_null($indexKey) && array_key_exists($columnKey, $subArray)) {
                $result[] = $subArray[$columnKey];
            } elseif (array_key_exists($indexKey, $subArray)) {
                if (is_null($columnKey)) {
                    $result[$subArray[$indexKey]] = $subArray;
                } elseif (array_key_exists($columnKey, $subArray)) {
                    $result[$subArray[$indexKey]] = $subArray[$columnKey];
                }
            }
        }
        return $result;
    }
}

function showpage($url,$page,$pages,$total,$t0=''){  
	$str = '';
	$page = $page > $pages ? $pages : $page;
	$str .= '<a class="pre" href="'.$url.(1).$t0.'">首页&nbsp;&nbsp;</a>';
	if ($page>1) {
        $str .= '<a class="pre" href="'.$url.(1).$t0.'">上一页&nbsp;&nbsp;</a>';
	} else {
	    $str .= '<a class="pre">上一页&nbsp;&nbsp;</a>';
	}
    if ($page<5) $start=1; $end=5;
	if ($page>=5){
	   $start = $page-2;
	   $end = $page+3;
	}
	$end = $end > $pages ? $pages : $end;
	for ($i=$start;$i<=$end;$i++) {
		if ($i==$page) {
		    $str .= '<span class="cur" style="color:#FF0000">'.$i.'&nbsp;&nbsp;</span>';
		} else {
		    $str .= '<a href="'.$url.$i.$t0.'">'.$i.'&nbsp;&nbsp;</a>';
		}
    }
    if ($page>=1 && $page<$pages) {
		$str .= '<a href="'.$url.($page+1).$t0.'">下一页</a>&nbsp;&nbsp;';
	} else {   
	    $str .= '<a class="next">下一页&nbsp;&nbsp;</a>';
	}
	$str .= '<a class="pre" href="'.$url.$pages.$t0.'">末页&nbsp;&nbsp;</a>';
	$str .= '<span class="pages_c">页次:'.$page.'/'.$pages.'&nbsp;&nbsp;&nbsp;总计:'.$total.' </span>';
    return $str;
}

