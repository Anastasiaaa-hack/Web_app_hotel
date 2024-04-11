<?php require_once("database.php"); ?>
<?php require_once("password.php"); ?>

<?php
	session_start();
	?>	 
<?php
	
	if(isset($_SESSION["session_username"])){
	header("Location: intropage.php");
	}

	if(isset($_POST["login"])){
		if(!empty($_POST['login']) && !empty($_POST['password'])) {
			$username=htmlspecialchars($_POST['login']);
			$password=htmlspecialchars($_POST['password']);
			$query =mysql_query("SELECT * FROM guest WHERE login='".$username."'");
			$row1=mysql_fetch_array($query);
			$numrows=mysql_num_rows($query);
			if($numrows!=0 && password_verify($password, $row1['password'])){
				 $_SESSION['session_username']=$username;	 
				   header("Location: intropage.php");
				/*while($row=mysql_fetch_assoc($query)){
				  $dbusername=$row['login'];
				  $dbpassword=$row['password'];
				}
				if($username == $dbusername && $password == $dbpassword){
					 $_SESSION['session_username']=$username;	 
				   header("Location: intropage.php");
				}*/
			} else {
				$message = "Invalid username or password!";
			}
			$query =mysql_query("SELECT * FROM administrator WHERE login='".$username."' AND password='".$password."'");
			$numrows=mysql_num_rows($query);
			if($numrows!=0){
				while($row=mysql_fetch_assoc($query)){
				  $dbusername=$row['login'];
				  $dbpassword=$row['password'];
				}
				if($username == $dbusername && $password == $dbpassword){
					 $_SESSION['session_username']=$username;	 
				   header("Location: admin.php");
				}
			} else {
				$message = "Invalid username or password!";
			}
		} else {
			$message = "All fields are required!";
		}
	}
	?>
	
	<?php if (!empty($message)) {echo "<p class='error'>" . "MESSAGE: ". $message . "</p>";} ?>

<!DOCTYPE html>
<body bgcolor = "#DEF2F1">
	<div>
		<div>
		<font size = "5">
			<h1 style="font-family: ‘Carme’, sans-serif">Вход</h1>
			<form action="" id="loginform" method="post"name="loginform">
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Имя пользователя<br>
						<input class="input" id="login" name="login"size="20" type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_pass" style="font-family: ‘Carme’, sans-serif">Пароль<br>
						<input class="input" id="password" name="password" size="20" type="password" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p> 
				<p class="submit">
					<input class="button" name="login1"type= "submit" value="Log In" style="height: 30px; width: 200px; font-size: 20px">
				</p>
				<p class="regtext" style="font-family: ‘Carme’, sans-serif">Еще не зарегистрированы?
					<a href= "register.php" style="font-family: ‘Carme’, sans-serif">Регистрация</a>!
				</p>
			</form>
		</font>
		</div>
	</div>
</body>
</html>