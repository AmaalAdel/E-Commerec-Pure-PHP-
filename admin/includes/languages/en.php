<?php
function lang( $phrase ){
    static $lang = array(

        'Home Admin'    => 'Home' ,
        'CATEGORIES'    => 'Categories',
        'ITEMS'         => 'Items',
        'MEMBERS'       => 'Members',
        'STATISTICS'    => 'Statistics',
        'Comments' =>'Comments',
        'LOGS' => 'Logs',
        
    );
    return $lang[$phrase];
}