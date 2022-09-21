<?php 

        if(ifItIsMethod('post')){
          if(isset($_POST['login'])) {
          if(isset($_POST['username']) && isset($_POST['password'])){

                login_user($_POST['username'], $_POST['password']);

            } else {

                redirect('../mycms');
            }
        }
    } 

?>

<div class="col-md-4">


                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Blog Search</h4>

                    <form action="search.php" method="post">
                    <div class="input-group">
                        <input name="search" type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" name="submit" type="submit">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                    </div>
                    </form>  <!-- search form -->
                    <!-- /.input-group -->
                </div>


                <!-- Login -->
                <div class="well">

                    <?php if(isset($_SESSION['user_role'])):  ?>

                        <h4>Logged in as <?php echo $_SESSION['username']; ?></h4>

                        <a href="includes/logout.php" class="btn btn-primary">Logout</a>
                        <?php else: ?>

                            <h4>Login</h4>

                    <form action="" method="post">
                    <div class="form-group">
                        <input placeholder="Enter Username" name="username" type="text" class="form-control">
                       
                    </div>

                    <h4>Password</h4>

                    <div class="input-group">
                        <input placeholder="Enter Password" name="password" type="password" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-primary" name="login" type="submit">Submit
                          </button>
                      </span>
                        
                    </div>
                        <div class="form-group">

                        <a href="forgot.php?forgot=<?php echo uniqid(true); ?>">Forgot password?</a>

                        </div>


                    </form>  <!-- search form -->

                    <?php endif; ?>

                    
                    <!-- /.input-group -->
                </div>



                <!-- Blog Categories Well -->
                <div class="well">


                <?php

                $query = "SELECT * FROM categories";
                $select_all_categories_sidebar = mysqli_query($connection, $query);

               
                ?>
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-unstyled">

                            <?php

                            while($row = mysqli_fetch_assoc($select_all_categories_sidebar)) {
                                $cat_title = $row['cat_title'];
                                $cat_id = $row['cat_id'];

                              
                                echo "<li><a href='category.php?category=$cat_id'>{$cat_title}</a></li>";
                            }

                            ?>
                              
                            </ul>
                        </div>
                        <!-- /.col-lg-6 -->
                        <!-- <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div> -->
                        <!-- /.col-lg-6 -->
                    </div>
                    <!-- /.row -->
                </div>


                <!-- Side Widget Well -->
                <?php include "includes/widget.php"; ?>

            </div>