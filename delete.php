<?php

session_start();

if(!isset($_SESSION["session_username"])):
header("location:login.php");
else:
?>
	
<?php require_once("database.php"); ?>

<?php
	if(isset($_POST["login"])){
		if(!empty($_POST['id_book'])) {
			$id_book=htmlspecialchars($_POST['id_book']);
			$link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB)
			or die("Error: ".mysqli_error($link));
			
			$query=mysql_query("SELECT * FROM booking WHERE ID_booking='".$id_book."'");
			$numrows=mysql_num_rows($query);
			$row = mysql_fetch_array($query);
			if($numrows==0){
				$message = "Wrong number of the booking";
			} else {
				$sql="DELETE FROM booking WHERE ID_booking='".$id_book."'";
				$result=mysql_query($sql); 
				if($result){
					$message = "Successfully deleted";
					$id = 0;
					$sql="UPDATE guest SET ID_booking='".$id."' WHERE ID_booking='".$id_book."'";
					$result=mysql_query($sql);
					$sql="DELETE FROM guest_booking WHERE ID_booking='".$id_book."'";
					$result=mysql_query($sql); 
					$sql="DELETE FROM booking_office WHERE ID_book='".$id_book."'";
					$result=mysql_query($sql); 
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
			<h1>Удаление брони</h1>
			<form action="delete.php" id="bookform" method="post"name="bookform">
				<p>
					<label for="id_book" style="font-family: ‘Carme’, sans-serif">Введите номер брони<br>
						<input class="input" id="id_book" name="id_book"size="32"  type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
					</label>
				</p>
				<p class="submit">
					<input class="button" id="login" name="login"type= "submit" value="Удалить" style="height: 30px; width: 200px; font-size: 20px">
				</p>
				<p><a href="admin.php">Вернуться в личный кабинет</a></p>
			</form>
		</div>
		</font>	
</body>
</html>
	
	
<?php endif; ?>