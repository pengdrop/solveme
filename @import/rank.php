<?php

	# get user count
	$p = $pdo->prepare("
		SELECT
			COUNT(*) AS `cnt`
		FROM
			`{$db_prefix}_user`
		WHERE
			`score`
	");
	$p->execute();
	$row = $p->fetch(PDO::FETCH_ASSOC);
	$rows_count = (int)$row['cnt'];
	unset($p, $row);

	$limit = 16;

	$first_page = 1;
	$last_page = (int)ceil($rows_count / $limit);
	$pagination_count = 9;

	if($argc === 2){
		$page = 1;

	}else if($argc === 4){
		$page = (int)$argv[3];

		if($page < $first_page){
			redirect('/rank/p/'.urlencode($first_page));

		}else if($last_page < $page){
			redirect('/rank/p/'.urlencode($last_page));
		}
	}

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
			".(int)(($page - 1) * $limit).",".(int)$limit."
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

	if($page === $first_page){
		$mark[0] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no1.png').'" alt="1">';
		$mark[1] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no2.png').'" alt="2">';
		$mark[2] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no3.png').'" alt="3">';
		$mark[3] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no4.png').'" alt="4">';
		$mark[4] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/no5.png').'" alt="5">';
		$mark[15] = '<img class="mt-m4" src="'.get_new_cache_link('/assets/img/bomb.png').'" alt="10">';
	}
	for($i = 0, $base_num = (int)(($page - 1) * $limit); isset($user_info[$i]); ++$i){
		$rank = $base_num + $i + 1;
?>
								<tr<?php if($user_info[$i]['username'] === $_SESSION['username']) echo ' class="info"'; ?>>
									<td scope="row" class="text-center"><?php echo isset($mark[$i]) ? $mark[$i] : strtoupper(base_convert($rank, 10, 16)); ?></td>
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
	unset($i, $base_num);
?>
							</tbody>
						</table>
						<nav class="text-center">
							<ul class="pagination mt-20 mb-10">
<?php
	// first & previous page
	if($first_page < $page){
?>
								<li><a href="/rank/p/<?php echo urlencode($first_page); ?>" aria-label="First"><span aria-hidden="true">&laquo;</span></a></li>
								<li><a href="/rank/p/<?php echo urlencode($page - 1); ?>" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>
<?php
	}

	// middle page
	if($page < ceil($pagination_count / 2)){
		$min = 1 - $page;
		$max = $pagination_count - $page;
	}else if(($last_page - $page + 1) < ceil($pagination_count / 2)){
		$min = -$pagination_count + ($last_page - $page + 1);
		$max = -1 + ($last_page - $page + 1);
	}else{
		$min = -floor($pagination_count / 2);
		$max = floor($pagination_count / 2);
	}

	for($i = (int)$min; $i <= (int)$max; ++$i){
		$now_page = (int)($page + $i);

		if($first_page <= $now_page && $now_page <= $last_page){
?>
								<li class="<?php if($now_page === $page) echo 'active'; ?>"><a href="/rank/p/<?php echo urlencode($now_page); ?>" aria-label="<?php echo secure_escape($now_page); ?> page"><?php echo secure_escape($now_page); ?></a></li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);

	// next & last page
	if($page < $last_page){
?>
								<li><a href="/rank/p/<?php echo urlencode($page + 1); ?>" aria-label="Next"><span aria-hidden="true">&rsaquo;</span></a></li>
								<li><a href="/rank/p/<?php echo urlencode($last_page); ?>" aria-label="Last"><span aria-hidden="true">&raquo;</span></a></li>
<?php
	}
?>
							</ul>
						</nav>
						<div class="bg-info text-center mt-10 p-10">
							<img class="mt-m4" src="<?php echo get_new_cache_link('/assets/img/user.png'); ?>" alt="☺"> <?php echo $all_users_info['usercount']; ?> peoples are currently joined.
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
