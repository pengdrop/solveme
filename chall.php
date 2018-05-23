<?php
	
	# check exist challenge
	if(isset($argv[2]{0}) && !isset($argv[3]{0})){
		$chall_link = $argv[2];
		is_chall_link($chall_link) or error(404);
		$p = $pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_problem`
			WHERE
				REPLACE(LOWER(`title`), ' ', '_')=:link
			LIMIT
				1
		");
		$p->bindParam(':link', $chall_link);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) or error(404);
	}

	# get prob count
	$p = $pdo->prepare("
		SELECT
			COUNT(*) AS `cnt`
		FROM
			`{$db_prefix}_problem`
	");
	$p->execute();
	$row = $p->fetch(PDO::FETCH_ASSOC);
	$rows_count = (int)$row['cnt'];
	unset($p, $row);

	$limit = 16;

	$first_page = 1;
	$last_page = (int)ceil($rows_count / $limit);
	$pagination_count = 9;

	if(isset($argv[2]{0}, $argv[3]{0}) && $argv[2] === 'p'){
		$page = (int)$argv[3];

		if($page < $first_page){
			redirect('/chall/p/'.urlencode($first_page));

		}else if($last_page < $page){
			redirect('/chall/p/'.urlencode($last_page));
		}
	}else{
		$page = 1;
	}

	# common header
	$title = __SITE__['title'].' » Challenge';
	$need_login = false;
	$js_files = [
		'/assets/js/chall.js'
	];
	$show_category = true;
	require __DIR__.'/header.php';

	# get prob info
	$p = $pdo->prepare("
		SELECT
			`category`,
			`title`,
			`score`,
			`contents`,
			`author`,
			`register_time`,
			EXISTS(SELECT 1 FROM `{$db_prefix}_authlog` WHERE `username`=:username AND `problem_no`=`p`.`no` LIMIT 1) AS `is_solved`,
			(SELECT COUNT(*) FROM `{$db_prefix}_authlog` WHERE `problem_no`=`p`.`no`) AS `solver`,
			(SELECT `username` FROM `{$db_prefix}_authlog` WHERE `problem_no`=`p`.`no` ORDER BY `no` ASC LIMIT 1) AS `first_blood`
		FROM
			`{$db_prefix}_problem` AS `p`
		ORDER BY
			`score` ASC,
			`register_time` DESC
		LIMIT
			".(int)(($page - 1) * $limit).",".(int)$limit."
	");
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$prob_info = $p->fetchAll(PDO::FETCH_ASSOC);

	# get user info
	$p = $pdo->prepare("
		SELECT
			`score`,
			(SELECT SUM(`prob`.`score`) FROM `{$db_prefix}_problem` AS `prob`) AS `total_score`,
			(SELECT ROUND(100 / `total_score` * `score`, 2)) AS `score_percent`
		FROM
			`{$db_prefix}_user`
		WHERE
			`username`=:username
		LIMIT
			1
	");
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$user_info = $p->fetch(PDO::FETCH_ASSOC);
?>
					<main class="main-body">
						<form id="auth-form">
							<div class="form-group">
								<label class="sr-only" for="auth-flag">Flag</label>
								<div class="input-group">
									<input type="text" id="auth-flag" class="form-control" placeholder="flag{ ... }" data-toggle="tooltip" data-placement="bottom" title="Enter the flag.">
									<span class="input-group-btn">
									<button class="auth-btn btn btn-dark" type="submit">Auth</button>
								</div>
							</div>
						</form>
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php
	for($i = 0; isset($prob_info[$i]); ++$i){
		$link = get_chall_link($prob_info[$i]['title']);
?>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="heading-<?php echo secure_escape($link); ?>">
									<h4 class="panel-title">
										<a class="prob-title pull-left mr-5" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo secure_escape($link); ?>" aria-expanded="<?php echo $link === $chall_link ? 'true' : 'false'; ?>" aria-controls="collapse-<?php echo secure_escape($link); ?>"><?php echo secure_escape($prob_info[$i]['title']); ?></a>
<?php
	if(strtotime($prob_info[$i]['register_time']) >= time() - 3600 * 24 * 7){
?>
										<span class="label label-warning ml-5">New</span>
<?php
	}

	if($prob_info[$i]['is_solved']){
?>
										<span class="label label-success ml-5">Solved</span>
<?php
	}
?>
										<span class="pull-right"><?php echo secure_escape($prob_info[$i]['score']); ?>pt</span>
										<div class="clearfix"></div>
									</h4>
								</div>
								<div id="collapse-<?php echo secure_escape($link); ?>" class="panel-collapse collapse<?php echo $link === $chall_link ? ' in' : ''; ?> text-left" role="tabpanel" aria-labelledby="heading-<?php echo secure_escape($link); ?>">
									<div class="panel-body">
										<span class="text-muted">
											Authored by <a href="/profile/<?php echo secure_escape(strtolower($prob_info[$i]['author'])); ?>"><?php echo secure_escape($prob_info[$i]['author']); ?></a> at <time><?php echo date('Y-m-d H:i:s',strtotime($prob_info[$i]['register_time'])); ?></time><?php if(isset($prob_info[$i]['first_blood'])) echo ', and first blood got by <a href="/profile/'.secure_escape(strtolower($prob_info[$i]['first_blood'])).'">'.secure_escape($prob_info[$i]['first_blood']).'</a>'; ?>.
										</span>
										<hr class="mt-10 mb-10">
										<?php echo secure_escape($prob_info[$i]['contents'], true); ?>
										<hr class="mt-10 mb-10">
<?php
	foreach(explode(',', strtolower($prob_info[$i]['category'])) as $cat){
?>
											<span class="label label-default mr-10"><?php echo secure_escape($cat); ?></span>
<?php
	}
	unset($cat);
?>
									</div>
								</div>
							</div>
<?php
	}
	# exist nothing
	if($i === 0){
?>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="heading-nope ?>">
									<h4 class="panel-title">
										<a class="prob-title pull-left" data-toggle="collapse" data-parent="#accordion" href="#collapse-nope" aria-expanded="false" aria-controls="collapse-nope">-</a>
										<span class="pull-right">-</span>
										<div class="clearfix"></div>
									</h4>
								</div>
								<div id="collapse-nope" class="panel-collapse collapse text-left" role="tabpanel" aria-labelledby="heading-nope">
									<div class="panel-body">
										<span class="text-muted">
											-
										</span>
										<hr class="mt-10 mb-10">
										-
									</div>
								</div>
							</div>
<?php
	}
	unset($i);
?>
						</div>
						<nav class="text-center">
							<ul class="pagination mt-0 mb-0">
<?php
	// first & previous page
	if($first_page < $page){
?>
								<li><a href="/chall/p/<?php echo urlencode($first_page); ?>" aria-label="First"><span aria-hidden="true">&laquo;</span></a></li>
								<li><a href="/chall/p/<?php echo urlencode($page - 1); ?>" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>
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
								<li class="<?php if($now_page === $page) echo 'active'; ?>"><a href="/chall/p/<?php echo urlencode($now_page); ?>" aria-label="<?php echo secure_escape($now_page); ?> page"><?php echo secure_escape($now_page); ?></a></li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);

	// next & last page
	if($page < $last_page){
?>
								<li><a href="/chall/p/<?php echo urlencode($page + 1); ?>" aria-label="Next"><span aria-hidden="true">&rsaquo;</span></a></li>
								<li><a href="/chall/p/<?php echo urlencode($last_page); ?>" aria-label="Last"><span aria-hidden="true">&raquo;</span></a></li>
<?php
	}
?>
							</ul>
						</nav>
						<div class="progress mt-15 mb-0">
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $user_info['score']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $user_info['total_score']; ?>" style="width:<?php echo $user_info['score_percent']; ?>%">
								<span><?php echo $user_info['score_percent']; ?>%</span>
							</div>
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
