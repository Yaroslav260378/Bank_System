<?php require "tmp/bd.php"; 
require "tmp/header.php"?>
<html>
 <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Главная</title>
<!-- <link rel="stylesheet" href="/css/global.css"> -->
<link rel='stylesheet' media='(min-width: 1200px)' href='/css/global.css' />
<link rel='stylesheet' media='(max-width: 1200px)' href='/css/mobile.css' />

<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>


<body>
	<?php if( isset($_SESSION['logged_user']) ) {  ?>
	<!-- Если авторизован то открывается сайт -->

<section class="main_section container"> <br>
<div class="container2">
<div class="iten item3">
				<form class="addcard" method="POST" action="/index.php">
					<p><strong>Создать карту</strong></p>
					<?php
					$data = $_POST;
    				$c_name = $data['card_name'];
    				if( $c_name == "credit" ){$c_name2 = "Кредитная";}else if($c_name == "puyer"){$c_name2="Для выплат";}else if($c_name == "nakopit"){$c_name2="Накопительная";}   ?>
    				<div class='card_add_name'>
					<select name="card_name" >
					<option value="<?php echo $c_name; ?>"><?php echo $c_name2; ?></option>
			        <option value="credit">Кредитная</option>
			        <option value="puyer">Для выплат</option>
			        <option value="nakopit">Накопительная</option>
    				</select> 
    				<input class="pin_widht" maxlength="4" minlength="4" required placeholder="ПИН-код" type="password" name="pin" value="<?php echo $data['pin']; ?>">
    				</div>

    				<label class="center label button_add_card"><input type="submit" name="add_card" value="">Создать карту</label>

    				<?php 
    				if( isset($data['add_card']) && ($data['card_name'] != '0') )
					{
						$_SESSION['c_name'] = $data['card_name'];
						if($data['card_name'] == credit) { $s="кредитную"; } else if($data['card_name']== puyer){
							$s="рабочую";} else if($data['card_name'] == "nakopit") {$s="накопительную";}
						echo '<p>Вы хотите создать ' .$s. ' карту?</p> <label class="center label button_add_card"><input type="submit" name="add_card_confirm" value="">Подтвердить</label>';
					}
    				?>

    		<!-- Обработка пред. формы -->
				<?php 
				if( isset($data['add_card_confirm']) )
				{
					$max_card = R::findLast('cards');
					$foo = bcadd($max_card->number, 1);
					if( $_SESSION['c_name'] == 'credit')
					{
						unset ($_SESSION['c_name']);
						$card = R::dispense(cards);
						$card->user_login = $_SESSION['logged_user']->email;
						$card->pin = password_hash($data['pin'], PASSWORD_DEFAULT);
						$card->card_type = 'credit';
						$card->number = $foo;
						$card->percent = 0.24;
						$card->money = 1000;
						$card->credit_limit = 1000;
						R::store($card);
						echo '<hr class="hrr" ><div class="center green">Карта с номером ' .$foo. ' создана</div>';
					} else if( $_SESSION['c_name'] == 'puyer' ) {
						unset ($_SESSION['c_name']);
						$card = R::dispense(cards);
						$card->user_login = $_SESSION['logged_user']->email;
						$card->pin = password_hash($data['pin'], PASSWORD_DEFAULT);
						$card->card_type = 'puyer';
						$card->number = $foo;
						$card->percent = 0.5;
						$card->money = 0;
						$card->credit_limit = 0;
						R::store($card);
						echo '<hr class="hrr" ><div class="center green">Карта с номером ' .$foo. ' создана</div>';
					} else if ( $_SESSION['c_name'] == 'nakopit' ) {
						unset ($_SESSION['c_name']);
						$card = R::dispense(cards);
						$card->user_login = $_SESSION['logged_user']->email;
						$card->pin = password_hash($data['pin'], PASSWORD_DEFAULT);
						$card->card_type = 'nakopit';
						$card->number = $foo;
						$card->percent = 0.5;
						$card->money = 0;
						$card->credit_limit = 0;
						R::store($card);
						echo '<hr class="hrr" ><div class="center green">Карта с номером ' .$foo. ' создана</div>';
					}
				}
				 ?>

				</form>
</div>
<div class="iten item1">
<!-- Форма выбора карт -->
<!-- Скрипт отправки формы после выбора карты -->
<script type="text/javascript">
function MyForm()
{
	var f_data = "<?php $select_number = $data['view_c']; ?>";
   document.getElementById('go').submit();
}
</script>
				<form class="view_card" id="go" method="POST" action="/index.php">
					<p><strong>Выбор карты</strong></p>

					<select name="view_c" class="center" onchange="return MyForm();">
					<option value="0">Выберите карту</option>
					<?php
					mysql_connect($host, $username, $pass);
					mysql_select_db($dbname);
					$data = $_SESSION['logged_user']->email;
					$res = mysql_query('select `number`, `card_type`, `money` from `cards` where user_login="'.$data.'"');
			
					while($row = mysql_fetch_assoc($res)){
						if($row['card_type'] == 'credit') {
					    ?>
					    <option class="red" value="<?php echo $row['number'];?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php } else if($row['card_type'] == 'nakopit') { ?>
					    <option class="green" value="<?php echo $row['number']; ?>"> <?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php } else if($row['card_type'] == 'puyer') { ?>
					    <option class="yellow" value="<?php echo $row['number'];?>"><?php echo $row['number'];?> <?php echo $row['money']; ?>грн</option>
					    <?php }
					}
					?>
    				</select> 


				</form>
</div>
<div class="iten item2">
<!-- Форма выбранной карты -->
				<form class="selected_card" method="POST" action="/index.php">
					<p><strong>Выбранная карта</strong></p>
					<p class="left abs"> <?php echo $select_number; ?> </p>
					<p class="right"> <?php $data = R::findOne('cards', 'number = ?', array($select_number)); echo $data->money; ?> UAN</p>

					<?php 
					if($data->card_type == 'credit') {
						echo "<p class='red left'>Тип карты: Кредитная</p>";
					} else if ($data->card_type == 'puyer') {
						echo "<p class='yellow left'>Тип карты: Для выплат</p>";
					} else if ($data->card_type == 'nakopit') {
						echo "<p class='green left'>Тип карты: Накопительная</p>";
					} ?>
				</form>
</div>
<div class="iten item4">
<!-- Перевода средств с карты на свою карту -->
				<form class="transfer" method="POST" action="/index.php">
					<p><strong>Перевод средств между своими счетами</strong></p>
					<p>С карты:</p>
					<?php $data = $_POST;
					    ?>

					<select name="transfer_c1" class="center">
					<option value="<?php echo $data['transfer_c1']; ?>"><?php echo $data['transfer_c1']; ?></option>
					<?php
					mysql_connect($host, $username, $pass);
					mysql_select_db($dbname);
					$data_mail = $_SESSION['logged_user']->email;
					$res = mysql_query('select `number`, `card_type`, `money` from `cards` where user_login="'.$data_mail.'"');
			
					while($row = mysql_fetch_assoc($res)){
						if($row['card_type'] == 'credit') {
					    ?>
					    <option class="red" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php  } else if($row['card_type'] == 'nakopit') {
					    ?>
					    <option class="green" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php } else if($row['card_type'] == 'puyer') {
					    ?>
					    <option class="yellow" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php }
					}
					?>
    				</select> 
    				<p>На карту:</p>

    				<select name="transfer_c2" class="center">
					<option value="<?php echo $data['transfer_c2']; ?>"><?php echo $data['transfer_c2']; ?></option>
					<?php
					mysql_connect($host, $username, $pass);
					mysql_select_db($dbname);
					$data_mail = $_SESSION['logged_user']->email;
					$res = mysql_query('select `number`, `card_type`, `money` from `cards` where user_login="'.$data_mail.'"');
			
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
					    <?php  }
					}
					?>
    				</select> 
    				<p>Сумма:</p>
    				 <input step="0.01" class="center" type="number" name="sum" value="<?php echo $data['sum']; ?>">

    				<label class="center label button_add_card"><input type="submit" name="do_transfer" value="">Перевести</label>
    				

					<?php 
					$summa = $data['sum'];
					
					if( isset($data['do_transfer3'] )) {
						$bank_card = R::findOne('cards', 'number=?', array('1000000000000000'));
						$card_transfer1 = $_SESSION['card_transfer1'];
						$card_transfer2 = $_SESSION['card_transfer2'];
						$percent = $_SESSION['percent'];
						$today = date("Y-m-d H:i:s"); 
						// добавление истории
						//карта отправителя
						$story = R::dispense(history);
						$story->card_num = $card_transfer1->number;
						$story->purpose = "Перевод на карту " . $card_transfer2->number;
						$story->sum = $data['sum'];
						$story->date = $today;
						$story->type = '0';
						R::store($story);
						//карта получателя
						$story = R::dispense(history);
						$story->card_num = $card_transfer2->number;
						$story->purpose = "Перевод с карты " . $card_transfer1->number;
						$story->sum = $data['sum'];
						$story->date = $today;
						$story->type = '1';
						R::store($story);
						// ---
						unset ($_SESSION['card_transfer1']);
						unset ($_SESSION['card_transfer2']);
						unset ($_SESSION['percent']);
							$card_transfer1->money = round($card_transfer1->money - $data['sum'] - $percent, 2);
							$card_transfer2->money = round($card_transfer2->money + $data['sum'], 2);
							$bank_card->money = $bank_card->money + $percent;
							R::store($bank_card);
							R::store($card_transfer1);
							R::store($card_transfer2);
							echo "<hr><p class='green'>Деньги переведены</p>"; }

					if( isset($data['do_transfer']) )
					{
						

						if( $data['transfer_c2'] != $data['transfer_c1']) {
						if( $data['transfer_c1'] > 0) {
						if( $data['transfer_c2'] > 0) {
						$card_transfer1 = R::findOne('cards', 'number=?', array($data['transfer_c1']));
						$card_transfer2 = R::findOne('cards', 'number=?', array($data['transfer_c2']));
						//подсчет процента
						if($card_transfer1->card_type == 'credit') {
							$percent = $data['sum'] / 100 * 4;
						} else if ($card_transfer1->card_type == 'puyer') {
							$percent = $data['sum'] / 100 * 0.5;
						
						} else if ($card_transfer1->card_type == 'nakopit') {
							$percent = $data['sum'] / 100 * 0.5;
						}
						//---
						if( $card_transfer1->money-$percent >= $data['sum']) {
						if( $data['sum'] > 0 ) {

						

						$_SESSION['card_transfer1'] = $card_transfer1;
						$_SESSION['card_transfer2'] = $card_transfer2;
						$_SESSION['percent'] = $percent;


						echo "<p>Коммисия: ".$percent."</p><label class='center label button_add_card'><input type='submit' name='do_transfer3' value=''>Подтвердить</label> ";
							
							
				} else { echo "<hr><p class='red'>Не выбрана сумма</p>";} 
				} else { echo "<hr><p class='red'>Недостаточно средств</p>";} 
				} else { echo "<hr><p class='red'>Не выбрана карта для перевода</p>";}
				} else { echo "<hr><p class='red'>Не выбрана карта</p>";} 
				} else { echo "<hr><p class='red'>Карта для перевода должна быть другая</p>"; } }
				 ?>
				
				</form>
