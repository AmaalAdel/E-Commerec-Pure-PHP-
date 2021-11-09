<?php
ob_start();
session_start();
if(isset($_SESSION['Username'])){
    $pageTitle = 'Dashboard';
    include 'init.php';

    $numUsers = 4;
    $latestUsers = getLatest('*','users', 'UserID',$numUsers);

    $numItems = 6;
    $latestItems= getLatest('*','items','Item_ID',$numItems);

    $numComments = 4;

?>
<div class="home-stats">
    <div class="container text-center">
        <h1> Dashboard </h1>
        <div class="row">

            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                   <div class="info">
                    Total Member
                        <span>
                            <a href="members.php"> <?php echo countItem('UserID' , 'users') ?> </a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-pendings">
                <i class="fa fa-user-plus"></i>
                    <div class="info">
                        Pending Member
                    <span><a href="members.php?do=Mange&page=pending">
                            <?php echo checkItem ("RegStatus" , "users" , 0) ?>
                        </a> </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                    Total Items
                    <span><a href="items.php"> <?php echo countItem('Item_ID' , 'items') ?> </a></span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                        Total Comments
                        <span>
                           <a href="comments.php"> <?php echo countItem('c_id' , 'comments') ?> </a> 
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

    <div class="latest"></div>
        <div class="container ">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-user"></i> Latest <?php echo $numUsers ?> Registerd Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">

                            <ul class="list-unstyled latest-users">
                                <?php
                                if(! empty($latestUsers)){
                                foreach ($latestUsers as $user) {
                                        echo "<li>";
                                            echo $user['Username'] ;
                                                echo "<a href='members.php?do=Edit&userid=".$user["UserID"]."'> ";
                                                    echo "<span class='btn btn-success pull-right'>";
                                                         echo "<i class='fa fa-edit'></i> Edit";
                                                            if ($user['RegStatus'] == 0) {
                                                                echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . " ' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Activate </a>";
                                                            }
                                                    echo "</span>";
                                                echo "</a>";
                                        echo"</li>";
                                }
                                }else{
                                    echo "there is No Members To Show";
                                }
                                ?>
                            </ul>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems ?> Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                    <?php
                                        if(! empty($latestItems)){
                                        foreach ($latestItems as $item) {
                                                echo "<li>";
                                                    echo $item['Name'] ;
                                                        echo "<a href='items.php?do=Edit&itemid=".$item["Item_ID"]."'> ";
                                                            echo "<span class='btn btn-success pull-right'>";
                                                                echo "<i class='fa fa-edit'></i> Edit";
                                                                    if ($item['Approve'] == 0) {
                                                                        echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . " ' class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Approve </a>";
                                                                    }
                                                            echo "</span>";
                                                        echo "</a>";
                                                echo"</li>";
                                        }
                                        }else{
                                            echo "there is No Items To Show";
                                        }
                                    ?>
                                </ul>
                        </div>
                    </div>
                </div>
            </div>

        <!-- start latest comment -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i> Latest <?php echo $numComments ?> Comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">

                        <?php

                            $stmt = $conn ->prepare("SELECT
                                                             comments.* , users.Username AS Member
                                                     FROM
                                                             comments
                                                     INNER JOIN 
                                                             users
                                                     ON
                                                             users.UserID = comments.user_id
                                                     ORDER BY
                                                             c_id DESC
                                                     LIMIT $numComments ");

                                            $stmt ->execute();
                                            $comments = $stmt -> fetchAll();
                                            if(!empty($comments)){
                                            foreach($comments as $comment){
                                                echo "<div class = 'comment-box'>";
                                                echo "<span class='member-n'>
                                                <a href='members.php?do=Edit&userid=".$comment['user_id']."'>". $comment['Member'] . "</a></span>";
                                                echo "<p class='member-c'>". $comment['comment'] . "</p>";
                                                echo "</div>";
                                                }
                                            }else{
                                                echo "There is No Comments To Show";
                                            }
                        ?>


                        </div>
                    </div>
                </div>
 

            </div>

            <!-- End latest comment -->

        </div>
    </div>

<?php
    include $tpl.'footer.php';
}else{
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>