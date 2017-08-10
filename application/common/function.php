<?php

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

if (!defined('IN_V5')) {
    exit('Access Denied');
}

//获取城市名称
function getAreaName($area_id) {
    $area = v5::t('area')->get_one("area_id=" . intval($area_id), "area_name");
    return $area['area_name'];
}

function system_error($message, $show = true, $save = true, $halt = true) {
    cls_error::system_error($message, $show, $save, $halt);
}

function setglobal($key, $value, $group = null) {
    global $_G;
    $key = explode('/', $group === null ? $key : $group . '/' . $key);
    $p = &$_G;
    foreach ($key as $k) {
        if (!isset($p[$k]) || !is_array($p[$k])) {
            $p[$k] = array();
        }

        $p = &$p[$k];
    }

    $p = $value;

    return true;
}

function getglobal($key, $group = null) {
    global $_G;
    $key = explode('/', $group === null ? $key : $group . '/' . $key);
    $v = &$_G;
    foreach ($key as $k) {
        if (!isset($v[$k])) {
            return null;
        }

        $v = &$v[$k];
    }

    return $v;
}

function getgpc($key, $type = 'trim', $default = NULL, $isfilter = true) {
    if (isset($_GET[$key]) === false) {
        switch ($type) {
            case 'array':
                return array();
                break;

            case 'intval':
                return ($default === NULL) ? 0 : (int) $default;
                break;

            case 'floatval':
                return ($default === NULL) ? 0.0 : (float) $default;
                break;

            case 'trim':
                /* 无默认值的情况 */
                if ($default === NULL) {
                    return '';
                }
                /* 仅有默认值的情况 */ elseif (strpos($default, '|') === false) {
                    return (string) $default;
                }
                /* 枚举的情况 */ else {
                    $_value = explode('|', $default);

                    return (string) $_value[0];
                }
                break;
        }
    } else {
        $value = $_GET[$key];
        switch ($type) {
            case 'array':
                $value = (array) $value;
                return $value;
                break;

            case 'intval':
                return (int) $value;
                break;

            case 'floatval':
                return (float) $value;
                break;

            case 'trim':
                $value = trim($value);
                $value = (string) $value;

                /* 枚举的情况 */
                if ($default !== NULL && strpos($default, '|') !== false) {
                    $_value = explode('|', $default);
                    if ($value === '' || in_array($value, $_value) === false) {
                        $value = (string) $_value[0];
                    }
                } elseif (empty($value) && $default !== NULL) {
                    $value = $default;
                }

                if ($isfilter) {
                    return $value;
                } else {
                    return $value;
                }

                break;
        }
    }
}

function safe_replace($string) {
    if (ROUTE_M == 'admin')
        return $string;

    $string = str_replace('%20', '', $string);
    $string = str_replace('%27', '', $string);
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);
    $string = str_replace('\\', '', $string);
    $string = str_replace(array('/', '.'), '', $string);
    $string = preg_replace("/%[0-9]{2}/is", "", $string);
    $string = remove_xss($string);
    return $string;
}

function remove_xss($string) {
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);
    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $parm = array_merge($parm1, $parm2);

    for ($i = 0; $i < sizeof($parm); $i++) {
        $pattern = '/';
        for ($j = 0; $j < strlen($parm[$i]); $j++) {
            if ($j > 0) {
                $pattern .= '(';
                $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                $pattern .= '|(&#0([9][10][13]);?)?';
                $pattern .= ')?';
            }

            $pattern .= $parm[$i][$j];
        }

        $pattern .= '/i';
        $string = preg_replace($pattern, '', $string);
    }

    return $string;
}

function daddslashes($string, $force = 1) {
    if (is_array($string)) {
        $keys = array_keys($string);
        foreach ($keys as $key) {
            $val = $string[$key];
            unset($string[$key]);
            $string[addslashes($key)] = daddslashes($val, $force);
        }
    } else {
        $string = addslashes($string);
    }

    return $string;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key != '' ? $key : getglobal('authkey'));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

function dhtmlspecialchars($string, $flags = null) {
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = dhtmlspecialchars($val, $flags);
        }
    } else {
        if ($flags === null) {
            $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
            if (strpos($string, '&amp;#') !== false) {
                $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
            }
        } else {
            if (PHP_VERSION < '5.4.0') {
                $string = htmlspecialchars($string, $flags);
            } else {
                if (strtolower(CHARSET) == 'utf-8') {
                    $charset = 'UTF-8';
                } else {
                    $charset = 'ISO-8859-1';
                }
                $string = htmlspecialchars($string, $flags, $charset);
            }
        }
    }
    return $string;
}

function fileext($filename) {
    return addslashes(strtolower(substr(strrchr($filename, '.'), 1, 10)));
}

function checkmobile() {
    global $_G;
    $mobile = array();
    static $mobilebrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
        'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
        'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
        'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
        'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
        'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
        'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
    static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom',
        'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh',
        'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');

    $pad_list = array('pad', 'gt-p1000');

    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if (dstrpos($useragent, $pad_list)) {
        return false;
    }
    if (($v = dstrpos($useragent, $mobilebrowser_list, true))) {
        $_G['mobile'] = $v;
        return '2';
    }
    if (($v = dstrpos($useragent, $wmlbrowser_list))) {
        $_G['mobile'] = $v;
        return '3'; //wml版
    }
    $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
    if (dstrpos($useragent, $brower))
        return false;

    $_G['mobile'] = 'unknown';
    if (isset($_G['mobiletpl'][$_GET['mobile']])) {
        return true;
    } else {
        return false;
    }
}

function dstrpos($string, $arr, $returnvalue = false) {
    if (empty($string))
        return false;
    foreach ((array) $arr as $v) {
        if (strpos($string, $v) !== false) {
            $return = $returnvalue ? $v : true;
            return $return;
        }
    }

    return false;
}

function memory($cmd, $key = '', $value = '', $ttl = 0, $prefix = '') {
    if ($cmd == 'check') {
        return v5::memory()->enable ? v5::memory()->type : '';
    } elseif (v5::memory()->enable && in_array($cmd, array('set', 'get', 'rm', 'inc', 'dec'))) {
        if (defined('V5_DEBUG') && V5_DEBUG) {
            if (is_array($key)) {
                foreach ($key as $k) {
                    v5::memory()->debug[$cmd][] = ($cmd == 'get' || $cmd == 'rm' ? $value : '') . $prefix . $k;
                }
            } else {
                v5::memory()->debug[$cmd][] = ($cmd == 'get' || $cmd == 'rm' ? $value : '') . $prefix . $key;
            }
        }

        switch ($cmd) {
            case 'set': return v5::memory()->set($key, $value, $ttl, $prefix);
                break;
            case 'get': return v5::memory()->get($key, $value);
                break;
            case 'rm': return v5::memory()->rm($key, $value);
                break;
            case 'inc': return v5::memory()->inc($key, $value ? $value : 1);
                break;
            case 'dec': return v5::memory()->dec($key, $value ? $value : -1);
                break;
        }
    }

    return null;
}

function dintval($int, $allowarray = false) {
    $ret = intval($int);
    if ($int == $ret || !$allowarray && is_array($int))
        return $ret;
    if ($allowarray && is_array($int)) {
        foreach ($int as &$v) {
            $v = dintval($v, true);
        }
        return $int;
    } elseif ($int <= 0xffffffff) {
        $l = strlen($int);
        $m = substr($int, 0, 1) == '-' ? 1 : 0;
        if (($l - $m) === strspn($int, '0987654321', $m)) {
            return $int;
        }
    }

    return $ret;
}

function _lang($file, $langvar = null, $vars = array(), $default = null) {
    global $_G;
    @list($path, $file) = @explode('/', $file);
    if (!$file) {
        $file = $path;
        $path = '';
    }

    $key = $path == '' ? $file : $path . '_' . $file;
    if (!isset($_G['lang'][$key])) {
        include V5_ROOT . './source/language/' . ($path == '' ? '' : $path . '/') . 'lang_' . $file . '.php';
        $_G['lang'][$key] = $lang;
    }

    $returnvalue = &$_G['lang'];

    $return = $langvar !== null ? (isset($returnvalue[$key][$langvar]) ? $returnvalue[$key][$langvar] : null) : $returnvalue[$key];
    $return = $return === null ? ($default !== null ? $default : $langvar) : $return;
    $searchs = $replaces = array();
    if ($vars && is_array($vars)) {
        foreach ($vars as $k => $v) {
            $searchs[] = '{' . $k . '}';
            $replaces[] = $v;
        }
    }

    if (is_string($return) && strpos($return, '{_G/') !== false) {
        preg_match_all('/\{_G\/(.+?)\}/', $return, $gvar);
        foreach ($gvar[0] as $k => $v) {
            $searchs[] = $v;
            $replaces[] = getglobal($gvar[1][$k]);
        }
    }

    $return = str_replace($searchs, $replaces, $return);
    return $return;
}

