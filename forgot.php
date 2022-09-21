<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>
<?php  include_once "includes/navigation.php"; ?>



<?php 

require './vendor/autoload.php';
require './classes/Example.php';


    if(!isset($_GET['forgot'])) {

        redirect('index');


    }

    if(IfItIsMethod('post')) {

        if(isset($_POST['email'])) {

            $email = escape($_POST['email']);

            $length = 50;

            $token = bin2hex(openssl_random_pseudo_bytes($length));

            if(email_exists($email)) {

               if($stmt = mysqli_prepare($connection, "UPDATE users SET token='{$token}' WHERE user_email = ?")) {

               
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);


                /* 
                
                configure phpmailer
                
                */

                $mail = new PHPMailer(true);


    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.mailtrap.io';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'a131ac618d2e89';                     //SMTP username
    $mail->Password   = 'bb7719dddad213';                               //SMTP password
    $mail->SMTPSecure = 'tls';                                  //Enable implicit TLS encryption
    $mail->Port       = 2525;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('ddddoviii@gmail.com', 'Denkis');
    $mail->addAddress($email, 'Denkis');     //Add a recipient
    

  
    //Content
    $mail->isHTML(true);   
    $mail->CharSet = 'UTF-8';                               //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = '<p>Please click to reset your password
   
    <a href="http://192.168.64.4/PHP-course/mycms/reset.php?email='.$email.'&token='.$token.' ">http://192.168.64.4/PHP-course/mycms/reset.php?email='.$email.'&token='.$token.'</a>

    
    </p>';

    if($mail->send()){

        $emailSent = true;
    
    } else {
       echo "NOT SENT";
    }








                // $mail = new PHPMailer(); 

                // $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
                // $mail->isSMTP();                                            
                // $mail->Host       = Config::SMTP_HOST;                                                                                           
                // $mail->Username   = Config::SMTP_USER;                    
                // $mail->Password   = Config::SMTP_PASSWORD;    
                // $mail->Port       = Config::SMTP_PORT;                         
                // $mail->SMTPSecure = 'tls';                                             
                // $mail->SMTPAuth   = true;
                // $mail->isHTML(true); 


                // $mail->setFrom('ddddoviii@gmail.com', 'DenisKis');
                // $mail->addAddress($email, 'Denkis');
                // $mail->Subject = 'This is a test email';

                // $mail->Body = 'Email body';

                // if($mail->send()) {

                //     echo "IT WAST SENT";
                // } else {
                //     echo "NOT SENT";
                // }
              
               } 

            }  else {
                echo "CONNECTION FAILED";
            }

        }


    }







?>



<!-- Page Content -->
<div class="container">

    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">


                        <?php if(!isset($emailSent)): ?>



                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 class="text-center">Forgot Password?</h2>
                                <p>You can reset your password here.</p>
                                <div class="panel-body">




                                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                                <input id="email" name="email" placeholder="email address" class="form-control"  type="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                        </div>

                                        <input type="hidden" class="hide" name="token" id="token" value="">
                                    </form>

                                </div><!-- Body-->

                                <?php else:  ?>

                                    <h2>Please check your email</h2>

                                <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php";?>

</div> <!-- /.container -->

