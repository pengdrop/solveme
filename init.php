<?php
	isset($_GET['host']) or die('Input the valid host of database.');
	isset($_GET['username']) or die('Input the valid username of database.');
	isset($_GET['password']) or die('Input the valid password of database.');
	isset($_GET['name']) or die('Input the valid name of database.');
	isset($_GET['prefix']) or die('Input the valid prefix of database.');

	$file_path = __DIR__.'/@import/init.sql';
	is_file($file_path) or die('init.sql file not found.');

	$file_body = file_get_contents($file_path);
	# remove bom
	if(substr($file_body, 0, 3) === "\xEF\xBB\xBF"){
		$file_body = substr($file_body, 3);
	}
	$sql_querys = explode(';', $file_body);
	is_array($sql_querys) or die('init.sql file was damaged.');

	try{
		$pdo = new PDO('mysql:host='.$_GET['host'].';dbname='.$_GET['name'], $_GET['username'], $_GET['password']);
	}catch(exception $e){
		die('sql server was down.');
	}

	foreach($sql_querys as $sql_query){
		$sql_query = trim($sql_query);
		if(!isset($sql_query[0])) continue;

		$sql_query = strtr($sql_query, ["[DB_PREFIX]" => $_GET['prefix']]);
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

	die('SQL initialized.');