function dmktime($date) {
    if (strpos($date, '-')) {
        $time = explode('-', $date);
        return mktime(0, 0, 0, $time[1], $time[2], $time[0]);
    }
    return 0;
}

function dimplode($array) {
    if (!empty($array)) {
        $array = array_map('addslashes', $array);
        return "'" . implode("','", is_array($array) ? $array : array($array)) . "'";
    } else {
        return 0;
    }
}

function cutstr($string, $length, $dot = ' ...') {
    if (strlen($string) <= $length) {
        return $string;
    }

    $pre = chr(1);
    $end = chr(1);
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);

    $strcut = '';
    if (strtolower(CHARSET) == 'utf-8') {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }

            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);
    } else {
        $_length = $length - 1;
        for ($i = 0; $i < $length; $i++) {
            if (ord($string[$i]) <= 127) {
                $strcut .= $string[$i];
            } else if ($i < $_length) {
                $strcut .= $string[$i] . $string[++$i];
            }
        }
    }

    $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

    $pos = strrpos($strcut, chr(1));
    if ($pos !== false) {
        $strcut = substr($strcut, 0, $pos);
    }
    return $strcut . $dot;
}

function dstripslashes($string) {
    if (empty($string))
        return $string;
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = dstripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}

function _debug($var = null, $vardump = false) {
    echo '<pre>';
    $vardump = empty($var) ? true : $vardump;
    if ($vardump) {
        var_dump($var);
    } else {
        print_r($var);
    }

    exit();
}

function ps($val, $dump = 1, $die = true) {
    if ($dump != 1) {
        $fun = 'var_dump';
    } else {
        $fun = (is_array($val) || is_object($val)) ? 'print_r' : 'printf';
    }
    header('Content-type:text/html;charset=utf-8');
    echo '<pre>';
    $fun($val);
    echo '</pre>';
    if ($die)
        die;
}

function p($val) {
    echo '<pre>';
    print_r($val);
    echo '</pre>';
}

function debuginfo() {
    global $_G;
    if (V5_DEBUG) {
        $_G['debuginfo'] = array(
            'time' => number_format((microtime(true) - $_G['starttime']), 6),
            'queries' => $_G['db']->querycount
        );

        return TRUE;
    } else {
        return FALSE;
    }
}

function template($template = 'index', $module = 'front', $style = '') {
    global $_G;
    $module = str_replace('/', DS, $module);
    if (empty($style)) {
        $style = $_G['setting']['style'];
    }

    $template_cache = v5::import('cls_template', '', 1);
    $compiledtplfile = DATA_PATH . 'template' . DS . $style . DS . $template . '.php';
    $tplfile = V5_ROOT . 'templates' . DS . $style . DS . $template . '.html';

    if (file_exists($tplfile)) {
        if (!file_exists($compiledtplfile) || (filemtime($tplfile) > filemtime($compiledtplfile))) {
            $template_cache->template_compile($module, $template, $style);
        }
    } else {
        $compiledtplfile = DATA_PATH . 'template' . DS . 'default' . DS . $template . '.php';
        $tplfile = V5_ROOT . 'templates' . DS . 'default' . DS . $template . '.html';
        if (!file_exists($compiledtplfile) || (file_exists($tplfile) && filemtime($tplfile) > filemtime($compiledtplfile))) {
            $template_cache->template_compile($module, $template, 'default');
        } elseif (!file_exists($tplfile)) {
            system_error('Template ' . $style . DS . $template . '.html' . ' does not exist');
        }
    }

    return $compiledtplfile;
}

function showmessage($msg, $url_forward = 'goback', $ms = 1250) {
    global $_G;

    if (defined('IN_ADMIN')) {
        include(cls_admin::admin_tpl('showmessage', 'admin'));
    } else {
        echo <<<EOT
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xml:lang="zh-cn" lang="zh-cn" xmlns="http://www.w3.org/1999/xhtml"> 
	<head>
	    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
		<title>请稍候...</title>
		<style type="text/css">
		body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td{margin:0;padding:0;}
		ol,ul{list-style:none;}
		caption,th{text-align:left;}
		h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:400;}
		q:before,q:after{content:'';}
		address{display:inline;}
		body{background:#fff;color:#1c2837;font:normal 15px arial, verdana, tahoma, sans-serif;position:relative;}
		h3,h4,h5,h6,strong{font-weight:700;}
		em{font-style:italic;}
		img,.input_check,.input_radio{vertical-align:middle;}
		td{padding:3px;}
		h2{font-size:15px;font-weight:400;clear:both;margin:0 0 8px;}
		body h3{font-weight:700;font-size:15px;color:#1d3652;padding:5px 8px 3px;}
		h3 img{margin-top:-2px;}
		h3 a{text-decoration:none;}
		a{color:#284b72;}
		a:hover{color:#528f6c;text-decoration:underline;}
		#aws_body.redirector{width:500px;margin:150px auto 0;max-width:90%;}
		.message{border-radius:3px;background:#f1f6ec;border:1px solid #b0ce94;color:#3e4934;line-height:150%;padding:10px 10px 10px 30px;}
		.message h3{color:#323232;padding:0;}
		.message.error{background-color:#f3dddd;color:#281b1b;font-size:15px;border-color:#deb7b7;}
		fieldset,img,abbr,acronym{border:0;}
		hr,legend{display:none;}
		</style>
		<!--/CSS-->
<script>
	function redirect(url){
        location.href = url;
      }
</script>
			</head>
	<body id='aws_body' class='redirector'>
		<div>
			<h1></h1>
			<h2>提示信息</h2>
EOT;

        echo '<p class=\'message\'><strong>' . $msg . '</strong><br /><br />';

        if ($url_forward == 'goback' || $url_forward == '') {
            echo '<a href="javascript:history.back();" >[点这里返回上一页]</a>';
        } else {

            echo '<script type="text/javascript">setTimeout("redirect(\'' . $url_forward . '\');", ' . $ms . ');</script>
                        <a href="' . $url_forward . '">如果您的浏览器没有自动跳转，请点这里继续</a>';
        }

        echo <<<EOT
			</p>
		</div>
	</body>
</html>
EOT;
    }

    exit;
}

function dmkdir($dir, $mode = 0777, $makeindex = TRUE) {
    if (!is_dir($dir)) {
        dmkdir(dirname($dir), $mode, $makeindex);
        @mkdir($dir, $mode);
        if (!empty($makeindex)) {
            @touch($dir . '/index.html');
            @chmod($dir . '/index.html', 0777);
        }
    }

    return true;
}

if (!function_exists('iconv')) {

    function iconv($in_charset, $out_charset, $str) {
        $in_charset = strtoupper($in_charset);
        $out_charset = strtoupper($out_charset);
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, $out_charset, $in_charset);
        } else {
            v5::load_sys_func('iconv');
            include(V5_ROOT . './source/function_iconv.php');
            $in_charset = strtoupper($in_charset);
            $out_charset = strtoupper($out_charset);
            if ($in_charset == 'UTF-8' && ($out_charset == 'GBK' || $out_charset == 'GB2312')) {
                return utf8_to_gbk($str);
            }

            if (($in_charset == 'GBK' || $in_charset == 'GB2312') && $out_charset == 'UTF-8') {
                return gbk_to_utf8($str);
            }

            return $str;
        }
    }

}

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val) - 1});
    switch ($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }

    return $val;
}

function download_img($imgurl, $dir_name, $is_sub_dir = true, $filename = '') {
    if (!$imgurl)
        return false;

    $save_path = V5_ROOT . 'uploadfile' . DS;
    $save_url = '/uploadfile/';
    $max_size = 10000000;
    $save_path = realpath($save_path) . '/';
    $imgdata = v5_file_get_contents($imgurl);

    if ($imgdata) {
        $file_size = strlen($imgdata);

        if (@is_dir($save_path) === false) {
            showmessage("上传目录不存在。");
        }

        if (@is_writable($save_path) === false) {
            showmessage("上传目录没有写权限。");
        }

        if ($file_size > $max_size) {
            showmessage("文件大小超过限制10M。");
        }

        //获得文件扩展名
        $file_name = basename($imgurl);
        $temp_arr = explode(".", $file_name);
        $file_ext = array_pop($temp_arr);
        $file_ext = trim($file_ext);
        $file_ext = strtolower($file_ext);

        if ($dir_name !== '') {
            $save_path .= $dir_name . DS;
            $save_url .= $dir_name . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
        }

        if ($is_sub_dir === true) {
            $ymd = date("Ymd");
            $save_path .= $ymd . "/";
            $save_url .= $ymd . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
        }

        if (!$filename) {
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
        } else {
            $new_file_name = $filename . '.' . $file_ext;
        }

        $file_path = $save_path . $new_file_name;

        if (file_put_contents($file_path, $imgdata) === false) {
            showmessage("下载文件失败。");
        }

        @chmod($file_path, 0644);
        if (!$filename) {
            $file_url = $save_url . $new_file_name;
        } else {
            $file_url = $filename . '.' . $file_ext;
        }

        return $file_url;
    }
}

