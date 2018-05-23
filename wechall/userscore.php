<?php
	if(isset($_GET['username']) && is_username($_GET['username'])){
		# get user info
		$p = $pdo->prepare("
			SELECT
				`x`.`rank`,
				`x`.`score`
			FROM
				(
					SELECT
						@rank:=@rank+1 AS `rank`,
						`username`,
						`score`,
						(SELECT `auth_time` FROM `{$db_prefix}_authlog` WHERE BINARY `username`=`u`.`username` ORDER BY `auth_time` DESC LIMIT 1) AS `last_solved`
					FROM
						`{$db_prefix}_user` AS `u`,
						(SELECT @rank:=0) AS `r`
					ORDER BY
						`score` DESC,
						`last_solved` ASC
				) AS `x`
			WHERE
				`x`.`username`=:username
			LIMIT
				1
		");
		$p->bindParam(':username', $_GET['username']);
		$p->execute();
		$user_info = $p->fetch(PDO::FETCH_ASSOC) or die('0');

		# get challenge info
		$p = $pdo->prepare("
			SELECT
				SUM(`score`) AS `maxscore`,
				COUNT(*) AS `challcount`
			FROM
				`{$db_prefix}_problem`
		");
		$p->execute();
		$chall_info = $p->fetch(PDO::FETCH_ASSOC) or die('0');

		# get authlog info
		$p = $pdo->prepare("
			SELECT
				COUNT(*) AS `challsolved`
			FROM
				`{$db_prefix}_authlog`
			WHERE
				`username`=:username
		");
		$p->bindParam(':username', $_GET['username']);
		$p->execute();
		$authlog_info = $p->fetch(PDO::FETCH_ASSOC) or die('0');

		# get all users info
		$p = $pdo->prepare("
			SELECT
				COUNT(*) AS `usercount`
			FROM
				`{$db_prefix}_user`
		");
		$p->execute();
		$all_users_info = $p->fetch(PDO::FETCH_ASSOC) or die('0');

		$username = $_GET['username'];
		$rank = $user_info['rank'];
		$score = $user_info['score'];
		$maxscore = $chall_info['maxscore'];
		$challsolved = $authlog_info['challsolved'];
		$challcount = $chall_info['challcount'];
		$usercount = $all_users_info['usercount'];

		die("{$username}:{$rank}:{$score}:{$maxscore}:{$challsolved}:{$challcount}:{$usercount}");
	}
	die('0');