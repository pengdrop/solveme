<?php
	if(isset($_POST['flag'])){
		# not logged in
		is_login() or output(['status' => 'l']);

		# remove flag{ }
		$_POST['flag'] = preg_replace('/\Aflag\{(.*)\}\z/is', '${1}', $_POST['flag']);

		# flag check
		$p = $pdo->prepare("
			SELECT
				`no`,
				`title`,
				`score`
			FROM
				`{$db_prefix}_problem`
			WHERE
				`flag`=:flag
			LIMIT
				1
		");
		$p->bindValue(':flag', secure_hash($_POST['flag']));
		$p->execute();
		$prob = $p->fetch(PDO::FETCH_ASSOC) or output(['status' => 'x']);

		# is already solved
		$p=$pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_authlog`
			WHERE
				`problem_no`=:problem_no AND
				`username`=:username
			LIMIT
				1
		");
		$p->bindParam(':problem_no', $prob['no']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) and output([
			'status' => 'a',
			'title' => secure_escape($prob['title']),
			'href' => secure_escape(get_chall_link($prob['title']))
		]);

		# insert auth log
		$p=$pdo->prepare("
			INSERT INTO
			`{$db_prefix}_authlog`
			(
				`problem_no`,
				`username`
			)
			VALUES
			(
				:problem_no,
				:username
			)
		");
		$p->bindParam(':problem_no', $prob['no']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();

		# update score
		$p=$pdo->prepare("
			UPDATE
				`{$db_prefix}_user`
			SET
				`score`=`score`+:score
			WHERE
				`username`=:username
			LIMIT
				1
		");
		$p->bindParam(':score', $prob['score']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();

		# success
		output([
			'status' => 'o',
			'title' => secure_escape($prob['title']),
			'href' => secure_escape(get_chall_link($prob['title'])),
			'score' => $prob['score']
		]);
	}