function uploads($file, $dir_name, $is_sub_dir = true, $filename = '') {
    $save_path = V5_ROOT . 'uploadfile' . DS;
    $save_url = '/uploadfile/';
    $max_size = 10000000;
    $save_path = realpath($save_path) . '/';

    //PHP上传失败
    if (!empty($file['error'])) {
        switch ($file['error']) {
            case '1':
                $error = '超过php.ini允许的大小。';
                break;
            case '2':
                $error = '超过表单允许的大小。';
                break;
            case '3':
                $error = '图片只有部分被上传。';
                break;
            case '4':
                $error = '请选择图片。';
                break;
            case '6':
                $error = '找不到临时目录。';
                break;
            case '7':
                $error = '写文件到硬盘出错。';
                break;
            case '8':
                $error = 'File upload stopped by extension。';
                break;
            case '999':
            default:
                $error = '未知错误。';
        }

        showmessage($error);
    }

    if (empty($_FILES) === false) {
        $file_name = $file['name'];
        $tmp_name = $file['tmp_name'];
        $file_size = $file['size'];
        if (!$file_name) {
            showmessage("请选择文件。");
        }

        if (@is_dir($save_path) === false) {
            showmessage("上传目录不存在。");
        }

        if (@is_writable($save_path) === false) {
            showmessage("上传目录没有写权限。");
        }

        if (@is_uploaded_file($tmp_name) === false) {
            showmessage("上传失败。");
        }

        if ($file_size > $max_size) {
            showmessage("上传文件大小超过限制。");
        }

        //获得文件扩展名
        $temp_arr = explode(".", $file_name);
        $file_ext = array_pop($temp_arr);
        $file_ext = trim($file_ext);
        $file_ext = strtolower($file_ext);

        if ($dir_name !== '') {
            $save_path .= $dir_name . DS;
            $save_url .= $dir_name . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
        }

        if ($is_sub_dir === true) {
            $ymd = date("Ymd");
            $save_path .= $ymd . "/";
            $save_url .= $ymd . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }
        }

        if (!$filename) {
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
        } else {
            $new_file_name = $filename . '.' . $file_ext;
        }

        $file_path = $save_path . $new_file_name;
        if (move_uploaded_file($tmp_name, $file_path) === false) {
            showmessage("上传文件失败。");
        }

        @chmod($file_path, 0644);
        if (!$filename) {
            $file_url = $save_url . $new_file_name;
        } else {
            $file_url = $filename . '.' . $file_ext;
        }

        return $file_url;
    }
}

//自定义一个遍历目录的函数，注意目录中的目录。
function rmdi_r($dirname) {
    //判断是否为一个目录，非目录直接关闭
    if (is_dir($dirname)) {
        //如果是目录，打开他
        $name = opendir($dirname);
        //使用while循环遍历
        while ($file = readdir($name)) {
            //去掉本目录和上级目录的点
            if ($file == "." || $file == "..") {
                continue;
            }
            //如果目录里面还有一个目录，再次回调
            if (is_dir($dirname . "/" . $file)) {
                rmdi_r($dirname . "/" . $file);
            }
            //如果目录里面是个文件，那么输出文件名
            if (is_file($dirname . "/" . $file)) {
                echo($dirname . "/" . $file) . '</br>';
            }
        }
        //遍历完毕关闭文件
        closedir($name);
        //输出目录名
        echo($dirname);
    }
}

function uploads_copy_pic($content) {
    $content = stripslashes($content);
    preg_match_all("/src=\"(.*?)\"/is", $content, $match);
    if ($match[1]) {
        $save_path = V5_ROOT . 'uploadfile' . DS . 'editor' . DS;
        p($save_path);
        $save_url = '/uploadfile/editor/';
        $max_size = 10000000;
        $save_path = realpath($save_path) . '/';
        p($save_path);
        $ymd = date("Ymd");
        $save_path .= $ymd . "/";
        p($save_path);
        $save_url .= $ymd . "/";
        p($save_url);
        if (!file_exists($save_path)) {
            mkdir($save_path);
        }
        p($match);
        foreach ($match[1] as $imgsrc) {
            if (strpos($imgsrc, 'base64') !== false) {
                $imgdata = base64_decode(str_replace('data:image/png;base64,', '', $imgsrc));
                $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.png';
                $file_path = $save_path . $new_file_name;
                file_put_contents($file_path, $imgdata);
                @chmod($file_path, 0644);
                $file_url = $save_url . $new_file_name;
                $content = str_replace($imgsrc, $file_url, $content);
            }
        }
    } else {
        return $content;
    }

    return $content;
}

function loadcache($cachenames, $force = false) {
    global $_G;

    static $loadedcache = array();

    $cachenames = is_array($cachenames) ? $cachenames : array($cachenames);
    $caches = array();
    foreach ($cachenames as $k) {
        if (!isset($loadedcache[$k]) || $force) {
            $caches[] = $k;
            $loadedcache[$k] = true;
        }
    }

    if (!empty($caches)) {
        foreach ($caches as $name) {
            if ($name == 'setting') {
                $_G['setting'] = getcache('settings', 'common');
            } else {
                $_G['cache'][$name] = getcache($name, 'common');
            }
        }
    }

    return true;
}

function getcache($name, $filepath = '', $type = 'file', $config = '') {
    v5::import('cls_cache_factory');
    if ($config) {
        $cacheconfig = getglobal('config/cache');
        $cache = cls_cache_factory::get_instance($cacheconfig)->get_cache($config);
    } else {
        $cache = cls_cache_factory::get_instance()->get_cache($type);
    }

    return $cache->get($name, '', $filepath);
}

function setcache($name, $data, $filepath = '', $type = 'file', $config = '', $timeout = 0) {
    if ($config) {
        $cacheconfig = getglobal('config/cache');
        $cache = cls_cache_factory::get_instance($cacheconfig)->get_cache($config);
    } else {
        $cache = cls_cache_factory::get_instance()->get_cache($type);
    }

    return $cache->set($name, $data, $timeout, $filepath);
}

function delcache($name, $filepath = '', $type = 'file', $config = '') {
    if ($config) {
        $cacheconfig = getglobal('config/cache');
        $cache = cls_cache_factory::get_instance($cacheconfig)->get_cache($config);
    } else {
        $cache = cls_cache_factory::get_instance()->get_cache($type);
    }

    return $cache->delete($name, '', '', $filepath);
}

function to_guid_string($mix) {
    if (is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }

    return md5($mix);
}

// xml编码
function xml_encode($data, $encoding = 'utf-8', $root = 'think') {
    $xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
    $xml.= '<' . $root . '>';
    $xml.= data_to_xml($data);
    $xml.= '</' . $root . '>';
    return $xml;
}

function data_to_xml($data) {
    $xml = '';
    foreach ($data as $key => $val) {
        is_numeric($key) && $key = "item id=\"$key\"";
        $xml.="<$key>";
        $xml.= ( is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
        list($key, ) = explode(' ', $key);
        $xml.="</$key>";
    }

    return $xml;
}

function dsetcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {
    global $_G;

    $config = $_G['config']['cookie'];
    $_G['cookie'][$var] = $value;
    $var = ($prefix ? $config['cookiepre'] : '') . $var;
    $_COOKIE[$var] = $value;

    if ($value == '' || $life < 0) {
        $value = '';
        $life = -1;
    }

    $life = $life > 0 ? getglobal('timestamp') + $life : ($life < 0 ? getglobal('timestamp') - 31536000 : 0);
    $path = $config['cookiepath'];

    $secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
    if (PHP_VERSION < '5.2.0') {
        setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure);
    } else {
        setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure, $httponly);
    }
}

function getcookie($key) {
    global $_G;
    return isset($_G['cookie'][$key]) ? $_G['cookie'][$key] : '';
}

