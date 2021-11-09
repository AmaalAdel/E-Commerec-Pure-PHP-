<?php
$do = isset($_GET['do']) ?  $_GET['do'] : 'Mange';
/*$do='';
if(isset($_GET['do'])){

    $do = $_GET['do'];
}else{

    $do = 'Mange';
} */

if($do == 'Mange'){
    echo "You are at the Mange page";
    echo '<a href="page.php?do=Add">Add new Category +</a>';
}
elseif ($do == "Add"){
    echo "Welcome You are at the Add gategory page";
}elseif ($do == 'Insert') {

    echo "Welcome You are at the Insert gategory page";
}else{
    echo "There\'s No page with this name";
}