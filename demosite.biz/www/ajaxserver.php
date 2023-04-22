<?
//Подключение к БД
$db_conn=mysqli_connect("localhost","root","");

//Если про подключении возникли проблемы
if(!$db_conn)
	die("Ошибка подключения к СУБД");

//Выбор базы данных
if(!mysqli_select_db($db_conn,"db_demosite"))
	die("Ошибка выбора базы данных db_demosite");

$country_id=(int)$_GET["country_id"];

$res=mysqli_query($db_conn,"SELECT * FROM cities WHERE CountryID=$country_id");

while($city=mysqli_fetch_array($res,MYSQL_BOTH)):?>
			<option value="<?=$city["ID"]?>"><?=$city["Name"]?></option>
<?endwhile;?>