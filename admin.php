<?php

session_start();

if(!isset($_SESSION["session_username"])):
header("location:login.php");
else:
?>
	
<body bgcolor = "#DEF2F1">
	<div>
		<font size = "5">
		<h2>Добро пожаловать, <span><?php echo $_SESSION['session_username'];?>! </span></h2>
		<p style="font-family: ‘Carme’, sans-serif"><h3>Вы работаете со следующими бронями:</h3></p>
		<?php
			require 'database.php';		
			$lg = $_SESSION['session_username'];
			$sql="SELECT ID_admin FROM administrator WHERE login='".$lg."'";
			$result=mysql_query($sql);
			$id_admin = mysql_fetch_array($result);
			$sql = "SELECT * FROM booking_office WHERE ID_admin='".$id_admin['ID_admin']."'";
			$result1 = mysql_query($sql);
			$row = mysql_fetch_array($result1);
			do {
				echo ($row['ID_book']."<br/>");
			} while ($row = mysql_fetch_array($result1));
        ?>
		<form action="admin.php" id="book" method="post"name="book">
			<p>
				<label for="id_book" style="font-family: ‘Carme’, sans-serif"><h3>Введите номер брони</h3>
					<input class="input" id="id_book" name="id_book"size="32"  type="text" value="" style="height: 30px; width: 200px; font-size: 20px">
				</label>
			</p>
			<p class="submit"><input class="button" id="register" name= "register" type="submit" value="Найти" style="height: 30px; width: 200px; font-size: 20px"></p>
		</form>
		<?php
			if(!empty($_POST['id_book'])) {
				$id_book = htmlspecialchars($_POST['id_book']);
				$sql = "SELECT * FROM booking WHERE ID_booking='".$id_book."'";
				$result1 = mysql_query($sql);
				$row1 = mysql_fetch_array($result1);
				$sql = "SELECT Description FROM room WHERE ID_room='".$row1['ID_room']."'";
				$result1 = mysql_query($sql);
				$desc = mysql_fetch_array($result1);
				echo("<p><u>Категория номера</u>: ".$desc['Description']."</p>
				<p><u>Дата заезда</u>: ".$row1['Date_beg']."</p>
				<p><u>Дата выезда</u>: ".$row1['Date_end']."</p>
				<p><u>Стоимость</u>: ".$row1['Cost']."</p>"
				);
				echo "<p><a href="."edit_book.php".">Редактировать</a></p><br/>";
			}
        ?>
		<p><a href="logout.php">Выйти</a> из системы</p>
		</font>
	</div>
</body>
	
	
<?php endif; ?>