<?php
	if(isset($_POST['email'], $_POST['password'], $_POST['new-password'], $_POST['comment'])){
		# not logged in
		is_login() or die(json_encode(array('status' => 'l')));

		is_email($_POST['email']) or die(json_encode(array('status' => 'x'))); # valid email
		is_password($_POST['password']) or die(json_encode(array('status' => 'x'))); # valid password
		if(isset($_POST['new-password'][0])){
			is_password($_POST['new-password']) or die(json_encode(array('status' => 'x'))); # valid new password
		}
		is_comment($_POST['comment']) or die(json_encode(array('status' => 'x'))); # valid comment

		$p = $pdo->prepare('SELECT 1 FROM solveme_user WHERE (SELECT email FROM solveme_user WHERE username=:username LIMIT 1)!=:new_email AND email=:new_email LIMIT 1');
		$p->bindParam(':new_email', $_POST['email']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) and die(json_encode(array('status' => 'e'))); # already exists email

		$p = $pdo->prepare('SELECT 1 FROM solveme_user WHERE username=:username AND password=:password LIMIT 1');
		$p->bindParam(':username', $_SESSION['username']);
		$p->bindValue(':password', secure_hash($_POST['password']));
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) or die(json_encode(array('status' => 'p'))); # wrong password

		# update info
		if(isset($_POST['new-password'][0])){ # password change
			$p=$pdo->prepare('UPDATE solveme_user SET email=:email, password=:password, comment=:comment WHERE username=:username LIMIT 1');
			$p->bindParam(':username', $_SESSION['username']);
			$p->bindParam(':email', $_POST['email']);
			$p->bindParam(':comment', $_POST['comment']);
			$p->bindValue(':password', secure_hash($_POST['new-password']));
			$p->execute();
		}else{
			$p=$pdo->prepare('UPDATE solveme_user SET email=:email, comment=:comment WHERE username=:username LIMIT 1');
			$p->bindParam(':username', $_SESSION['username']);
			$p->bindParam(':email', $_POST['email']);
			$p->bindParam(':comment', $_POST['comment']);
			$p->execute();
		}

		# success
		die(json_encode(array('status' => 'o')));
	}