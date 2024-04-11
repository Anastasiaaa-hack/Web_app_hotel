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
			$service= htmlspecialchars($_POST['selectvalue']);
			$date=htmlspecialchars($_POST['date1']);
			$lg = $_SESSION['session_username'];
			$sql = "SELECT ID_guest FROM guest WHERE login='".$lg."'";
			$result=mysql_query($sql);
			$id_guest=mysql_fetch_array($result);
			
			$link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB)
			or die("Error: ".mysqli_error($link));
			$querty = "SELECT * FROM services";	
			$result = mysqli_query($link, $querty);
			$row = mysqli_fetch_array($result);
			do {
				if ($row['Description'] == $service) {
					$id_service = $row['ID_service'];
					$cost = $row['Cost'];
				}
			} while ($row = mysqli_fetch_array($result));
			
			$query=mysql_query("SELECT * FROM booked_services WHERE ID_service='".$id_service."'");
			$numrows=mysql_num_rows($query);
			$row = mysql_fetch_array($query);
			if($numrows==0){
				$sql="INSERT INTO booked_services (ID_guest, ID_service, Date) VALUES('".$id_guest['ID_guest']."', '".$id_service."', '".$date."')";
				$result=mysql_query($sql); 
				$id = mysql_insert_id();
				if($result){
					$message = "Service booked";
				} else {
					$message = "Failed to insert data information!";
				}
			} else {
				$f = true;
				do {
					if ($row['Date'] == $date) {
						$message = "This service has been already booked! Please try another one!";
						$f = false;
					}
				} while ($row = mysql_fetch_array($query));
				if ($f) {
					$sql="INSERT INTO booked_services (ID_guest, ID_service, Date) VALUES('".$id_guest['ID_guest']."', '".$id_service."', '".$date."')";
					$result=mysql_query($sql); 
					$id = mysql_insert_id();
					if($result){
						$message = "Service booked";
					} else {
						$message = "Failed to insert data information!";
					}
				}
				else {
					$message = "Try other dates, please";
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
			<h1>Заказ услуг</h1>
			<form action="book_service.php" id="bookform" method="post"name="bookform">
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Выберите услугу<br>
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
					<input class="button" id="login" name="login"type= "submit" value="Забронировать" style="height: 30px; width: 200px; font-size: 20px">
				</p>
				<p><a href="intropage.php">Вернуться в личный кабинет</a></p>
			</form>
		</div>
		</font>	
</body>
</html>
	
	
<?php endif; ?>