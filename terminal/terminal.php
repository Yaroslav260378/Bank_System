<?php require "../tmp/bd.php";
	  require "left_menu.php";

	  $data = $_POST;
	  $zero = 0;
	  if( isset($data['do_input']) ) {
	  $_SESSION['input'] = $_SESSION['input'] + $data['money'];
	  $percent = $_SESSION['input'] / 100 * 2;
	  }
	  ?>

<?php if( isset($_SESSION['card_num']) ) : ?>
	<!-- Если авторизован то открывается сайт -->

<form class="t_container t_height t_form_pop" method="POST" action="/terminal/terminal.php">
<p>Здесь вы можете пополнить вашу карту. Принимаются купюры номиналом 1, 2, 5, 10, 20, 50, 100, 200, 500 грн.</p>
<p><strong>Внесенная сумма: <?php echo $_SESSION['input']+$zero; ?> грн</strong></p>
<p>Комиссия: <?php echo $percent; ?></p>
<input type="submit" name="go" value="Пополнить">

<?php 
 $data = $_POST;
 $zero = 0;
if( isset($data['go']) ) {
	  $bank_card = R::findOne('cards', 'number=?', array('1000000000000000'));
	  $percent = $_SESSION['input'] / 100 * 2;
	  $_SESSION['input'] = $_SESSION['input'] - $percent;

	  $_SESSION['card_num']->money = $_SESSION['card_num']->money + $_SESSION['input'];
	  $bank_card->money = $bank_card->money + $percent;
	  

		$today = date("Y-m-d H:i:s"); 
		// добавление истории
		$story = R::dispense(history);
		$story->card_num = $_SESSION['card_num']->number;
		$story->purpose = "Пополнение карты через терминал";
		$story->sum = $_SESSION['input'];
		$story->date = $today;
		$story->type = '1';
		R::store($story);
		// ---
		R::store($bank_card);
		R::store($_SESSION['card_num']);
		unset($_SESSION['input']);
		echo "<p class=' green'>Карта успешно пополнена</p>";
	  }
?>
</form>

<hr class="t_margin_height" color="#ff0000" style="margin: 0 auto;" />
<a href="/terminal/t.jpg">Терминал</a>


<form  class="t_right_out" method="POST" action="/terminal/terminal.php">
	<p>Купюроприемник</p>
	<input type="number" name="money">
	<input type="submit" name="do_input">
</form>


	<!-- Если не авторизован то на страницу авторизации -->	
	<?php else :?>
		<script type="text/javascript">
		window.location = "/terminal/card_num.php"
		</script>

	<?php endif; ?>	