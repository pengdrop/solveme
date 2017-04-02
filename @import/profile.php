<?php
	# parse username
	if(isset($argv[2]) && is_username($argv[2])){
		$is_mine = !strcasecmp($argv[2], $_SESSION['username']);
		$username = $argv[2];
	}else{
		$is_mine = true;
		$username = $_SESSION['username'];
	}

	# get user info
	$p = $pdo->prepare('
		SELECT 
			username,
			email,
			score,
			(SELECT auth_time FROM authlog WHERE username=:username ORDER BY auth_time ASC LIMIT 1) AS last_solved_time
		FROM user
		WHERE username=:username
		LIMIT 1
	');
	$p->bindParam(':username', $username);
	$p->execute();
	$user_info = $p->fetch(PDO::FETCH_ASSOC) or error(404);

	# get sum of problem's score
	$p = $pdo->prepare('SELECT SUM(score) FROM problem');
	$p->bindParam(':username', $username);
	$p->execute();
	$total_score = $p->fetch(PDO::FETCH_ASSOC)['SUM(score)'];
	$progress_width = round(100 / $total_score * $user_info['score'], 2);

	# common header
	$title = 'Solve Me » Profile';
	$need_login = true;
	$css_files = array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
		'/css/common.css'
	);
	$js_files = array(
		'//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
		'/js/common.js'
	);
	$show_category = true;
	require __DIR__.'/header.php';
?>
					<div class="main-body">
						<div class="pull-left">
							<h3 class="clear-margin">
								<?php echo secure_escape($user_info['username']); ?><span class="badge"><?php echo $user_info['score']; ?>pt</span>
							</h3>
<?php
	# email protect
	if($is_mine || is_admin()){
?>
							<a href="mailto:<?php echo $user_info['email']; ?>"><?php echo $user_info['email']; ?></a>
<?php
	}else{
?>
							<a class="secret-contact" href='#' ><?php echo preg_replace('/^.*@(.*)$/is', '**secret**@${1}', $user_info['email']); ?></a>
							<div class="alert-parent"></div>
<?php
	}
?>
						</div>
						<div class="clearfix text-right">
<?php
	if($is_mine){
?>
							<a class="btn btn-default btn-sm m-t-10 m-r-5" href="/profile/edit">Edit</a><a class="btn btn-danger btn-sm m-t-10" href="/logout/<?php echo md5($_SESSION['username']); ?>">Logout</a>
<?php
	}
?>
						</div>
						<div class="progress m-t-10 m-b-10">
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $user_info['score']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $total_score; ?>" style="width: <?php echo $progress_width; ?>%">
								<span><?php echo $progress_width; ?>%</span>
							</div>
						</div>
						<table class="table table-striped table-hover table-responsive clear-margin">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Problem</th>
									<th scope="col">Solved Time</th>
								</tr>
							</thead>
							<tbody>
<?php
	$p = $pdo->prepare('
		SELECT 
			(SELECT title FROM problem WHERE no=p.problem_no LIMIT 1) AS problem_title,
			(SELECT score FROM problem WHERE no=p.problem_no LIMIT 1) AS problem_score,
			auth_time
		FROM authlog AS p 
		WHERE username=:username
		ORDER BY no ASC
		LIMIT 0, 30
	');
	$p->bindParam(':username', $username);
	$p->execute();
	$log_info = $p->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; isset($log_info[$i]); ++$i){
?>
								<tr>
									<td scope="row"><?php echo $i+1; ?></td>
									<td><?php echo secure_escape($log_info[$i]['problem_title']); ?><span class="badge"><?php echo secure_escape($log_info[$i]['problem_score']); ?>pt</span></td>
									<td><?php echo $log_info[$i]['auth_time']; ?></td>
								</tr>
<?php
	}
	if($i === 0){
?>
								<tr>
									<td scope="row">-</td>
									<td>-</td>
									<td>-</td>
								</tr>
<?php
	}
?>
							</tbody>
						</table>
					</div>
<?php
	# common footer
	require __DIR__.'/footer.php';
