<?php
	# common header
	$title = __SITE__['title'].' » Profile';
	$need_login = true;
	$show_category = true;
	require __DIR__.'/header.php';

	# parse username
	if(isset($argv[2]) && is_username($argv[2])){
		$is_mine = !strcasecmp($argv[2], $_SESSION['username']);
		$username = $argv[2];
	}else{
		$is_mine = true;
		$username = $_SESSION['username'];
	}

	# get user info
	$p = $pdo->prepare("
		SELECT
			`username`,
			`email`,
			`open_email`,
			`comment`,
			`score`,
			(SELECT SUM(`prob`.`score`) FROM `{$db_prefix}_problem` AS `prob`) AS `total_score`,
			(SELECT ROUND(100 / `total_score` * `score`, 2)) AS `score_percent`,
			(SELECT `auth_time` FROM `{$db_prefix}_authlog` WHERE `username`=:username ORDER BY `auth_time` ASC LIMIT 1) AS `last_solved_time`
		FROM
			`{$db_prefix}_user`
		WHERE
			`username`=:username
		LIMIT
			1
	");
	$p->bindParam(':username', $username);
	$p->execute();
	$user_info = $p->fetch(PDO::FETCH_ASSOC);
?>
					<main class="main-body">
						<div class="pull-left">

							<div class="media">
								<div class="media-left media-middle">
									<img class="media-object p-0" src="<?php echo get_gravatar_link($user_info['email'], 85); ?>" style="width:85px" data-toggle="modal" data-target="#profile-image-modal">
								</div>
								<div class="media-body">
									<h3 class="m-0">
										<?php echo secure_escape($user_info['username']); ?> <span class="badge"><?php echo $user_info['score']; ?>pt</span>
									</h3>

<?php
	$label = '';

	if(is_admin_username($username)){
		$label .= '<span class="label label-primary mr-5">Admin</span>';
	}
	else if((int)$user_info['score_percent'] === 100){
		$label .= '<span class="label label-success mr-5">All Clear</span>';
	}
	else if((int)$user_info['score_percent'] === 0){
		$label .= '<span class="label label-default mr-5">Newbie</span>';
	}
	else{
		$label .= '<span class="label label-warning mr-5">Normal</span>';
	}

?>
									<h4 class="mt-10"><?php echo $label; ?></h4>
<?php
	# email protect
	if($is_mine || is_admin() || $user_info['open_email'] === '1'){
?>
									<a href="mailto:<?php echo $user_info['email']; ?>"><?php echo $user_info['email']; ?></a>
<?php
	}else{
?>
									<a class="secret-contact"><?php echo preg_replace('/^.*@(.*)$/is', '**secret**@${1}', $user_info['email']); ?></a>
<?php
	}
?>

								</div>
							</div>
						</div>
						<!-- Modal -->
						<div class="modal fade" id="profile-image-modal" tabindex="-1" role="dialog" aria-labelledby="profile-image-modal-title" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="sans-serif" aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="profile-image-modal-title"><?php echo secure_escape($user_info['username']); ?>'s profile image</h4>
									</div>
									<div class="modal-body text-center">
										<img class="media-object p-0 h-center" src="<?php echo get_gravatar_link($user_info['email'], 400); ?>" style="width:400px">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<blockquote class="mt-10 mb-10"><?php
	if(isset($user_info['comment'][0])){
		echo filter_var($user_info['comment'], FILTER_VALIDATE_URL) ?
			secure_escape('[url]'.$user_info['comment'].'[/url]', true) :
			secure_escape($user_info['comment']);
	}else{
		echo '<span class="text-muted">...</span>';
	}
?></blockquote>
						<div class="progress mb-10">
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $user_info['score']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $user_info['total_score']; ?>" style="width:<?php echo $user_info['score_percent']; ?>%">
								<span><?php echo $user_info['score_percent']; ?>%</span>
							</div>
						</div>
						<table class="table table-striped table-hover table-responsive m-0">
							<colgroup>
								<col style="width:10%">
								<col style="width:50%">
								<col style="width:40%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col" class="text-center">#</th>
									<th scope="col">Problem</th>
									<th scope="col">Solved Time</th>
								</tr>
							</thead>
							<tbody>
<?php
	$p = $pdo->prepare("
		SELECT
			(SELECT `title` FROM `{$db_prefix}_problem` WHERE `no`=`p`.`problem_no` LIMIT 1) AS `problem_title`,
			(SELECT `score` FROM `{$db_prefix}_problem` WHERE `no`=`p`.`problem_no` LIMIT 1) AS `problem_score`,
			`auth_time`,
			(SELECT `username`=:username FROM `{$db_prefix}_authlog` WHERE `problem_no`=`p`.`problem_no` ORDER BY `no` ASC LIMIT 1) AS `is_first_blood`
		FROM
			`{$db_prefix}_authlog` AS `p`
		WHERE
			`username`=:username
		ORDER BY
			`no` ASC
	");
	$p->bindParam(':username', $username);
	$p->execute();
	$log_info = $p->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; isset($log_info[$i]); ++$i){
		$problem_link = get_chall_link($log_info[$i]['problem_title']);
?>
								<tr>
									<td scope="row" class="text-center"><?php echo strtoupper(base_convert($i+1, 10, 16)); ?></td>
									<td><a href="/chall/<?php echo secure_escape($problem_link); ?>"><?php echo secure_escape($log_info[$i]['problem_title']); ?></a> <span class="badge"><?php echo secure_escape($log_info[$i]['problem_score']); ?>pt</span><?php if($log_info[$i]['is_first_blood']==='1') echo ' <span class="label label-success">First blood</span>'; ?></td>
									<td><time><?php echo $log_info[$i]['auth_time']; ?></time></td>
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
								</tr>
<?php
	}
?>
							</tbody>
						</table>
						<div class="text-right mt-10 mb-10">
<?php
	if($is_mine){
?>
							<a class="btn btn-default mr-5" href="/profile/edit">Edit</a><a class="btn btn-dark" href="/logout/<?php echo get_logout_link(); ?>">Logout</a>
<?php
	}
?>
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
