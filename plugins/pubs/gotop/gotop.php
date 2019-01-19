<?php
defined('IN_TS') or die('Access Denied.');

function gotop_html(){
	echo '<script type="text/javascript" src="'.SITE_URL.'plugins/pubs/gotop/jquery.goToTop.js"></script>';
}
function gotop_css(){
    echo '<link href="'.SITE_URL.'plugins/pubs/gotop/style.css" rel="stylesheet" type="text/css" />';
}
addAction('pub_footer', 'gotop_html');
addAction('pub_header_top', 'gotop_css');
