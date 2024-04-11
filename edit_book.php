<?php

session_start();

if(!isset($_SESSION["session_username"])):
header("location:login.php");
else:
?>
	
<?php require_once("database.php"); ?>

<?php
	if(isset($_POST["login"])){
		if(!empty($_POST['selectvalue']) && !empty($_POST['date1']) && !empty($_POST['date2']) && !empty($_POST['id_book'])) {
			$category= htmlspecialchars($_POST['selectvalue']);
			$begin=htmlspecialchars($_POST['date1']);
			$end=htmlspecialchars($_POST['date2']);
			$id_book=htmlspecialchars($_POST['id_book']);
			$discount=htmlspecialchars($_POST['selectvalue2']);
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
				$message = "Wrong number of the booking";
			} else {
				$f = true;
				do {
					if (($row['Date_beg'] <= $begin && $row['Date_end'] >= $begin) || ($row['Date_beg'] <= $end && $row['Date_end'] >= $end) || ($row['Date_beg'] >= $begin && $row['Date_end'] <= $end)) {
						if ($row['ID_booking'] != $id_book) {
							$message = "That room has been already booked! Please try another one!";
							$f = false;
						}
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
					$sql="UPDATE booking SET ID_room='".$id_room."', Date_beg='".$begin."', Date_end='".$end."', Cost='".$cost_all."' WHERE ID_booking='".$id_book."'";
					$result=mysql_query($sql); 
					if ($discount != 0) {
						$sql="UPDATE booking SET Cost = '".$cost_all."' * ('".$discount."' / 100) WHERE ID_booking='".$id_book."'";
						$result=mysql_query($sql);
					}
					if($result){
						$message = "Successfully updated";
						$lg = $_SESSION['session_username'];
						$sql="SELECT ID_admin FROM administrator WHERE login='".$lg."'";
						$result=mysql_query($sql);
						$id_admin = mysql_fetch_array($result);
						$sql="SELECT * FROM booking_office WHERE ID_admin='".$id_admin['ID_admin']."'";
						$result=mysql_query($sql);
						$numrows=mysql_num_rows($result);
						$f = true;
						if ($numrows != 0) {
							do {
								if ($row['ID_book'] == $id_book) {
									$f = false;
								}
							} while ($row = mysql_fetch_array($result));
						}
						if ($f) {
							$sql="INSERT INTO booking_office (ID_book, ID_admin) VALUES('".$id_book."', '".$id_admin['ID_admin']."')";
							$result=mysql_query($sql);
						}
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
			<h1>Редактировать бронь</h1>
			<form action="edit_book.php" id="bookform" method="post"name="bookform">
				<p>
					<label for="id_book" style="font-family: ‘Carme’, sans-serif">Введите номер брони<br>
						<input class="input" id="id_book" name="id_book"size="32"  type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Выберите категорию номера<br>
					<select name ="selectvalue" id="selectvalue" style="height: 30px; width: 200px; font-size: 20px">
						<option value="standart">standart</option>
						<option value="evrostandart">evrostandart</option>
						<option value="lux">lux</option>
						<option value="family">family</option>
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
				<p>
					<label for="user_login" style="font-family: ‘Carme’, sans-serif">Выберите предоставляемую скидку<br>
					<select name ="selectvalue2" id="selectvalue2" style="height: 30px; width: 200px; font-size: 20px">
						<option value="63">63%</option>
						<option value="44">44%</option>
						<option value="41">41%</option>
						<option value="0">0%</option>
					</select>
					</label>
				</p>
				<p class="submit">
					<input class="button" id="login" name="login"type= "submit" value="Редактировать" style="height: 30px; width: 200px; font-size: 20px">
				</p>
				<p><a href="delete.php">Удалить бронь</a></p><br/>
				<p><a href="admin.php">Вернуться в личный кабинет</a></p>
			</form>
		</div>
		</font>	
</body>
</html>
	
	
<?php endif; ?>