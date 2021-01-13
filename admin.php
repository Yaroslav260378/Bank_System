<?php 
	require "tmp/bd.php"; ?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Админ-панель</title>
<link rel="stylesheet" href="/css/global.css">

<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<form class="news" method="POST" action="/admin.php">
					<p><strong>Блок новостей</strong></p>
					<p>1 блок</p>
					<?php $set1 = R::load( 'news', 1);
					   $set2 = R::load( 'news', 2);
					   $set3 = R::load( 'news', 3); ?>
					<p><textarea class="area" rows="3" cols="45" name="text1"  maxlength="150"><?php echo $set1->text; ?></textarea></p>
					<p>2 блок</p>
					<p><textarea class="area" rows="3" cols="45" name="text2" maxlength="150"><?php echo $set2->text; ?></textarea></p>
					<p>3 блок</p>
					<p><textarea class="area" rows="3" cols="45" name="text3" maxlength="150"><?php echo $set3->text; ?></textarea></p>
					

    				<label class="center label button_add_card"><input type="submit" name="reload_news" value="">Обновить новости</label>

    		<!-- Обработка пред. формы -->
				<?php 
				$data = $_POST;
				if( isset($data['reload_news']) )
				{
					//обновляем новости
					$update = R::load( 'news', 1);
					$update->text = $data['text1']; 
					R::store($update);

					$update = R::load( 'news', 2);
					$update->text = $data['text2']; 
					R::store($update);

					$update = R::load( 'news', 3);
					$update->text = $data['text3']; 
					R::store($update);

					?> 
					<script type="text/javascript">
					window.location = "admin.php"
					</script>
					<?php
				}
				 ?>

				</form>