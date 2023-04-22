<?php
	$greeting="";
	if(isset($_POST["btn_Go"])) {
		$user_name=$_POST["user_name"];
		$greeting="Здравствуйте, $user_name !";
	}
?>
<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<form action="" method="POST">
			Введите ваше имя:<br/>
			<input name="user_name" type="text" value="<?=$_POST["user_name"]?>"/><br/>
			<input name="btn_Go" type="submit" value="Ввод"/>
			<div><?=$greeting?></div>
		</form>
	</body>
</html>
