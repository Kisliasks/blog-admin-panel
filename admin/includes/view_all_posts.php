<?php 

if(isset($_POST['checkBoxArray'])) {
    
    foreach($_POST['checkBoxArray'] as $postValueId) {
    $bulk_options = $_POST['bulk_options'];

    switch($bulk_options) {
        case 'Published':

            $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
            $update_to_publish_status = mysqli_query($connection, $query);
            break;

            case 'Draft':

            $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";
            $update_to_draft_status = mysqli_query($connection, $query);
            break;

            case 'delete':

            $query = "DELETE FROM posts WHERE post_id = {$postValueId} ";
            $delete_posts = mysqli_query($connection, $query);
            break;

            case 'clone':
                
            $query = "SELECT * FROM posts WHERE post_id = {$postValueId} ";
            $select_post_query = mysqli_query($connection, $query);

            while($row = mysqli_fetch_array($select_post_query)) {
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_date = $row['post_date'];
                $post_author = $row['post_author'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_content = $row['post_content'];
                
                $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) ";

                $query .= "VALUES('{$post_category_id}','{$post_title}','{$post_author}',now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}') ";
        
                $create_post_query = mysqli_query($connection, $query);
        
                comfirmQuery($create_post_query);

                break;

            }

        }
    }
}


?>

<form action="" method="post">


<table class="table table-bordered table-hover">

<div id="bulkOptionsContainer" class="col-xs-4">

    <select name="bulk_options" id="" class="form-control">
        <option value="">Select Options</option>
        <option value="Published">Published</option>
        <option value="Draft">Draft</option>
        <option value="delete">Delete</option>
        <option value="clone">Clone</option>

    </select>
</div>

<div class="col-xs-4">
<input type="submit" name="submit" class="btn btn-success" value="Apply">
<a class="btn btn-primary" href="posts.php?source=add_posts">Add New</a>

</div>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllBoxes"></th>
                            <th>Id</th>
                            <th>Author</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Tags</th>
                            <th>Comments</th>
                            <th>Date</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>

                        </tr>
                    </thead>
                    <tbody>

 <?php 

// $query = "SELECT * FROM posts ORDER BY post_id DESC";

$user = currentUser();

$query = "SELECT posts.post_id, posts.post_author, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image,  ";
$query .= "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title  ";
$query .= " FROM posts ";
$query .= " LEFT JOIN categories ON posts.post_category_id = categories.cat_id WHERE post_author = '$user' ORDER BY posts.post_id DESC";

$select_posts = mysqli_query($connection, $query);

while($row = mysqli_fetch_assoc($select_posts)) {
    $post_id = $row['post_id'];
    $post_author = $row['post_author'];
    $post_title = $row['post_title'];
    $post_category_id = $row['post_category_id'];
    $post_status = $row['post_status'];
    $post_image = $row['post_image'];
    $post_tags = $row['post_tags'];
    $post_comment_count = $row['post_comment_count'];
    $post_date = $row['post_date'];
    $post_views_count = $row['post_views_count'];
    $category_title = $row['cat_title'];
    $category_id = $row['cat_id'];

    echo "<tr>";
    ?>
    <td><input type='checkbox' class='checkBoxes' name='checkBoxArray[]' value='<?php echo $post_id;  ?>'></td>
    <?php
   
    echo "<td>$post_id</td>";
    echo "<td>$post_author</td>";
    echo "<td>$post_title</td>";

            // $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
            // $select_categories_id = mysqli_query($connection, $query);

            // while($row = mysqli_fetch_assoc($select_categories_id)) {
            // $cat_id = $row['cat_id'];
            // $cat_title = $row['cat_title'];

            // }
   
    echo "<td>$category_title</td>";
    echo "<td>$post_status</td>";
    echo "<td><img width='100' src='../images/$post_image'$post_image</td>";
    echo "<td>$post_tags</td>";


    $query = "SELECT * FROM comments WHERE comment_post_id = {$post_id}";
    $send_comment_query = mysqli_query($connection, $query);

    while($row = mysqli_fetch_array($send_comment_query)) {

    if(!$row['comment_post_id'] = null) {

    $comment_id = $row['comment_id'];
    }
        $count_comments = mysqli_num_rows($send_comment_query);
        if(mysqli_num_rows($send_comment_query) == null) {
            $count_comments = 0;
        }

}
    global $comment_id;
    global $count_comments;

    echo "<td><a href='comment.php?id={$post_id}'>$count_comments</a></td>";
    echo "<td>$post_date</td>";
    echo "<td><a class='btn btn-primary' href='../post.php?p_id={$post_id}'>View Post</a></td>";
    echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>"; 

    ?>

    <form action="" method="post">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <?php 
        echo '<td><input class="btn btn-danger" type="submit" name="delete" value="Delete"></td>';
    ?>
    </form>


<?php

    // echo "<td><a onClick=\"javascript: return confirm('???? ????????????, ???????????') \"; href='posts.php?delete={$post_id}'>Delete</a></td>";
    echo "<td><a href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";        
    echo "</tr>";

} 

?>

    </tbody>
  </table>
</form>
                
<?php 
 
 if(isset($_POST['delete'])) {
 
    $the_post_id = escape($_POST['post_id']);

    $query = "DELETE FROM posts WHERE post_id = '{$the_post_id}' ";
    $delete_query = mysqli_query($connection, $query);
 
    header("Location: posts.php");
 }

 if(isset($_GET['reset'])) {
 
    $the_post_id = $_GET['reset'];

    $query = "UPDATE posts SET post_views_count = 0 WHERE post_id = {$the_post_id} ";
    $reset_query = mysqli_query($connection, $query);
 
    header("Location: posts.php");
 }
?>