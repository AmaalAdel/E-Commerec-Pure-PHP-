<?php
include "connect.php";

$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$func = 'includes/functions/';
$css = 'layout/css/';
$js='layout/js/';

include $func.'functions.php';
include $lang.'en.php';
include $tpl.'header.php';

if(!isset($noNavbar)){
    include $tpl.'navbar.php';
}

