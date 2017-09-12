<?php
	if(isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['comment'])){

		is_login() and die(json_encode(array('status' => 'a'))); # already logged

		is_username($_POST['username']) or die(json_encode(array('status' => 'x'))); # valid username
		is_email($_POST['email']) or die(json_encode(array('status' => 'x'))); # valid email
		is_password($_POST['password']) or die(json_encode(array('status' => 'x'))); # valid password
		is_comment($_POST['comment']) or die(json_encode(array('status' => 'x'))); # valid comment

		$p = $pdo->prepare('SELECT 1 FROM solveme_user WHERE username=:username LIMIT 1');
		$p->bindParam(':username', $_POST['username']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) and die(json_encode(array('status' => 'u'))); # already exists username

		$p = $pdo->prepare('SELECT 1 FROM solveme_user WHERE email=:email LIMIT 1');
		$p->bindParam(':email', $_POST['email']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) and die(json_encode(array('status' => 'e'))); # already exists email

		$p = $pdo->prepare("INSERT INTO solveme_user(username, email, password, comment, score) VALUES(:username, :email, :password, :comment, 0)");
		$p->bindParam(':username', $_POST['username']);
		$p->bindParam(':email', $_POST['email']);
		$p->bindValue(':password', secure_hash($_POST['password']));
		$p->bindParam(':comment', $_POST['comment']);
		$p->execute();

		$p = $pdo->prepare('SELECT 1 FROM solveme_user WHERE username=:username AND email=:email AND password=:password LIMIT 1');
		$p->bindParam(':username', $_POST['username']);
		$p->bindParam(':email', $_POST['email']);
		$p->bindValue(':password', secure_hash($_POST['password']));
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) or die(json_encode(array('status' => 'x'))); # not exists username

		set_login($_POST['username']);

		# success
		die(json_encode(array(
			'status' => 'o',
			'username' => $_POST['username']
		)));
	}