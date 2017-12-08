<?php
	# config
	require __DIR__.'/@import/config.php';

	isset($_GET['username']) and $_GET['username'] === __DB__['username'] or die('Input the valid username of database.');
	isset($_GET['password']) and $_GET['password'] === __DB__['password'] or die('Input the valid password of database.');

	$file_path = __DIR__.'/@import/init.sql';
	is_file($file_path) or die('init.sql file not found.');

	$file_body = file_get_contents($file_path);
	# remove bom
	if(substr($file_body, 0, 3) === "\xEF\xBB\xBF"){
		$file_body = substr($file_body, 3);
	}
	$sql_querys = explode(';', $file_body);
	is_array($sql_querys) or die('init.sql file was damaged.');

	foreach($sql_querys as $sql_query){
		$sql_query = trim($sql_query);
		if(!isset($sql_query[0])) continue;

		$p = $pdo->prepare($sql_query);
		$p->execute();

		echo '<code>', $p->queryString, '</code><br>', PHP_EOL;
		$err = $p->errorInfo();
		if(isset($err[1])){
			echo $p->errorInfo()[2], '<br><br>', PHP_EOL;
		}else{
			echo 'Succeed.<br><br>', PHP_EOL;
		}
	}

	unlink(__FILE__);

	die('SQL initialized.');