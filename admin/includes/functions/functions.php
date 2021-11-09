<?php

function getAllFrom($fieldId,$table,$where=NULL,$and=NULL,$orderfield,$ordering='DESC'){

    global $conn;
    $getAll = $conn->prepare("SELECT $fieldId FROM $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all= $getAll->fetchAll();
    return $all;
}

function getTitle(){
    global $pageTitle ;
   if(isset($pageTitle)){
       echo $pageTitle;
   }else{
       echo "Default";
   }
}

/*
 * Home Redirect function v2.0
 */
function redirectHome($theMsg ,$url = null, $seconds = 3){

   //  Home Redirect function v1.0
   /* echo "<div class='alert alert-danger '>$errorMsg</div>";
    echo "<div class='alert alert-info'>You will be Redirect to Home page after $seconds Seconds.</div>";
    header("Refresh: $seconds; url=index.php");
    exit(); */

    if($url === null){
        $url = 'index.php';
        $link = "Home Page";
    }else{


        if(isset($_SERVER['HTTP_REFERER'])&& !empty($_SERVER['HTTP_REFERER'])){

            $url = $_SERVER['HTTP_REFERER'];
            $link = "Previous Page";
        }else{

            $url = 'index.php';
            $link = "Home Page";
        }

    }
    echo $theMsg;
    echo "<div class='alert alert-info'>You will be Redirect to $link after $seconds Seconds.</div>";
    header("Refresh: $seconds; url=$url");
    exit();
}
/*
 * function to check item in database v1.0
 */
function checkItem ($select , $from , $value){
    global $conn ;
    $statement = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}

/*
 * count number of items function V1.0
 * function to count number of items rows
 */
function countItem($item ,$table){
    global $conn;
    $stmt2 = $conn->prepare("SELECT COUNT($item)FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}
/*
 * Get latest records Function V1.0
 * function to get latest [items,users,comments] from database
 */
function getLatest($select , $table, $order , $limit=5){
    global $conn;
    $getStat = $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStat->execute();
    $rows = $getStat->fetchAll();
    return $rows;
}