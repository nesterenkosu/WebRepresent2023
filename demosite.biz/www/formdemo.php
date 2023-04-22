<?php
	//Подключение к БД
	$db_conn=mysqli_connect("localhost","root","");
	
	//Если про подключении возникли проблемы
	if(!$db_conn)
		die("Ошибка подключения к СУБД");
	
	//Выбор базы данных
	if(!mysqli_select_db($db_conn,"db_demosite"))
		die("Ошибка выбора базы данных db_demosite");
	
	$form_fields=$_POST;
	
	$errfields=Array();
	if(isset($_POST["btn_go"])){
		$user_name = mysqli_real_escape_string($db_conn,$_POST["user_name"]);
		$user_age = (int)$_POST["user_age"];
		$user_login =  mysqli_real_escape_string($db_conn,$_POST["user_login"]);
		$user_password =  mysqli_real_escape_string($db_conn,$_POST["user_password"]);
		$user_confirm =  mysqli_real_escape_string($db_conn,$_POST["user_confirm"]);
		
		$error_message = "";
		
		if(trim($user_name) == "") {
			$error_message.="Поле ИМЯ не заполнено<br/>";
			$errfields[]="#user_name";			
		}
		
		if(trim($user_age) == "") {
			$error_message.="Поле ВОЗРАСТ не заполнено<br/>";
			$errfields[]="#user_age";			
		}

		$reg_age = "/^[0-9]+$/";
		if(!preg_match($reg_age,$user_age)) {
			$error_message.="Поле ВОЗРАСТ содержит недопустимые символы<br/>";
			$errfields[]="#user_age";
			
		}
		
		$reg_login = "/^[A-Za-z1-9]{5,}$/";
		if(!preg_match($reg_login,$user_login)) {
			$error_message.="Поле ЛОГИН содержит недопустимые символы<br/>";
			$errfields[]="#user_login";	
		}
		
		if(trim($user_password) == "") {
			$error_message.="Поле ПАРОЛЬ не заполнено<br/>";
			$errfields[]="#user_password";			
		}else{
			$reg_password = "/^.{7,}$/";
			if(!preg_match($reg_password,$user_password)) {
				$error_message.="Поле ПАРОЛЬ должно содержать не менее 7 символов<br/>";
				$errfields[]="#user_password";				
			}
		}
		
		if(trim($user_confirm) == "") {
			$error_message.="Поле ПОДТВЕРЖДЕНИЕ ПАРОЛЯ не заполнено<br/>";
			$errfields[]="#user_confirm";			
		}else{
			if($user_password!=$user_confirm) {
				$error_message.="ПАРОЛЬ не совпадает с ПОДТВЕРЖДЕНИЕМ<br/>";
				$errfields[]="#user_confirm";
			}
		}	
		
		if(trim($error_message=="")){
			
			if(trim($form_fields["user_id"])=="") {
				//Сохранение данных из формы в таблицу БД
				mysqli_query($db_conn,"
					INSERT INTO users(
						Name,Age,Login,Password
					)VALUES(
						'$user_name','$user_age','$user_login','$user_password'
					)
				");
			}else {
				$user_id=(int)$_POST["user_id"];
				mysqli_query($db_conn,"
					UPDATE users
					SET Name='$user_name',
						Age='$user_age',
						Login='$user_login',
						Password='$user_password'
					WHERE
						ID=$user_id
				");
			}
			
			echo mysqli_error($db_conn);
			
			//Перенаправление браузера на эту же страницу
			//для сброса закешированных POST-параметров
			header("Location: $_SERVER[PHP_SELF]");
		}
	}
	
	
	
	//Обработчик нажатия на гиперссылку "Редактировать"
	if(isset($_GET["edit_id"])) {
		$edit_id=(int)$_GET["edit_id"];
		
		$res=mysqli_query($db_conn,"SELECT * FROM users WHERE ID=$edit_id");
		$user=mysqli_fetch_array($res,MYSQL_BOTH);
		
		$form_fields["ID"]=$user["ID"];
		$form_fields["user_name"]=$user["Name"];
		$form_fields["user_age"]=$user["Age"];
		$form_fields["user_login"]=$user["Login"];
		$form_fields["user_password"]=$user["Password"];
		
		
	}
	
	//Обработчик нажатия на гиперссылку "Удалить"
	if(isset($_GET["del_id"])) {
		$del_id=(int)$_GET["del_id"];
		
		mysqli_query($db_conn,"DELETE FROM users WHERE ID=$del_id");
		
		//Перенаправление браузера на эту же страницу
		//для сброса закешированных POST-параметров
		header("Location: $_SERVER[PHP_SELF]");
	}
	
	$country_id=0;
	//Обработчик нажатия на кнопку "Выбрать"
	if(isset($_POST["sel_country"])) {
		$country_id=(int)$_POST["user_country"];
		
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<!--Подключение библиотеки jQuery-->
		<script src="jquery-3.6.4.min.js"></script>
		<script src="myscript.js"></script>
		<script>			
			$(function(){
				//Функция будет вызвана после полной загрузки страницы
				$("#btn_go").click(function(){
					//Функция будет вызвана при нажатии на кнопку
					
					//Сброс форматов полей ввода и сообщения об ошибке
					$("div#error_message").css("display","none");
					$("input").css("border-color","");
					
					//получение имени, введённого в текстовое поле
					let user_name = $("#user_name").val();
					let user_age = $("#user_age").val();
					let user_login =  $("#user_login").val();
					let user_password =  $("#user_password").val();
					let user_confirm =  $("#user_confirm").val();
					
					let error_message = "";
					
					if(user_name.trim() == "") {
						error_message+="Поле ИМЯ не заполнено<br/>";
						$("#user_name").css("border-color","red");
					}
					
					if(user_age.trim() == "") {
						error_message+="Поле ВОЗРАСТ не заполнено<br/>";
						$("#user_age").css("border-color","red");
					}

					let reg_age = /^[0-9]+$/;
					if(!reg_age.test(user_age)) {
						error_message+="Поле ВОЗРАСТ содержит недопустимые символы<br/>";
						$("#user_age").css("border-color","red");
					}
					
					let reg_login = /^[A-Za-z1-9]{5,}$/;
					if(!reg_login.test(user_login)) {
						error_message+="Поле ЛОГИН содержит недопустимые символы<br/>";
						$("#user_login").css("border-color","red");
					}
					
					if(error_message!="") {
						$("div#error_message").css("display","block");
						$("div#error_message").html(error_message);
					}
				});
			});			
		</script>
		<style>
			.must {
				color: red;
			}
			
			div#error_message {
				color: red;
				border-style: solid;
				border-size: 1px;
				border-color: red;
				background-color: #fad9d9;
				display: none;
			}
			
			<?if(count($errfields)>0):?>
			<?=implode(",",$errfields)?>{
				border-color: red;
			}
			
			div#error_message {
				display: block;
			}
			<?endif;?>
		</style>
	</head>
	<body>
		<form action="" method="POST">
		<h1>Форма регистрации на сайте</h1>
		<div id="error_message"><?=$error_message?></div>
		<b>ID:</b><br/>
		<input name="user_id" type="text" size="3" readonly value="<?=$form_fields["ID"]?>"/><br/>
		<b>Имя:</b><span class="must">*</span><br/>
		<input id="user_name" name="user_name" value="<?=$form_fields["user_name"]?>" type="text" size="30"/><br/>		
		<b>Возраст:</b><span class="must">*</span><br/>
		<input id="user_age" name="user_age" type="text" size="3" value="<?=$form_fields["user_age"]?>"/><br/>
		<b>Логин:</b><span class="must">*</span><br/>
		<input id="user_login" name="user_login" type="text" size="15" value="<?=$form_fields["user_login"]?>"/><br/>
		<b>Пароль:</b><span class="must">*</span><br/>
		<input id="user_password" name="user_password" type="password" size="15" value="<?=$form_fields["user_password"]?>"/><br/>
		<b>Подтверждение пароля:</b><span class="must">*</span><br/>
		<input id="user_confirm" name="user_confirm" type="password" size="15" value="<?=$form_fields["user_confirm"]?>"/><br/>
		
		<b>Страна:</b><span class="must">*</span><br/>
		<?$res=mysqli_query($db_conn,"SELECT * FROM countries");?>
		<select name="user_country">
			<?while($country=mysqli_fetch_array($res,MYSQL_BOTH)):?>
			<option value="<?=$country["ID"]?>"><?=$country["Name"]?></option>
			<?endwhile;?>
		</select><input type="submit" name="sel_country" value="Выбрать"/><br/>
		<b>Город:</b><span class="must">*</span><br/>
		<?$res=mysqli_query($db_conn,"SELECT * FROM cities WHERE CountryID=$country_id");?>
		<select>
			<?while($city=mysqli_fetch_array($res,MYSQL_BOTH)):?>
			<option ><?=$city["Name"]?></option>
			<?endwhile;?>
		</select><br/>
		
		
		<input id="btn_go" name="btn_go" type="submit" value="Зарегистрироваться"/>	
		<div id="greeting"></div>
		</form>
		<br/>
		<table border="1">
			<tr>
				<th>ID</th>
				<th>Имя</th>
				<th>Возраст</th>
				<th>Логин</th>
				<th></th>
			</tr>
			<?$res=mysqli_query($db_conn,"SELECT * FROM users");?>
			<?while($user=mysqli_fetch_array($res,MYSQL_BOTH)):?>
			<tr>
				<td><?=$user["ID"]?></td>
				<td><?=$user["Name"]?></td>
				<td><?=$user["Age"]?></td>
				<td><?=$user["Login"]?></td>
				<td>
					<a href="?edit_id=<?=$user["ID"]?>">Редактировать</a>&nbsp;
					<a href="?del_id=<?=$user["ID"]?>" onclick="return confirm('Действительно удалить?')">Удалить</a>
				</td>
			</tr>
			<?endwhile;?>
		</table>
	</body>
</html>
