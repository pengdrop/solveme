<?php
	if(isset($_POST['email'], $_POST['password'], $_POST['new-password'], $_POST['comment'])){
		# not logged in
		is_login() or output(['status' => 'l']);

		is_email($_POST['email']) or output(['status' => 'x']); # valid email
		is_open_email($_POST['open-email']) or output(['status' => 'x']); # valid open email
		is_password($_POST['password']) or output(['status' => 'x']); # valid password
		if(isset($_POST['new-password'][0])){
			is_password($_POST['new-password']) or output(['status' => 'x']); # valid new password
		}
		is_comment($_POST['comment']) or output(['status' => 'x']); # valid comment

		$p = $pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_user`
			WHERE
				(SELECT `email` FROM `{$db_prefix}_user` WHERE `username`=:username LIMIT 1)!=:new_email AND
				`email`=:new_email
			LIMIT
				1
		");
		$p->bindParam(':new_email', $_POST['email']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) and output(['status' => 'e']); # already exists email

		$p = $pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_user`
			WHERE
				`username`=:username AND
				`password`=:password
			LIMIT
				1
		");
		$p->bindParam(':username', $_SESSION['username']);
		$p->bindValue(':password', secure_hash($_POST['password']));
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) or output(['status' => 'p']); # wrong password

		# update info
		if(isset($_POST['new-password'][0])){ # password change
			$p=$pdo->prepare("
				UPDATE
					`{$db_prefix}_user`
				SET
					`email`=:email,
					`open_email`=:open_email,
					`password`=:password,
					`comment`=:comment
				WHERE
					`username`=:username
				LIMIT
					1
			");
			$p->bindParam(':username', $_SESSION['username']);
			$p->bindParam(':email', $_POST['email']);
			$p->bindValue(':open_email', isset($_POST['open-email']) ? '1' : '0');
			$p->bindParam(':comment', $_POST['comment']);
			$p->bindValue(':password', secure_hash($_POST['new-password']));
			$p->execute();
		}else{
			$p=$pdo->prepare("
				UPDATE
					`{$db_prefix}_user`
				SET
					`email`=:email,
					`open_email`=:open_email,
					`comment`=:comment
				WHERE
					`username`=:username
				LIMIT
					1
			");
			$p->bindParam(':username', $_SESSION['username']);
			$p->bindParam(':email', $_POST['email']);
			$p->bindValue(':open_email', isset($_POST['open-email']) ? '1' : '0');
			$p->bindParam(':comment', $_POST['comment']);
			$p->execute();
		}

		# success
		output(['status' => 'o']);
	}