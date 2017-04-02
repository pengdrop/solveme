<?php
	if(isset($_POST['flag'])){
		# not logged in
		is_login() or die(json_encode(array('status' => 'l')));

		# remove flag{ }
		$_POST['flag'] = preg_replace('/^flag{(.*)}$/is', '${1}', $_POST['flag']);

		# flag check
		$p = $pdo->prepare('SELECT no, title, score FROM problem WHERE flag=:flag LIMIT 1');
		$p->bindValue(':flag', secure_hash($_POST['flag']));
		$p->execute();
		$prob = $p->fetch(PDO::FETCH_ASSOC) or die(json_encode(array('status' => 'x')));

		# is already solved
		$p=$pdo->prepare('SELECT 1 FROM authlog WHERE problem_no=:problem_no AND username=:username LIMIT 1');
		$p->bindParam(':problem_no', $prob['no']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) and die(json_encode(array('status' => 'a')));

		# insert auth log
		$p=$pdo->prepare("INSERT INTO authlog(problem_no, username) VALUES(:problem_no, :username)");
		$p->bindParam(':problem_no', $prob['no']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();

		# update score
		$p=$pdo->prepare('UPDATE user SET score=score+:score WHERE username=:username LIMIT 1');
		$p->bindParam(':score', $prob['score']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();

		# success
		die(json_encode(array(
			'status' => 'o',
			'title' => secure_escape($prob['title']),
			'score' => $prob['score']
		)));
	}