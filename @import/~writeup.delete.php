<?php
	if(isset($_POST['no'])){

		# not logged in
		is_login() or output(['status' => 'l']);

		is_writeup_no($_POST['no']) or output(['status' => 'x']); # valid no

		# check exist writeup
		$p = $pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_writeup`
			WHERE
				`no`=:no
			LIMIT
				1
		");
		$p->bindParam(':no', $_POST['no']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) or output(['status' => 'n']);

		# check permisson
		if(!is_admin()){
			$p = $pdo->prepare("
				SELECT
					1
				FROM
					`{$db_prefix}_writeup`
				WHERE
					`no`=:no AND
					`author`=:username
				LIMIT
					1
			");
			$p->bindParam(':no', $_POST['no']);
			$p->bindParam(':username', $_SESSION['username']);
			$p->execute();
			$p->fetch(PDO::FETCH_ASSOC) or output(['status' => 'p']);
		}

		# delete writeup
		$p = $pdo->prepare("
			DELETE
			FROM
				`{$db_prefix}_writeup`
			WHERE
				`no`=:no
			LIMIT
				1
		");
		$p->bindParam(':no', $_POST['no']);
		$p->execute();

		# check exist writeup
		$p = $pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_writeup`
			WHERE
				`no`=:no
			LIMIT
				1
		");
		$p->bindParam(':no', $_POST['no']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) and output(['status' => 'x']);

		# success
		output(['status' => 'o']);
	}