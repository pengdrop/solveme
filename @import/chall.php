<?php
	# common header
	$title = 'Solve Me » Challenge';
	$need_login = true;
	$css_files = array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
		'/css/common.css'
	);
	$js_files = array(
		'//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
		'/js/common.js',
		'/js/chall.js'
	);
	$show_category = true;
	require __DIR__.'/header.php';
?>
					<div class="main-body">
						<form id="auth-form">
							<div class="form-group">
								<label class="sr-only" for="auth-flag">Flag</label>
								<div class="input-group">
									<input type="text" id="auth-flag" class="form-control" placeholder="flag{ ... }" data-toggle="tooltip" data-placement="bottom" title="Enter the flag.">
									<span class="input-group-btn">
									<button class="auth-btn btn btn-success" type="submit">Auth</button>
								</div>
								<div class="alert-parent"></div>
							</div>
						</form>
						<div class="panel-group clear-margin" id="accordion" role="tablist" aria-multiselectable="true">
<?php
	$p = $pdo->prepare('
		SELECT 
			title, 
			score, 
			contents, 
			author, 
			EXISTS(SELECT 1 FROM authlog WHERE username=:username AND problem_no=p.no LIMIT 1) AS is_solved, 
			(SELECT COUNT(*) FROM authlog WHERE problem_no=p.no) AS solver, 
			(SELECT username FROM authlog WHERE problem_no=p.no ORDER BY no ASC LIMIT 1) AS firstblood
		FROM problem AS p 
		ORDER BY score ASC
	');
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$prob_info = $p->fetchAll(PDO::FETCH_ASSOC);

	for($i = 0; isset($prob_info[$i]); ++$i){
?>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="heading-<?php echo $i; ?>">
									<h4 class="panel-title">
										<a class="prob-title pull-left" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse-<?php echo $i; ?>"><?php echo secure_escape($prob_info[$i]['title']); ?></a>
<?php
	if($prob_info[$i]['is_solved']){
?>
										<span class="label label-success m-l-10">Solved</span>
<?php
	}
?>
										<span class="pull-right"><?php echo secure_escape($prob_info[$i]['score']); ?>pt</span>
										<div class="clearfix"></div>
									</h4>
								</div>
								<div id="collapse-<?php echo $i; ?>" class="panel-collapse collapse text-left" role="tabpanel" aria-labelledby="heading-<?php echo $i; ?>">
									<div class="panel-body"><?php echo secure_escape($prob_info[$i]['contents'], true); ?></div>
								</div>
							</div>
<?php
	}
?>
						</div>
					</div>
<?php
	# common footer
	require __DIR__.'/footer.php';
