<?php
	# common header
	$title = __SITE__['title'].' » Rank';
	$need_login = true;
	$show_category = true;
	require __DIR__.'/header.php';

	# get rank info
	$p = $pdo->prepare("
		SELECT
			`username`,
			`comment`,
			`score`,
			(SELECT `auth_time` FROM `{$db_prefix}_authlog` WHERE `username`=`u`.`username` ORDER BY `auth_time` DESC LIMIT 1) AS `last_solved`
		FROM
			`{$db_prefix}_user` AS `u`
		WHERE
			`score`
		ORDER BY
			`score` DESC,
			`last_solved` ASC
		LIMIT
			0, 16
	");
	$p->execute();
	$user_info = $p->fetchAll(PDO::FETCH_ASSOC);

	# get all users info
	$p = $pdo->prepare("
		SELECT
			COUNT(*) AS `usercount`
		FROM
			`{$db_prefix}_user`
	");
	$p->execute();
	$all_users_info = $p->fetch(PDO::FETCH_ASSOC);
?>
					<main class="main-body">
						<table class="table table-striped table-hover table-responsive m-0">
							<colgroup>
								<col style="width:10%">
								<col style="width:30%">
								<col style="width:20%">
								<col style="width:40%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col" class="text-center">#</th>
									<th scope="col">Username</th>
									<th scope="col">Score</th>
									<th scope="col">Last Solved Time</th>
								</tr>
							</thead>
							<tbody>
<?php
	$mark[0] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no1.png').'" alt="1">';
	$mark[1] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no2.png').'" alt="2">';
	$mark[2] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no3.png').'" alt="3">';
	$mark[3] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no4.png').'" alt="4">';
	$mark[4] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no5.png').'" alt="5">';
	$mark[15] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/bomb.png').'" alt="10">';
	for($i = 0; isset($user_info[$i]); ++$i){
?>
								<tr<?php if($user_info[$i]['username'] === $_SESSION['username']) echo ' class="info"'; ?>>
									<td scope="row" class="text-center"><?php echo isset($mark[$i]) ? $mark[$i] : strtoupper(base_convert($i+1, 10, 16)); ?></td>
									<td><a href="/profile/<?php echo strtolower(secure_escape($user_info[$i]['username'])); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo secure_escape($user_info[$i]['comment']); ?>"><?php echo secure_escape($user_info[$i]['username']); ?></a></td>
									<td><span class="badge"><?php echo $user_info[$i]['score']; ?>pt</span></td>
									<td><time><?php echo $user_info[$i]['last_solved']; ?></time></td>
								</tr>
<?php
	}
	# exist nothing
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
						<div class="bg-info text-center mt-10 p-10">
							<img class="mt-m4" src="<?php echo get_new_cache_link('/assets/img/user.png'); ?>" alt="☺"> <?php echo $all_users_info['usercount']; ?> peoples are currently joined.
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
