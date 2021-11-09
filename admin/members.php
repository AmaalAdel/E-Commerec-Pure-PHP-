<?php
session_start();
$pageTitle = "Members";
if(isset($_SESSION['Username'])){
    include 'init.php';
    $do = isset($_GET['do']) ?  $_GET['do'] : 'Mange';

    if($do=='Mange'){

        $query ='';
        if(isset($_GET['page']) && $_GET['page']=='pending'){
            $query = "AND RegStatus =0";
        }

        $stmt = $conn ->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");

        $stmt ->execute();
        $rows = $stmt -> fetchAll();

        if(!empty($rows)){
        ?>

        <h1 class="text-center">Mange Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table mange-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Register Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['UserID'] . "</td>";
                        echo "<td>";
                        if(empty($row['avatar'])){
                            echo "No image" ;
                        }else{
                            ECHO "<img src='uploads/avatars/" . $row['avatar'] . "' alt = '' />";
                        }
                        echo "</td>";
                        echo "<td>" . $row['Username'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>" . $row['Date'] . "</td>";

                        echo "<td>
                               <a href='members.php?do=Edit&userid=" . $row['UserID'] . " ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                               <a href='members.php?do=Delete&userid=" . $row['UserID'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";

                        if ($row['RegStatus'] == 0) {
                            echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . " ' class='btn btn-info activate'><i class='fa fa-check'></i> Activate </a>";
                        }


                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>

        <?php }else{
            echo "<div class='container'>";
            echo"<div class='alert alert-info'> There is No Members To Show </div>";
            echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
            echo "</div>";
        } ?>
     <?php
          }elseif ($do == 'Add'){?>

        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

                <!-- start Username field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name = "username"  class="form-control"  autocomplete="off" required="required" placeholder="Username To login Into Shop"/>
                    </div>
                </div>
                <!-- End Username field -->

                <!-- start password field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-4">

                    <input type="password" name = "password" class="password form-control" autocomplete="new-password" required="required"  placeholder="Password Must be hard and Complex"/>
                    <i class="show-pass fa fa-eye fa-2x"></i>
                </div>
        </div>
        <!-- End password field -->

        <!-- start Email field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10 col-md-4">
            <input type="email" name = "email"  class="form-control" required="required" placeholder="Email Must be Valid" />
        </div>
        </div>
        <!-- End Email field -->

        <!-- start Fullname field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Full Name</label>
            <div class="col-sm-10 col-md-4">
            <input type="text" name = "full"  class="form-control" required="required" placeholder="Full Name appear in your profile Page" />
        </div>
        </div>
        <!-- End Fullname field -->

        <!-- start Avatar field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">User Avatar</label>
            <div class="col-sm-10 col-md-4">
            <input type="file" name = "avatar"  class="form-control" required="required"  />
        </div>
        </div>
        <!-- End Avatar field -->

        <!-- start submit field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value = "Add Member" class="btn btn-primary btn-lg" />
            </div>
        </div>
        <!-- End submit field -->

        </form>

        </div>

  <?php
    }elseif ($do == 'Insert'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert Member</h1>";
            echo "<div class = 'container'>";

            //Upload variables
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];
            //list of allowed file type to upload

            $avatarAllowedExtension = array("jpeg","jpg","png","gif");

            $avatarExtension = strtolower(end(explode('.',$avatarName)));


            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            $hashpass= sha1($_POST['password']);
            $formErrors = array();
            if (strlen($user) < 3) {
                $formErrors[] = "User cant be less than <strong>3 letters</strong>";
            }
            if (strlen($user) > 20) {
                $formErrors[] = "User cant be more than <strong>20 letters</strong>";
            }
            if (empty($user)) {
                $formErrors[] = "User name cant be <strong>empty</strong>";
            }
            if (empty($pass)) {
                $formErrors[] = "Password cant be <strong>empty</strong>";
            }
            if (empty($email)) {
                $formErrors[] = "Email cant be <strong>empty</strong> ";
            }
            if (empty($name)) {
                $formErrors[] = "Full name cant be <strong>empty</strong>";
            }
            if(!empty($avatarName) && !in_array($avatarExtension,$avatarAllowedExtension)){

                $formErrors[]="This Extension is not <strong > Allowes </strong>";
            }
            if(empty($avatarName)){
                $formErrors[]="Avatar is  <strong > Required </strong>";
            }
            if($avatarSize > 4194304){
                $formErrors[]="Avatar cant be larger than  <strong > 4MB </strong>";
            }

            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>".$error."</div>";
            }


            if (empty($formErrors)) {
                $avatar = rand(0,100000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp,'uploads\avatars\\'.$avatar);

                $check = checkItem("Username", "users", $user);
                if ($check == 1) {

                    $theMsg = "<div class='alert alert-danger'>Sorry This User is exist</div>";
                    redirectHome($theMsg,'back');
                } else {

                    $stmt = $conn->prepare('INSERT INTO
                                                    users(Username , Password, Email, FullName ,RegStatus ,Date , avatar)
                                                    VALUES (:zuser , :zpass , :zmail , :zname ,1 , now(),:zavatar)');
                    $stmt->execute(array(
                        'zuser'   => $user,
                        'zpass'   => $hashpass,
                        'zmail'   => $email,
                        'zname'   => $name,
                        'zavatar' => $avatar
                    ));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                    redirectHome($theMsg,'back');
            }
            }


        } else {
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger' >Sorry you cant browse this page directly</div>";
            redirectHome($theMsg);
            echo " </div>";
        }
        echo "</div>";

    }elseif ($do=='Edit'){

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid'])? intval($_GET['userid']) : 0;

        $stmt = $conn ->prepare("SELECT * FROM  Users WHERE UserID =? LIMIT 1");

        $stmt ->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0){?>

        <h1 class="text-center">Edit Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
                <input type="hidden" name="userid" value="<?php echo $userid ?>">
                <!-- start Username field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name = "username"  class="form-control" value="<?php echo $row['Username']?>" autocomplete="off" required="required"/>
                    </div>
                </div>
                <!-- End Username field -->

                <!-- start password field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-4"">
                    <input type="hidden" name = "oldpassword" value="<?php echo $row['Password']?>"/>
                        <input type="password" name = "newpassword" class="form-control" autocomplete="new-password" placeholder="Leave blank if you dont want to change"/>
                    </div>
                </div>
                <!-- End password field -->

                <!-- start Email field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-4"">
                        <input type="email" name = "email" value="<?php echo $row['Email']?>" class="form-control" required="required" />
                    </div>
                </div>
                <!-- End Email field -->

                <!-- start Fullname field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-4"">
                        <input type="text" name = "full" value="<?php echo $row['FullName']?>" class="form-control" required="required" />
                    </div>
                </div>
                <!-- End Fullname field -->

                <!-- start submit field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value = "Save" class="btn btn-primary btn-lg" "/>
                    </div>
                </div>
                <!-- End submit field -->

            </form>

        </div>
   <?php
        }else{
            echo "<div class= 'container'>";
            $theMsg = "<div class='alert alert-danger'>There Is No Such Id</div>";
            redirectHome($theMsg);
            echo "</div>";
        }
        }elseif ($do=='Update') {


            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Update Member</h1>";
                echo "<div class = 'container'>";
                $id = $_POST['userid'];
                $user = $_POST['username'];
                $email = $_POST['email'];
                $name = $_POST['full'];

                $pass = (empty($_POST['newpassword'])) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                $formErrors = array();
                if (strlen($user) < 3) {
                    $formErrors[] = "User cant be less than <strong>3 letters</strong>";
                }
                if (strlen($user) > 20) {
                    $formErrors[] = "User cant be more than <strong>20 letters</strong>";
                }
                if (empty($user)) {
                    $formErrors[] = "User name cant be<strong>empty</strong>";
                }
                if (empty($email)) {
                    $formErrors[] = "Email cant be <strong>empty</strong> ";
                }
                if (empty($name)) {
                    $formErrors[] = "Full name cant be <strong>empty</strong>";
                }
                foreach ($formErrors as $error) {
                    echo "<div class='alert alert-danger'>".$error."</div>";
                }


                if (empty($formErrors)) {
                    $stmt2 = $conn->prepare("SELECT * FROM users WHERE Username=? AND UserID != ?");
                    $stmt2->execute(array($user,$id));
                    $count = $stmt2->rowcount();
                    if($count == 1){
                        $theMsg = "<div class='alert alert-danger'> Sorry this User is exist </div>";
                        redirectHome($theMsg,'back');
                    }else{
                    $stmt = $conn->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg,'back');
                }
             }

            } else {

                echo "<div class = 'container '>";
                $theMsg = "<div class= 'alert alert-danger'>Sorry you cant browse this page directly </div>";
                redirectHome($theMsg);
                    echo "</div>";
            }
            echo "</div>";

    }elseif ($do == 'Delete'){
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class = 'container'>";
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid'])? intval($_GET['userid']) : 0;

            $check = checkItem("UserID", "users", $userid);

             if($check > 0){

                $stmt = $conn->prepare("DELETE FROM users WHERE UserID =:zuser");
                $stmt->bindParam(":zuser",$userid);
                $stmt->execute();
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
                redirectHome($theMsg,'back');

            }else{
                $theMsg= "<div class='alert alert-danger'>This ID is not exist</div>";
                redirectHome($theMsg);
            }

            echo "</div>";
    } elseif ($do == 'Activate'){
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class = 'container'>";

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid'])? intval($_GET['userid']) : 0;

        $check = checkItem("UserID", "users", $userid);
        if($check > 0){

            $stmt = $conn->prepare("UPDATE users SET RegStatus = 1 WHERE UserID =?");
            $stmt->execute(array($userid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
            redirectHome($theMsg);

        }else{
            $theMsg= "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectHome($theMsg);
        }

        echo "</div>";
    }

    include $tpl.'footer.php';
}else{
    header('Location: index.php');
    exit();
}