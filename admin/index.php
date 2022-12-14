<?php include "includes/admin_header.php"; ?>

    <div id="wrapper">

        <!-- Navigation -->
       
        <?php include "includes/admin_navigation.php"; ?>


        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           
                            Welcome to admin 
                            <?php echo strtoupper(get_user_name()); ?> <br>
                            <small>Role: Admin</small>
                        </h1>
                        
                    </div>
                </div>
                <!-- /.row -->

       
                <!-- /.row -->
                
                <div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-file-text fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
<?php

// $user = currentUser();
// $query = "SELECT * FROM posts WHERE post_author = '$user' ";
// $select_all_posts = mysqli_query($connection, $query);
// while($row = mysqli_fetch_assoc($select_all_posts)) {

// $post_count = mysqli_num_rows($select_all_posts);
// }

?>
                  <div class='huge'><?php echo $post_count = record_count(get_all_user_posts()) ; ?></div>
                        <div>Posts</div>
                    </div>
                </div>
            </div>
            <a href="posts.php">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
 
     <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">

                    <?php 
   
//     $query = "SELECT * FROM comments";
// $select_all_comments = mysqli_query($connection, $query);
// $row = mysqli_fetch_assoc($select_all_comments); 


//     $comments_count = mysqli_num_rows($select_all_comments);


?>
                     <div class='huge'><?php echo $comments_count = record_count(get_all_posts_user_comments()); ?></div>

                      <div>Comments</div>
                    </div>
                </div>
            </div>
            <a href="comments.php">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>


    
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">

                    <?php 

// $query = "SELECT * FROM categories";
// $select_all_categories = mysqli_query($connection, $query);
// while($row = mysqli_fetch_assoc($select_all_categories)) {

// $category_count = mysqli_num_rows($select_all_categories);

// }
?>
                        <div class='huge'><?php echo $category_count = recordCount('categories'); ?></div>
                         <div>Categories</div>
                    </div>
                </div>
            </div>
            <a href="categories.php">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>



                <!-- /.row  CHARTS -->   

<?php 


// $post_published_count = checkStatus('posts','post_status','Published');

// $post_draft_count = checkStatus('posts','post_status','Draft');


$subscriber_count = checkStatus('users','user_role','subscriber');

$post_published_count = record_count(get_all_users_publish_posts()); 

$post_draft_count = record_count(get_all_users_draft_posts()); 

$approved_comment_count = record_count(get_all_user_approved_posts_comments());

$unapproved_comment_count = record_count(get_all_user_unapproved_posts_comments());


?>
            <div class="row">

            <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Date', 'Count'],

          <?php

            $element_text = ['All Posts', 'Active Posts', 'Draft Posts', 'Comments', 'Approve Comments', 'Pending Comments', 'Subscribers', 'Categories'];
            $element_count = [$post_count, $post_published_count, $post_draft_count, $comments_count, $approved_comment_count, $unapproved_comment_count, $subscriber_count, $category_count];


            for($i = 0;$i < 7; $i++) {

                echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
            }

            ?>
        //   ['', 1000],
          
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: '',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

<div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>

 </div>

            </div>
            <!-- /.container-fluid -->

        </div>


        <!-- /#page-wrapper -->


        <?php include "includes/admin_footer.php"; ?>


    