function thumb($imgurl, $width = 0, $height = 0, $autocut = 1, $smallpic = 'nopic.gif') {
    global $image;

    $upload_path = V5_ROOT . 'uploadfile' . DS;
    $upload_url = '/uploadfile/';

    $imgurl_replace = str_replace($upload_url, '', $imgurl);
    if (!extension_loaded('gd') || strpos($imgurl_replace, '://'))
        return $imgurl;
    list($width_t, $height_t, $type, $attr) = getimagesize($upload_path . $imgurl_replace);
    if ($width >= $width_t || $height >= $height_t)
        return $imgurl;

    $newimgurl = dirname($imgurl_replace) . '/thumb_' . $width . '_' . $height . '_' . basename($imgurl_replace);

    if (file_exists($upload_path . $newimgurl))
        return $upload_url . $newimgurl;

    if (!is_object($image)) {
        v5::import('cls_image');
        $image = new cls_image(1);
    }

    if ($image->thumb($upload_path . $imgurl_replace, $upload_path . $newimgurl, $width, $height, '', $autocut)) {
        unlink(V5_ROOT . $imgurl);
        return $upload_url . $newimgurl;
    } else {
        return $imgurl;
    }
}

/**
 * 水印添加
 * @param $source 原图片路径
 * @param $target 生成水印图片途径，默认为空，覆盖原图
 * @param $siteid 站点id，系统需根据站点id获取水印信息
 */
function watermark($source, $target = '', $siteid) {
    global $image_w;
    if (empty($source))
        return $source;
    if (!extension_loaded('gd') || strpos($source, '://'))
        return $source;
    if (!$target)
        $target = $source;
    if (!is_object($image_w)) {
        v5::load_sys_class('image', '', '0');
        $image_w = new image(0, $siteid);
    }
    $image_w->watermark($source, $target);
    return $target;
}

function cache_page_start() {
    $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . safe_replace($_SERVER['QUERY_STRING']) : $path_info);
    define('CACHE_PAGE_ID', md5($relate_url));
    $contents = cache(CACHE_PAGE_ID, '', 'page_tmp/' . substr(CACHE_PAGE_ID, 0, 2));
    if ($contents && intval(substr($contents, 15, 10)) > SYS_TIME) {
        echo substr($contents, 29);
        exit;
    }

    if (!defined('HTML'))
        define('HTML', true);
    return true;
}

function cache_page($ttl = 360, $isjs = 0) {
    if ($ttl == 0 || !defined('CACHE_PAGE_ID'))
        return false;
    $contents = ob_get_contents();

    if ($isjs)
        $contents = format_js($contents);
    $contents = "<!--expiretime:" . (SYS_TIME + $ttl) . "-->\n" . $contents;
    cache(CACHE_PAGE_ID, $contents, 'page_tmp/' . substr(CACHE_PAGE_ID, 0, 2));
}

function v5_file_get_contents($url, $timeout = 30) {
    $stream = stream_context_create(array('http' => array('timeout' => $timeout)));
    return @file_get_contents($url, 0, $stream);
}

function send_http_status($code) {
    static $_status = array(
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    );

    if (isset($_status[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        header('Status:' . $code . ' ' . $_status[$code]);
    }
}

function _404($msg = '', $url = '') {
    global $_G;

    if ($msg) {
        $data = array(
            'request_url' => $_SERVER['REQUEST_URI'],
            'ip' => $_SERVER['REMOTE_ADDR'],
            'addtime' => $_SERVER['REQUEST_TIME'],
            'referer' => $_G['referer']
        );

        v5::t('404_log', 'admin')->insert($data);
    }

    if ($url) {
        redirect($url);
    } else {
        send_http_status(404);
        $page = getcache('404page', 'common');
        if (empty($page)) {
            $page = <<<HTML
<html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">	
<title>页面未找到</title>
<body style="margin:0; padding:0">
<center>页面未找到</center>
</body>
</html>
HTML;
        }

        exit($page);
    }
}

function _redirect($url, $time = 0, $msg = '') {
    $url = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg)) {
        $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
    }

    if (!headers_sent()) {
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }

        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0) {
            $str .= $msg;
        }

        exit($str);
    }
}

function str_len($str) {
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
    if (is_utf8($str)) {
        $chu_shu = 3;
    } else {
        $chu_shu = 2;
    }

    if ($length) {
        return strlen($str) - $length + intval($length / $chu_shu);
    } else {
        return strlen($str);
    }
}

function is_utf8($str) {
    $c = 0;
    $b = 0;
    $bits = 0;
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c >= 254))
                return false;
            elseif ($c >= 252)
                $bits = 6;
            elseif ($c >= 248)
                $bits = 5;
            elseif ($c >= 240)
                $bits = 4;
            elseif ($c >= 224)
                $bits = 3;
            elseif ($c >= 192)
                $bits = 2;
            else
                return false;
            if (($i + $bits) > $len)
                return false;
            while ($bits > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191)
                    return false;
                $bits--;
            }
        }
    }

    return true;
}

function get_new_url($url) {
    echo $url . '<hr>';
    preg_match("/m=([a-z]+)&c=([a-z]+)&a=([a-z]+)&(.*)/is", $url, $match);
    if ($match[1] && $match[2] && $match[3]) {
        $m = trim($match[1]);
        $c = trim($match[2]);
        $a = trim($match[3]);
        $args_array = array();
        $args = trim($match[4]);
        if (strpos($args, '=') !== false) {
            preg_match_all("/([a-z]+)=([a-z0-9]+)/is", $args, $match);
            if (count($match) >= 3) {
                foreach ($match[1] as $key => $field) {
                    $value = trim($match[2][$key], '&');
                    $args_array[$field] = $value;
                }
            }
        }

        print_r($args_array);
        exit;
    } else {
        return $url;
    }
}

function comp_array_len($a, $b) {
    return(strLen($a) < strLen($b));
}

function to_sqls($data, $front = ' AND ', $in_column = false) {
    if ($in_column && is_array($data)) {
        $ids = '\'' . implode('\',\'', $data) . '\'';
        $sql = "$in_column IN ($ids)";
        return $sql;
    } else {
        if ($front == '') {
            $front = ' AND ';
        }
        if (is_array($data) && count($data) > 0) {
            $sql = '';
            foreach ($data as $key => $val) {


                if (is_array($val)) {

                    if (is_array($val[1])) {

                        //如果是数组侧分成字符串
                        $val[1] = "(" . implode(",", $val[1]) . ")";
                        $sql .= $sql ? " $front `$key` " . $val[0] . " '$val[1]' " : " `$key` " . $val[0] . " {$val[1]} ";
                    } else {
                        $sql .= $sql ? " $front `$key` " . $val[0] . " '$val[1]' " : " `$key` " . $val[0] . " '$val[1]' ";
                    }
                } else {
                    $sql .= $sql ? " $front `$key` = '$val' " : " `$key` = '$val' ";
                }
            }
            return $sql;
        } elseif (is_array($data) && count($data) == 0) {
            return '';
        } else {
            return $data;
        }
    }
}

function pre_pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(), $setpages = 10) {
    if ($urlrule == '') {
        $urlrule = url_par('page={$page}');
    }

    $multipage = '';
    if ($num > $perpage) {
        $page = $setpages + 1;
        $offset = ceil($setpages / 2 - 1);
        $pages = ceil($num / $perpage);
        if (defined('IN_ADMIN') && !defined('PAGES'))
            define('PAGES', $pages);
        $from = $curr_page - $offset;
        $to = $curr_page + $offset;
        $more = 0;
        if ($page >= $pages) {
            $from = 2;
            $to = $pages - 1;
        } else {
            if ($from <= 1) {
                $to = $page - 1;
                $from = 2;
            } elseif ($to >= $pages) {
                $from = $pages - ($page - 2);
                $to = $pages - 1;
            }

            $more = 1;
        }

        $multipage .= '<li><a class="a1">' . $num . '条</a></li>';

        if ($curr_page > 0) {
            $multipage .= ' <li><a href="' . pageurl($urlrule, $curr_page - 1, $array) . '" class="a1">上一页</a></li>';
            if ($curr_page == 1) {
                $multipage .= ' <li class="selected"><a href="#">1</a></li>';
            } elseif ($curr_page > 6 && $more) {
                $multipage .= ' <li><a href="' . pageurl($urlrule, 1, $array) . '">1</a>..</li>';
            } else {
                $multipage .= ' <li><a href="' . pageurl($urlrule, 1, $array) . '">1</a></li>';
            }
        }

        for ($i = $from; $i <= $to; $i++) {
            if ($i != $curr_page) {
                $multipage .= ' <li><a href="' . pageurl($urlrule, $i, $array) . '">' . $i . '</a></li>';
            } else {
                $multipage .= ' <li class="selected"><a href="#">' . $i . '</a></li>';
            }
        }

        if ($curr_page < $pages) {
            if ($curr_page < $pages - 5 && $more) {
                $multipage .= ' ..<li><a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a></li>';
            } else {
                $multipage .= ' <li><a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a></li>';
            }
        } elseif ($curr_page == $pages) {
            $multipage .= ' <li class="selected"><a href="#">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page, $array) . '" class="a1">下一页</a></li>';
        } else {
            $multipage .= ' <li><a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a></li>';
        }
    }

    return $multipage;
}

function pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(), $setpages = 10) {
    if ($urlrule == '') {
        $urlrule = url_par('page={$page}');
    }

    $multipage = '';
    if ($num > $perpage) {
        $page = $setpages + 1;
        $offset = ceil($setpages / 2 - 1);
        $pages = ceil($num / $perpage);
        if (defined('IN_ADMIN') && !defined('PAGES'))
            define('PAGES', $pages);
        $from = $curr_page - $offset;
        $to = $curr_page + $offset;
        $more = 0;
        if ($page >= $pages) {
            $from = 2;
            $to = $pages - 1;
        } else {
            if ($from <= 1) {
                $to = $page - 1;
                $from = 2;
            } elseif ($to >= $pages) {
                $from = $pages - ($page - 2);
                $to = $pages - 1;
            }

            $more = 1;
        }

        $multipage .= '<a class="a1">' . $num . '条</a>';
        if ($curr_page > 0) {
            $multipage .= ' <a href="' . pageurl($urlrule, $curr_page - 1, $array) . '" class="a1">上一页</a>';
            if ($curr_page == 1) {
                $multipage .= ' <span>1</span>';
            } elseif ($curr_page > 6 && $more) {
                $multipage .= ' <a href="' . pageurl($urlrule, 1, $array) . '">1</a>..';
            } else {
                $multipage .= ' <a href="' . pageurl($urlrule, 1, $array) . '">1</a>';
            }
        }

        for ($i = $from; $i <= $to; $i++) {
            if ($i != $curr_page) {
                $multipage .= ' <a href="' . pageurl($urlrule, $i, $array) . '">' . $i . '</a>';
            } else {
                $multipage .= ' <span>' . $i . '</span>';
            }
        }

        if ($curr_page < $pages) {
            if ($curr_page < $pages - 5 && $more) {
                $multipage .= ' ..<a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a>';
            } else {
                $multipage .= ' <a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a>';
            }
        } elseif ($curr_page == $pages) {
            $multipage .= ' <span>' . $pages . '</span> <a href="' . pageurl($urlrule, $curr_page, $array) . '" class="a1">下一页</a>';
        } else {
            $multipage .= ' <a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a>';
        }
    }

    return $multipage;
}

function pageurl($urlrule, $page, $array = array()) {
    if (strpos($urlrule, '~')) {
        $urlrules = explode('~', $urlrule);
        $urlrule = $page < 2 ? $urlrules[0] : $urlrules[1];
    }

    $findme = array('{$page}');
    $replaceme = array($page);

    if (is_array($array)) {
        foreach ($array as $k => $v) {
            $findme[] = '{$' . $k . '}';
            $replaceme[] = $v;
        }
    }

    $url = str_replace($findme, $replaceme, $urlrule);
    $url = str_replace(array('http://', '//', '~'), array('~', '/', 'http://'), $url);
    return $url;
}

function url_par($par, $url = '') {
    global $_G;
    if ($url == '')
        $url = $_G['siteurl'] . $_SERVER['REQUEST_URI'];
    $pos = strpos($url, '?');

    if ($pos === false) {
        $url .= '?' . $par;
    } else {
        $querystring = substr(strstr($url, '?'), 1);
        parse_str($querystring, $pars);
        $query_array = array();
        foreach ($pars as $k => $v) {
            if ($k != 'page')
                $query_array[$k] = $v;
        }
        $querystring = http_build_query($query_array) . '&' . $par;
        $url = substr($url, 0, $pos) . '?' . $querystring;
    }

    return $url;
}

function get_c_url($data) {
    $categorys = getcache('allcategorys', 'common');
    $category = $categorys[$data['catid']];
    $catdir = $category['catdir'];
    $param = array(
        'id' => $data['id'],
        'updatetime' => $data['id'],
        'title' => $data['title'],
        'catid' => $data['catid'],
        'pinyin' => $data['pinyin'],
        'py' => $data['py'],
        'mulu' => $catdir,
    );

    return url('front', 'index', 'soft', $param);
}

function _url($leixing, $data, $a = '', $param = '') {
    global $_G;

    $settings = getcache('settings', 'common');

    $url = $urlpre = '';
    if ($leixing == 'video')
        $urlpre = '.html';
    elseif ($leixing == 'admin' or $leixing == 'front' or $leixing == 'mobile') {
        if ($param) {
            if (is_string($param)) {
                $str = "\$param = $param;";
                eval($str);
            }

            foreach ($param as $key => $val) {
                $urlpre .= $key . '=' . $val . '&';
            }
        }

        if (empty($a))
            $a = 'index';
        $url = '/index.php?m=' . $leixing . '&c=' . $data . '&a=' . $a . '&' . $urlpre;
        return $url;
    } else
        $urlpre = '/';

    if ($settings['urlrule'] == 1) {
        $url = '/' . $data['id'] . $urlpre;
    } else {
        $url = '/' . $data['filename'] . $urlpre;
    }

    return $url;
}

function get_pinyin($str, $ishead = 0, $isclose = 0) {
    global $pinyins;

    $restr = '';
    $str = trim($str);
    $slen = strlen($str);

    if ($slen < 2) {
        return $str;
    }

    if (count($pinyins) == 0) {
        $fp = fopen(V5_ROOT . 'source' . DS . 'encoding' . DS . 'pinyin-utf8.dat', 'r');
        while (!feof($fp)) {
            $line = trim(fgets($fp));
            $pinyins[$line[0] . $line[1] . $line[2]] = substr($line, 4, strlen($line) - 4);
        }

        fclose($fp);
    }

    for ($i = 0; $i < $slen; $i++) {
        if (ord($str[$i]) > 0x80) {
            $c = $str[$i] . $str[$i + 1] . $str[$i + 2];
            $i = $i + 2;
            if (isset($pinyins[$c])) {
                if ($ishead == 0) {
                    $restr .= $pinyins[$c];
                } else {
                    $restr .= $pinyins[$c][0];
                }
            } else {
                $restr .= "-";
            }
        } elseif (preg_match("/[a-z0-9]/is", $str[$i])) {
            $restr .= $str[$i];
        } else {
            $restr .= "";
        }
    }

    if ($isclose == 1) {
        unset($pinyins);
    }

    return $restr;
}

function parsexmlattr($attr, $tag, $attrarray) {
    $attr = str_replace('&', '___', $attr);
    $xml = '<tpl><tag ' . $attr . ' /></tpl>';
    $xml = simplexml_load_string($xml);
    if (!$xml) {
        system_error('xml tag error: ' . $attr);
    }

    $xml = (array) ($xml->tag->attributes());
    $array = array_change_key_case($xml['@attributes']);
    if ($array) {
        $attrs = explode(',', $attrarray);
        foreach ($attrs as $name) {
            if (isset($array[$name])) {
                $array[$name] = str_replace('___', '&', $array[$name]);
            }
        }

        return $array;
    }
}

function password($password, $encrypt = '') {
    $pwd = array();
    $pwd['encrypt'] = $encrypt ? $encrypt : create_randomstr();
    $pwd['password'] = md5(md5(trim($password)) . $pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}

function create_randomstr($lenth = 6) {
    return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

function random($length, $chars = '0123456789') {
    $hash = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }

    return $hash;
}

function is_password($password) {
    $strlen = strlen($password);
    if ($strlen >= 6 && $strlen <= 20)
        return true;
    return false;
}

/**
 * 检测输入中是否含有错误字符
 *
 * @param char $string 要检查的字符串名称
 * @return TRUE or FALSE
 */
function is_badword($string) {
    $badwords = array("\\", '&', ' ', "'", '"', '/', '*', ',', '<', '>', "\r", "\t", "\n", "#");
    foreach ($badwords as $value) {
        if (strpos($string, $value) !== FALSE) {
            return TRUE;
        }
    }

    return FALSE;
}

function is_username($username) {
    $strlen = strlen($username);
    if (is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)) {
        return false;
    } elseif (20 < $strlen || $strlen < 2) {
        return false;
    }

    return true;
}

