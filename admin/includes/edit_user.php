<?php

if(isset($_POST['update_user'])) {  // update variables

   $the_user_id = $_GET['us_id'];
   
    $user_firstname = $_POST['user_firstname']; 
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];

    // $post_image = $_FILES['image']['name'];
    // $post_image_temp = $_FILES['image']['tmp_name'];

    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    // $post_date = date('d-m-y');
 


        // update query // edit user query

       

        if(empty($user_role)) {    // select role
        
            $query = "SELECT * FROM users WHERE user_id = $the_user_id ";
            $select_user_role = mysqli_query($connection, $query);
    
            while($row = mysqli_fetch_array($select_user_role)) {
                $user_role = $row['user_role']; 
            }
        } 


        // UPdate password with new hash function

        if(!empty($user_password)) {   // password  

            $query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
            $get_user_query = mysqli_query($connection, $query_password);

            comfirmQuery($get_user_query);

            $row = mysqli_fetch_array($get_user_query);

            $db_user_password = $row['user_password'];  // вывели хэшированный пароль, чтобы сравнить 

            if($user_password != $db_user_password) {
                $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
            }


            $query = "UPDATE users SET user_firstname = '{$user_firstname}', user_lastname = '{$user_lastname}', ";
            $query .= "user_role = '{$user_role}', username = '{$username}', user_email = '{$user_email}', user_password = '{$hashed_password}' WHERE user_id = '{$the_user_id}' ";
       
               $update_user_query = mysqli_query($connection, $query);
               header("Location: users.php");
       
                   
               comfirmQuery($update_user_query);
       
        }

   
} else {
    // header("Location: index.php");
}

?>

<?php

    if(isset($_GET['us_id'])) {  
        $the_user_id = $_GET['us_id'];  
                                                                                            // выводим значения из таблицы в переменые $row 
    $query = "SELECT * FROM users WHERE user_id = '{$the_user_id}' ";
        $view_user_query = mysqli_query($connection, $query);

    
        while($row = mysqli_fetch_assoc($view_user_query)) {

            $username = $row['username'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_role = $row['user_role'];

        
?>
<form action="" method="post" enctype="multipart/form-data">

<div class="form-group">
        <label for="title">Firstname</label>
        <input type="text" class="form-control" name="user_firstname" value="<?php echo $user_firstname; ?>">
    </div>

    <div class="form-group">
        <label for="post_status">Lastname</label>
        <input type="text" class="form-control" name="user_lastname" value="<?php echo $user_lastname; ?>">
    </div>

    <div class="form-group">

<!-- // Role users  -->
<select name="user_role" id="">   

<option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
<?php
if($user_role == 'admin') {

  echo "<option value='subscriber'>subscriber</option>";

} else {
    echo "<option value='admin'>admin</option>";
}

?>
  
</select>

</div>

  

    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file"  name="image">
    </div> -->

    <div class="form-group">
        <label for="post_tags">Username</label>
        <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
    </div>

    <div class="form-group">
        <label for="user_email">Email</label>
        <textarea name="user_email" class="form-control" id="" cols="10" rows="1" type='email'><?php echo $user_email; ?></textarea>
    </div>


    <div class="form-group">
        <label for="post_tags">Password</label>
        <input type="password" class="form-control" name="user_password" autocomplete="off">
    </div>

    <?php } } ?>

    <div class="form-group">
       
        <input type="submit" class="btn btn-primary" name="update_user" value="Update User">
    </div>

    
</form>

