<?php
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
			`title`,
			`score`
		FROM
			`{$db_prefix}_problem` AS `p`
		WHERE
			(SELECT 1 FROM `{$db_prefix}_authlog` WHERE `problem_no`=`p`.`no` AND `username`=:username)
		ORDER BY
			`score` ASC
	");
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$chall_info = $p->fetchAll(PDO::FETCH_ASSOC);
?>
					<main class="main-body">
						<form id="writeup-upload-form">
							<div class="panel panel-default">
								<div id="writeup-upload-chall-title" class="panel-heading panel-title">
									<div class="form-group mb-0">
<?php
	if(count($chall_info)){
?>
										<label class="sr-only" for="writeup-upload-chall">Challenge</label>
										<select class="form-control" id="writeup-upload-chall" data-toggle="tooltip" data-placement="bottom" title="Select the challenge.">
<?php
		foreach($chall_info as $chall){
?>
											<option value="<?php echo secure_escape(get_chall_link($chall['title'])); ?>"><?php echo secure_escape($chall['title']); ?> (<?php echo $chall['score']; ?>pt)</option>
<?php
		}
?>
										</select>
<?php
	}else{
?>
										<select class="form-control" id="writeup-upload-chall" data-toggle="tooltip" data-placement="bottom" title="Select the challenge." disabled>
											<option>-</option>
										</select>
<?php
	}
?>
									</div>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label class="sr-only" for="writeup-upload-contents">Contents</label>
										<textarea class="form-control resize-v" id="writeup-upload-contents" rows="13" placeholder="Contents" data-toggle="tooltip" data-placement="bottom" title="Enter the contents"></textarea>
									</div>
									<div class="clearfix text-right">
										<button type="submit" class="btn btn-dark mr-5">Upload</button><button type="button" class="btn btn-default go-back" data-href="/writeup">Cancel</button>
									</div>
								</div>
							</div>
						</form>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