function is_ie() {
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if ((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false))
        return false;
    if (strpos($useragent, 'msie ') !== false)
        return true;
    return false;
}

function file_down($filepath, $filename = '') {
    if (!$filename)
        $filename = basename($filepath);
    if (is_ie())
        $filename = rawurlencode($filename);
    $filetype = fileext($filename);
    $filesize = sprintf("%u", filesize($filepath));
    if (ob_get_length() !== false)
        @ob_end_clean();
    header('Pragma: public');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Encoding: none');
    header('Content-type: ' . $filetype);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-length: ' . $filesize);
    readfile($filepath);
    exit;
}

function get_spider() {
    $spider = NULL;

    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }

    $searchengine_bot = array(
        'googlebot',
        'bingbot',
        'baiduspider',
        'msnbot',
        '360spider',
        'yahoo! slurp',
        'sogou web spider',
        'sogou news spider',
        'YisouSpider',
        'EasouSpider',
        'www.baidu.com/search/spider.html',
    );

    $searchengine_name = array(
        'google',
        'bing',
        'baidu',
        'MSN',
        '360',
        'yahoo',
        'sogou',
        'sogou',
        'Yisou',
        'Easou',
        'baidu',
    );

    $spider = strtolower($_SERVER['HTTP_USER_AGENT']);

    foreach ($searchengine_bot AS $key => $value) {
        if (strpos($spider, $value) !== false) {
            $spider = $searchengine_name[$key];
            return $spider;
        }
    }

    if (preg_match("/(spider|bot)/is", $spider)) {
        return 'other';
    } else {
        return false;
    }
}

function webscan_white($webscan_white_url = array()) {
    $url_path = $_SERVER['PHP_SELF'];
    $url_var = $_SERVER['QUERY_STRING'];

    foreach ($webscan_white_url as $key => $value) {
        if (!empty($url_var) && !empty($value)) {
            if (stristr($url_path, $key) && stristr($url_var, $value)) {
                return false;
            }
        } elseif (empty($url_var) && empty($value)) {
            if (stristr($url_path, $key)) {
                return false;
            }
        }
    }

    return true;
}

function webscan_stopattack($strfiltkey, $strfiltvalue, $arrfiltreq, $method) {
    $strfiltvalue = webscan_arr_foreach($strfiltvalue);

    if (preg_match("/" . $arrfiltreq . "/is", $strfiltvalue) == 1) {
        webscan_slog(array('request_url' => $_SERVER["REQUEST_URI"], 'ip' => $_SERVER["REMOTE_ADDR"], 'addtime' => time(), 'method' => $method, 'rkey' => $strfiltkey, 'rdata' => $strfiltvalue, 'user_agent' => $_SERVER['HTTP_USER_AGENT']));
        webscan_pape();
    }

    if (preg_match("/" . $arrfiltreq . "/is", $strfiltkey) == 1) {
        webscan_slog(array('request_url' => $_SERVER["REQUEST_URI"], 'ip' => $_SERVER["REMOTE_ADDR"], 'addtime' => time(), 'method' => $method, 'rkey' => $strfiltkey, 'rdata' => $strfiltkey, 'user_agent' => $_SERVER['HTTP_USER_AGENT']));
        webscan_pape();
    }
}

function webscan_slog($logs) {
    if ($logs) {
        v5::t('safe_log', 'admin')->insert($logs);
    }
}

function webscan_pape() {
    send_http_status(400);
    $pape = <<<HTML
<html>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">	
<title>输入内容存在危险字符，安全起见，已被本站拦截</title>
<body style="margin:0; padding:0">
<center><iframe width="100%" align="center" height="870" frameborder="0" scrolling="no" src="/stopattack.html"></iframe></center>
</body>
</html>
HTML;
    exit($pape);
}

function webscan_arr_foreach($arr) {
    static $str;
    if (!is_array($arr)) {
        return $arr;
    }

    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            webscan_arr_foreach($val);
        } else {
            $str[] = $val;
        }
    }

    return implode($str);
}

function str_replace_once($needle, $replace, $haystack) {
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        return $haystack;
    }

    $result = if_str_in_a($needle, $haystack);
    if ($result === true)
        return $haystack;

    return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function if_str_in_a($str1, $str2) {
    $pos = strpos($str2, $str1);
    if ($pos === false) {
        return false;
    }

    $str1_pre = substr($str2, 0, $pos);
    $pos1 = strrpos($str1_pre, '<');
    $pos2 = strrpos($str1_pre, '>');

    if ($pos1 === false and $pos2 === false) {
        return false;
    } elseif ($pos1 !== false and $pos2 !== false and $pos2 > $pos1) {
        return false;
    }

    $str1_next = substr($str2, $pos + strlen($str1));
    $pos3 = strpos($str1_next, '<');
    $pos4 = strpos($str1_next, '>');

    if ($pos3 === false and $pos4 === false) {
        return false;
    } elseif ($pos3 !== false and $pos4 !== false and $pos3 > $pos4) {
        return true;
    } elseif ($pos3 === false and $pos4 !== false) {
        return true;
    }

    return false;
}

function cdebug($var) {
    echo '<!--';
    print_r($var);
    echo '-->';
}

function send_admin_mail() {
    $additional_header = array();
    $additional_header[] = 'MIME-Version: 1.0';
    $additional_header[] = 'Content-Type: text/plain; charset=utf-8';
    $additional_header[] = 'Content-Transfer-Encoding: 8bit';
    $additional_header[] = 'From: ' . '<seoblog@seowhy.com>';
    mail('24400683@qq.com', '新博客申请提交', '有新的博客申请提交了，请注意通过！', implode("\r\n", $additional_header));
    //mail('328051836@qq.com', '新博客申请提交', '有新的博客申请提交了，请注意通过！', implode("\r\n", $additional_header));
}

function read_rss($url, $limit = 10) {
    $titles = array();
    $rss_array = array();
    $buff = file_get_contents($url);
    $buff = bianma($buff);
    $xml = simplexml_load_string($buff);

    foreach ($xml->channel->item as $it) {
        $title = $it->title;
        $link = $it->link;
        $title_md5 = md5($title);
        if (!in_array($title_md5, $titles)) {
            $rss_array[] = array('title' => $title, 'link' => $link);
            $titles[] = md5($title);
        }
    }

    $rss_array = array_slice($rss_array, 0, $limit);
    return $rss_array;
}

function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function bianma($html, $code = 'UTF-8') {
    $source_code = '';
    $match = array();
    $code = strtoupper($code);
    preg_match("/<\?xml version=\"1\.0\" encoding=\"(.*?)\"\?>/is", $html, $match);
    if (isset($match[1]) and ! empty($match[1])) {
        $source_code = strtoupper(trim($match[1]));
    } else {
        return $html;
    }

    if ($source_code != $code) {
        $html = @mb_convert_encoding($html, $code, $source_code);
    }

    return $html;
}

function check_login($return_url='') {
    global $_G;

    $allow_action = array(
        'login_admin',
        'login',
        'reg',
        'shiting',
        'getpassword',
        'reset_password',
        'paomadeng',
        'tuiguangurl',
        'send_mobile_checkcode',
        'callback',
        'login_weixin',
        'user_bd_weixin',
        'callback_alipay',
        'alipay_success',
        'send_code', //发送找回密码验证码
    );

    if ((!$_G['session'] or ! $_G['session']['uid']) and ! in_array(ROUTE_A, $allow_action)) {
        //file_put_contents(V5_ROOT.'logout_server.txt',var_export($_SERVER,true)."\r\n", FILE_APPEND);
        if ($return_url) {
            redirect($return_url); die;
        }
        if ($_SERVER['HTTP_HOST'] == 'wap.xue.hitui.com') {
            redirect('index.php?m=mobile_new&c=index&a=login'); 
        } else {
            redirect('index.php?m=public&c=index');
        }
    }
}

function get_user_info($field = '') {
    global $_G;

    if ($field) {
        return $_G['userinfo'][$field];
    } else {
        $uid = getglobal('session/uid');
        $_G['userinfo'] = v5::t('user', 'admin')->get_one(array('uid' => $uid));
    }
}

function video_time_format($playtime, $true = false) {
    if ($true) {
        $d = floor($playtime / 86400);
        $shengyu_time = $playtime - $d * 86400;
        $h = floor($shengyu_time / 3600);
        if ($d < 10)
            $d = '0' . $d;
        if ($h < 10)
            $h = '0' . $h;
        return $d . '天' . $h . '时';
    }
    else {
        $h = floor($playtime / 3600);
        $shengyu_time = $playtime - $h * 3600;
        $m = floor($shengyu_time / 60);
        $s = $shengyu_time - $m * 60;
        if ($h < 10)
            $h = '0' . $h;
        if ($m < 10)
            $m = '0' . $m;
        if ($s < 10)
            $s = '0' . $s;
        return $h . ':' . $m . ':' . $s;
    }
}

