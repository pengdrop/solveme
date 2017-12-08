<?php
	# common header
	$title = __SITE__['title'].' » Writeup';
	$need_login = true;
	$js_files = [
		'/assets/js/jquery.min.js',
		'/assets/js/bootstrap.min.js',
		'/assets/js/common.js'
	];
	$show_category = true;
	require __DIR__.'/header.php';
?>
					<main class="main-body">
						<table class="table table-striped table-hover table-responsive m-0">
							<colgroup>
								<col style="width:10%">
								<col style="width:40%">
								<col style="width:20%">
								<col style="width:30%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col" class="text-center">#</th>
									<th scope="col">Problem</th>
									<th scope="col">Writter</th>
									<th scope="col">Writed Time</th>
								</tr>
							</thead>
							<tbody>
<?php
	$p = $pdo->prepare("
		SELECT
			`no`,
			`problem_no`,
			(SELECT `title` FROM `{$db_prefix}_problem` WHERE `no`=`w`.`problem_no` LIMIT 1) AS `problem_title`,
			(SELECT COUNT(*) FROM `{$db_prefix}_writeup` WHERE `problem_no`=`w`.`problem_no` AND `no` <= `w`.`no`) AS `idx`,
			`author`,
			(SELECT `comment` FROM `{$db_prefix}_user` AS `u` WHERE `username`=`w`.`author` LIMIT 1) AS `user_comment`,
			`register_time`
		FROM
			`{$db_prefix}_writeup` AS `w`
		ORDER BY
			`no` DESC
		LIMIT
			0, 16
	");
	$p->execute();
	$writeup_info = $p->fetchAll(PDO::FETCH_ASSOC);

	$p = $pdo->prepare("
		SELECT
			`problem_no`
		FROM
			`{$db_prefix}_authlog` AS `a`
		WHERE
			`username`=:username
	");
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$my_solved_prob_info = $p->fetchAll(PDO::FETCH_ASSOC);

	$my_solved_list = [];
	foreach($my_solved_prob_info as $i => $v){
		array_push($my_solved_list, $v['problem_no']);
	}

	for($i = 0; isset($writeup_info[$i]); ++$i){
?>
								<tr<?php if($writeup_info[$i]['author'] === $_SESSION['username']) echo ' class="info"'; ?>>
									<td scope="row" class="text-center"><?php echo strtoupper(base_convert($writeup_info[$i]['no'], 10, 16)); ?></td>
<?php
		# if solved,
		if(is_admin() || in_array($writeup_info[$i]['problem_no'], $my_solved_list, true)){
?>
									<td><a href="/writeup/<?php echo $writeup_info[$i]['no']; ?>"><?php echo secure_escape($writeup_info[$i]['problem_title']); ?> — #<?php echo $writeup_info[$i]['idx']; ?></a></td>
<?php
		# if not solved,
		}else{
?>
									<td><img src="<?php echo get_new_cache_link('/assets/img/lock.png'); ?>" alt="🔒" style="margin-top:-4px"> <?php echo secure_escape($writeup_info[$i]['problem_title']); ?> — #<?php echo $writeup_info[$i]['idx']; ?></td>
<?php
		}
?>							
									<td><a href="/profile/<?php echo strtolower(secure_escape($writeup_info[$i]['author'])); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo secure_escape($writeup_info[$i]['user_comment']); ?>"><?php echo secure_escape($writeup_info[$i]['author']); ?></a></td>
									<td><time><?php echo $writeup_info[$i]['register_time']; ?></time></td>
								</tr>
<?php
	}
	if($i === 0){
?>
								<tr>
									<td scope="row" class="text-center">-</td>
									<td>-</td>
									<td>-</td>
									<td>-</td>
								</tr>
<?php
	}
?>
							</tbody>
						</table>
						<div class="mt-10 text-right">
							<a class="btn btn-default" href="/writeup/upload">Upload</a>
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
