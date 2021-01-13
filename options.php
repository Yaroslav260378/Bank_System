<?php require "tmp/bd.php"; 
require "tmp/header.php"?>

 <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Настройки</title>
<link rel='stylesheet' media='(min-width: 1200px)' href='/css/global.css' />
<link rel='stylesheet' media='(max-width: 1200px)' href='/css/mobile.css' />

<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<!-- Форма выбора карт -->
<br>
<section class="main_section container">
<div class="container2">
<div class="item item1">
<!-- Форма закрытия карты -->
				<form class="view_card" method="POST" action="/options.php">
					<p><strong>Закрыть карту</strong></p>
					<?php 
						$data = $_POST; ?>

					<select name="close" class="center">
					<option value="<?php echo $data['close']; ?>"><?php echo $data['close']; ?></option>
					<?php
					mysql_connect($host, $username, $pass);
					mysql_select_db($dbname);
					$data = $_SESSION['logged_user']->email;
					$res = mysql_query('select `number`, `card_type`, `money` from `cards` where user_login="'.$data.'"');
			
					while($row = mysql_fetch_assoc($res)){
						if($row['card_type'] == 'credit') {
					    ?>
					    <option class="red" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php } else if($row['card_type'] == 'nakopit') {
					    ?>
					    <option class="green" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php } else if($row['card_type'] == 'puyer') {
					    ?>
					    <option class="yellow" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php }
					}
					?>
    				</select> 

    				<label class="center label button_add_card"><input type="submit" name="do_close" value="">Закрыть карту</label>


    		<!-- Обработка пред. формы -->
				<?php 
				$data = $_POST;
				if( isset($data['do_close']) )
				{
					$close_c = R::findOne('cards', 'number=?', array($data['close']));
					$clear_history = R::findOne('history', 'card_num=?', array($close_c->number));
					$ostatok = $close_c->money-$close_c->credit_limit;
					if( $data['close'] > 0 ) {
					if( $close_c->money >= $close_c->credit_limit ) {
						$_SESSION['close_c'] = $close_c;
						$_SESSION['clear_history'] = $clear_history;
						$_SESSION['ostatok'] = $ostatok;
						echo "<hr><p class='justify otstup'>Ваших средств на карте: <span class='red'>".$ostatok."</span> грн. Если на карте остались ваши деньги, переведите их на другую карту или они уйдут в фонд банка.</p>";
						echo '<label class="center label button_add_card"><input type="submit" name="do_close_confirm" value="">Подтвердить</label>';

					} else { echo "<hr><p class='red otstup'>Нельзя закрыть карту с не закрытым кредитом</p>"; }
					} else { echo "<hr><p class='red'>Не выбрана карта</p>"; }
				}
				if( isset($data['do_close_confirm']) )
				{
					$close_c = $_SESSION['close_c'];
					if( isset($_SESSION['clear_history']) ) {
						$clear_history = $_SESSION['clear_history'];
						R::trash( $clear_history );
						unset ($_SESSION['clear_history']);
					}
					$ostatok = $_SESSION['ostatok'];
					R::trash( $close_c );
					$bank_card = R::findOne('cards', 'number=?', array('1000000000000000'));
					$bank_card->money = $bank_card->money + $ostatok;
					unset ($_SESSION['close_c']);
					unset ($_SESSION['ostatok']);
					echo "<hr><p class='green'>Карта закрыта</p>";
				}
				 ?>

				</form>
</div>
<div class="item item2">

<!-- Форма профиля пользователя -->
<form class="form_profile" method="POST" action="/options.php">

					<p><strong>Профиль</strong></p>
					<p> <?php echo $_SESSION['logged_user']->fam; ?>  
						<?php  echo $_SESSION['logged_user']->name; ?>
						<?php echo $_SESSION['logged_user']->otchestvo; ?> </p>
					<p><?php echo $_SESSION['logged_user']->email; ?>  </p>

    

				</form>
			</div>
</div>
</section>