<?php
	if(isset($_POST['username'], $_POST['password'])){

		is_login() and die(json_encode(array('status' => 'a'))); # already logged

		is_username($_POST['username']) or die(json_encode(array('status' => 'x'))); # valid username 
		is_password($_POST['password']) or die(json_encode(array('status' => 'x'))); # valid password

		$p = $pdo->prepare('SELECT username FROM user WHERE username=:username AND password=:password LIMIT 1');
		$p->bindParam(':username', $_POST['username']);
		$p->bindValue(':password', secure_hash($_POST['password']));
		$p->execute();
		$my_info = $p->fetch(PDO::FETCH_ASSOC) or die(json_encode(array('status' => 'x'))); # failure

		set_login($my_info['username']);

		# success
		die(json_encode(array(
			'status' => 'o',
			'username' => $my_info['username']
		)));
	}