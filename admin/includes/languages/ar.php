<?php
function lang( $phrase ){
    static $lang = array(

        'Home Admin' => 'منطقة المسئول ' ,
        'CATEGORIES' => '',
        'ITEMS' => '',
        'MEMBERS' => '',
        'STATISTICS' => '',
        'Comments' =>'',
        'LOGS' => '',
    );
    return $lang[$phrase];
}