<?php

namespace TTBundle\Utils;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once 'HTTP/Request2.php';

use Symfony\Component\DependencyInjection\ContainerInterface;

class Utils
{
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->translator    = $this->container->get('translator');
    }
    
    public function GUID()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
    
    public function htmlEntityDecode($val, $stripslashe = 0)
    {
        if ($stripslashe == 1) {
            $val = stripslashes($val);
        }
        $val = html_entity_decode($val);
        if ($stripslashe == 0) {
            $val = preg_replace('#(\\\r|\\\r\\\n|\\\n)#', '<br/>', $val);
            $val = stripslashes($val);
        }
        return $val;
    }

    /**
     * converts a name to url
     * @return string the url 
     */
    public function nameDataToURL( $id, $name )
    {
        $name = $this->cleanTitleData($name);
        $url = str_replace('+', '-', $name);

        $url = str_replace(' ', '-', $url);
        $url = preg_replace('/[^a-z0-9A-Z\-]/', '', $url);
        $url = str_replace('--', '-', $url);

        $url = substr($url, 0, 80);

        if ($url[strlen($url) - 1] != '-')
            $url = $url . '-';

        $url = $url . $id;

        return $url;
    }
    
    public function htmlEntityDecodeSEO($val, $stripslashe = 0)
    {
        $val = $this->htmlEntityDecode($val, $stripslashe);
        $val = $this->cleanTitleDataSEO($val);
        $val = str_replace('-', ' ', $val);
        $val = str_replace('+', ' ', $val);
        $val = str_replace('  ', ' ', $val);
        $val = str_replace('  ', ' ', $val);
        return $val;
    }
    
    public function generateLangURL($lang, $path, $page_type = '')
    {
        if ( !$lang || $lang == '' ) $lang = 'en';
        $page_type = strtolower($page_type);
        
        $langroute        = '';
        $subdomain_suffix = ( $this->container->hasParameter('subdomain_suffix') ) ? $this->container->getParameter('subdomain_suffix') : '';
        
        if ($lang != 'en') $langroute        = '/'.$lang;
        if (substr($path, 0, 1) != '/') $path             = '/'.$path;
        
        $currentServerURL = $this->container->get('TTRouteUtils')->UriCurrentServerURL();
		
		$subdomain_root = 'www';
		
		if (in_array($page_type, array('media', 'restaurants', 'corporate', 'channels', 'deals', 'where-is', 'nearby')))
			$subdomain_root = $page_type;
		
		$langroute = $currentServerURL[0].$subdomain_root.$subdomain_suffix.'.'.$currentServerURL[1].$langroute;
		
        return $langroute.$path;
    }

    public function generateLangRoute($lang, $route, $request = array())
    {
        if ($lang != 'en') $route .= '_lang';
        return $this->container->get('router')->generate($route, $request);
    }
    
    public function convertMinToDaysHrsMin($minutes)
    {
        $day  = floor($minutes / 1440);
        $hour = floor(($minutes - $day * 1440) / 60);
        $min  = $minutes - ($day * 1440) - ($hour * 60);
        
        $durationText = '';
        $durationText .= ($day > 0) ? $day.' day' : '';
        $durationText .= ($day > 1) ? 's' : '';
        $durationText .= ($day > 0 && $hour > 0) ? ' and ' : '';
        $durationText .= ($hour > 0) ? $hour.' hour' : '';
        $durationText .= ($hour > 1) ? 's' : '';
        $durationText .= ($hour > 0 && $min > 0) ? ' and ' : '';
        $durationText .= ($min > 0) ? $min.' minute' : '';
        $durationText .= ($min > 1) ? 's' : '';
        
        return $durationText;
    }

    public function returnUserDisplayName( $userInfo, $display_options = null, $lang = 'en' )
    {
        $default_options = array(
            'max_length' => null
        );
        if ($display_options != null) $options = array_merge($default_options, $display_options);
        else $options = $default_options;

        if ($userInfo->getDisplayFullname() == 1) {
            $fullname = $this->htmlEntityDecode($userInfo->getFullName());
            $fullname = htmlspecialchars($fullname);
            if (strlen($fullname) <= 1) {
                $fullname = $userInfo->getYourUserName();
            }
        } else {
            $fullname = $userInfo->getYourUserName();
        }

        if ($options['max_length'] != null && strlen($fullname) > $options['max_length']) {
            $fullname = $this->getMultiByteSubstr( $fullname, $options['max_length'], NULL, $lang );
        }
        return $fullname;
    }

    public function returnUserArrayDisplayName($userInfo, $display_options = null, $lang = 'en' )
    {
        if( isset( $userInfo['cu_displayFullname'] ) )
        {
            $userInfo['u_displayFullname'] = $userInfo['cu_displayFullname'];
        }

        if( isset( $userInfo['cu_fullname'] ) )
        {
            $userInfo['u_fullname'] = $userInfo['cu_fullname'];
        }

        if( isset( $userInfo['cu_yourusername'] ) )
        {
            $userInfo['u_yourusername'] = $userInfo['cu_yourusername'];
        }

        $default_options = array(
            'max_length' => null
        );
        if ($display_options != null) $options = array_merge($default_options, $display_options);
        else $options         = $default_options;

        if ($userInfo['u_displayFullname'] == 1) {
            $fullname = $this->htmlEntityDecode($userInfo['u_fullname']);
            $fullname = htmlspecialchars($fullname);
            if (strlen($fullname) <= 1) {
                $fullname = $userInfo['u_yourusername'];
            }
        } else {
            $fullname = $userInfo['u_yourusername'];
        }
        if ($options['max_length'] != null && strlen($fullname) > $options['max_length']) {
            $fullname = $this->getMultiByteSubstr( $fullname, $options['max_length'], NULL, $lang );
        }
        return $fullname;
    }
    
    public function remove_accents($string)
    {
        if (!preg_match('/[\x80-\xff]/', $string)) return $string;
        
        if ($this->seems_utf8($string)) {
            $chars = array(
                // Decompositions for Latin-1 Supplement
                chr(195).chr(128) => 'A',
                chr(195).chr(129) => 'A',
                chr(195).chr(130) => 'A',
                chr(195).chr(131) => 'A',
                chr(195).chr(132) => 'A',
                chr(195).chr(133) => 'A',
                chr(195).chr(135) => 'C',
                chr(195).chr(136) => 'E',
                chr(195).chr(137) => 'E',
                chr(195).chr(138) => 'E',
                chr(195).chr(139) => 'E',
                chr(195).chr(140) => 'I',
                chr(195).chr(141) => 'I',
                chr(195).chr(142) => 'I',
                chr(195).chr(143) => 'I',
                chr(195).chr(145) => 'N',
                chr(195).chr(146) => 'O',
                chr(195).chr(147) => 'O',
                chr(195).chr(148) => 'O',
                chr(195).chr(149) => 'O',
                chr(195).chr(150) => 'O',
                chr(195).chr(153) => 'U',
                chr(195).chr(154) => 'U',
                chr(195).chr(155) => 'U',
                chr(195).chr(156) => 'U',
                chr(195).chr(157) => 'Y',
                chr(195).chr(159) => 's',
                chr(195).chr(160) => 'a',
                chr(195).chr(161) => 'a',
                chr(195).chr(162) => 'a',
                chr(195).chr(163) => 'a',
                chr(195).chr(164) => 'a',
                chr(195).chr(165) => 'a',
                chr(195).chr(167) => 'c',
                chr(195).chr(168) => 'e',
                chr(195).chr(169) => 'e',
                chr(195).chr(170) => 'e',
                chr(195).chr(171) => 'e',
                chr(195).chr(172) => 'i',
                chr(195).chr(173) => 'i',
                chr(195).chr(174) => 'i',
                chr(195).chr(175) => 'i',
                chr(195).chr(177) => 'n',
                chr(195).chr(178) => 'o',
                chr(195).chr(179) => 'o',
                chr(195).chr(180) => 'o',
                chr(195).chr(181) => 'o',
                chr(195).chr(182) => 'o',
                chr(195).chr(182) => 'o',
                chr(195).chr(185) => 'u',
                chr(195).chr(186) => 'u',
                chr(195).chr(187) => 'u',
                chr(195).chr(188) => 'u',
                chr(195).chr(189) => 'y',
                chr(195).chr(191) => 'y',
                // Decompositions for Latin Extended-A
                chr(196).chr(128) => 'A',
                chr(196).chr(129) => 'a',
                chr(196).chr(130) => 'A',
                chr(196).chr(131) => 'a',
                chr(196).chr(132) => 'A',
                chr(196).chr(133) => 'a',
                chr(196).chr(134) => 'C',
                chr(196).chr(135) => 'c',
                chr(196).chr(136) => 'C',
                chr(196).chr(137) => 'c',
                chr(196).chr(138) => 'C',
                chr(196).chr(139) => 'c',
                chr(196).chr(140) => 'C',
                chr(196).chr(141) => 'c',
                chr(196).chr(142) => 'D',
                chr(196).chr(143) => 'd',
                chr(196).chr(144) => 'D',
                chr(196).chr(145) => 'd',
                chr(196).chr(146) => 'E',
                chr(196).chr(147) => 'e',
                chr(196).chr(148) => 'E',
                chr(196).chr(149) => 'e',
                chr(196).chr(150) => 'E',
                chr(196).chr(151) => 'e',
                chr(196).chr(152) => 'E',
                chr(196).chr(153) => 'e',
                chr(196).chr(154) => 'E',
                chr(196).chr(155) => 'e',
                chr(196).chr(156) => 'G',
                chr(196).chr(157) => 'g',
                chr(196).chr(158) => 'G',
                chr(196).chr(159) => 'g',
                chr(196).chr(160) => 'G',
                chr(196).chr(161) => 'g',
                chr(196).chr(162) => 'G',
                chr(196).chr(163) => 'g',
                chr(196).chr(164) => 'H',
                chr(196).chr(165) => 'h',
                chr(196).chr(166) => 'H',
                chr(196).chr(167) => 'h',
                chr(196).chr(168) => 'I',
                chr(196).chr(169) => 'i',
                chr(196).chr(170) => 'I',
                chr(196).chr(171) => 'i',
                chr(196).chr(172) => 'I',
                chr(196).chr(173) => 'i',
                chr(196).chr(174) => 'I',
                chr(196).chr(175) => 'i',
                chr(196).chr(176) => 'I',
                chr(196).chr(177) => 'i',
                chr(196).chr(178) => 'IJ',
                chr(196).chr(179) => 'ij',
                chr(196).chr(180) => 'J',
                chr(196).chr(181) => 'j',
                chr(196).chr(182) => 'K',
                chr(196).chr(183) => 'k',
                chr(196).chr(184) => 'k',
                chr(196).chr(185) => 'L',
                chr(196).chr(186) => 'l',
                chr(196).chr(187) => 'L',
                chr(196).chr(188) => 'l',
                chr(196).chr(189) => 'L',
                chr(196).chr(190) => 'l',
                chr(196).chr(191) => 'L',
                chr(197).chr(128) => 'l',
                chr(197).chr(129) => 'L',
                chr(197).chr(130) => 'l',
                chr(197).chr(131) => 'N',
                chr(197).chr(132) => 'n',
                chr(197).chr(133) => 'N',
                chr(197).chr(134) => 'n',
                chr(197).chr(135) => 'N',
                chr(197).chr(136) => 'n',
                chr(197).chr(137) => 'N',
                chr(197).chr(138) => 'n',
                chr(197).chr(139) => 'N',
                chr(197).chr(140) => 'O',
                chr(197).chr(141) => 'o',
                chr(197).chr(142) => 'O',
                chr(197).chr(143) => 'o',
                chr(197).chr(144) => 'O',
                chr(197).chr(145) => 'o',
                chr(197).chr(146) => 'OE',
                chr(197).chr(147) => 'oe',
                chr(197).chr(148) => 'R',
                chr(197).chr(149) => 'r',
                chr(197).chr(150) => 'R',
                chr(197).chr(151) => 'r',
                chr(197).chr(152) => 'R',
                chr(197).chr(153) => 'r',
                chr(197).chr(154) => 'S',
                chr(197).chr(155) => 's',
                chr(197).chr(156) => 'S',
                chr(197).chr(157) => 's',
                chr(197).chr(158) => 'S',
                chr(197).chr(159) => 's',
                chr(197).chr(160) => 'S',
                chr(197).chr(161) => 's',
                chr(197).chr(162) => 'T',
                chr(197).chr(163) => 't',
                chr(197).chr(164) => 'T',
                chr(197).chr(165) => 't',
                chr(197).chr(166) => 'T',
                chr(197).chr(167) => 't',
                chr(197).chr(168) => 'U',
                chr(197).chr(169) => 'u',
                chr(197).chr(170) => 'U',
                chr(197).chr(171) => 'u',
                chr(197).chr(172) => 'U',
                chr(197).chr(173) => 'u',
                chr(197).chr(174) => 'U',
                chr(197).chr(175) => 'u',
                chr(197).chr(176) => 'U',
                chr(197).chr(177) => 'u',
                chr(197).chr(178) => 'U',
                chr(197).chr(179) => 'u',
                chr(197).chr(180) => 'W',
                chr(197).chr(181) => 'w',
                chr(197).chr(182) => 'Y',
                chr(197).chr(183) => 'y',
                chr(197).chr(184) => 'Y',
                chr(197).chr(185) => 'Z',
                chr(197).chr(186) => 'z',
                chr(197).chr(187) => 'Z',
                chr(197).chr(188) => 'z',
                chr(197).chr(189) => 'Z',
                chr(197).chr(190) => 'z',
                chr(197).chr(191) => 's',
                // Euro Sign
                chr(226).chr(130).chr(172) => 'E',
                // GBP (Pound) Sign
                chr(194).chr(163) => ''
            );
            
            $string = strtr($string, $chars);
        } else {
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158).chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194).chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202).chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210).chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218).chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227).chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235).chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243).chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251).chr(252).chr(253).chr(255);
            
            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";
            
            $string              = strtr($string, $chars['in'], $chars['out']);
            $double_chars['in']  = array(
                chr(140),
                chr(156),
                chr(198),
                chr(208),
                chr(222),
                chr(223),
                chr(230),
                chr(240),
                chr(254)
            );
            $double_chars['out'] = array(
                'OE',
                'oe',
                'AE',
                'DH',
                'TH',
                'ss',
                'ae',
                'dh',
                'th'
            );
            $string              = str_replace($double_chars['in'], $double_chars['out'], $string);
        }
        return $string;
    }
    
    public function cleanTitleDataAlt($titles)
    {
        $title = $this->cleanTitleData($titles);
        $title = str_replace('+', ' ', $title);
        return $title;
    }
    
    public function cleanTitleData($titles)
    {
        $titles = html_entity_decode($titles, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $title  = str_replace("'", " ", $titles);
        $title  = preg_replace('/\r\n|\r|\n/', '', $title);
        $title  = trim($title);
        $title  = str_replace('"', " ", $title);
        $title  = str_replace("’", " ", $title);
        $title  = str_replace("`", " ", $title);
        $title  = str_replace(',', "-", $title);
        $title  = str_replace('(', "-", $title);
        $title  = str_replace(')', "-", $title);
        $title  = str_replace('?', "-", $title);
        $title  = str_replace('#', "", $title);
        $title  = str_replace('!', "-", $title);
        $title  = str_replace('}', "-", $title);
        $title  = str_replace('.', "-", $title);
        $title  = str_replace('/', "-", $title);
        $title  = str_replace(' & ', "-", $title);
        $title  = str_replace('&', '-and-', $title);
        $title  = str_replace(">", "-", $title);
        $title  = str_replace("<", "-", $title);
        $title  = str_replace(' ', "-", $title);
        $title  = str_replace('-', "-", $title);
        $title  = str_replace("%+", "-", $title);
        $title  = str_replace("%-", "-", $title);
        $title  = str_replace("100%", "100", $title);
        $title  = str_replace("%", "-", $title);
        $title  = str_replace('+', "-", $title);
        $title  = preg_replace("/\-+/", '-', $title);
        $title  = str_replace('-', "+", $title);
        $title  = $this->remove_accents($title);
        $title  = preg_replace('/[^a-z0-9A-Z\-+]/', '', $title);
        $title  = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return $title;
    }
    
    public function cleanTitleDataSEO($titles)
    {
        $titles = html_entity_decode($titles, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $title  = str_replace("'", " ", $titles);
        $title  = preg_replace('/\r\n|\r|\n/', '', $title);
        $title  = trim($title);
        $title  = str_replace('"', " ", $title);
        $title  = str_replace("’", " ", $title);
        $title  = str_replace("`", " ", $title);
        $title  = str_replace('?', "+", $title);
        $title  = str_replace('#', "", $title);
        $title  = str_replace('!', "+", $title);
        $title  = str_replace('}', "+", $title);
        $title  = str_replace('/', "+", $title);
        $title  = str_replace(' & ', '+', $title);
        $title  = str_replace('&', '+and+', $title);
        $title  = str_replace(">", "+", $title);
        $title  = str_replace("<", "+", $title);
        $title  = str_replace(' ', '+', $title);
        $title  = str_replace("%+", "+", $title);
        $title  = str_replace("%-", "-", $title);
        $title  = str_replace("100%", "100", $title);
        $title  = str_replace("%", "+", $title);
        $title  = str_replace('-', '+', $title);
        $title  = html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return $title;
    }
    
    public function cleanTitle($title)
    {
        $ret = html_entity_decode($title);
        $ret = $this->remove_accents($ret);
        $ret = str_replace(' ', '-', $ret);
        $ret = str_replace(' ', '-', $ret);
        $ret = str_replace('.', '', $ret);
        $ret = preg_replace('/[^a-z0-9A-Z\-]/', '', $ret);
        $ret = str_replace('--', '-', $ret);
        
        return $ret;
    }
    
    public function normalizeData($data)
    {
        if (is_array($data)) {
            $data = implode('', $data);
        }
        
        $ret = $this->remove_accents($data);
        $ret = preg_replace('/[^a-zA-Z0-9]/', '', $ret);
        $ret = strtolower($ret);
        return $ret;
    }
    
    public function seems_utf8($str)
    {
        $length = strlen($str);
        for ($i = 0; $i < $length; $i ++) {
            $c = ord($str[$i]);
            if ($c < 0x80) $n = 0; // 0bbbbbbb
            elseif (($c & 0xE0) == 0xC0) $n = 1; // 110bbbbb
            elseif (($c & 0xF0) == 0xE0) $n = 2; // 1110bbbb
            elseif (($c & 0xF8) == 0xF0) $n = 3; // 11110bbb
            elseif (($c & 0xFC) == 0xF8) $n = 4; // 111110bb
            elseif (($c & 0xFE) == 0xFC) $n = 5; // 1111110b
            else return false; // Does not match any model
            for ($j = 0; $j < $n; $j ++) { // n bytes matching 10bbbbbb follow ?
                if (( ++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) return false;
            }
        }
        return true;
    }
    
    public function debug($var)
    {
        $bt     = debug_backtrace();
        $caller = array_shift($bt);
        
        echo '<div style="background:#FF0"><pre>';
        echo 'File: '.$caller['file'].' line: '.$caller['line'].'<br />';
        print_r($var);
        echo "</pre></div>";
    }
    
    public function hash_to_line($hash = array())
    {
        if (!$hash) return '';
        
        $line = '';
        
        foreach ($hash as $key => $value) {
            if ($value && is_array($value)) $line .= ($line ? ', ' : '').(is_numeric($key) ? '' : $key.':: ').'('.$this->hash_to_line($value).')';
            else $line .= ($line ? ', ' : '').(is_numeric($key) ? '' : $key.':: ').($value !== null ? $value : '');
        }
        
        return $line;
    }
    /*
     * Returns parts (array('date' => ..., 'time' => ...) of a date/time string, optionally given gmt_offset and an array of options.
     * gmt_offset can be of the form: [+|-]hours.minutes
     * options currently supports date_format which defaults to Y-m-d\TH:i:sP
     */
    
    public function date_time_parts($date_time_string, $gmt_offset = null, $options = array())
    {
        $default_options   = array(
            'date_format' => "Y-m-d\TH:i:sP"
        );
        $effective_options = array_merge($default_options, $options);
        
        $offset_sign    = '+';
        $offset_hours   = '00';
        $offset_minutes = '00';
        
        if ($gmt_offset !== null) {
            $tmp_string = substr($gmt_offset, 0, 1);
            
            if (in_array($tmp_string, array(
                '+',
                '-'
            ))) {
                $offset_sign = $tmp_string;
                
                $gmt_offset = substr($gmt_offset, 1);
            }
            
            $offset_parts = explode('.', $gmt_offset);
            $offset_hours = sprintf("%02d", $offset_parts[0]);
            
            if (count($offset_parts) > 1) $offset_minutes = $offset_parts[1];
            
            $offset_minutes = sprintf("%02d", ('0.'.$offset_minutes) * 60);
            
            $gmt_offset = $offset_sign.$offset_hours.':'.$offset_minutes;
        }
        
        $date_array = date_parse_from_format($effective_options['date_format'], $date_time_string.($gmt_offset ? $gmt_offset : ''));
        
        $date_time['date'] = '';
        $date_time['time'] = '';
        
        if (!$date_array['error_count']) {
            $unix_date = mktime($date_array['hour'], $date_array['minute'], $date_array['second'], $date_array['month'], $date_array['day'], $date_array['year']);
            
            $date_time['date'] = date("M d", $unix_date);
            
            $date_time['time'] = sprintf("%02d:%02d", $date_array['hour'], $date_array['minute']);
        }
        
        return $date_time;
    }
    
    public function getMinutesFromTime($hms_time_string = null)
    {
        if (!$hms_time_string) return 0;
        
        // list($hours, $minutes, $seconds) = explode(':', $hms_time_string); // generates a PHP Notice if the input string is not well formatted
        $hms_array = explode(':', $hms_time_string);
        
        if (!is_numeric($hms_array[0])) return 0;
        
        $response_minutes = ($hms_array[0] * 60);
        
        if (count($hms_array) > 1) {
            if (!is_numeric($hms_array[1])) return 0;
        }
        
        return ($hms_array[0] * 60 + $hms_array[1]);
    }
    /*
     * This function counts the number of subs in a data_set at a given sub key_index.
     *
     * Supposing data_set is an array containing subs as in
     * array( array('A', 'B', 'C'),
     * array('D', 'E', 'F'),
     * array('G', 'H'),
     * array('I'),
     * 'J'
     * );
     *
     * Then (key_index, count_total_subs) = (0, 5 = count('A', 'D', 'G', 'I', 'J')),
     * (1, 3 = count('B', 'E', 'H')),
     * (2, 2 = count('C', 'F')),
     * (>= 3, 0) // the maximum index in each data_array is less than 3
     *
     *
     * If data_set is
     * array( array(array('A', 'B'), 'C'),
     * array(array('D'), 'E', 'F'),
     * array('G', 'H'),
     * array('I'),
     * 'J'
     * );
     *
     * Then (key_index, count_total_subs) = (0, 6 = count('A', 'B') + count('D') + ('G', 'I', 'J')),
     * (1, 3 = count('C', 'E', 'H')),
     * (2, 1 = count('F')),
     * (>= 3, 0) // the maximum index in each data_array is still less than 3
     */
    
    public function count_total_subs($data_set, $key_index)
    {
        if (!$data_set || !is_array($data_set)) return 0;
        
        $total_subs = 0;
        
        foreach ($data_set as $data_array) {
            if (isset($data_array[$key_index])) $total_subs += (is_array($data_array[$key_index]) ? count($data_array[$key_index]) : 1);
        }
        
        return $total_subs;
    }
    /*
     * Converts array('hours' => 2, 'minutes' => 30) to string '2h 30m'
     * Keys 'hours' or 'minutes' can be omitted.
     * If both are omitted, an empty string is returned.
     */
    
    public function duration_to_string($duration)
    {
        if (!$duration || !is_array($duration)) return '';
        
        $duration_string = '';
        
        if ($duration['hours']) $duration_string = sprintf('%02d', $duration['hours']).'h';
        
        if (isset($duration['minutes'])) $duration_string .= ($duration_string ? ' ' : '').sprintf('%02d', $duration['minutes']).'m';
        
        return $duration_string;
    }
    /*
     * Converts a numeric value expressing a unit of time in minutes to array('hours' => ..., 'minutes' => ...)
     */
    
    public function mins_to_duration($minutes)
    {
        $duration = array(
            'hours' => 0,
            'minutes' => 0
        );
        
        if ($minutes >= 60) {
            $duration['hours'] = floor($minutes / 60);
            
            $duration['minutes'] = floor($minutes - ($duration['hours'] * 60));
        } else {
            $duration['minutes'] = $minutes;
        }
        
        return $duration;
    }
    /*
     * Normalizes a node name according to the following format: a_b_c...
     */
    
    public function normalize_node_name($node_name)
    {
        $name = preg_replace_callback("/^([A-Z]+)([A-Z][a-z])/", function ($matches) {
            return strtolower($matches[1]).'_'.strtolower($matches[2]);
        }, $node_name);
            $name = preg_replace_callback("/(?<=[a-z])([A-Z][a-z])/", function ($matches) {
                return '_'.strtolower($matches[1]);
            }, $name);
                $name = preg_replace_callback("/^([A-Z][^A-Z]+)([A-Z].+)?/", function ($matches) {
                    return strtolower($matches[1]).(count($matches) > 2 ? '_'.strtolower($matches[2]) : '');
                }, $name);
                    return preg_replace_callback("/^([A-Z]{2,3}_[A-Z][a-z]+)/", function ($matches) {
                        return strtolower($matches[1]);
                    }, $name);
    }
    /*
     * Fills ref using the data contained in dom_node (DOM Node).
     */
    
    public function fetch_node_info($dom_node, &$ref, $normalize_names = true)
    {
        if (!$dom_node->hasAttributes() && $dom_node->hasChildNodes() && $dom_node->childNodes->length == 1 && $dom_node->childNodes->item(0)->nodeType == XML_TEXT_NODE) {
            $ref = $dom_node->nodeValue;
        } else {
            if ($dom_node->hasAttributes()) {
                foreach ($dom_node->attributes as $attribute_node) {
                    if ($attribute_node->name == $dom_node->localName && !$ref) {
                        $ref = $attribute_node->value;
                    } else {
                        $ref[($normalize_names ? $this->normalize_node_name($attribute_node->name) : $attribute_node->name)] = $attribute_node->value;
                    }
                }
            }
            
            if ($dom_node->hasChildNodes()) {
                if ($dom_node->childNodes->length == 1 && $dom_node->childNodes->item(0)->nodeType == XML_TEXT_NODE) {
                    $ref['value'] = $dom_node->nodeValue;
                } else {
                    $sameLocalNames     = false;
                    $previous_node_name = null;
                    
                    foreach ($dom_node->childNodes as $childNode) {
                        $tmp_node_name = ($normalize_names ? $this->normalize_node_name($childNode->localName) : $childNode->localName);
                        if ($previous_node_name && $previous_node_name == $tmp_node_name) {
                            $sameLocalNames = true;
                            
                            break;
                        }
                        
                        $previous_node_name = $tmp_node_name;
                    }
                    
                    foreach ($dom_node->childNodes as $childNode) {
                        $tmp_node_name = ($normalize_names ? $this->normalize_node_name($childNode->localName) : $childNode->localName);
                        
                        if (!$sameLocalNames) {
                            if (!isset($ref[$tmp_node_name])) {
                                $ref[$tmp_node_name] = array();
                            } else if (!is_array($ref[$tmp_node_name])) {
                                $ref[$tmp_node_name]          = array();
                                $ref[$tmp_node_name]['value'] = $ref[$tmp_node_name];
                            }
                        }
                        
                        $tmp_ref = &$ref[$tmp_node_name];
                        if ($sameLocalNames) {
                            // $ref[] = array();
                            
                            $tmp_ref = &$ref[count($ref) - 1];
                            
                            unset($ref[$tmp_node_name]);
                        }
                        
                        $this->fetch_node_info($childNode, $tmp_ref, $normalize_names);
                    }
                }
            }
        }
    }
    
    public function xmlString2domXPath($xml_string)
    {
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        
        $libxml_parse_constants = LIBXML_XINCLUDE | LIBXML_NOBLANKS | LIBXML_NSCLEAN;
        
        if (defined('LIBXML_COMPACT')) // available in libxml >= 2.6.21
            $libxml_parse_constants |= LIBXML_COMPACT;
            
            if (defined('LIBXML_PARSEHUGE')) $libxml_parse_constants |= LIBXML_PARSEHUGE;
            
            $doc->loadXML($xml_string, $libxml_parse_constants);
            
            libxml_use_internal_errors(false);
            
            $xpath = new \DOMXPath($doc);
            try {
                $simpleXMLElement      = new \SimpleXMLElement($xml_string, $libxml_parse_constants);
                $namespace_definitions = $simpleXMLElement->getNamespaces(true);
            } catch (\Exception $e) {
                $namespace_definitions = 0;
            }
            
            if (!$namespace_definitions) {
                $namespace_definitions = array(
                    'SOAP-ENV' => 'http://schemas.xmlsoap.org/soap/envelope/',
                    'wsse' => 'http://schemas.xmlsoap.org/ws/2002/12/secext',
                    'eb' => 'http://www.ebxml.org/namespaces/messageHeader',
                    'xmlns' => 'http://www.opentravel.org/OTA/2002/11',
                    'xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
                );
            }
            
            foreach ($namespace_definitions as $namespace => $namespace_url) {
                if (!$namespace) $namespace = 'xmlns';
                $xpath->registerNamespace($namespace, $namespace_url);
            }
            
            return $xpath;
    }
    
    public function send_data($url, $data = null, $request_method = \HTTP_Request2::METHOD_GET, $credentials = array('auth_method' => 'none', 'username' => null,
        'password' => null), $additional_headers = null, $connection_config = array())
    {
        // This function requires more optimization, more on this later...
        if ($credentials && !isset($credentials['auth_method'])) $credentials['auth_method'] = 'none';
        
        if (!$url || (isset($credentials['auth_method']) && !in_array(strtolower($credentials['auth_method']), array(
            'none',
            'basic',
            'digest'
        )))) return null;
        
        $defaults = array(
            'auth_method' => 'none',
            'username' => null,
            'password' => ''
        );
        
        if (!$credentials || !is_array($credentials)) $credentials = array();
        
        $credentials = array_merge($defaults, $credentials);
        
        if ($credentials['auth_method'] != 'none' && !isset($credentials['username']) && (!$additional_headers || !array_key_exists('Authorization', $additional_headers))) return null;
        
        $request = new \HTTP_Request2();
        $request->setURL($url);
        $request->setAdapter('curl');
        
        $use_cookies = false;
        if (isset($connection_config['use_cookies'])) {
            $use_cookies = $connection_config['use_cookies'];
            
            unset($connection_config['use_cookies']);
        }
        
        if ($use_cookies && !$request->getCookieJar()) {
            $request->setCookieJar(true);
            $request->getCookieJar()->serializeSessionCookies(true);
        }
        
        $config_set = array(
            'follow_redirects' => true,
            'protocol_version' => '1.1',
            'connect_timeout' => 10, // Connection timeout in seconds
            'timeout' => 30 // Total number of seconds a request can take; Use 0 for no limit. Should be greater than connect_timeout
        );
        
        if ($connection_config) {
            // check the following page for possible connection config parameters:: http://pear.php.net/manual/en/package.http.http-request2.config.php
            // alternatively:: https://pear.php.net/package/HTTP_Request2/docs/latest/HTTP_Request2/HTTP_Request2.html#methodsetConfig
            
            foreach ($connection_config as $config_param => $config_value) {
                if (substr($config_param, 0, 4) == 'ssl_') continue;
                
                $config_set[$config_param] = $config_value;
            }
        }
        
        if (substr($url, 0, 5) == 'https') {
            if ($connection_config) {
                foreach ($connection_config as $config_param => $config_value) {
                    if (substr($config_param, 0, 4) != 'ssl_') continue;
                    
                    $config_set[$config_param] = $config_value;
                }
            }
            
            $ssl_config = array(
                'ssl_verify_peer' => false,
                'ssl_verify_host' => false
            ); // ssl_cafile, ssl_capath, ssl_local_cert, ssl_passphrase
            
            $config_set = array_merge($ssl_config, $config_set);
        }
        
        $unknown_config_params = array();
        
        // $request->setConfig($config_set); // this throws an exception in case one of the parameters is unknown. In that case, the whole set of parameters will be discarded, we don't want that!
        if ($config_set) {
            foreach ($config_set as $config_param => $config_value) {
                try {
                    $request->setConfig($config_param, $config_value);
                } catch (\HTTP_Request2_Exception $config_exception) {
                    $unknown_config_params[$config_param] = $config_value;
                }
            }
        }
        
        if ($request_method == \HTTP_Request2::METHOD_POST) $request->setMethod($request_method);
        // $request->setMethod($request_method)->setHeader('Content-Type: application/x-www-form-urlencoded');
        
        if ($data && !is_array($data)) {
            try {
                $request->setBody($data);
            } catch (\HTTP_Request2_LogicException $le) {
                // echo "<br/><br/>Error:: " . $le->getMessage() . "<br/><br/>";
            }
        }
        
        if (in_array(strtolower($credentials['auth_method']), array(
            'basic',
            'digest'
        )) && (!$additional_headers || ($additional_headers && !array_key_exists('Authorization', $additional_headers)))) {
            $request->setAuth($credentials['username'], $credentials['password'], (strtolower($credentials['auth_method']) == 'basic' ? \HTTP_Request2::AUTH_BASIC : \HTTP_Request2::AUTH_DIGEST));
        }
        
        if ($additional_headers && is_array($additional_headers)) {
            foreach ($additional_headers as $header_name => $header_value) {
                $request->setHeader($header_name, $header_value);
            }
            
            if ($use_cookies) {
                $cookies = $request->getCookieJar()->getAll();
                
                if (isset($cookies['expires'])) $cookies['expires'] = time() + (60 * 10);
            }
        }
        
        // $data is expected to be of the form: array(name => value, name => value, name => array(name => value, name => value), name => value)
        if ($data && is_array($data)) {
            if ($request_method == \HTTP_Request2::METHOD_GET) {
                $http_url = $request->getUrl();
                $http_url->setQueryVariables($data);
            } else if ($request_method == \HTTP_Request2::METHOD_POST) {
                $request->addPostParameter($data);
                
                /*
                 * foreach ($data as $data_key => $data_value)
                 * {
                 * $request->addPostParameter($data_key, $data_value);
                 * }
                 */
            }
        }
        
        $response_error   = RESPONSE_SUCCESS;
        $response_status  = 0;
        $response_text    = '';
        $response_headers = array();
        $response_cookies = array();
        
        $response = null;
        
        try {
            $response = $request->send();
            
            $response_text    = $response->getBody();
            $response_headers = $response->getHeader();
            
            if ($use_cookies) $response_cookies = $response->getCookies();
            
            $response_status = $response->getStatus();
            
            if ($use_cookies) {
                if ($response_status == 200) {
                    // $request->getCookieJar()->addCookiesFromResponse($response, $request->getURL());
                    
                    if (!$request->getCookieJar() && $response_cookies) {
                        foreach ($response_cookies as $cookie_data) {
                            foreach ($cookie_data as $cookie_name => $cookie_value) {
                                $request->addCookie($cookie_name, $cookie_value);
                            }
                        }
                    }
                } else {
                    $response_error = RESPONSE_ERROR;
                }
            }
        } catch (\HTTP_Request2_Exception $e) {
            $response_text  = $e->getMessage()."<br/><br/>Trace:: ".$e->getTraceAsString();
            $response_error = RESPONSE_ERROR;
        }
        
        $response_array = array(
            'response_error' => $response_error,
            'response_status' => $response_status,
            'request_method' => $request_method,
            'url' => $request->getUrl()->getURL(),
            'request_headers' => $request->getHeaders(),
            'response_text' => $response_text,
            'response_headers' => $response_headers
        );
        
        if ($use_cookies) $response_array['response_cookies'] = $response_cookies;
        
        $response_array['connection_config_params'] = $request->getConfig();
        
        if ($unknown_config_params) $response_array['unknown_config_params'] = $unknown_config_params;
        
        if ($response != null && $response_error == RESPONSE_ERROR) {
            $response_array['reason_phrase'] = $response->getReasonPhrase();
        }
        
        if ($request_method == \HTTP_Request2::METHOD_GET) {
            $response_array['query_variables']  = $request->getUrl()->getQueryVariables();
            $response_array['protocol_version'] = $request->getConfig('protocol_version');
        } else if ($request_method == \HTTP_Request2::METHOD_POST) {
            $response_array['request_body'] = $request->getBody();
        }
        
        return $response_array;
    }
    
    public function hash_equals($str1, $str2)
    {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i --) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
    
    public function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        
        $ip = $remote;
        
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        }
        
        return $ip;
    }
    
    public function decToDMS($dec)
    {
        
        // Converts decimal longitude / latitude to DMS
        // ( Degrees / minutes / seconds )
        // This is the piece of code which may appear to
        // be inefficient, but to avoid issues with floating
        // point math we extract the integer part and the float
        // part by using a string function.
        $vars   = explode(".", $dec);
        $deg    = $vars[0];
        $tempma = "0.".$vars[1];
        
        $tempma = $tempma * 3600;
        $min    = floor($tempma / 60);
        $sec    = $tempma - ($min * 60);
        
        return html_entity_decode("{$deg}&deg;{$min}'{$sec}\"");
    }

    public function safeXML($text)
    {
        $res = str_replace("&", "&amp;", $text);
        $res = strip_tags($res);
        $res = trim($res);
        return $res;
    }
    
    public function encloseWithArray($array)
    {
        // CURL does not conform with WSDL format, so even if the element is set as 0..n,
        // 1 element: $array = array('grandchild1' => '123', 'grandchild2' => 'qwe')
        // n element: $array = array(0 => ('grandchild1' => '123', 'grandchild2' => 'qwe'))
        // we will standardize the array to accommodate n format
        $array = (array) $array;
        if (count(array_filter(array_keys($array), 'is_string')) > 0) {
            return array(
                $array
            );
        } else {
            return $array;
        }
    }
    
    public function randomString($n_chars, $options = null)
    {
        $default_options = array(
            'chars' => array(),
            'use_letters' => true,
            'use_digits' => true
        );
        
        $effective_options = $default_options;
        if ($options && is_array($options)) $effective_options = array_merge($default_options, $options);
        
        if ($effective_options['use_digits']) $effective_options['chars'] = array_merge($effective_options['chars'], range(0, 9));
        
        if ($effective_options['use_letters']) {
            $effective_options['chars'] = array_merge($effective_options['chars'], range('A', 'Z'));
            
            $effective_options['chars'] = array_merge($effective_options['chars'], range('a', 'z'));
        }
        
        if (!$effective_options['chars']) return '';
        
        shuffle($effective_options['chars']);
        
        $randomString = '';
        
        for ($char_index = 0; $char_index < $n_chars; $char_index ++) {
            $randomString .= $effective_options['chars'][mt_rand(0, count($effective_options['chars']) - 1)];
        }
        
        return $randomString;
    }
    
    public function get_key_set($data_array, &$key_set, $options = null)
    {
        if (!$data_array || !is_array($data_array)) return;
        
        $default_options = array(
            'key_to_child_elements' => null,
            'sort_keys' => false,
            'sort_flag' => SORT_STRING,
            'associative_result' => false,
            'keys_from_field' => null,
            'values_from_key' => null
        );
        
        $effective_options = $default_options;
        if ($options && is_array($options)) $effective_options = array_merge($default_options, $options);
        
        foreach (array_keys($data_array) as $array_key) {
            $tmp_key = $array_key;
            
            if ($effective_options['keys_from_field']) $tmp_key = $data_array[$array_key][$effective_options['keys_from_field']];
            
            if ($effective_options['associative_result'] && $effective_options['values_from_key'] && isset($data_array[$array_key][$effective_options['values_from_key']]))
                $key_set[$tmp_key] = $data_array[$array_key][$effective_options['values_from_key']];
                else if ($effective_options['keys_from_field']) $key_set[$tmp_key] = $tmp_key;
                else $key_set[]         = $tmp_key;
                
                if ($effective_options['key_to_child_elements'] && isset($data_array[$array_key][$effective_options['key_to_child_elements']]) && $data_array[$array_key][$effective_options['key_to_child_elements']])
                    $this->get_key_set($data_array[$array_key][$effective_options['key_to_child_elements']], $key_set, $effective_options);
        }
        
        if ($effective_options['sort_keys']) {
            /*
             * if ($effective_options['associative_result'] && $effective_options['values_from_key'])
             * ksort($key_set, $effective_options['sort_flag']);
             * else
             * asort($key_set, $effective_options['sort_flag']);
             */
             
             if ($effective_options['sort_flag'] == SORT_NUMERIC) asort($key_set, SORT_NUMERIC);
             else natcasesort($key_set);
        }
    }
    
    public function concatenate_values($values, $options = null)
    {
        if (!$values) return '';
        
        $concatenated_value = '';
        
        if (!$options || !is_array($options)) {
            foreach ($values as $value) {
                $concatenated_value .= $value;
            }
            
            return $concatenated_value;
        }
        
        if (isset($options['delimiter']) || (isset($options['delimiter_specs']) && $options['delimiter_specs'])) {
            if (isset($options['delimiter']) && $options['delimiter'] == null) $options['delimiter'] = '';
            
            if (isset($options['delimiter_specs']) && $options['delimiter_specs'] && !isset($options['delimiter'])) $options['delimiter'] = '';
            
            if (isset($options['delimiter_specs']) && $options['delimiter_specs']) $options['associative_values'] = true;
            
            if (isset($options['associative_values']) && $options['associative_values']) {
                foreach ($values as $field_name => $field_value) {
                    $current_start_delimiter = ($concatenated_value ? $options['delimiter'] : '');
                    $current_end_delimiter   = '';
                    
                    if (isset($options['delimiter_specs']) && isset($options['delimiter_specs'][$field_name])) {
                        $current_field_specs = $options['delimiter_specs'][$field_name];
                        
                        if (isset($current_field_specs['start_delimiter'])) {
                            if (isset($current_field_specs['prepend_global_delimiter']) && $current_field_specs['prepend_global_delimiter'])
                                $current_start_delimiter .= $current_field_specs['start_delimiter'];
                                else $current_start_delimiter = $current_field_specs['start_delimiter'];
                        }
                        
                        if (isset($current_field_specs['end_delimiter'])) $current_end_delimiter = $current_field_specs['end_delimiter'];
                    }
                    
                    $concatenated_value .= $current_start_delimiter.trim($field_value, $options['delimiter']).$current_end_delimiter;
                }
            } else {
                foreach ($values as $indx => $value) {
                    $concatenated_value .= ($indx ? $options['delimiter'].trim($value, $options['delimiter']) : $value);
                }
            }
        }
        
        return $concatenated_value;
    }
    
    public function copy_array_value(&$data_array, $source_field, $destination_field, $options = null)
    {
        if (!isset($data_array[$source_field])) return;
        
        $default_options   = array(
            'copy_by_reference' => true
        );
        $effective_options = $default_options;
        if ($options) $effective_options = array_merge($default_options, $options);
        
        if ($effective_options['copy_by_reference']) $data_array[$destination_field] = &$data_array[$source_field];
        else $data_array[$destination_field] = $data_array[$source_field];
    }
    
    public function move_array_value(&$data_array, $source_field, $destination_field)
    {
        if (!isset($data_array[$source_field])) return;
        
        $data_array[$destination_field] = $data_array[$source_field];
        unset($data_array[$source_field]);
    }
    
    private function get_key_to_child_elements($original_key_to_child_elements, $field_conversion_specs = null)
    {
        if (!$field_conversion_specs || !is_array($field_conversion_specs)) return $original_key_to_child_elements;
        
        $modifier_functions = array(
            'copy_array_value' => array(
                'source_field_index' => 1,
                'destination_field_index' => 2,
                'options' => array(
                    'field_index' => 3,
                    'copy_by_reference' => false
                )
            ),
            'move_array_value' => array(
                'source_field_index' => 1,
                'destination_field_index' => 2
            )
        );
        
        $key_to_child_elements = $original_key_to_child_elements;
        
        foreach ($field_conversion_specs as $field_specs) {
            if (!in_array($field_specs['function_name'], array_keys($modifier_functions)) || !isset($field_specs['arguments']) || !is_array($field_specs['arguments'])) continue;
            
            $modifier_function = $modifier_functions[$field_specs['function_name']];
            
            if (isset($field_specs['arguments'][$modifier_function['source_field_index']]) && $field_specs['arguments'][$modifier_function['source_field_index']] == $original_key_to_child_elements) {
                if (isset($modifier_function['options'])) {
                    $matched_option_values = true;
                    
                    if (!isset($field_specs['arguments'][$modifier_function['options']['field_index']])) {
                        $matched_options_values = false;
                    } else {
                        foreach ($modifier_function['options'] as $option_name => $forced_value) {
                            if ($option_name == 'field_index') continue;
                            
                            if (!in_array($option_name, $field_specs['arguments'][$modifier_function['options']['field_index']]) || $field_specs['arguments'][$modifier_function['options']['field_index']][$option_name]
                                != $forced_value) {
                                    $matched_option_values = false;
                                    
                                    break;
                                }
                        }
                    }
                    
                    if (!$matched_option_values) break;
                }
                
                $key_to_child_elements = $field_specs['arguments'][$modifier_function['destination_field_index']];
                
                break;
            }
        }
        
        return $key_to_child_elements;
    }
    
    public function convert_fields(&$data_array, $options = null)
    {
        if (!$data_array || !is_array($data_array)) return $data_array;
        
        $default_options   = array(
            'modify_reference' => true,
            'key_to_child_elements' => null
        );
        $effective_options = $default_options;
        if ($options && is_array($options)) $effective_options = array_merge($default_options, $options);
        
        $data = &$data_array;
        if (!$effective_options['modify_reference']) $data = $data_array;
        
        foreach (array_keys($data) as $data_key) {
            $key_to_child_elements = $effective_options['key_to_child_elements'];
            
            if ($effective_options['field_conversion_specs'] && is_array($effective_options['field_conversion_specs'])) {
                $key_to_child_elements = $this->get_key_to_child_elements($effective_options['key_to_child_elements'], $effective_options['field_conversion_specs']);
                
                foreach ($effective_options['field_conversion_specs'] as $field_specs) {
                    $function_arguments = array();
                    
                    if (isset($field_specs['arguments'])) {
                        if (!is_array($field_specs['arguments'])) $field_specs['arguments'] = array(
                            $field_specs['arguments']
                        );
                    }
                    
                    if (isset($field_specs['forced_literals']) && !is_array($field_specs['forced_literals'])) {
                        $field_specs['forced_literals'] = array(
                            $field_specs['forced_literals']
                        );
                    }
                    
                    foreach ($field_specs['arguments'] as $key => $argument) {
                        if ($argument == 'data_array') {
                            $function_arguments[$key] = &$data[$data_key];
                            
                            continue;
                        }
                        
                        if (is_array($argument)) {
                            $dummy_argument = (isset($argument['dummy_argument']) && $argument['dummy_argument']);
                            unset($argument['dummy_argument']);
                            
                            $tmp_array = array();
                            
                            if ($dummy_argument) {
                                $tmp_array = $argument;
                            } else {
                                foreach ($argument as $sub_key => $sub_argument) {
                                    if (isset($data[$data_key][$sub_argument]) && (!isset($field_specs['forced_literals']) || !in_array($sub_argument, $field_specs['forced_literals'])))
                                        $tmp_array[$sub_key] = $data[$data_key][$sub_argument];
                                        else $tmp_array[$sub_key] = $sub_argument; // literal value
                                }
                            }
                            
                            $function_arguments[$key] = $tmp_array;
                        } else if (isset($data[$data_key][$argument]) && (!isset($field_specs['forced_literals']) || !in_array($argument, $field_specs['forced_literals'])))
                            $function_arguments[$key] = $data[$data_key][$argument];
                            else $function_arguments[$key] = $argument; // literal value
                    }
                    
                    if (method_exists($this, $field_specs['function_name'])) {
                        if (isset($field_specs['field_name']))
                            $data[$data_key][$field_specs['field_name']] = call_user_func_array(array(
                                $this,
                                $field_specs['function_name']
                            ), $function_arguments);
                            else
                                call_user_func_array(array(
                                    $this,
                                    $field_specs['function_name']
                                ), $function_arguments);
                    } else if (function_exists($field_specs['function_name'])) {
                        if (isset($field_specs['field_name'])) $data[$data_key][$field_specs['field_name']] = call_user_func_array($field_specs['function_name'], $function_arguments);
                        else call_user_func_array($field_specs['function_name'], $function_arguments);
                    }
                }
            }
            
            if ($key_to_child_elements && isset($data[$data_key][$key_to_child_elements]) && $data[$data_key][$key_to_child_elements]) {
                if ($effective_options['modify_reference']) $this->convert_fields($data[$data_key][$key_to_child_elements], $effective_options);
                else $data[$data_key][$key_to_child_elements] = $this->convert_fields($data[$data_key][$key_to_child_elements], $effective_options);
            }
        }
        
        if (!$effective_options['modify_reference']) return $data;
    }
    
    public function flatten_array($data_array)
    {
        if (!$data_array) return '';
        
        $flattened_array = '';
        
        foreach ($data_array as $data_key => $data_value) {
            $flattened_array .= ($flattened_array ? ', ' : '').$data_key.':: ';
            
            if (is_array($data_value)) {
                $flattened_array .= $this->flatten_array($data_value);
            } else {
                $flattened_array .= $data_value;
            }
        }
        
        return $flattened_array;
    }

    public function formatDateAsString($in_ts)
    {
        $time_min   = intval((time() - $in_ts) / 60);
        $time_hours = intval($time_min / 60);
        $time_days  = intval($time_hours / 24);
        $time_week  = round($time_days / 7);
        $time_month = round($time_days / 30);
        $time_yeari = intval($time_month / 12);
        $time_year  = round($time_month / 12, 1);

        $time  = '';
        $time2 = '';
        //        $this->translator = $this->get('translator');
        if ($time_min <= 1) {
            $which_msg = $this->translator->trans('1 minute ago');
        } else if ($time_min < 60) {
            $time      = $time_min;
            $which_msg = $this->translator->transChoice('1 minute ago|%minutes% minutes ago', $time, array('%minutes%' => $time));
        } else if ($time_hours >= 1 && $time_hours < 24) {
            $which_msg = $this->translator->transChoice('1 hour ago|%hours% hours ago', $time_hours, array("%hours%" => $time_hours));
        } else if ($time_hours < 48) {
            $time3     = date('Y-m-d h:i A', $in_ts);
            $time2     = $this->returnSocialTimeFormat($time3, 2);
            $which_msg = $this->translator->trans('yesterday at %time%', array("%time%" => $time2));
        } else if ($time_days < 7) {
            $time      = $time_days;
            $time3     = date('Y-m-d h:i A', $in_ts);
            $time2     = $this->returnSocialTimeFormat($time3, 2);
            $which_msg = $this->translator->transChoice('1 day ago|%days% days ago', $time, array('%days%' => $time));
        } else if ($time_week >= 1 && $time_week < 5) {
            $time      = $time_week;
            $which_msg = $this->translator->transChoice('1 week ago|%weeks% weeks ago', $time, array('%weeks%' => $time));
        } else if ($time_month >= 1 && $time_month <= 12) {
            $time      = $time_month;
            $which_msg = $this->translator->transChoice('1 month ago|%months% months ago', $time, array('%months%' => $time));
        } else if ($time_yeari >= 1) {
            $time      = $time_yeari;
            $which_msg = $this->translator->transChoice('1 year ago|%years% years ago', $time, array('%years%' => $time));
        }
        return $which_msg;
    }

    public function returnSocialTimeFormat($time_date, $_case = 1)
    {
        global $request;
        $timezone_cookie = $request->cookies->get('timezone', '');
        switch ($_case) {
            case 1://Feb dd, YYYY at 7:50 PM OR AM
                //           if (!isset($_COOKIE['timezone'])) {
                if (!isset($timezone_cookie)) {
                    $timeDate = strftime("%b %d, %Y", strtotime($time_date)).$this->translator->trans(' at ').strftime('%I:%M', strtotime($time_date));
                } else {
                    $ts       = new \DateTime($time_date, new \DateTimeZone('GMT'));
                    $ts->add(\DateInterval::createFromDateString($timezone_cookie.' minutes'));
                    $timeDate = $ts->format("M d, Y").$this->translator->trans(' at ').$ts->format("h:i A");
                }
                break;
            case 2://7:50 PM OR AM
                //           if (!isset($_COOKIE['timezone'])) {
                if (!isset($timezone_cookie)) {
                    $timeDate = date('h:i A', strtotime($time_date));
                } else {
                    $ts       = new \DateTime($time_date, new \DateTimeZone('GMT'));
                    $ts->add(\DateInterval::createFromDateString($timezone_cookie.' minutes'));
                    $timeDate = $ts->format("h:i A");
                }
                break;
            case 3://Feb dd, YYYY
                //           if (!isset($_COOKIE['timezone'])) {
                if (!isset($timezone_cookie)) {
                    $timeDate = strftime('%b %d, %Y', strtotime($time_date));
                } else {
                    $ts       = new \DateTime($time_date, new \DateTimeZone('GMT'));
                    $ts->add(\DateInterval::createFromDateString($timezone_cookie.' minutes'));
                    $timeDate = $ts->format("M d, Y");
                }
                break;
            case 4://YYYY-mm-dd
                //           if (!isset($_COOKIE['timezone'])) {
                if (!isset($timezone_cookie)) {
                    $timeDate = strftime('Y-m-d', strtotime($time_date));
                } else {
                    $ts       = new \DateTime($time_date, new \DateTimeZone('GMT'));
                    $ts->add(\DateInterval::createFromDateString($timezone_cookie.' minutes'));
                    $timeDate = $ts->format("Y-m-d");
                }
                break;
            case 5://dd-mm-YYYY
                //           if (!isset($_COOKIE['timezone'])) {
                if (!isset($timezone_cookie)) {
                    $timeDate = strftime('d-m-Y', strtotime($time_date));
                } else {
                    $ts       = new \DateTime($time_date, new \DateTimeZone('GMT'));
                    $ts->add(\DateInterval::createFromDateString($timezone_cookie.' minutes'));
                    $timeDate = $ts->format("d-m-Y");
                }
                break;
            case 6://March dd, YYYY
                //           if (!isset($_COOKIE['timezone'])) {
                if (!isset($timezone_cookie)) {
                    $timeDate = strftime('%B %d, %Y', strtotime($time_date));
                } else {
                    $ts       = new \DateTime($time_date, new \DateTimeZone('GMT'));
                    $ts->add(\DateInterval::createFromDateString($timezone_cookie.' minutes'));
                    $timeDate = $ts->format("F d, Y");
                }
                break;
            default://Feb dd, YYYY at 7:50 PM OR AM
                //            if (!isset($_COOKIE['timezone'])) {
                if (!isset($timezone_cookie)) {
                    $timeDate = strftime("%b %d, %Y", strtotime($time_date)).$this->translator->trans(' at ').strftime('%I:%M', strtotime($time_date));
                } else {
                    $ts       = new \DateTime($time_date, new \DateTimeZone('GMT'));
                    $ts->add(\DateInterval::createFromDateString($timezone_cookie.' minutes'));
                    $timeDate = $ts->format("M d, Y").$this->translator->trans(' at ').$ts->format("h:i A");
                }
                break;
        }
        return $timeDate;
    }
    
    public function seconds_to_time_string($seconds)
    {
        $dtF = new \DateTime();
        $dtT = new \DateTime("@$seconds");
        
        // return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes, and %s seconds');
        
        $date_interval = $dtF->diff($dtT);
        
        $breakdown_values = array();
        
        $dateItervalProperties = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second'
        );
        
        foreach ($dateItervalProperties as $designator => $dateIntervalProperty) {
            if ($date_interval->$designator) $breakdown_values[] = array(
                $designator => $date_interval->$designator
            );
        }
        
        if (!$breakdown_values) return '';
        
        $time_string = '';
        $last_index  = count($breakdown_values) - 1;
        
        foreach ($breakdown_values as $indx => $time_info) {
            $designator = array_keys($time_info)[0];
            
            $time_string .= ($indx ? ', ' : '').($indx && $indx == $last_index ? 'and ' : '').$time_info[$designator].' '.$dateItervalProperties[$designator].($time_info[$designator] > 1 ? 's' : '');
        }
        
        return $time_string;
    }
    
    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    // *****************************************************************************************
    // Dates Functions
    /**
     * This calculates days to date.
     *
     * @param \DateTime $date
     * @return String Returns result like '1 year 1 month 1 day'
     */
    public function calculateDaysToDate($date)
    {
        $today = date_create('now', $date->getTimezone());
        $diff  = date_diff($today, $date);
        $diff  = $diff->format("%y %m %d");
        $diff  = explode(' ', $diff);
        
        $str = (($diff[0]) ? "{$diff[0]} year".(($diff[0] > 1) ? 's' : '') : '');
        $str .= (($diff[1]) ? " {$diff[1]} month".(($diff[1] > 1) ? 's' : '') : '');
        $str .= (($diff[2]) ? " {$diff[2]} day".(($diff[2] > 1) ? 's' : '') : '');
        
        return trim($str);
    }
    
    /**
     * This method computes for the number of nights between two dates.
     *
     * @param Mixed $from       date from
     * @param Mixed $to         date to
     * @return Integer number of nights
     */
    public function computeNights($from, $to)
    {
        if (!$from || !$to) return 0;
        
        if ($from instanceof \DateTime && $to instanceof \DateTime) {
            $interval = $from->diff($to);
        } else {
            $interval = date_diff(date_create($from), date_create($to));
        }
        return $interval->format('%a');
    }
    
    /**
     * This method formats a date.
     *
     * @param Mixed $date   	The date.
     * @param String $format    The format.
     * @return formatted date.
     */
    public function formatDate($date, $format = '')
    {
        // $date = (is_int($date)) ? $date : strtotime($date);
        switch ($format) {
            case 'militaryTime':
                $format = 'H:i';
                break;
            case 'collapsed':
                $format = 'd/m/Y';
                break;
            case 'short':
                $format = 'd / m / Y';
            case 'long':
                $format = 'l, j F Y'; //Saturday, 10 April 2019
            case 'long2':
                $format = 'D, M j Y'; //Sat, Apr 10 2019
                break;
            case 'longDateTime':
                $format = 'j F Y h:i A';
                break;
            case 'datetime':
                $format = 'd/m/Y h:i A';
                break;
            case 'short':
            default:
                $format = 'Y-m-d';
                break;
        }
        
        if (is_int($date)) {
            $date = date($format, $date);
        } elseif ($date instanceof \DateTime) {
            $date = $date->format($format);
        } else {
            $date = date_create($date);
            $date = date_format($date, $format);
        }
        return $date;
    }
    
    /**
     * This method formats our response to JSON.
     *
     * @param Mixed $data
     * @return Response
     */
    public function createJSONResponse($data)
    {
        if (!empty($data)) {
            // make sure our array are utf8 encoded.
            array_walk_recursive($data, function(&$value) {
                if(is_string($value)){
                    $value = preg_replace('/\s{2,}/m', ' ', $value);
                    $value = mb_convert_encoding($value, "UTF-8");
                }
            });
        } else {
            $data = '';
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * This method returns a list of credit cards.
     *
     * @param Mixed $creditCards
     * @param Boolean $isRest
     * @return Array    List of credit cards.
     */
    public function getCCDetails($creditCards, $isRest = false)
    {
        $ccDetails = array();
        $cards     = (array) $creditCards;
        
        if (empty($cards)) {
            $cards = array(
                'AX',
                'BC',
                'CUP-Debit',
                'DC',
                'DS',
                'JC',
                'CA',
                'VI',
                'AT',
                'CB',
                'DL',
                'DN',
                'DR',
                'DV',
                'EC',
                'IK',
                'MC'
            );
        }
        
        foreach ($cards as $cc) {
            switch ($cc) {
                case 'AMEX':
                case 'AX':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'American Express',
                    'image' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/americanexpress.png', null, $isRest)
                        );
                    break;
                case 'BC':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'BC Card',
                    'image' => ''
                        );
                    break;
                case 'CUP-Debit':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'China UnionPay - Debit',
                    'image' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/cup.png', null, $isRest)
                        );
                    break;
                case 'DINERS':
                case 'DC':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'Diners Club',
                    'image' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/diners.png', null, $isRest)
                        );
                    break;
                case 'DS':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'Discover',
                    'image' => ''
                        );
                    break;
                case 'JCB Int':
                case 'JC':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'Japan Credit Bureau',
                    'image' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/jcb.png', null, $isRest)
                        );
                    break;
                case 'MASTER':
                case 'CA':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'Euro/Mastercard',
                    'image' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/mastercard.png', null, $isRest)
                        );
                    break;
                case 'VISA':
                case 'VI':
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => 'Visa',
                    'image' => $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/visa.png', null, $isRest)
                        );
                    break;
                default:
                    // AT, CB, DL, DN, DR, DV, EC, IK, MC
                    $ccDetails[] = array(
                    'code' => $cc,
                    'name' => $cc,
                    'image' => ''
                        );
            }
        }
        
        if (!is_array($creditCards)) {
            $ccDetails = $ccDetails[0];
        }
        
        return $ccDetails;
    }
    
    /**
     * This method returns credit card validity options.
     *
     * @return Array The credit card validity options.
     */
    public function getCCValidityOptions()
    {
        $dateData = getdate();
        
        $ccValidityOptions['currentMonth'] = $dateData['mon'];
        $ccValidityOptions['currentYear']  = $dateData['year'];
        
        $ccValidityOptions['monthList'] = array();
        
        for ($m = 1; $m <= 12; $m++) {
            $ccValidityOptions['monthList'][$m] = date('F', mktime(0, 0, 0, $m, 1, $ccValidityOptions['currentYear']));
        }
        return $ccValidityOptions;
    }
    
    /**
     * This method converts a given number to kilometers.
     *
     * @param decimal   $number The number to convert
     * @param string    $fromUnit The unit to convert from as per OTA naming convention
     * @return decimal  $number The converted number
     */
    public function convertToKilometers($number, $fromUnit)
    {
        switch ($fromUnit) {
            case 'Kilometers':
                break;
            case 'Miles':
                $number *= 1.60934;
                break;
            case 'Meters':
                $number *= .001;
                break;
            case 'Millimeters':
                $number /= 1000000;
                break;
            case 'Centimeters':
                $number /= 100000;
                break;
            case 'Yards':
                $number *= 0.0009144;
                break;
            case 'Feet':
                $number *= 0.0003048;
                break;
            case 'Inches':
                $number /= 39370.1;
                break;
        }
        
        return number_format($number, 2);
    }
    
    /**
     * This method converts a given area number from Sqft(Square foot) to Sqm(Square meters)
     *
     * @param integer   $numberToConvert    The number to convert
     * @return integer                      The converted number
     */
    public function convertSqftToSqm($numberToConvert)
    {
        return intval($numberToConvert * 0.092903);
    }
    
    /**
     * Tells whether we're on the corporate portal or not
     *
     */
    public function isCorporateSite()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $host    = $request->server->get('HTTP_HOST', '');
        
        $isCorporate = ((substr($host, 0, 9) == 'corporate') || $request->attributes->get('isRestCorporate') == 'true');
        
        return $isCorporate;
    }
    
    /**
     * Performs a multi-byte safe substr() operation based on number of characters. 
     */
    public function getMultiByteSubstr( $text, $count, $count2, $lang, $add_dots=true )
    {
        if( $count2==NULL )
        {
            $count2 = $count;
        }
        if ( $lang == 'in' || $lang == 'cn')
        {
            if (strlen($text) > $count2)
            {
                if( $add_dots )
                {
                    $text = mb_substr($text, 0, ($count2-3) ).' ...';
                }else{
                    $text = mb_substr($text, 0, $count2 );
                }
            }

        } else {            
            if (strlen($text) > $count)
            {
                if( $add_dots )
                {
                    $count = $count-3;
                }

                if (preg_match("/^.{1,$count}\b/s", $text, $match))
                {
                    $text = $match[0];
                }
                
                if( $add_dots )
                {
                    $text .= ' ...';
                }
            }
        }
        return $text;
    }

    public function cut_sentence_length($sentence, $max_chars, $line_width = 100)
    {
        $out        = str_replace('  ', ' ', $sentence);
        $left_chars = $max_chars;
        $orig       = $out;
        $out        = substr($orig, 0, $left_chars);
        $left_chars -= $line_width * substr_count($out, '<br/>');
        $left_chars -= $line_width * substr_count($out, '<br>');
        if ($left_chars < 0) {
            //too many new lines just take till the first new line.
            $first_new_line_pos = min(array(strpos($out, '<br/>'), strpos($out, '<br>')));
            $out                = substr($out, 0, $first_new_line_pos);
            $out                .= ' ...';
            return $out;
        }
        if (strlen($orig) > $left_chars) {
            $out      = substr($orig, 0, $left_chars);
            $desc_arr = explode(' ', $out);
            if (strstr($out, ' '.$desc_arr[count($desc_arr) - 1].' ') == null) unset($desc_arr[count($desc_arr) - 1]);
            $out      = implode(' ', $desc_arr);
            $out      .= ' ...';
        }
        return $out;
    }
    
    /**
     * Function that gets the Default image to display in twig
     *
     * @param params
     * set index isThumbnail to use customized size for the image where you can also
     * set the height and width by setting the params imgWidth and imgHeight
     * If no imgWidth and imgHeight are set where isThumbnail is set the method will return thumbnail of sizes 255, 148 set as default
     *
     *
     * @return path
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    public function generateDefaultDealImage($params = array())
    {
        $category = $this->cleanTitleData($params['categoryName']);
        $category = str_replace('+', '-', $category);
        $city     = $this->cleanTitleData($params['cityName']);
        $city     = str_replace('+', '-', $city);
        
        $sourcename = $category."-".$city."-".$params['imageId'].".jpg";
        $sourcepath = "media/deals/".$params['apiId']."/".$params['dealCode']."/";
        
        if (isset($params['isThumbnail']) && $params['isThumbnail']) {
            $imgWidth  = isset($params['imgWidth']) ? $params['imgWidth'] : 255;
            $imgHeight = isset($params['imgHeight']) ? $params['imgHeight'] : 148;
            
            $dealImagePath = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, $imgWidth, $imgHeight, 'deals'.$imgWidth.$imgHeight, $sourcepath, $sourcepath, 65);
        } else {
            $dealImagePath = $this->container->get("TTRouteUtils")->generateMediaURL("/".$sourcepath.$sourcename);
        }
        
        
        $dir = $this->container->getParameter('CONFIG_SERVER_ROOT');
        if ($this->container->get("TTFileUtils")->fileExists($dir.$dealImagePath)) {
            return $dealImagePath;
        } else {
            $defaultImage = $this->container->get("TTRouteUtils")->generateMediaURL("/media/images/deals/default.jpg");
            return $defaultImage;
        }
    }
    
    /**
     * This method returns the default hotel image that can be displayed in a twig directly via an AppExtension method. If isThumbnail is set and imgWidth/imgHeight
     * are sent, those sizes sent will be taken into consideration. If isThumbnail is set, but imgWidth and imgHeight are not sent, a default thumbnail size will be used. If isThumbnail is
     * not set, original image path will be returned.
     *
     * @param params Array(dupePoolId, filename, location, isThumbnail, imgWidth, imgHeight)
     *
     * @return The image path
     */
    public function getDefaultHotelImage($params = array())
    {
        $dir        = $this->container->getParameter('CONFIG_SERVER_ROOT');
        $sourcepath = 'media/images/';
        
        $dupePoolId = $params['dupePoolId'];
        $sourcename = $params['filename'];
        $location   = $params['location'];
        
        if (!empty($location)) {
            $img        = '/media/hotels/g'.$dupePoolId.'/'.$location.'/'.$sourcename;
            $sourcepath = 'media/hotels/g'.$dupePoolId.'/'.$location.'/';
        } else {
            $img        = '/media/hotels/g'.$dupePoolId.'/'.$sourcename;
            $sourcepath = 'media/hotels/g'.$dupePoolId.'/';
        }
        
        if (isset($params['isThumbnail']) && $params['isThumbnail']) {
            $imgWidth  = isset($params['imgWidth']) ? $params['imgWidth'] : 284;
            $imgHeight = isset($params['imgHeight']) ? $params['imgHeight'] : 162;
            $imagePath = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, $imgWidth, $imgHeight, 'hotels-'.$imgWidth.$imgHeight, $sourcepath, $sourcepath, 65);
        } else {
            $imagePath = $this->container->get("TTRouteUtils")->generateMediaURL($img);
        }
        
        if ($this->container->get("TTFileUtils")->fileExists($dir.$img)) {
            return $imagePath;
        } else {
            $defaultImage = $this->container->get("TTRouteUtils")->generateMediaURL("/media/images/hotel-icon-image2.jpg");
            return $defaultImage;
        }
    }
    
    /**
     * this is created to add Log to the Flight, thi is a very usefull way to debbug a problem, and track the flight action
     * @param String $message
     * @param array $params
     * @param boolean $cleanParams
     */
    public function addFlightLog($message, $params = array()) // , $cleanParams = false)
    {
        if (!isset($params) || !is_array($params)) $params = array();
        
        if ($params) {
            /*
             if ($cleanParams)
             {
             foreach (array_keys($params) as $param_key)
             {
             if ($param_key == 'userId')
             continue;
             
             $this->cleanParams($params[$param_key]);
             }
             }
             */
             
             foreach (array_keys($params) as $param_key) {
                 if ($param_key == 'userId') continue;
                 
                 $params[$param_key] = json_encode($params[$param_key]);
             }
        }
        
        if (!isset($params['userId'])) $params['userId'] = 0;
        
        $logger = $this->container->get('monolog.logger.flights');
        $logger->info("User {userId} - ".$message, $params);
    }
    
    /**
     * Encode a string to URL-safe base-64
     * Original source:: http://googlemaps.github.io/url-signing/UrlSigner.php-source
     *
     * @param value The value to encode
     *
     * @return base64-encoded value with character replacement to ensure we get a safe URL.
     */
    function encodeBase64UrlSafe($value)
    {
        return str_replace(array('+', '/'), array('-', '_'), base64_encode($value));
    }
    
    /**
     * Decode a string from URL-safe base-64; Decode a string encoded by function encodeBase64UrlSafe
     * Original source:: http://googlemaps.github.io/url-signing/UrlSigner.php-source
     *
     * @param value The value to decode
     *
     * @return base-64 decoded value of a string with character replacement to ensure we get a safe URL.
     */
    function decodeBase64UrlSafe($value)
    {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $value));
    }
    
    /**
     * Sign a URL with a given crypto key.
     * Note that this URL must be properly URL-encoded.
     * Original source:: http://googlemaps.github.io/url-signing/UrlSigner.php-source
     * The original code was modified to support URL anchors (see url_components['fragment'])
     *
     * @param value The value to decode
     *
     * @return base-64 decoded value of a string with character replacement to ensure we get a safe URL.
     */
    function signUrl($originalURL, $privateKey)
    {
        $url_components = parse_url($originalURL);
        
        $urlPartToSign = $url_components['path'].'?'.$url_components['query'];
        
        // Decode the private key into its binary format
        $decodedKey = $this->decodeBase64UrlSafe($privateKey);
        
        // Create a signature using the private key and the URL-encoded string using HMAC SHA1. This signature will be binary.
        $signature = hash_hmac("sha1", $urlPartToSign, $decodedKey, true);
        
        $encodedSignature = $this->encodeBase64UrlSafe($signature);
        
        return $originalURL."&signature=".$encodedSignature.(isset($url_components['fragment']) ? '#'.$url_components['fragment'] : '');
    }

    public function validate_alphanumeric_underscore($str)
    {
        return preg_match('/^[a-zA-Z0-9.\-@_]+$/', $str);
    }
    
    public function check_email_address($email) {
        // First, we check that there's one @ symbol,
        // and that the lengths are right.
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            // Email invalid because wrong number of characters
            // in one section or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if
            (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
    â†ª'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
                return false;
            }
        }
        // Check if domain is IP. If not,
        // it should be valid domain name
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if
                (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
    â†ª([A-Za-z0-9]+))$", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Convert array to object
     * @param array $array
     * @param object instance of class
     */
    public function array_to_obj($array, &$obj)
    {
        foreach ($array as $key => $value) {
            if(property_exists($obj, $key)) {
                if (is_array($value)) {
                    $obj->$key = new \stdClass();
                    $this->array_to_obj($value, $obj->$key);
                }
                else {
                    $setter = "set" . ucfirst($key);
                    if (method_exists($obj, $setter)) {
                        $obj->$setter($value);
                    } else {
                        /**check if $key is accessible public
                         * private - skip
                         * public - set value
                         * get $obj accessible properties
                         */
                        $properties = get_object_vars($obj);
                        if (array_key_exists($key, $properties)) {
                            $obj->$key = $value;
                        }
                    }
                }
            }
        }
        return $obj;
    }

    /**
     * convert array to object used for datatable
     */
    public function prepareDatatableObj($result_arr)
    {
        $result_obj = new \stdClass();
        foreach ($result_arr as $key => $value)
		{
			$result_obj->$key = $value;
		}

		return $result_obj;
    }
    
    public function highlightSearchStr($str, $term)
    {
        return preg_replace(array('/'.$term.'/i'), '<b>'.$term.'</b>', $str);
    }
}
