 <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/css/global.css">

<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<section class="t_menu1 abs">
	<span class="white center t_logo"><strong>Terminal</strong></span>
	<span class="white center t_logo"><strong><?php echo $_SESSION['card_num']->number; ?></strong></span>

		<nav>
			<ul class="t_menu center">
				<li>
					<?php
					if( $_SERVER['REQUEST_URI'] == "/terminal/terminal.php") {
					echo '<a class="t_button t_button_hover" href="/terminal/terminal.php">Пополнение</a>';
					} else {echo '<a class="t_button" href="/terminal/terminal.php">Пополнение</a>';}
					?>
				</li>
				<li>
					<?php
					if( $_SERVER['REQUEST_URI'] == "/terminal/output.php") {
					echo '<a class="t_button t_button_hover" href="/terminal/output.php">Вывод наличных</a>';
					} else {echo '<a class="t_button" href="/terminal/output.php">Вывод наличных</a>';}
					?>
				</li>
				<li>

				<a class="t_button" href="/terminal/logout.php">Завершить работу</a>
					
				</li>
			</ul>
		</nav>
	
</section>