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
		<?php
			require 'database.php';
			$sql_select = "SELECT * FROM guest";
			$result = mysql_query($sql_select);
			$row = mysql_fetch_array($result);
			do{
				if ($row['login'] == $_SESSION['session_username']) {
					echo("<p><h3>Личные данные: </h3></p>");
					echo("<p><u>Гость</u>: ".$row['F']." ".$row['I']." ".$row['O']."</p> 
					<p><u>Дата рождения</u>: ".$row['Birth_date']."</p>
					<p><u>Паспортные данные</u>: ".$row['Passport']."</p>"
					);
					echo("<p><h3>Забронированы следующие номера: </h3></p>");
					$sql="SELECT * FROM guest_booking WHERE ID_guest='".$row['ID_guest']."'";
					$result1 = mysql_query($sql);
					$g_book = mysql_fetch_array($result1);
					$numrows=mysql_num_rows($result1);
					if ($numrows != 0) {
						do {
							$sql = "SELECT * FROM booking WHERE ID_booking='".$g_book['ID_booking']."'";
							$result2 = mysql_query($sql);
							$row1 = mysql_fetch_array($result2);
							$sql = "SELECT Description FROM room WHERE ID_room='".$row1['ID_room']."'";
							$result2 = mysql_query($sql);
							$desc = mysql_fetch_array($result2);
							echo("<p><u>Категория номера</u>: ".$desc['Description']."</p>
							<p><u>Дата заезда</u>: ".$row1['Date_beg']."</p>
							<p><u>Дата выезда</u>: ".$row1['Date_end']."</p>
							<p><u>Стоимость</u>: ".$row1['Cost']."</p><br/>"
							);
						} while($g_book = mysql_fetch_array($result1));
					}
					echo('<p style="font-family: ‘Carme’, sans-serif"><a href="book.php">Забронировать номер</a></p>');
					echo("<p><h3>Заказаны следующие услуги: </h3></p>");
					$sql="SELECT * FROM booked_services WHERE ID_guest='".$row['ID_guest']."'";
					$result1 = mysql_query($sql);
					$booked_s = mysql_fetch_array($result1);
					$numrows=mysql_num_rows($result1);
					if ($numrows != 0) {
						do {
							$sql = "SELECT * FROM services WHERE ID_service='".$booked_s['ID_service']."'";
							$result2 = mysql_query($sql);
							$row1 = mysql_fetch_array($result2);
							echo("<p><u>Услуга</u>: ".$row1['Description']."</p>
							<p><u>Дата</u>: ".$booked_s['Date']."</p>
							<p><u>Стоимость</u>: ".$row1['Cost']."</p><br/>"
							);
						} while($booked_s = mysql_fetch_array($result1));
					}
				}
			} while($row = mysql_fetch_array($result));
        ?>
		<p style="font-family: ‘Carme’, sans-serif"><a href="book_service.php">Заказать услугу</a></p>
		<p style="font-family: ‘Carme’, sans-serif"><a href="delete_service.php">Отменить услугу</a></p>
		<p style="font-family: ‘Carme’, sans-serif"><a href="logout.php">Выйти</a> из системы</p>
		</font>
	</div>
</body>
	
	
<?php endif; ?>