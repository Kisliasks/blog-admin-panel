<?php 

function escape($srting) {
    global $connection;
   return mysqli_real_escape_string($connection, trim(strip_tags($srting)));

}



function insert_categories() {

global $connection;
if(isset($_POST['submit'])) {

$cat_title = $_POST['cat_title'];

if($cat_title == "" || empty($cat_title)) {

echo "This field should not be empty";
} else {

 $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES(?) ");
 mysqli_stmt_bind_param($stmt, 's', $cat_title);
 mysqli_stmt_execute($stmt);

 

 if(!$stmt) {

     die('QUERY FAILED'. mysqli_error($connection));
         }
        }
        mysqli_stmt_close($stmt);
    }
}




function findAllCategories(){
    
    global $connection;
    $query = "SELECT * FROM categories";
    $select_all_categories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_all_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        echo "<tr>";                     
        echo  "<td>{$cat_id}</td>";
        echo  "<td>{$cat_title}</td>";
        echo  "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo  "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}



function deleteCategories() {

    global $connection;
    if(isset($_GET['delete'])) {

        $the_cat_id = $_GET['delete'];

        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php");
        }

}


function comfirmQuery($result) {
    global $connection;
    if(!$result) {

        die("QUERY FAILED" . mysqli_error($connection));

    }

}

function get_all_user_posts() {

    $user = currentUser();
    
  return $result = query("SELECT * FROM posts WHERE post_author = '{$user}'");
         
}

function get_all_posts_user_comments(){

    $user = currentUser();

return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE post_author = '{$user}'");

}

function record_count($result) {

    return mysqli_num_rows($result);

}


function users_online() {

    if(isset($_GET['onlineusers'])) {


    global $connection;

    if(!$connection){

        session_start();
        include("../includes/db.php");

        $session = session_id();
        $time = time();
        $time_out_in_seconds = 30;  // через 30 секунд после появляения онлайн и выхода, пользователь онлайн не отобразиться
        $time_out = $time - $time_out_in_seconds;
        
        $query = "SELECT * FROM users_online WHERE user_session = '$session' ";
        $send_query = mysqli_query($connection, $query);
        $count = mysqli_num_rows($send_query);
        
        if($count == NULL) {
            mysqli_query($connection, "INSERT INTO users_online(user_session, user_time) VALUES('$session', '$time')");
        } else { 
            mysqli_query($connection, "UPDATE users_online SET user_time = '$time' WHERE user_session = '$session' ");
        }
        
        $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE user_time > '$time_out' "); // $time_out - это откат назад на 30 секунд. эта переменная динамична внутри кода php и через 30 секунд сравнивается с постоянным значением user_time, и когда они равны, эта строка из бд не будет выводиться. Поначалу user_time всегда больше на 30 секунд, но пременная $time_out меняется, ведь время идет, а внутри переменной есть функция time();
        echo $count_user = mysqli_num_rows($users_online_query);
    
    
    }
   
   }
}

users_online();




function recordCount($table) {

    global $connection;
    $query = "SELECT * FROM " . $table;
    $select_all_post = mysqli_query($connection, $query);

    $result = mysqli_num_rows($select_all_post);

    comfirmQuery($result);

    return $result;
}

function get_all_users_publish_posts() {
    $user = currentUser();
    
    return $result = query("SELECT * FROM posts WHERE post_author = '{$user}' AND post_status = 'Published'");

}

function get_all_users_draft_posts() {

    $user = currentUser();
    
    return $result = query("SELECT * FROM posts WHERE post_author = '{$user}' AND post_status = 'Draft'");

}

function get_all_user_approved_posts_comments() {

    $user = currentUser();

    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE post_author = '{$user}' AND comment_status = 'approve'");
    

}

function get_all_user_unapproved_posts_comments() {
    $user = currentUser();

    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE post_author = '{$user}' AND comment_status = 'unapproved'");
    
    
}



function checkStatus($table,$column,$status) {


    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $result = mysqli_query($connection, $query);

    return mysqli_num_rows($result);

}

function get_user_name() {

    if(isset($_SESSION['username'])) {
        return $_SESSION['username'];
    } else {
        return null;
    }

}




