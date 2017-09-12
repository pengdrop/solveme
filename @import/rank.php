<?php
	# common header
	$title = 'Solve Me » Rank';
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
									<th scope="col">Comment</th>
									<th scope="col">Score</th>
									<th scope="col">Last Solved</th>
								</tr>
							</thead>
							<tbody>
<?php
	$p = $pdo->prepare("
		SELECT 
			username,
			comment,
			score,
			(SELECT auth_time FROM solveme_authlog WHERE username=u.username ORDER BY auth_time DESC LIMIT 1) AS last_solved
		FROM solveme_user AS u 
		WHERE score
		ORDER BY 
			score DESC,
			last_solved ASC
		LIMIT 0, 30
	");
	$p->execute();
	$user_info = $p->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; isset($user_info[$i]); ++$i){
?>
								<tr<?php if($user_info[$i]['username'] === $_SESSION['username']) echo ' class="info"'; ?>>
									<td scope="row"><?php echo $i+1; ?></td>
									<td><a href="/profile/<?php echo strtolower(secure_escape($user_info[$i]['username'])); ?>"><?php echo secure_escape($user_info[$i]['username']); ?></a></td>
									<td><?php echo secure_escape($user_info[$i]['comment']); ?></td>
									<td><?php echo $user_info[$i]['score']; ?>pt</td>
									<td><?php echo $user_info[$i]['last_solved']; ?></td>
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
