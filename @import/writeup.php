<?php

	# get writeup count
	$p = $pdo->prepare("
		SELECT
			COUNT(*) AS `cnt`
		FROM
			`{$db_prefix}_writeup`
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
			redirect('/writeup/p/'.urlencode($first_page));

		}else if($last_page < $page){
			redirect('/writeup/p/'.urlencode($last_page));
		}
	}

	# common header
	$title = __SITE__['title'].' » Writeup';
	$need_login = false;
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
			".(int)(($page - 1) * $limit).",".(int)$limit."
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
						<nav class="text-center">
							<ul class="pagination mt-20 mb-10">
<?php
	// first & previous page
	if($first_page < $page){
?>
								<li><a href="/writeup/p/<?php echo urlencode($first_page); ?>" aria-label="First"><span aria-hidden="true">&laquo;</span></a></li>
								<li><a href="/writeup/p/<?php echo urlencode($page - 1); ?>" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>
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
								<li class="<?php if($now_page === $page) echo 'active'; ?>"><a href="/writeup/p/<?php echo urlencode($now_page); ?>" aria-label="<?php echo secure_escape($now_page); ?> page"><?php echo secure_escape($now_page); ?></a></li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);

	// next & last page
	if($page < $last_page){
?>
								<li><a href="/writeup/p/<?php echo urlencode($page + 1); ?>" aria-label="Next"><span aria-hidden="true">&rsaquo;</span></a></li>
								<li><a href="/writeup/p/<?php echo urlencode($last_page); ?>" aria-label="Last"><span aria-hidden="true">&raquo;</span></a></li>
<?php
	}
?>
							</ul>
						</nav>
						<div class="mt-10 text-right">
							<a class="btn btn-default" href="/writeup/upload">Upload</a>
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