function notice_user_msg($uid, $msg, $vid = 0) {
    v5::t("msg")->insert(array('uid' => $uid, 'msg' => $msg, 'vid' => $vid, 'status' => 0, 'addtime' => time()));
}

function get_user_msg() {
    $uid = getglobal('session/uid');
    $msgs = v5::t("msg")->select("uid='$uid'", "*", "10", "status asc,mid desc");
    return $msgs;
}

function check_user_view_video_qx($vgid) {
    $uid = getglobal('session/uid');
    $userinfo = v5::t('user')->get_one("uid='$uid'");

    if (!$userinfo['mobilestatus']) {

        showmessage('您没有通过短信验证，没有权限查看该视频组', 'index.php?m=front_new&c=index&a=info&t=mobile');
    }

    if (v5::t('user_hack')->get_one("uid=$uid")) {
        showmessage('没有权限查看该视频组');
    }

    //检查当前用户的会员是否到期
    /*
      if(!$userinfo['gqtime'])
      {
      $tmp = v5::t('user_group')->get_one("ugid=".$userinfo['ugid']);
      $gqtime = strtotime("+".$tmp['gqtime']." day",$userinfo['regtime']);
      if($gqtime < time())
      {
      showmessage('您的会员权限已到期，请及时续费！');
      }
      }
      else
      {
      list($y, $m, $d) = explode('-',$userinfo['gqtime']);
      $gqtime = mktime(0,0,1,$m,$d,$y);
      if($gqtime < time())
      {
      showmessage('您的会员权限已到期，请及时续费！');
      }
      }

      if($userinfo['gqtime'] < time())
      {
      showmessage('您的会员权限已到期，请及时续费！');
      }
     */
    if ($userinfo['gqtime'] < time()) {
        showmessage('您的会员权限已到期，请及时续费！');
    }
    //检查当前用户的用户组是否有权限查看该视频
    $ugid = $userinfo['ugid'];
    $qxs = v5::t('video_group_qx')->select("ugid='$ugid'", "vgid");

    $is_have_qx = false;
    foreach ($qxs as $qx) {
        if ($qx['vgid'] == $vgid) {
            $is_have_qx = true;
            break;
        }
    }

    //扩展用户组
    $user_ext_groups = v5::t('ext_user_group')->select("uid='$uid'");
    $ugids = array();
    foreach ($user_ext_groups as $val) {
        $ugids[] = $val['ugid'];
    }

    $qxs = v5::t('video_group_qx')->select("vgid='$vgid'", "ugid");

    foreach ($qxs as $qx) {
        if (in_array($qx['ugid'], $ugids)) {
            $is_have_qx = true;
            break;
        }
    }

    if (!$is_have_qx) {
        showmessage('没有权限查看该视频组');
    }
}

function get_mobile_code($mobile) {
    $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
    $mobile_code = random_mobile(4, 1);
    $post_data = "account=cf_jieshun&password=123456&mobile=" . $mobile . "&content=" . rawurlencode("您的验证码是：" . $mobile_code . "。请不要把验证码泄露给其他人。");
    $gets = xml_to_array(Post($post_data, $target));
    if ($gets['SubmitResult']['code'] == 2) {
        $_SESSION['mobile'] = $mobile;
        $_SESSION['mobile_code'] = $mobile_code;
        setglobal('session/mobile', $mobile);
        setglobal('session/mobile_code', $mobile_code);
    }
    return $mobile_code;
}

function get_email_code($tomail, $subject = '', $body = '', $username = '') {
    v5::import('cls_phpmailer', '', 0);
    $email_code = random_mobile(4, 1);
    if (!$subject) {
        $subject = "您好，" . $username . "请查收嗨推验证码";
    }

    if (!$body) {
        $body = "<h1>您的验证码是：<font color='red'><b>" . $email_code . "</b></font>为保护您的账号安全，请勿泄露验证码给任何人。</h1>";
    }

    setglobal('session/email', $tomail);
    setglobal('session/email_code', $email_code);

    $mail = new PHPMailer();
    $mail->CharSet = "UTF-8"; //核心代码，可以解决乱码问题
    $mail->IsSMTP();                            // 经smtp发送  
    $mail->Host = "services.unimarketing.com.cn";           // SMTP 服务器  
    $mail->SMTPAuth = true;                     // 打开SMTP 认证  
    $mail->Username = "ihitui.com";    // 用户名  
    $mail->Password = "hPr/x+t6re8YRfj7tjDvZrpaJng=";          // 密码  
    $mail->From = "admin@edm.51hitui.com";                  // 发信人  
    $mail->FromName = "=?UTF-8?B?" . base64_encode("嗨推") . "?=";        // 发信人别名  
    $mail->AddAddress($tomail);                 // 收信人  
    //$mail->WordWrap = 50;  
    $mail->IsHTML(true);                            // 以html方式发送  
    $mail->Subject = $subject;                 // 邮件标题  
    $mail->Body = $body;                    // 邮件内空  
    //$mail->AltBody  =  "请使用HTML方式查看邮件。";  
    return $mail->Send();
}

function Post($curlPost, $url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $return_str = curl_exec($curl);
    curl_close($curl);
    return $return_str;
}

function xml_to_array($xml) {
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    if (preg_match_all($reg, $xml, $matches)) {
        $count = count($matches[0]);
        for ($i = 0; $i < $count; $i++) {
            $subxml = $matches[2][$i];
            $key = $matches[1][$i];
            if (preg_match($reg, $subxml)) {
                $arr[$key] = xml_to_array($subxml);
            } else {
                $arr[$key] = $subxml;
            }
        }
    }

    return $arr;
}

function random_mobile($length = 6, $numeric = 0) {
    PHP_VERSION < '4.2.0' && mt_srand((double) microtime() * 1000000);
    if ($numeric) {
        $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }

    return $hash;
}

function ismobile() {
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-'
            , 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipad', 'ipod', 'blackberry', 'meizu',
            'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini',
            'operamobi', 'opera mobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );

        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", $userAgent) && strpos($userAgent, 'ipad') === false) {
            return true;
        }
    }

    return false;
}

function reply_ask_by_weixin($subject, $main_uid, $replay_username, $reply_content) {
    $info = v5::t('user')->get_one("uid=$main_uid");
    if (!$info) {
        return false;
    }

    v5::import('cls_wechat', '', 0);

    $weixin_config = array(
        'appid' => APPID,
        'appsecret' => APPSECRET,
        'token' => TOKEN,
        'encodingaeskey' => ENCODINGAESKEY,
        'debug' => DEBUG,
        'logcallback' => LOGCALLBACK
    );


    $weixin = new cls_wechat($weixin_config);
    $template_id = 'I4j6oOL5ffHjSot4QH0mcj4-pdockfEsrfMKaJJGHnY';
    $openid = $info['openid'];

    if ($openid) {
        $data = array(
            'touser' => $openid,
            'template_id' => $template_id,
            'topcolor' => '#7b68ee',
            'data' => array(
                'first' => array('value' => $subject, 'color' => '#FF0000'),
                'keyword1' => array('value' => $replay_username, 'color' => '#FF0000'),
                'keyword2' => array('value' => date('Y-m-d H:i:s'), 'color' => '#FF0000'),
                'keyword3' => array('value' => $reply_content, 'color' => '#FF0000'),
                'remark' => array('value' => '若以上回复内容不完整，请登录嗨推学院查看完整信息。', 'color' => '#0000FF'),
            ),
        );

        $weixin->sendTemplateMessage($data);
    } else {
        return false;
    }
}

function login_msg_by_weixin($openid, $username, $ip) {
    v5::import('cls_wechat', '', 0);

    $weixin_config = array(
        'appid' => APPID,
        'appsecret' => APPSECRET,
        'token' => TOKEN,
        'encodingaeskey' => ENCODINGAESKEY,
        'debug' => DEBUG,
        'logcallback' => LOGCALLBACK
    );


    $weixin = new cls_wechat($weixin_config);
    $template_id = '9x7aHeCl03neA52xqbyZm7Oh_8IFjeGo9XbA0M4paaE';
    $subject = '亲爱的' . $username . '，您已经成功登陆嗨推学院';
    $subject1 = date('Y年m月d日 H:i:s');
    $areanme = convertip($ip);
    $areanme = trim($areanme, '-');
    $areanme = trim($areanme);

    if ($openid) {
        $data = array(
            'touser' => $openid,
            'template_id' => $template_id,
            'topcolor' => '#7b68ee',
            'data' => array(
                'first' => array('value' => $subject, 'color' => '#FF0000'),
                'keyword1' => array('value' => $subject1, 'color' => '#FF0000'),
                'keyword2' => array('value' => $ip . "(" . $areanme . ")", 'color' => '#FF0000'),
                'remark' => array('value' => '如果不是您本人操作请联系系统管理员', 'color' => '#0000FF'),
            ),
        );

        $weixin->sendTemplateMessage($data);
    } else {
        return false;
    }
}

