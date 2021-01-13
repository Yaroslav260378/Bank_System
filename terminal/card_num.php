<?php 
	require "../tmp/bd.php"; ?>

<form method="POST" action="/terminal/card_num.php"> 
	<input type="number" name="card_num">

	<input type="submit" name="do_card_num">
</form>

<?php

$data = $_POST;
$errors = array();
if( isset($data['do_card_num']) )
{
	if( $data['card_num'] > 0 ) {
		$doo =  bcadd('1000000000000000', $data['card_num']);
		$card = R::findOne('cards', 'number = ?', array($doo) );
		}
	if( $card )
	{
			//Логиним пользователя

			$_SESSION['card_num'] = $card;
			?>

			<script type="text/javascript">
			window.location = "/terminal/terminal.php"
			</script>

			<?php

		} 
}
?>