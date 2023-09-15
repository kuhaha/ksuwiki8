<?php
// PukiWiki - Yet another WikiWikiWeb clone
// aname.inc.php
// Copyright
//   2002-2020 PukiWiki Development Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// timed plugin - show/hide content according to specified schedule, 
// #timed(since,until[,hide])
// since, until - datetime, in format acceptable by DateTime()
// hide - if given, page will be invisible during [since, until]  
// e.g., 
// #timed('2023-9-16 12:45:12','2023-10-1 12:21:21',hide)

// Show usage
function plugin_timed_feedback($message = '')
{
	if ($message == '') {
		return '#timed(since,until[,hide])' . '<br />';
	} else {
		return '#timed: ' . $message . '<br />';
	}
}

// #timed
function plugin_timed_convert()
{
	if (func_num_args() < 2)
		return plugin_timed_feedback();

	return plugin_timed_validate(func_get_args());
}

// Timed plugin itself
function plugin_timed_validate($args = array())
{
	global $vars, $_timed_messages;
	$args = array_map('_trim', $args);
	$body = '';
	$t = date_create_immutable();
	$since = date_create_immutable($args[0]);
	$until = date_create_immutable($args[1]);
	$hide  = count($args)>=3 and $args[2]=='hide';
	if ($hide){
		$show = !($since<=$t and $t<=$until); 
	}else{
		$show = ($since<=$t and $t<=$until); 
	}
	if ($show) 
		return '<div style="color:green;font-size:16pt;">' . $_timed_messages['msg_visible'] . '</div>';
	else	
		_die('<div style="color:blue;font-size:16pt;">' . $_timed_messages['msg_invisible'] . '</div>');
	
}

function _trim($string){
	return trim(trim($string),"'\""); 
}

function _die($msg)
{
	$title = $page = 'Timed page' ;
	$body = <<<EOD
<h3>TimedPage: 時間限定公開ページ</h3>
<strong>$msg</strong>
EOD;

	pkwk_common_headers();
	if(defined('SKIN_FILE') && file_exists(SKIN_FILE) && is_readable(SKIN_FILE)) {
		catbody($title, $page, $body);
	} else {
		$charset = 'utf-8';
		if(defined('CONTENT_CHARSET')) {
			$charset = CONTENT_CHARSET;
		}
		header("Content-Type: text/html; charset=$charset");
		print <<<EOD
<!DOCTYPE html>
<html>
 <head>
  <meta http-equiv="content-type" content="text/html; charset=$charset">
  <title>$title</title>
 </head>
 <body>
 $body
 </body>
</html>
EOD;
	}
	exit;
}