function js_msg($msg, $button_ok = '确认', $button_cancel = '取消', $str1 = '', $str2 = '') {
    echo '<SCRIPT src="/static/js/jquery.js" type=text/javascript></SCRIPT>
		 <SCRIPT src="/static/js/jquery.ui.draggable.js" type=text/javascript></SCRIPT>
		 <SCRIPT src="/static/js/jquery.alerts.js" type=text/javascript></SCRIPT>
		 <LINK media=screen href="/static/js/jquery.alerts.css" type=text/css rel=stylesheet>';
    echo "<script>
			jConfirm('" . $msg . "', '嗨推学院','" . $button_ok . "','" . $button_cancel . "', function(r) {
			if(r == true)
			{
				" . $str1 . "
			}
			else
			{
				" . $str2 . "
			}
		});
		 </script>";
}

/*
 * 中文字符截取
 * @param      string        $string       被处理的字符串
 * @param      int           $start        开始截取的位置
 * @param      int           $length       截取的字符长度
 * @param      string        $dot          缩略符号
 * @param      string        $charset      字符编码
 * @return       string      $new          成功截取后的字符串
 */

function jiequ($string, $start, $length, $dot = '', $charset = "utf-8") {
    //判断当前的环境中是否开启了mb_substr这个函数
    if (function_exists("mb_substr")) {

        if (mb_strlen($string, $charset) > $length) {
            //如果开启了就可以直接使用这个
            return mb_substr($string, $start, $length, $charset) . $dot;
        }
        return mb_substr($string, $start, $length, $charset);
    }
    //否则就是下面没开启
    $new = '';
    //判断是否是gbk，是gbk就转码成utf-8
    if ($charset === 'gbk') {
        $string = iconv("gbk", "utf-8", $string);
    }
    //下面这个只能使用在utf-8的编码格式中
    $str = preg_split('//u', trim($string));
    for ($i = $start, $len = 1; $i < count($str) - 1 && $len <= $length; $i++, $len++) {
        $new .= $str[$i + 1];
    }
    //如果是gbk，就需要在返回结果之前，把之前的转换编码恢复一下
    if ($charset === 'gbk') {
        $new = iconv("utf-8", "gbk", $new);
    }
    return count($str) - 2 < $length ? $new : $new . $dot;
}

/*
 * 时间戳转换
 *
 *
 */

function wordtime($time) {
    $time = (int) substr($time, 0, 10);
    $int = time() - $time;
    $str = '';
    if ($int <= 10) {
        $str = sprintf('刚刚', $int);
    } elseif ($int < 60) {
        $str = sprintf('%d秒前', $int);
    } elseif ($int < 3600) {
        $str = sprintf('%d分钟前', floor($int / 60));
    } elseif ($int < 86400 / 2) {
        $str = sprintf('%d小时前', floor($int / 3600));
    } elseif ($int < 86400 * 2) {
        $str = sprintf('%d天前', floor($int / 86400));
    } else {
        $str = date('m月d日 H:i:s', $time);
    }
    return $str;
}

function convertip($ip) {

    $return = '';

    if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {

        $iparray = explode('.', $ip);

        if ($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
            $return = '- LAN';
        } elseif ($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
            $return = '- Invalid IP Address';
        } else {
            $tinyipfile = V5_ROOT . 'source/ipdata/tinyipdata.dat';
            $fullipfile = V5_ROOT . 'source/ipdata/wry.dat';
            if (@file_exists($tinyipfile)) {
                $return = convertip_tiny($ip, $tinyipfile);
            } elseif (@file_exists($fullipfile)) {
                $return = convertip_full($ip, $fullipfile);
            }
        }
    }

    return mb_convert_encoding($return, 'UTF-8', 'GBk');
}

function convertip_tiny($ip, $ipdatafile) {

    static $fp = NULL, $offset = array(), $index = NULL;

    $ipdot = explode('.', $ip);
    $ip = pack('N', ip2long($ip));

    $ipdot[0] = (int) $ipdot[0];
    $ipdot[1] = (int) $ipdot[1];

    if ($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
        $offset = @unpack('Nlen', @fread($fp, 4));
        $index = @fread($fp, $offset['len'] - 4);
    } elseif ($fp == FALSE) {
        return '- Invalid IP data file';
    }

    $length = $offset['len'] - 1028;
    $start = @unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

    for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

        if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
            $index_offset = @unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
            $index_length = @unpack('Clen', $index{$start + 7});
            break;
        }
    }

    @fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
    if ($index_length['len']) {
        return '- ' . @fread($fp, $index_length['len']);
    } else {
        return '- Unknown';
    }
}

function convertip_full($ip, $ipdatafile) {

    if (!$fd = @fopen($ipdatafile, 'rb')) {
        return '- Invalid IP data file';
    }

    $ip = explode('.', $ip);
    $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

    if (!($DataBegin = fread($fd, 4)) || !($DataEnd = fread($fd, 4)))
        return;
    @$ipbegin = implode('', unpack('L', $DataBegin));
    if ($ipbegin < 0)
        $ipbegin += pow(2, 32);
    @$ipend = implode('', unpack('L', $DataEnd));
    if ($ipend < 0)
        $ipend += pow(2, 32);
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;

    $BeginNum = $ip2num = $ip1num = 0;
    $ipAddr1 = $ipAddr2 = '';
    $EndNum = $ipAllNum;

    while ($ip1num > $ipNum || $ip2num < $ipNum) {
        $Middle = intval(($EndNum + $BeginNum) / 2);

        fseek($fd, $ipbegin + 7 * $Middle);
        $ipData1 = fread($fd, 4);
        if (strlen($ipData1) < 4) {
            fclose($fd);
            return '- System Error';
        }
        $ip1num = implode('', unpack('L', $ipData1));
        if ($ip1num < 0)
            $ip1num += pow(2, 32);

        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }

        $DataSeek = fread($fd, 3);
        if (strlen($DataSeek) < 3) {
            fclose($fd);
            return '- System Error';
        }
        $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
        fseek($fd, $DataSeek);
        $ipData2 = fread($fd, 4);
        if (strlen($ipData2) < 4) {
            fclose($fd);
            return '- System Error';
        }
        $ip2num = implode('', unpack('L', $ipData2));
        if ($ip2num < 0)
            $ip2num += pow(2, 32);

        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                fclose($fd);
                return '- Unknown';
            }
            $BeginNum = $Middle;
        }
    }

    $ipFlag = fread($fd, 1);
    if ($ipFlag == chr(1)) {
        $ipSeek = fread($fd, 3);
        if (strlen($ipSeek) < 3) {
            fclose($fd);
            return '- System Error';
        }
        $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
        fseek($fd, $ipSeek);
        $ipFlag = fread($fd, 1);
    }

    if ($ipFlag == chr(2)) {
        $AddrSeek = fread($fd, 3);
        if (strlen($AddrSeek) < 3) {
            fclose($fd);
            return '- System Error';
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }

        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr2 .= $char;

        $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
        fseek($fd, $AddrSeek);

        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr1 .= $char;
    } else {
        fseek($fd, -1, SEEK_CUR);
        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr1 .= $char;

        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return '- System Error';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }
        while (($char = fread($fd, 1)) != chr(0))
            $ipAddr2 .= $char;
    }
    fclose($fd);

    if (preg_match('/http/i', $ipAddr2)) {
        $ipAddr2 = '';
    }
    $ipaddr = "$ipAddr1 $ipAddr2";
    $ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
    $ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
    $ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
    if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
        $ipaddr = '- Unknown';
    }

    return '- ' . $ipaddr;
}

//根据回答 返回回答者用户名
function get_answer_user($aid) {
    $one = v5::t("video_dayi_answer")->get_one(array('aid' => $aid));
    if ($one['paid'] == 0) {
        return null;
    }

    $pone = v5::t("video_dayi_answer")->get_one(array('aid' => $one['paid']));

    $user = v5::t('user', 'admin')->get_one(array('uid' => $pone['uid']));
    return $user['username'];
}

?>