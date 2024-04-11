<?php

session_start();

if(!isset($_SESSION["session_username"])):
header("location:login.php");
else:
?>
	
<?php require_once("database.php"); ?>

<?php
	if(isset($_POST["login"])){
		if(!empty($_POST['selectvalue']) && !empty($_POST['date1'])) {
			$desc=htmlspecialchars($_POST['selectvalue']);
			$date=htmlspecialchars($_POST['date1']);
			$link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB)
			or die("Error: ".mysqli_error($link));
			
			$lg = $_SESSION['session_username'];
			$sql = "SELECT ID_guest FROM guest WHERE login='".$lg."'";
			$result=mysql_query($sql);
			$id_guest=mysql_fetch_array($result);
			$query=mysql_query("SELECT * FROM services WHERE Description='".$desc."'");
			$id_service = mysql_fetch_array($query);
			$query=mysql_query("SELECT * FROM booked_services WHERE ID_guest='".$id_guest['ID_guest']."' AND ID_service='".$id_service['ID_service']."'");
			$numrows=mysql_num_rows($query);
			$row = mysql_fetch_array($query);
			if($numrows==0){
				$message = "Wrong data";
			} else {
				$sql="DELETE FROM booked_services WHERE ID_guest='".$id_guest['ID_guest']."' AND ID_service='".$id_service['ID_service']."' AND Date='".$date."'";
				$result=mysql_query($sql); 
				$id = mysql_insert_id();
				if($result){
					$message = "Successfully deleted";
				} else {
					$message = "Failed to insert data information!";
				}
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
			<h1>Отказ от услуги</h1>
			<form action="delete_service.php" id="bookform" method="post"name="bookform">
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Выберите услугу для отказа<br>
					<select name ="selectvalue" id="selectvalue" style="height: 30px; width: 200px; font-size: 20px">
						<option value="spa">СПА-комплекс</option>
						<option value="transport">Трансфер</option>
						<option value="laundry">Прачечная</option>
						<option value="trip">Экскурсии</option>
					</select>
					</label>
				</p>
				<p>
					<label for="user_pass" style="font-family: ‘Carme’, sans-serif">Выберите дату<br>
						<input type="date" name="date1" id="date1" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p class="submit">
					<input class="button" id="login" name="login"type= "submit" value="Отменить" style="height: 30px; width: 200px; font-size: 20px">
				</p>
				<p><a href="intropage.php">Вернуться в личный кабинет</a></p>
			</form>
		</div>
		</font>	
</body>
</html>
	
	
<?php endif; ?>