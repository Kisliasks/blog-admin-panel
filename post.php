<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

    <!-- Navigation -->
    

    <?php include "includes/navigation.php"; ?>

    <?php  


    if(isset($_POST['liked'])) {
        
      $post_id = $_POST['post_id'];
      $user_id = $_POST['user_id'];

        // SELECT POST
        $postResult = mysqli_query($connection, "SELECT * FROM posts WHERE post_id = $post_id");
        
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];


        // UPDATE POST WITH LIKES

        $result_like = mysqli_query($connection, "UPDATE posts SET likes = ($likes + 1) WHERE post_id = $post_id");

        if(!$result_like) {
            die("QUERY FAILED". mysqli_error($connection));
        }

        // CREATE LIKES FOR POST

        mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
        exit();
    }

    if(isset($_POST['unliked'])) {
        
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
  
          // SELECT POST
          $postResult = mysqli_query($connection, "SELECT * FROM posts WHERE post_id = $post_id");
          
          $post = mysqli_fetch_array($postResult);
          $likes = $post['likes'];
  
  
          // DELETE LIKES
  
          $result_like = mysqli_query($connection, "UPDATE posts SET likes = ($likes - 1) WHERE post_id = $post_id");
  
          if(!$result_like) {
              die("QUERY FAILED". mysqli_error($connection));
          }
  
          // DELETE USERS IN LIKES TABLE
  
          mysqli_query($connection, "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id");
          exit();
      }

    ?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            <?php

            if(isset($_GET['p_id'])){

             $the_post_id = $_GET['p_id'];

             $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id ";
             $send_query = mysqli_query($connection, $view_query);

             if(!$send_query) {
                 die("QUERY FAILED". mysqli_error($connection));
             }


             if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                $query = "SELECT * FROM posts WHERE post_id = '$the_post_id' "; 


             } else {
                $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'Published' ";
             }

            
                
                $select_all_posts_query = mysqli_query($connection, $query);
                if(!$select_all_posts_query) {
                    die('QUERY FAILED'.mysqli_error($connection));
                }

                if(mysqli_num_rows($select_all_posts_query) < 1 ) {
                    echo "<h1 class='text-center'>NO POSTS</h1>";
                } else {

                while($row = mysqli_fetch_assoc($select_all_posts_query)) {
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];
                                    
                
            ?>




                <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                </h2>
                <p class="lead">
                    by <a href="index.php"><?php echo $post_author; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date; ?></p>
                <hr>
                <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                <hr>
                <p><?php echo $post_content; ?></p>
                <!-- <a class="btn btn-primary" href="post.php?p_id=">Read More <span class="glyphicon glyphicon-chevron-right"></span></a> -->

                <hr>

<?php  if(isLoggedIn()) { ?>



                <div class='row'>
                    <p class='pull-right'><a class='<?php echo userLikedThisPost($post_id) ? 'unlike' : 'like'; ?>' href=""><span class="glyphicon glyphicon-thumbs-up"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="<?php echo userLikedThisPost($post_id) ? 'I liked this before' : 'Want to like it?'; ?>"
                    ></span>
                    <?php echo userLikedThisPost($post_id) ? 'Unlike' : 'Like'; ?></a></p>
                </div>

<?php } else {  ?>

                <div class='row'>
                <p class='pull-right'>Тебе нужен логин, Малек, чтобы оставлять лайки<a href="mycms/login">Login</a></p>
                </div>


 <?php } ?>      
                

                <div class='row'>
                    <p class='pull-right likes'>Likes: <?php getPostLikes($post_id); ?></p>
                </div>

                <div class="clearfix"></div>

                <?php } }
                
            
            } else {
                header("Location: index.php");
            }
                              
                ?>



                <!-- Blog Comments -->

                <?php
                if(isset($_SESSION['username'])) {
                    if(isset($_POST['create_comment'])) {
                        
                        $the_post_id = $_GET['p_id'];

                        $comment_author = $_POST['comment_author'];
                        $comment_email = $_POST['comment_email'];
                        $comment_content = $_POST['comment_content'];
                        
                        if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                        $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) VALUES ('{$the_post_id}', '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";

                        $query_result = mysqli_query($connection, $query);
                        if(!$query_result) {
                            die("QUERY FAILED". mysqli_error($connection));
                        }
                      
    // $query = "UPDATE posts SET post_comment_count = {post_comment_count + 1} WHERE post_id = $the_post_id ";
    // $update_comment_count = mysqli_query($connection, $query);
    // if(!$update_comment_count) {
    //     die("QUERY FAILED eeee". mysqli_error($connection));
    // }



                        } else {
                            echo "<script>alert('НЕ БАЛУЙСЯ. ВВЕДИ КОММЕНТАРИЙ И ПРОЧИЕ ДАННЫЕ, МАЛЕК.')</script>";
                        }
                    }
                
                 } ?>

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form role="form" action="" method="post">

                    <div class="form-group">
                        <label for="Author">Author</label>
                           <input type="text" class="form-control" name="comment_author">
                        </div>
                        <div class="form-group">
                            <label for="Email">Email</label>
                           <input type="email" class="form-control" name="comment_email">
                        </div>
                        

                        <div class="form-group">
                            <label for="Comment">Input your comment</label>
                            <textarea name="comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
                    <?php

                    $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                    $query .= "AND comment_status = 'approve' ";
                    $query .= "ORDER BY comment_id DESC ";
                    $select_comment_query = mysqli_query($connection, $query);
                    if(!$select_comment_query) {
                        die("Query failed". mysqli_error($connection));
                    }

                    while($row = mysqli_fetch_assoc($select_comment_query)) {
                        $comment_date = $row['comment_date'];
                        $comment_content = $row['comment_content'];
                        $comment_author = $row['comment_author'];
                    

                    ?>
                <!-- Comment -->
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author; ?>
                            <small><?php echo $comment_date;   ?></small>
                        </h4>
                        <?php echo $comment_content;?>
                    </div>
                </div>

                <?php }   ?>    
                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        
        <?php include "includes/footer.php"; ?>

        <script>

            $(document).ready(function(){

                $("[data-toggle='tooltip']").tooltip();

                var post_id = <?php echo $_GET['p_id']; ?>;
                var user_id = <?php echo loggedInUserId(); ?>;

                // Liking
                $('.like').click(function(){


                 $.ajax({

                    url: "post.php?p_id=<?php echo $_GET['p_id']; ?>",
                    type: 'post',
                    data: {
                        'liked': 1,
                        'post_id': post_id,
                        'user_id': user_id
                         
                    }

                 });
                });

                // Unlike
                    $('.unlike').click(function(){

                   

                    $.ajax({

                    url: "post.php?p_id=<?php echo $_GET['p_id']; ?>",
                    type: 'post',
                    data: {
                        'unliked': 1,
                        'post_id': post_id,
                        'user_id': user_id
                        
                    }

                    });
                    });

            });



        </script>