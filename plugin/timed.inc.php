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
function plugin_timed_usage($message = '')
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
		return plugin_timed_usage();

	return plugin_timed_tag(func_get_args());
}

// Timed plugin itself
function plugin_timed_tag($args = array())
{
	global $vars;
	if (empty($args) || $args[0] == '') return plugin_aname_usage();

	$body = '';
	$_format = 'Y-m-d H:i:s';
	$_current = date($_format);
	$_future = date($_format, mktime(0,0,0,1,1,date('Y')+ 10));
	$_since = _get('since', $args, $_current); 
	$_until = _get('unti', $args, $_future);  
	$_show  = ! in_array('hide', $args); 

	$t_current = date_create_immutable($_current);
	$t_since = date_create_immutable($_since);
	$t_until = date_create_immutable($_until);
	//DEBUG
	// echo $t_current->format($_format), PHP_EOL;
	// echo $t_since->format($_format), PHP_EOL;
	// echo $t_until->format($_format), PHP_EOL;

	$valid1 = $_current and $_since and ($_current >= $_since);
	$valid2 = $_current and $_until and ($_current <= $_until);
	if ($valid1 and $valid2 and !$_show){
		return 'Forbidden to view this page!';
	}else{
		return 'Allowed to view this page';
	}


}

function _get($option, $args, $default=null){
	if (isset($args[$option])){
		return $args[$option];
	}else{
		return $default;
	}
}