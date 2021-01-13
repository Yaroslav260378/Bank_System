<?php require "../tmp/bd.php";
	  require "left_menu.php";
	  ?>

<?php if( isset($_SESSION['card_num']) ) : ?>
	<!-- Если авторизован то открывается сайт -->


<form  class="t_container t_height output_form" method="POST" action="/terminal/output.php">

<?php 
 $data = $_POST;
 $zero = 0;
if( isset($data['do_output']) ) {
	  $bank_card = R::findOne('cards', 'number=?', array('1000000000000000'));
	  $percent = $data['money'] / 100 * 2;
	  $data['money'] = $data['money'];
	  if( $data['money'] == 50 or $data['money']  == 100 or $data['money'] == 200 or $data['money'] == 500 ) {
	  if( $_SESSION['card_num']->money >= ($data['money'] + $percent) ) {
	  $_SESSION['card_num']->money = $_SESSION['card_num']->money - $data['money'] - $percent;
	  $bank_card->money = $bank_card->money + $percent;
	  $out = $data['money'];
	  

		$today = date("Y-m-d H:i:s"); 
		// добавление истории
		$story = R::dispense(history);
		$story->card_num = $_SESSION['card_num']->number;
		$story->purpose = "Снятие наличных через терминал";
		$story->sum = $data['money'];
		$story->date = $today;
		$story->type = '0';
		R::store($story);
		// ---
		R::store($bank_card);
		R::store($_SESSION['card_num']);
		$mes = 1;
	} else { $mes = 2; }
	} else { $mes = 3; }
}
?>

	<p>Вы можете вывести деньги с карты написав сумму которую хотите вывести. <br>Снять можно купюры номиналом 50, 100, 200, 500 грн. Комиссия составляет 2%.</p>
	<p><strong>Выдача наличных</strong></p>
	<p>Доступных средств для снятия: <?php echo $_SESSION['card_num']->money;?> грн</p>
	<input type="number" name="money">
	<input type="submit" name="do_output" value="Снять наличные">

	<?php
	if( $mes == 1 ) {
		echo "<p class=' green'>Возьмите ваши деньги</p>";
		$mes = 0;
	} else if( $mes == 2 ) {
		echo "<p class=' red'>Недостаточно средств на карте</p>";
		$mes = 0;
	} else if( $mes == 3 ) {
		echo "<p class=' red'>Выдаются только купюры номиналом 50, 100, 200, 500 грн.</p>";
		$mes = 0;
	}
	?>

</form>

<hr class="t_margin_height" color="#ff0000" style="margin: 0 auto;" />
<a href="/terminal/t.jpg">Терминал</a>


<form  class="t_right_out" method="POST" action="/terminal/terminal.php">
	<span>Выдача наличных</sp>
	<P><STRONG>Выдано наличных: <?php echo $out; ?> грн</STRONG></P>
</form>

	<!-- Если не авторизован то на страницу авторизации -->	
	<?php else :?>
		<script type="text/javascript">
		window.location = "/terminal/card_num.php"
		</script>

	<?php endif; ?>	