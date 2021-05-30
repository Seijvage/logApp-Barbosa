<?php

 require_once 'config/db.php';

 $email = '' ;
 $password = '';
 $email_err = '';
 $password_err = '';
 


 if( $_SERVER['REQUEST_METHOD'] === 'POST')
 {
   $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

   $email = trim($_POST['email']);
   $password = trim($_POST['password']);

   if(empty($email))
   {
     $email_err = 'Please enter email';
   }

   if(empty($password))
   {
     $password_err = 'Please enter password';
   }

   if(empty($email_err) && empty($password_err))
   {
     $sql = 'SELECT name, email, password from users where email = :email';

     if($stmt = $pdo->prepare($sql))
     {
       $stmt->bindParam(':email', $email,PDO::PARAM_STR);

       if( $stmt->execute())
       {
         if($stmt->rowCount() == 1)
         {
           if($row = $stmt->fetch())
           {
             $hashed_password = $row['password'];
             if(password_verify($password, $hashed_password))
             {
               session_start();
               $_SESSION['email'] = $email;
               $_SESSION['name'] = $row['name'];
               header('location: index.php');
             } else {
               $password_err = 'The password you entered is incorrect';
             }
           } 
         } else {
           $email_err = 'No Account found for that email';
         }
       } else {
         die(' Something went wrong');
       }
     }
     unset($stmt);
   } //  
   unset($pdo);
 }



?>
<?php include('inc/header.php'); ?>
  <br/>
  <div style="width:30%; margin: auto; text-align: center;">
    <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" class="form-signin">
      <img class="mb-4" src="img/bootstrap.svg" alt="" width="100" height="100">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputEmail" class="sr-only">Username</label>
      <input type="text" id="username" name="username" class="form-control" placeholder="Username" required="" autofocus="">
      <br/><label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      <button type="submit" name="submit" value="Submit" class="btn btn-lg btn-primary btn-block">Sign in</button>

    </form>
  </div>
<?php include('inc/footer.php'); ?>