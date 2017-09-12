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
			comment,
			score,
			(SELECT SUM(prob.score) FROM solveme_problem AS prob) AS total_score,
			(SELECT ROUND(100 / total_score * score, 2)) AS score_percent,
			(SELECT auth_time FROM solveme_authlog WHERE username=:username ORDER BY auth_time ASC LIMIT 1) AS last_solved_time
		FROM solveme_user
		WHERE username=:username
		LIMIT 1
	');
	$p->bindParam(':username', $username);
	$p->execute();
	$user_info = $p->fetch(PDO::FETCH_ASSOC);

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
	require __DIR__.'/header.php';//var_dump($user_info);die;
?>
					<div class="main-body">
						<div class="pull-left">
							<h3 class="clear-margin">
								<?php echo secure_escape($user_info['username']); ?> <span class="badge"><?php echo $user_info['score']; ?>pt</span>
							</h3>
<?php
	$label = '';

	if(in_array(strtolower($username), $admin_list, true)){
		$label .= '<span class="label label-primary m-r-10">Admin</span>';
	}
	else if((int)$user_info['score_percent'] === 0){
		$label .= '<span class="label label-default m-r-10">Newbie</span>';
	}
	else if((int)$user_info['score_percent'] === 100){
		$label .= '<span class="label label-success m-r-10">All Clear</span>';
	}

	if($label){
?>
							<h4 class="m-t-10"><?php echo $label; ?></h4>
<?php
	}

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
						<blockquote class="m-t-10 m-b-10">
							<?php echo secure_escape($user_info['comment']); ?>&nbsp;
						</blockquote>
						<div class="progress m-b-10">
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $user_info['score']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $user_info['total_score']; ?>" style="width: <?php echo $user_info['score_percent']; ?>%">
								<span><?php echo $user_info['score_percent']; ?>%</span>
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
			(SELECT title FROM solveme_problem WHERE no=p.problem_no LIMIT 1) AS problem_title,
			(SELECT score FROM solveme_problem WHERE no=p.problem_no LIMIT 1) AS problem_score,
			auth_time,
			(SELECT username=:username FROM solveme_authlog WHERE problem_no=p.problem_no ORDER BY no ASC LIMIT 1) AS is_first_blood
		FROM solveme_authlog AS p 
		WHERE username=:username
		ORDER BY no ASC
	');
	$p->bindParam(':username', $username);
	$p->execute();
	$log_info = $p->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; isset($log_info[$i]); ++$i){
?>
								<tr>
									<td scope="row"><?php echo $i+1; ?></td>
									<td><?php echo secure_escape($log_info[$i]['problem_title']); ?> <span class="badge"><?php echo secure_escape($log_info[$i]['problem_score']); ?>pt</span><?php if($log_info[$i]['is_first_blood']==='1') echo ' <span class="label label-success">First blood</span>'; ?></td>
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
