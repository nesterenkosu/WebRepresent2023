<!DOCTYPE html>
<html>
	<head>
		<style>
			table#layout {
				width: 100%;
			}
			
			td#sidebar {
				width: 20%;				
			}
			
			td#content {
				vertical-align: top;
				background-color: white;
			}
			
			td#footer {
				text-align: center;
			}
			
			td {
				background-color: cyan;
			}
		</style>
	</head>
	<body>
		<table id="layout">
			<tr>
				<td id="header" colspan="2">
					<a href="">Логотип сайта</a>
				</td>
			</tr>
			<tr>
				<td id="sidebar">
					<ul>
						<li><a href="news.php">Новости</a></li>
						<li><a href="actions.php">Акции</a></li>
						<li><a href="about.php">О компании</a></li>
						<li><a href="reviews.php">Отзывы покупателей</a></li>
					</ul>
				</td>
				<td id="content">
					<h1>Основная часть страницы</h1>
				</td>
			</tr>
			<tr>
				<td id="footer" colspan="2">ООО Наша фирма 2023 (с)</td>
			</tr>
		</table>
	</body>
</html>
