<?php 
	require "tmp/bd.php"; ?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Авторизация</title>

<link rel='stylesheet' media='(min-width: 1200px)' href='/css/global.css' />
<link rel='stylesheet' media='(max-width: 1200px)' href='/css/mobile.css' />

<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<?php

$data = $_POST;
$errors = array();
if( isset($data['do_login']) )
{
	$user = R::findOne('users', 'email = ?', array($data['email']) );

			if ($data['email'] == "admin@mail.ru" && $data['password'] == "admin") { ?>

				<script type="text/javascript">
			window.location = "admin.php"
			</script> 
		 <?php }
	else if( $user )
	{
		//логин существует
		if( password_verify($data['password'], $user->password) )
		{
			//Логиним пользователя

			$_SESSION['logged_user'] = $user;
			echo '<div> Здравствуйте ' .$_SESSION['logged_user']->email. '</div>'; ?>

			<script type="text/javascript">
			window.location = "index.php"
			</script>

			<?php

		} else
		{
			$errors[] = 'Неверный пароль';
		}
	} else 
	{
		$errors[] = 'Пользователь с таким E-mail не найден';
	}
	if( !empty($errors) )
		{
			echo '<div id="errors">' .array_shift($errors).  '</div><hr>';
		}
}
?>



<form class="login_form abs" action="/login.php" method="POST">

<p><strong>Авторизация</strong></p>

		<p>
		<input id="dynamic-label-input" type="email" name="email" value="<?php echo @$data['login']; ?>" placeholder="E-mail" >
		</p>

		<p>
		<input id="dynamic-label-input" type="password" name="password" value="<?php echo @$data['password']; ?>"  placeholder="Password" >
		</p>

	<label class="center label button_in"><input type="submit" name="do_login" value="">Войти</label>
	<a href="/sign_up.php"><label class="center label button_in">Создать аккаунт</label></a>
</form>

<div class="news_block">
	<p class="center"><STRONG>Новости</STRONG></p>
	<p class="justify">● <?php $set1 = R::load( 'news', 1); echo $set1->text; ?></p>
	<hr>
	<p class="justify">● <?php $set1 = R::load( 'news', 2); echo $set1->text; ?></p>
	<hr>
	<p class="justify">● <?php $set1 = R::load( 'news', 3); echo $set1->text; ?></p>
</div>
