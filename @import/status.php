<?php

	# get authlog count
	$p = $pdo->prepare("
		SELECT
			COUNT(*) AS `cnt`
		FROM
			`{$db_prefix}_authlog`
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
			redirect('/status/p/'.urlencode($first_page));

		}else if($last_page < $page){
			redirect('/status/p/'.urlencode($last_page));
		}
	}

	# common header
	$title = __SITE__['title'].' » Status';
	$need_login = false;
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
			".(int)(($page - 1) * $limit).",".(int)$limit."
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
						<nav class="text-center">
							<ul class="pagination mt-20 mb-10">
<?php
	// first & previous page
	if($first_page < $page){
?>
								<li><a href="/status/p/<?php echo urlencode($first_page); ?>" aria-label="First"><span aria-hidden="true">&laquo;</span></a></li>
								<li><a href="/status/p/<?php echo urlencode($page - 1); ?>" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>
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
								<li class="<?php if($now_page === $page) echo 'active'; ?>"><a href="/status/p/<?php echo urlencode($now_page); ?>" aria-label="<?php echo secure_escape($now_page); ?> page"><?php echo secure_escape($now_page); ?></a></li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);

	// next & last page
	if($page < $last_page){
?>
								<li><a href="/status/p/<?php echo urlencode($page + 1); ?>" aria-label="Next"><span aria-hidden="true">&rsaquo;</span></a></li>
								<li><a href="/status/p/<?php echo urlencode($last_page); ?>" aria-label="Last"><span aria-hidden="true">&raquo;</span></a></li>
<?php
	}
?>
							</ul>
						</nav>
						<div class="bg-info text-center mt-10 p-10">
							<img class="mt-m4" src="<?php echo get_new_cache_link('/assets/img/user.png'); ?>" alt="☺"> Currently authenticated <?php echo $rows_count; ?> times.
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
