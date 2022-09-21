<?php 

    if(isset($_GET['p_id'])) {

      $the_post_id = $_GET['p_id'];

    }

    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
   $select_post_by_id = mysqli_query($connection, $query);

   while($row = mysqli_fetch_assoc($select_post_by_id)) {

      $post_id = $row['post_id']; 
       $post_title = $row['post_title']; 
       $post_author_db = $row['post_author']; 
       $post_category_id = $row['post_category_id'];
       $post_status = $row['post_status'];
   
       $post_image = $row['post_image'];
    //    $post_image_temp = $row['image']['tmp_name'];
   
       $post_tags =  $row['post_tags'];
       $post_content =  $row['post_content'];
       $post_date = $row['post_date'];
       $post_comment_count = $row['post_comment_count'];

   }



if(isset($_POST['update_post'])) {

    
    $post_title = escape($_POST['title']); 
    $post_author = escape($_POST['author']); 
    $post_category_id = escape($_POST['post_category']);
    $post_status = escape($_POST['post_status']);

    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];

    $post_tags = escape($_POST['post_tags']);
    $post_content = escape($_POST['post_content']);

    move_uploaded_file($post_image_temp, "../images/$post_image" );

    if(empty($post_category_id)) {
        
        $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
        $select_post_category = mysqli_query($connection, $query);

        while($row = mysqli_fetch_array($select_post_category)) {
            $cat_id = $row['post_category_id']; 
        }
    }
    
    if(empty($post_image)) {
        
        $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
        $select_image = mysqli_query($connection, $query);

        while($row = mysqli_fetch_array($select_image)) {
            $post_image = $row['post_image']; 
        }
    } 

    if(empty($post_status)) {
        
        $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
         $select_post_status = mysqli_query($connection, $query);

             while($row = mysqli_fetch_array($select_post_status)) {
                $post_status = $row['post_status']; 
    } 
}

//     if(empty($post_author)) {
        
//         $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
//          $select_post_author = mysqli_query($connection, $query);

//              while($row = mysqli_fetch_array($select_post_author)) {
//                 $post_author = $row['post_author']; 
// } 
//     }

    
    $query = "UPDATE posts SET post_title = '{$post_title}', post_author = '{$post_author}', post_category_id = '{$post_category_id}', post_status = '{$post_status}', post_date = now(), post_image = '{$post_image}', post_content = '{$post_content}', post_tags = '{$post_tags}'  WHERE post_id = {$the_post_id} ";
    $update_query = mysqli_query($connection, $query);
    // header("Location: posts.php");

    $update_post = mysqli_query($connection, $query);

    comfirmQuery($update_post);

    echo "<p class='bg-success'>Post Updated:"." "."<a href='../post.php?p_id={$the_post_id}'>View Post</a> or <a href='posts.php'>Edit more posts</a></p>";
    
}     

?>
 

<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="title">
    </div>

    <div class="form-group">

        <!-- // Post category  -->
        <label for="post_category">Category</label>
    <select name="post_category" id="">   
    <?php

    $query = "SELECT * FROM categories ";
    $select_categories = mysqli_query($connection, $query);

    comfirmQuery($select_categories);

    while($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        if($cat_id == $post_category_id) {

            echo  "<option selected value='$cat_id'>{$cat_title}</option>";


        } else {

            echo  "<option value='$cat_id'>{$cat_title}</option>";
        }
    
}

  

?>
    </select>
 
    </div>

    <!-- <div class="form-group">
        <label for="title">Post Author</label>
        <input value=" <?php  $post_author; ?>" type="text" class="form-control" name="author">
    </div> -->


    <div class="form-group">

<!-- // Users  -->
<label for="author">Users</label>
<select name="author" id="">   

    <?php echo  "<option value='$post_author_db'>{$post_author_db}</option>"; ?>
<?php

$query = "SELECT * FROM users ";
$select_users = mysqli_query($connection, $query);

comfirmQuery($select_categories);

while($row = mysqli_fetch_assoc($select_users)) {
$user_id = $row['user_id'];
$username = $row['username'];

echo  "<option value='$username'>{$username}</option>";

}
?>
</select>


</div>




    <div class="form-group">
    <select name="post_status" id="">

        <option value="<?php $post_status; ?>"><?php echo $post_status; ?></option>

        <?php

                if($post_status == 'Published') {

                    echo  "<option value='Draft'>Draft</option>";
                } else {
                    echo  "<option value='Published'>Published</option>";
                }

            
        ?>
      



    </select>
    </div>

    <div class="form-group">
       <img src="../images/<?php echo $post_image; ?>" width="100" alt="">
       <input type="file" name="image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea  name="post_content" class="form-control" id="summernote" cols="30" rows="10" ><?php echo $post_content; ?>
      
        </textarea>
    </div>

    <div class="form-group">
       
        <input type="submit" class="btn btn-primary" name="update_post" value="Update Post">
    </div>


</form>