</div>
<div class="iten item5">
<!-- Перевода средств с карты по номеру -->
				<form class="transfer_number_form" method="POST" action="/index.php">
					<p><strong>Перевод средств по номеру карты</strong></p>
					<p>С карты:</p>
					<?php $data = $_POST; ?>

					<select name="transfer_c3" class="center">
					<option value="<?php echo $data['transfer_c3']; ?>"><?php echo $data['transfer_c3']; ?></option>
					<?php
					mysql_connect($host, $username, $pass);
					mysql_select_db($dbname);
					$data_mail = $_SESSION['logged_user']->email;
					$res = mysql_query('select `number`, `card_type`, `money` from `cards` where user_login="'.$data_mail.'"');
			
					while($row = mysql_fetch_assoc($res)){
						if($row['card_type'] == 'credit') {
					    ?>
					    <option class="red" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php } else if($row['card_type'] == 'nakopit') {
					    ?>
					    <option class="green" value="<?php echo $row['number'];?>"><?php echo $row['number'];?> <?php echo $row['money']; ?>грн</option>
					    <?php } else if($row['card_type'] == 'puyer') {
					    ?>
					    <option class="yellow" value="<?php echo $row['number']; ?>"><?php echo $row['number']; ?> <?php echo $row['money']; ?>грн</option>
					    <?php }
					}
					?>
    				</select> 
    				<p>На счет:</p>
    				<input step="1" class="center" type="number" name="num_c" maxlength="16" minlength="16" value="<?php echo $data['num_c']; ?>">
    				
    				
    				<p>Сумма:</p>
    				 <input step="0.01" class="center" type="number" name="sum2" value="<?php echo $data['sum2']; ?>">

    				<label class="center label button_add_card"><input type="submit" name="do_transfer2" value="">Перевести</label>
    			

					<?php 
					$summa = $data['sum2'];
					$bank_card = R::findOne('cards', 'number=?', array('1000000000000000'));

					if( isset($data['do_transfer_num_confirm']) ) {
						$card_transfer3 = $_SESSION['card_transfer3'];
						$card_transfer4 = $_SESSION['card_transfer4'];
						$percent = $_SESSION['percent'];
						$today = date("Y-m-d H:i:s");
						// добавление истории
						//карта отправителя
						$story = R::dispense(history);
						$story->card_num = $card_transfer3->number;

						if( $data['nazn'] != '') {
						$story->purpose = 'Перевод по номеру карты ' . $card_transfer4->number . ' Сообщение: ' . $data['nazn'];
						} else {$story->purpose = 'Перевод по номеру карты ' . $card_transfer4->number;}

						$story->sum = $data['sum2'];
						$story->date = $today;
						$story->type = '0';
						R::store($story);
						//карта получателя
						$story = R::dispense(history);
						$story->card_num = $card_transfer4->number;

						if( $data['nazn'] != '') {
						$story->purpose = 'Перевод по номеру карты от ' . $card_transfer3->number . ' Сообщение: ' . $data['nazn'];
						} else {$story->purpose = 'Перевод по номеру карты от ' . $card_transfer3->number;}

						$story->sum = $data['sum2'];
						$story->date = $today;
						$story->type = '1';
						R::store($story);
						// ---
						unset ($_SESSION['card_transfer3']);
						unset ($_SESSION['card_transfer4']);
						unset ($_SESSION['percent']);
							$card_transfer3->money = round($card_transfer3->money - $data['sum2'] - $percent, 2);
							$card_transfer4->money = round($card_transfer4->money + $data['sum2'], 2);
							$bank_card->money = $bank_card->money + $percent;
							R::store($bank_card);
							R::store($card_transfer3);
							R::store($card_transfer4);
							echo "<hr><p class='green'>Деньги переведены</p>"; }

					if( isset($data['do_transfer2']) )
					{
						if( $data['transfer_c3'] != $data['num_c']) {
						if( $data['transfer_c3'] > 0) {
						if( $data['num_c'] > 0) {
						$card_transfer3 = R::findOne('cards', 'number=?', array($data['transfer_c3']));
						$card_transfer4 = 0;
						$card_transfer4 = R::findOne('cards', 'number=?', array($data['num_c']));
						if( $card_transfer4 != 0) {
						//подсчет процента
							if($card_transfer3->card_type == 'credit') {
							$percent = $data['sum2'] / 100 * 4;
						} else if ($card_transfer3->card_type == 'puyer') {
							$percent = $data['sum2'] / 100 * 0.5;
						
						} else if ($card_transfer3->card_type == 'nakopit') {
							$percent = $data['sum2'] / 100 * 0.5;
						}

						//---
						if( $card_transfer3->money-$percent >= $data['sum2']) {
						if( $data['sum2'] > 0 ) {

						
						$_SESSION['card_transfer3'] = $card_transfer3;
						$_SESSION['card_transfer4'] = $card_transfer4;
						$_SESSION['percent'] = $percent;
					
						$pers = R::findOne('users', 'email=?', array($card_transfer3->user_login));
						echo "<p>Карта: ".$data['num_c']."</p> <p>".$pers->fam." ".$pers->name." ".$pers->otchestvo."</p>
						<input class='nazn center' type='text' name='nazn' placeholder='Назначение платежа'>
						<p>Комиссия: ".$percent." </p> <label class='center label button_add_card'><input type='submit' name='do_transfer_num_confirm' value=''>Подтвердить</label> ";
						

				} else { echo "<hr><p class='red'>Не выбрана сумма</p>";} 
				} else { echo "<hr><p class='red'>Недостаточно средств</p>";} 
				} else { echo "<hr><p class='red'>Карты с номером ".$data['num_c']." не существует</p>";} 
				} else { echo "<hr><p class='red'>Не выбрана карта для перевода</p>";}
				} else { echo "<hr><p class='red'>Не выбрана карта</p>";} 
				} else { echo "<hr><p class='red'>Карта для перевода должна быть другая</p>"; } }
				 ?>
				
				</form>
</div>
<div class="iten item6">

				<form class="history_form" method="POST" action="/index.php">
					<p><strong>История транзакций</strong></p>


					<?php
					mysql_connect($host, $username, $pass);
					mysql_select_db($dbname);

					$res = mysql_query('select `purpose`, `sum`, `date`, `type` from `history` where card_num="'.$select_number.'" order by id desc');
			
					while($row = mysql_fetch_assoc($res)){
						if( $row['type'] == '1' ) {
							echo '<p class="left green widthh">+'.$row['sum'].' грн</p>';
						} else 
						if( $row['type'] == '0' ) {
							echo '<p class="left red widthh">-'.$row['sum'].' грн</p>';
						}

						echo '<p class="right">'.$row['date'].'</p><p>Назначение платежа: '.$row['purpose'].'</p><hr>';
											}
					?>
    				</select> 

    			
				
				</form>
</div>				
</div>
</section>

	<!-- Если не авторизован то на страницу авторизации -->	
	<?php } else { ?>
		<script type="text/javascript">
		window.location = "login.php"
		</script>

	<?php } ?>	
</body>
</html>