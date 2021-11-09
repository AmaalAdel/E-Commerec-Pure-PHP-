<?php
ob_start();
session_start();
$pageTitle = "Categories";
if(isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Mange';

    if ($do == 'Mange') {

        $sort = 'ASC';
        $sort_array =array('ASC','DESC');
        if(isset($_GET['sort'])&& in_array($_GET['sort'],$sort_array)){
            $sort = $_GET['sort'];
        }
        $stmt2= $conn -> prepare("SELECT * FROM Categories WHERE parent = 0 ORDER BY Ordering $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll(); ?>

        <h1 class="text-center"> Mange Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i>
                    Mange Categories
                        <div class="option pull-right">
                            <i class="fa fa-sort"></i> Ordering:[
                            <a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a> |
                            <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a> ]
                            <i class="fa fa-eye"></i> View:[
                            <span class="active" data-view="full">Full</span> |
                            <span data-view="classic">Classic</span> ]
                        </div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($cats as $cat){
                        echo "<div class ='cat'>";
                            echo "<div class='hidden_buttons'>";
                                echo "<a href='categories.php?do=Edit&catid=".$cat['ID']." 'class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                echo "<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                            echo "</div>";
                            echo "<h3>". $cat['Name']."</h3>";
                                echo "<div class='full-view'>";
                                    echo  "<p>" ; if($cat['Description'] == ''){echo "This Category has no description";}else{ echo $cat['Description'];} echo "</p>";
                                    if ($cat['Visibility'] == 1){ echo '<span class="Visibility"><i class="fa fa-eye"></i> Hidden</span>';}
                                    if ($cat['Allow_Comment'] == 1){ echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
                                    if ($cat['Allow_Ads'] == 1){ echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>';}
                                
                                    $childCats = getAllFrom("*","categories","WHERE parent= {$cat['ID']}","","ID","ASC");
                                    if(!empty($childCats)){
                                    echo "<h4 class='child-head'>Child Categories</h4>";
                                    echo "<ul class = 'list-unstyled child-cats'>";
                                    foreach($childCats as $c){
                                        echo "<li class = 'child-link'>
                                        <a href='categories.php?do=Edit&catid= ".$c['ID']." ' >" .$c['Name'] . "</a>
                                        <a href='categories.php?do=Delete&catid=".$c['ID']."' class=' show-delete confirm'> Delete </a>
                                        </li>" ;
                                        }
                                    echo "</ul>";
                                    }
                                 echo "</div>";
                            echo "</div>";
                        echo "<hr>";
                    }
                    ?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
        </div>

            <?php
    }elseif ($do=='Add'){ ?>

        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">

                <!-- start Name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name = "name"  class="form-control"  autocomplete="off" required="required" placeholder="Name of the Category"/>
                    </div>
                </div>
                <!-- End Name field -->

                <!-- start Description field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4">

                    <input type="text" name = "description" class="form-control"  placeholder="Describe the Category"/>
                </div>
        </div>
        <!-- End Description field -->

        <!-- start Ordering field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Ordering</label>
            <div class="col-sm-10 col-md-4">
            <input type="text" name = "ordering"  class="form-control"  placeholder="Number to arrange the Categories" />
        </div>
        </div>
        <!-- End Ordering field -->


        <!-- start category type -->

        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Category Type</label>
            <div class="col-sm-10 col-md-4">
            <select name='parent'>
                <option value="0">None</option>
                <?php 
                $allCats = getAllFrom("*","categories","WHERE parent = 0","","ID","ASC");
                foreach($allCats as $cat){
                    echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>' ;
                }
                ?>
            </select>
        </div>
        </div>

        <!-- End category type -->

        <!-- start Visibility field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Visible</label>
            <div class="col-sm-10 col-md-4">

            <div>
                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                <label for="vis-yes">Yes</label>
            </div>
            <div>
                <input id="vis-no" type="radio" name="visibility" value="1" />
                <label for="vis-no">No</label>
            </div>

        </div>
        </div>
        <!-- End Visibility field -->

        <!-- start Commenting field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Commenting</label>
            <div class="col-sm-10 col-md-4">

            <div>
                <input id="com-yes" type="radio" name="commenting" value="0" checked />
                <label for="com-yes">Yes</label>
            </div>
            <div>
                <input id="com-no" type="radio" name="commenting" value="1" />
                <label for="com-no">No</label>
            </div>

        </div>
        </div>
        <!-- End Commenting field -->

        <!-- start Ads field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Allow Ads</label>
            <div class="col-sm-10 col-md-4">

            <div>
                <input id="Ads-yes" type="radio" name="ads" value="0" checked />
                <label for="Ads-yes">Yes</label>
            </div>
            <div>
                <input id="Ads-no" type="radio" name="ads" value="1" />
                <label for="Ads-no">No</label>
            </div>

        </div>
        </div>
        <!-- End Ads field -->

        <!-- start submit field -->
        <div class="form-group form-group-lg">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value = "Add Category" class="btn btn-primary btn-lg" "/>
            </div>
        </div>
        <!-- End submit field -->

        </form>

        </div>


<?php

    }elseif ($do=='Insert'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class = 'container'>";

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $parent = $_POST['parent'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];



                $check = checkItem("Name", "categories", $name);
                if ($check == 1) {

                    $theMsg = "<div class='alert alert-danger'>Sorry This Category is exist</div>";
                    redirectHome($theMsg,'back');
                } else {

                    $stmt = $conn->prepare('INSERT INTO
                                                    categories(Name , Description, parent,Ordering, Visibility ,	Allow_Comment ,Allow_Ads )
                                                    VALUES (:zname , :zdesc ,:zparent , :zorder , :zvisible ,:zcomment , :zads)');
                    $stmt->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zparent' => $parent,
                        'zorder' => $order,
                        'zvisible' => $visible,
                        'zcomment' => $comment,
                        'zads' => $ads
                    ));

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>';
                    redirectHome($theMsg,'back');
                }



        } else {
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger' >Sorry you cant browse this page directly</div>";
            redirectHome($theMsg,'back');
            echo " </div>";
        }
        echo "</div>";

    }elseif ($do == 'Edit'){

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']) : 0;

        $stmt = $conn ->prepare("SELECT * FROM  Categories WHERE ID =? ");

        $stmt ->execute(array($catid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0){?>


            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">

                    <input type="hidden" name="catid" value="<?php echo $catid ?>">
                    <!-- start Name field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name = "name"  class="form-control"   required="required" placeholder="Name of the Category" value="<?php echo $cat['Name'] ?>"/>
                        </div>
                    </div>
                    <!-- End Name field -->

                    <!-- start Description field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">

                        <input type="text" name = "description" class="form-control"  placeholder="Describe the Category" value="<?php echo $cat['Description'] ?>"/>
                    </div>
            </div>
            <!-- End Description field -->

            <!-- start Ordering field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Ordering</label>
                <div class="col-sm-10 col-md-4">
                <input type="text" name = "ordering"  class="form-control"  placeholder="Number to arrange the Categories" value="<?php echo $cat['Ordering'] ?>"/>
            </div>
            </div>
            <!-- End Ordering field -->

                <!-- start category type -->

            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Category Type</label>
                <div class="col-sm-10 col-md-4">
                <select name='parent'>
                    <option value="0">None</option>
                    <?php 
                    $allCats = getAllFrom("*","categories","WHERE parent = 0","","ID","ASC");
                    foreach($allCats as $c){
                        echo '<option value="'.$c['ID'].'"' ;

                        if($cat['parent'] == $c['ID']){ echo "selected ";}
                        echo " >". $c['Name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            </div>

            <!-- End category type -->


            <!-- start Visibility field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Visible</label>
                <div class="col-sm-10 col-md-4">

                <div>
                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility']==0){ echo 'checked';} ?> />
                    <label for="vis-yes">Yes</label>
                </div>
                <div>
                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility']==1){ echo 'checked';} ?> />
                    <label for="vis-no">No</label>
                </div>

            </div>
            </div>
            <!-- End Visibility field -->

            <!-- start Commenting field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Allow Commenting</label>
                <div class="col-sm-10 col-md-4">

                <div>
                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment']==0){ echo 'checked';} ?> />
                    <label for="com-yes">Yes</label>
                </div>
                <div>
                    <input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment']==1){ echo 'checked';} ?>/>
                    <label for="com-no">No</label>
                </div>

            </div>
            </div>
            <!-- End Commenting field -->

            <!-- start Ads field -->
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Allow Ads</label>
                <div class="col-sm-10 col-md-4">

                <div>
                    <input id="Ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads']==0){ echo 'checked';} ?> />
                    <label for="Ads-yes">Yes</label>
                </div>
                <div>
                    <input id="Ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads']==1){ echo 'checked';} ?> />
                    <label for="Ads-no">No</label>
                </div>

            </div>
            </div>
            <!-- End Ads field -->

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

    }elseif ($do == 'Update'){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class = 'container'>";
            $id = $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $parent = $_POST['parent'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];



                $stmt = $conn->prepare("UPDATE Categories SET Name = ? , Description = ? , Ordering = ? , parent=? , Visibility = ?  , Allow_Comment = ? , Allow_Ads= ?  WHERE ID = ?");
                $stmt->execute(array($name, $desc, $order,$parent, $visible,$comment, $ads,$id));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg,'back');


        } else {

            echo "<div class = 'container '>";
            $theMsg = "<div class= 'alert alert-danger'>Sorry you cant browse this page directly </div>";
            redirectHome($theMsg);
            echo "</div>";
        }
        echo "</div>";

    }elseif ($do == 'Delete'){

        echo "<h1 class='text-center'>Delete Category</h1>";
        echo "<div class = 'container'>";
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']) : 0;

        $check = checkItem("ID", "Categories", $catid);

        if($check > 0){

            $stmt = $conn->prepare("DELETE FROM Categories WHERE ID =:zid");
            $stmt->bindParam(":zid",$catid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>';
            redirectHome($theMsg,'back');

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
ob_end_flush();
?>