function is_admin() {
    global $connection;

    if(isLoggedIn()){

    $query = "SELECT user_role FROM users WHERE user_id = ".$_SESSION['user_id']. " ";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);

        $row = mysqli_fetch_array($result);
        if($row['user_role'] == 'admin') {

            return true;
        } else {
            return false;
        }
    }
    return false;
}





function username_exists($username) {

    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);

    $num_rows = mysqli_num_rows($result);

    if($num_rows > 0) {
        return true;
    } else {
        return false;
    }

   
}

function email_exists($email) {

    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email' ";
    $result = mysqli_query($connection, $query);
    comfirmQuery($result);

    $num_rows = mysqli_num_rows($result);

    if($num_rows > 0) {
        return true;
    } else {
        return false;
        
    }
  
}

function redirect($location) {

    return header("Location: " . $location);
    exit;
}

function IfItIsMethod($method=null){

    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
        return true;
    }
    return false;
}

function isLoggedIn() {
    if(isset($_SESSION['user_role'])) {
        return true;
    }
    return false;
}


function loggedInUserId(){

    if(isLoggedIn()){

        $result = query("SELECT * FROM users WHERE username ='".$_SESSION['username'] ."'");
       comfirmQuery($result);
        $user = mysqli_fetch_array($result);
   return mysqli_num_rows($result) >= 1 ? $user['user_id'] : false;

        if(mysqli_num_rows($result) >= 1) {
            return $user['user_id'];
        }
    }
    return false;
}

function userLikedThisPost($post_id = '') {

   $result = query("SELECT * FROM likes WHERE user_id =". loggedInUserId() . " AND post_id ={$post_id} ");
   comfirmQuery($result);
   
   return mysqli_num_rows($result) >= 1 ? true : false;
}

function getPostLikes($post_id){

    $result = query("SELECT * FROM likes WHERE post_id = $post_id");
    comfirmQuery($result);
    echo mysqli_num_rows($result);
}


function query($query){

    global $connection;
    return mysqli_query($connection, $query);
}




function checkIfUserIsLoggedInAndRedirect($redirectLocation=null) {

    if(isLoggedIn()) {
        redirect($redirectLocation);
    }
}



function register_user($username, $email, $password) {

    global $connection;

   
            $username = mysqli_real_escape_string($connection, $username);
            $email    = mysqli_real_escape_string($connection, $email);
            $password = mysqli_real_escape_string($connection, $password);


            $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));

            // INSERT USER DATA
            $query = "INSERT INTO users(username, user_email, user_password, user_role) ";
            $query .= "VALUES('{$username}','{$email}','{$password}','subscriber' )";
            $register_user_query = mysqli_query($connection, $query);

            comfirmQuery($register_user_query);

}



function login_user($username, $password) {

    global $connection;

   $username = trim($username);
   $password = trim($password);
    
    $username =  mysqli_real_escape_string($connection, $username);
    $password =  mysqli_real_escape_string($connection, $password);
    
    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if(!$select_user_query) {
        die("QUERY FAILED". mysqli_error($connection));
    }
    
        while($row = mysqli_fetch_assoc($select_user_query)) {
            
            $db_user_id = $row['user_id'];
            $db_username = $row['username'];
            $db_user_password = $row['user_password'];
            $db_user_firstname = $row['user_firstname'];
            $db_user_lastname = $row['user_lastname'];
            $db_user_role = $row['user_role'];
        
    
                //  $password = crypt($password, $db_user_password); // функция возвращает пароль из баззы данных, который захеширован
    
            if(password_verify($password, $db_user_password)) {
    
                $_SESSION['user_id'] = $db_user_id;
                $_SESSION['username'] = $db_username;
                $_SESSION['firstname'] = $db_user_firstname;
                $_SESSION['lastname'] = $db_user_lastname;
                $_SESSION['user_role'] = $db_user_role;
               
                redirect("/PHP-course/mycms/admin");
            
            } else {
                return false;
         }

}

}


function currentUser() {

    if(isset($_SESSION['username'])) {
        return $_SESSION['username'];
    }

    return false;
}


function image_placeholder($image=null) {

if(!$image) {

    return 'image_1.jpg';

    } else {
        return $image;
    }

}
?>
