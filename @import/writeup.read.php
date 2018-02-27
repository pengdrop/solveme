<?php
	$writeup_no = $argv[2];

	# check exist writeup
	is_writeup_no($writeup_no) or error(404);
	$p = $pdo->prepare("
		SELECT
			`problem_no`
		FROM
			`{$db_prefix}_writeup`
		WHERE
			`no`=:no
		LIMIT
			1
	");
	$p->bindParam(':no', $writeup_no);
	$p->execute();
	$writeup = $p->fetch(PDO::FETCH_ASSOC) or error(404);

	# check permission
	if(!is_admin()){
		$p = $pdo->prepare("
			SELECT
				1
			FROM
				`{$db_prefix}_authlog`
			WHERE
				`problem_no`=:problem_no AND
				`username`=:username
			LIMIT
				1
		");
		$p->bindParam(':problem_no', $writeup['problem_no']);
		$p->bindParam(':username', $_SESSION['username']);
		$p->execute();
		$p->fetch(PDO::FETCH_ASSOC) or error(403);
	}

	# common header
	$title = __SITE__['title'].' » Writeup';
	$need_login = true;
	$js_files = [
		'/assets/js/writeup.js'
	];
	$show_category = true;
	require __DIR__.'/header.php';

	# get writeup
	$p = $pdo->prepare("
		SELECT
			`no`,
			(SELECT COUNT(*) FROM `{$db_prefix}_writeup` WHERE `problem_no`=`w`.`problem_no` AND `no` <= `w`.`no`) AS `idx`,
			(SELECT `title` FROM `{$db_prefix}_problem` WHERE `no`=`w`.`problem_no` LIMIT 1) AS `problem_title`,
			`contents`,
			`author`,
			(SELECT `comment` FROM `{$db_prefix}_user` WHERE `username`=`w`.`author` LIMIT 1) AS `author_comment`,
			`register_time`,
			EXISTS(SELECT 1 FROM `{$db_prefix}_authlog` WHERE `username`=:username AND `problem_no`=`w`.`problem_no` LIMIT 1) AS `is_solved`
		FROM
			`{$db_prefix}_writeup` AS `w`
		WHERE
			`no`=:no
		LIMIT
			1
	");
	$p->bindParam(':no', $writeup_no);
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$writeup_info = $p->fetch(PDO::FETCH_ASSOC);

	# get author's info
	$p = $pdo->prepare("
		SELECT
			`email`,
			`comment`
		FROM
			`{$db_prefix}_user`
		WHERE
			`username`=:username
		LIMIT
			1
	");
	$p->bindParam(':username', $writeup_info['author']);
	$p->execute();
	$author_info = $p->fetch(PDO::FETCH_ASSOC);

?>
					<main class="main-body">
						<div class="panel panel-default mb-10">
							<div class="panel-heading panel-title">
								<?php echo secure_escape($writeup_info['problem_title']); ?> — #<?php echo $writeup_info['idx']; ?>
							</div>
							<div class="panel-body" style="min-height:250px">
								<span class="text-muted">
									Written by <a href="/profile/<?php echo secure_escape(strtolower($writeup_info['author'])); ?>"><?php echo secure_escape($writeup_info['author']); ?></a> at <time><?php echo date('Y-m-d H:i:s',strtotime($writeup_info['register_time'])); ?></time>, and origin is <a href="/chall/<?php echo secure_escape(get_chall_link($writeup_info['problem_title'])); ?>"><?php echo secure_escape($writeup_info['problem_title']); ?></a>
								</span>
								<hr class="mt-10 mb-10">
								<?php echo secure_escape($writeup_info['contents'], true); ?>
							</div>
							<div class="panel-body">
								<hr class="mt-10 mb-10">
								<span class="text-muted">About writer</span>
								<div class="media">
									<div class="media-left">
										<img class="media-object p-0" src="<?php echo get_gravatar_link($author_info['email'], 85); ?>" style="width:85px">
									</div>
									<div class="media-body">
										<h4 class="media-heading"><?php echo secure_escape($writeup_info['author']); ?></h4>
										<blockquote class="mt-10 mb-0">
											<?php echo filter_var($author_info['comment'], FILTER_VALIDATE_URL) ? secure_escape('[url]'.$author_info['comment'].'[/url]', true) : secure_escape($author_info['comment']); ?>&nbsp;
										</blockquote>
									</div>
								</div>
							</div>

						</div>
						<div class="clearfix text-right">
							<?php
								if($writeup_info['author'] === $_SESSION['username']){
									?><button class="btn btn-dark mr-5" data-toggle="modal" data-target="#delete-writeup-modal">Delete</button><?php
								}
							?><button class="btn btn-default go-back" data-href="/writeup">List</button>
						</div>
						<!-- Modal -->
						<div class="modal fade" id="delete-writeup-modal" tabindex="-1" role="dialog" aria-labelledby="delete-writeup-modal-title" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="sans-serif" aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="delete-writeup-modal-title">Delete a writeup</h4>
									</div>
									<div class="modal-body">
										Are you sure you want to delete this writeup?
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger delete-writeup" data-no="<?php echo $writeup_no; ?>" data-dismiss="modal">Delete</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
