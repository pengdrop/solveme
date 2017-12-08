<?php
	# common header
	$title = __SITE__['title'].' » Status';
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
								<col style="width:25%">
								<col style="width:35%">
								<col style="width:30%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col" class="text-center">#</th>
									<th scope="col">Username</th>
									<th scope="col">Problem</th>
									<th scope="col">Solved Time</th>
								</tr>
							</thead>
							<tbody>
<?php
	$p = $pdo->prepare("
		SELECT
			`no`,
			`username`,
			(SELECT `comment` FROM `{$db_prefix}_user` AS `u` WHERE `username`=`p`.`username` LIMIT 1) AS `user_comment`,
			(SELECT `title` FROM `{$db_prefix}_problem` WHERE `no`=`p`.`problem_no` LIMIT 1) AS `problem_title`,
			(SELECT `score` FROM `{$db_prefix}_problem` WHERE `no`=`p`.`problem_no` LIMIT 1) AS `problem_score`,
			`auth_time`
		FROM
			`{$db_prefix}_authlog` AS `p`
		ORDER BY
			`no` DESC
		LIMIT
			0, 16
	");
	$p->execute();
	$log_info = $p->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; isset($log_info[$i]); ++$i){
		$problem_link = get_chall_link($log_info[$i]['problem_title']);
?>
								<tr<?php if($log_info[$i]['username'] === $_SESSION['username']) echo ' class="info"'; ?>>
									<td scope="row" class="text-center"><?php echo strtoupper(base_convert($log_info[$i]['no'], 10, 16)); ?></td>
									<td><a href="/profile/<?php echo strtolower(secure_escape($log_info[$i]['username'])); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo secure_escape($log_info[$i]['user_comment']); ?>"><?php echo secure_escape($log_info[$i]['username']); ?></a></td>
									<td><a href="/chall/<?php echo secure_escape($problem_link); ?>"><?php echo secure_escape($log_info[$i]['problem_title']); ?></a> <span class="badge"><?php echo secure_escape($log_info[$i]['problem_score']); ?>pt</span></td>
									<td><time><?php echo $log_info[$i]['auth_time']; ?></time></td>
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
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
