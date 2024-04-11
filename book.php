<?php

session_start();

if(!isset($_SESSION["session_username"])):
header("location:login.php");
else:
?>
	
<?php require_once("database.php"); ?>

<?php
	if(isset($_POST["login"])){
		if(!empty($_POST['selectvalue']) && !empty($_POST['date1']) && !empty($_POST['date2'])) {
			$category= htmlspecialchars($_POST['selectvalue']);
			$begin=htmlspecialchars($_POST['date1']);
			$end=htmlspecialchars($_POST['date2']);
			
			$link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB)
			or die("Error: ".mysqli_error($link));
			$querty = "SELECT * FROM room";
			$result = mysqli_query($link, $querty);
			$row = mysqli_fetch_array($result);
			do {
				if ($row['Description'] == $category) {
					$id_room = $row['ID_room'];
					$cost = $row['Cost'];
				}
			} while ($row = mysqli_fetch_array($result));
			
			$query=mysql_query("SELECT * FROM booking WHERE ID_room='".$id_room."'");
			$numrows=mysql_num_rows($query);
			$row = mysql_fetch_array($query);
			if($numrows==0){
				$sql="INSERT INTO booking (ID_room, Date_beg, Date_end, Cost) VALUES('".$id_room."', '".$begin."', '".$end."', '".$cost."')";
				$result=mysql_query($sql); 
				$id = mysql_insert_id();
				if($result){
					$message = "Booking Successfully Created";
					$lg = $_SESSION['session_username'];
					$sql="UPDATE guest SET ID_booking='".$id."' WHERE login='".$lg."'";
					$result=mysql_query($sql);
				} else {
					$message = "Failed to insert data information!";
				}
			} else {
				$f = true;
				do {
					if (($row['Date_beg'] <= $begin && $row['Date_end'] >= $begin) || ($row['Date_beg'] <= $end && $row['Date_end'] >= $end) || ($row['Date_beg'] >= $begin && $row['Date_end'] <= $end)) {
						$message = "That room has been already booked! Please try another one!";
						$f = false;
					}
				} while ($row = mysql_fetch_array($query));
				if ($f) {
					function countDays($d1, $d2) {
						$d1_ts = strtotime($d1);
						$d2_ts = strtotime($d2);
						$seconds = abs($d1_ts - $d2_ts);
						return floor($seconds / 86400);
					}
					$cnt = countDays($begin, $end) + 1;
					$cost_all = $cnt * $cost;
					$sql="INSERT INTO booking (ID_room, Date_beg, Date_end, Cost) VALUES('".$id_room."', '".$begin."', '".$end."', '".$cost_all."')";
					$result=mysql_query($sql); 
					$id = mysql_insert_id();
					if($result){
						$message = "Booking Successfully Created";
						$lg = $_SESSION['session_username'];
						$sql = "SELECT ID_guest FROM guest WHERE login='".$lg."'";
						$result=mysql_query($sql);
						$id_guest=mysql_fetch_array($result);
						$sql="INSERT INTO guest_booking (ID_guest, ID_booking) VALUES('".$id_guest['ID_guest']."', '".$id."')";
						$result=mysql_query($sql);
						$a=1;
						$sql="INSERT INTO booking_office (ID_book, ID_admin) VALUES ('".$id."', '".$a."')";
						$result=mysql_query($sql);
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
			<h1>Бронирование</h1>
			<form action="book.php" id="bookform" method="post"name="bookform">
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Выберите категорию номера<br>
					<select name ="selectvalue" id="selectvalue" style="height: 30px; width: 200px; font-size: 20px">
						<option value="standart">Стандарт</option>
						<option value="evrostandart">Евростандарт</option>
						<option value="lux">Люкс</option>
						<option value="family">Семейный</option>
					</select>
					</label>
				</p>
				<p>
					<label for="user_pass" style="font-family: ‘Carme’, sans-serif">Выберите дату начала брони<br>
						<input type="date" name="date1" id="date1" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_pass" style="font-family: ‘Carme’, sans-serif">Выберите дату окончания брони<br>
						<input type="date" name="date2" id="date2" style="height: 30px; width: 200px; font-size: 20px">
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