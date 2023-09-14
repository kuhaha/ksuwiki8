<?php
// PukiWiki - Yet another WikiWikiWeb clone
// aname.inc.php
// Copyright
//   2002-2020 PukiWiki Development Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// timed plugin - show/hide content according to specified schedule 

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
	if (func_num_args() < 1)
		return plugin_timed_feedback();

	return plugin_timed_validate(func_get_args());
}

// Timed plugin itself
function plugin_timed_validate($args = array())
{
	global $vars;
	if (count($args)<2 || $args[0] == '' || $args[1] == '') {
		return plugin_timed_feedback();
	}
	$args = array_map('_trim', $args);
	$body = '';
	$_format = 'Y-m-d H:i:s';
	$_current = date($_format);
	$_since = $args[0]; 
	$_until = $args[1];  
	$_hide  = count($args)>=3 and$args[2]=='hide';
	$t = date_create_immutable($_current);
	$since = date_create_immutable($_since);
	$until = date_create_immutable($_until);
	$was_allowed = '&#128586;You are currently allowed to view this page!';
	$not_allowed = '&#128584;You are currently not allwed to view this page!';
	if ($_hide){
		$body .= ($since<=$t and $t<=$until) ? $not_allowed : $was_allowed; 
	}else{
		$body .= ($since<=$t and $t<=$until) ? $was_allowed : $not_allowed; 
	}	
	return '<div style="color:green;font-size:18pt;">' . $body . '</div>';
}

function _trim($string){
	return trim(trim($string),"'\""); 
}
