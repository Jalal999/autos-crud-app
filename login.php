
<?php 
	require_once "pdo.php";
	session_start();

	$salt = 'XyZzy12*_';
	$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; 

	if (isset($_POST['email']) && isset($_POST['pass'])){
		unset($_SESSION['email']);

		if( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
			$_SESSION['error'] = "User name and password are required";
			header('Location: login.php');
			return;

		} else if (strpos($_POST['email'], '@')===false){
        	$_SESSION['error'] = "Email must have an at-sign (@)";
        	header('Location: login.php');
            return;	

        } else {
            $check = hash('md5', $salt.$_POST['pass']);
            
            if ( $check == $stored_hash ) {
                error_log("Login success ".$_POST['email']);
                $_SESSION['email'] = $_POST['email'];
                header('Location: index.php');
                return;
            } else {
                $_SESSION['error'] = "Incorrect password";
                error_log("Login fail ".$_POST['email']." $check");
                header('Location: login.php');
                return;
            }
        }   
    }

 ?>

<!DOCTYPE html>
<html>
<head></head>
<body>

	<?php 
		if(isset($_SESSION['error'])){
			echo ('<p style="color:red">'.$_SESSION['error']."</p>\n");
			unset($_SESSION['error']);
		}
	 ?>

	<form method="post">
		<p>User Name <input type="text" name="email"></p>
		<p>Password <input type="text" name="pass"></p>
		<input type="submit" name="login" value="Log In">
		<a href="index.php">Cancel</a>
	</form>

</body>
</html>