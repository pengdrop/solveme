<?php
	if(isset($_GET['username'], $_GET['email']) && !is_username($_GET['username']) && !is_email($_GET['email'])){
		# user check
		$p = $pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_user`
			WHERE
				BINARY `username`=:username AND
				BINARY `email`=:email
			LIMIT
				1
		");
		$p->bindParam(':username', $_GET['username']);
		$p->bindParam(':email', $_GET['email']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) or die('0');
		die('1');
	}else{
		die('0');
	}