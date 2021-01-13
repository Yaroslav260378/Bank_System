<?php require "tmp/bd.php"; ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Регистрация</title>
<link rel="stylesheet" href="/css/global.css">

<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<style>
body {
	background: url(https://99px.ru/sstorage/53/2011/12/tmb_29652_6919.jpg);
	background-repeat: no-repeat;
	background-size: cover;
}
</style>
</head>

<?php

	$data = $_POST;
	$errors = array();
	if( isset($data['do_signup']) )
	{
		//Здесь регистрируем

		if( trim($data['fam']) == '' )
		{
			$errors[] = 'Введите фамилию';
		}

		if( trim($data['name']) == '' )
		{
			$errors[] = 'Введите имя';
		}

		if( trim($data['otchestvo']) == '' )
		{
			$errors[] = 'Введите отчество';
		}

		if( $data['email'] == '' )
		{
			$errors[] = 'Введите Email';
		}

		if( $data['password'] == '' )
		{
			$errors[] = 'Введите пароль';
		}

		if( $data['password2'] != $data['password'] )
		{
			$errors[] = 'Повторный пароль введен не верно';
		}

		if( R::count('users', "email = ?", array($data['email'])) > 0  )
		{
			$errors[] = 'Пользователь с таким Email уже существует';
		}


		if( empty($errors) )
		{
			//Можно регистрировать
			$user = R::dispense(users);
			$user->email = $data['email'];
			$user->password = password_hash($data['password'], PASSWORD_DEFAULT);
			$user->fam = $data['fam'];
			$user->name = $data['name'];
			$user->otchestvo = $data['otchestvo'];
			R::store($user);
			?>
			<script type="text/javascript">
			window.location = "index.php"
			</script>
			<?php
		} else {
			echo '<div class="center red" id="errors"><strong>' .array_shift($errors).  '</strong></div><hr>';
		}
	}
?>

<form class="register_form" action="/sign_up.php" method="POST">

	<p><strong>Регистрация</strong></p>
	<p>
		<input placeholder="Фамилия" type="text" name="fam" value="<?php echo @$data['fam']; ?>" >
	</p>

	<p>
		<input placeholder="Имя" type="text" name="name" value="<?php echo @$data['name']; ?>" >
	</p>

	<p>
		<input placeholder="Отчество" type="text" name="otchestvo" value="<?php echo @$data['otchestvo']; ?>" >
	</p>

	<p>
		<input placeholder="Email" type="email" name="email" value="<?php echo @$data['email']; ?>">
	</p>

	<p>
		<input placeholder="Пароль" type="password" name="password" value="<?php echo @$data['password']; ?>">
	</p>

	<p>
		<input placeholder="Пароль еще раз" type="password" name="password2" value="<?php echo @$data['password2']; ?>">
	</p>

<p>
	<button type="submit" name="do_signup">Зарегестрироваться</button>
</p>

</form>
