<?php require_once("database.php"); ?>
<?php require_once("password.php"); ?>

<?php
	if(isset($_POST["register"])){
		if(!empty($_POST['F']) && !empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['I']) && !empty($_POST['O'])&& !empty($_POST['date'])&& !empty($_POST['number'])) {
			$f= htmlspecialchars($_POST['F']);
			$i= htmlspecialchars($_POST['I']);
			$o= htmlspecialchars($_POST['O']);
			$username=htmlspecialchars($_POST['login']);
			$password=htmlspecialchars($_POST['password']);
			$password=password_hash($password, PASSWORD_BCRYPT);
			$date=htmlspecialchars($_POST['date']);
			$number=htmlspecialchars($_POST['number']);
			$query=mysql_query("SELECT * FROM guest WHERE login='".$username."'");
			$numrows=mysql_num_rows($query);
			if($numrows==0){
				$sql="INSERT INTO guest (F, I, O, Birth_date, Passport, login, password) VALUES('".$f."', '".$i."', '".$o."', '".$date."', '".$number."', '".$username."', '".$password."')";
				$result=mysql_query($sql); 
				if($result){
					$message = "Account Successfully Created";
					header ('Location: login.php');
				} else {
					$message = "Failed to insert data information!";
				}
			} else {
				$message = "That username already exists! Please try another one!";
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
			<h1>Регистрация</h1>
			<form action="register.php" id="registerform" method="post"name="registerform">
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Фамилия<br>
						<input class="input" id="F" name="F"size="20"  type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Имя<br>
						<input class="input" id="I" name="I"size="20"  type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Отчество<br>
						<input class="input" id="O" name="O"size="20"  type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_pass" style="font-family: ‘Carme’, sans-serif">Дата рождения<br>
						<input type="date" name="date" id="date" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Серия и номер паспорта (без пробела)<br>
						<input class="input" id="number" name="number"size="20"  type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_pass" style="font-family: ‘Carme’, sans-serif">Имя пользователя<br>
						<input class="input" id="login" name="login"size="20" type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_pass" style="font-family: ‘Carme’, sans-serif">Пароль<br>
						<input class="input" id="password" name="password"size="20"   type="password" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p class="submit"><input class="button" id="register" name= "register" type="submit" value="Зарегистрироваться" style="height: 30px; width: 200px; font-size: 20px"></p>
					<p class="regtext" style="font-family: ‘Carme’, sans-serif">Уже зарегистрированы? 
						<a href= "login.php" style="font-family: ‘Carme’, sans-serif">Введите имя пользователя</a>!
					</p>
			</form>
		</div>
		</font>
	</div>
</body>
</html>