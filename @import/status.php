<?php
	# common header
	$title = 'Solve Me » Status';
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
						<table class="table table-striped table-hover table-responsive clear-margin">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Username</th>
									<th scope="col">Problem</th>
									<th scope="col">Solved Time</th>
								</tr>
							</thead>
							<tbody>
<?php
	$p = $pdo->prepare('
		SELECT 
			no,
			username,
			(SELECT title FROM problem WHERE no=p.problem_no LIMIT 1) AS problem_title,
			(SELECT score FROM problem WHERE no=p.problem_no LIMIT 1) AS problem_score,
			auth_time
		FROM authlog AS p 
		ORDER BY no DESC
		LIMIT 0, 30
	');
	$p->execute();
	$log_info = $p->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; isset($log_info[$i]); ++$i){
?>
								<tr<?php if($log_info[$i]['username'] === $_SESSION['username']) echo ' class="info"'; ?>>
									<td scope="row"><?php echo $log_info[$i]['no']; ?></td>
									<td><a href="/profile/<?php echo strtolower(secure_escape($log_info[$i]['username'])); ?>" target="_blank"><?php echo secure_escape($log_info[$i]['username']); ?></a></td>
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
