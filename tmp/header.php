<head>
	<meta charset="UTF-8">
<link rel='stylesheet' media='(min-width: 1200px)' href='../css/global.css' />
<link rel='stylesheet' media='(max-width: 1200px)' href='../css/mobile.css' />
	<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<header class="blue">
	<div class="container clearfix">
		<a href="/index.php" class="absolute button_logo"> <?php echo $_SESSION['logged_user']->email; ?> </a>
		<nav>
			<ul class="menu">
				<li>
					<a class="button" href="/options.php">Настройки</a>
				</li>
				<li class="menu">
					<a class="button" href="/logout.php">Выход</a>
				</li>
			</ul>
		</nav>
	</div>
</header>