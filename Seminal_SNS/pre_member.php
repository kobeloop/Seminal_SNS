<?php
	// データベース接続
	require_once("db.php");
	$dbh = db_connect();
	
	// 仮登録用のテーブル
	$sql = "CREATE TABLE pre_member" 
	. "("
	. "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
	. "urltoken VARCHAR(128) NOT NULL,"
	. "mail VARCHAR(50) NOT NULL,"
	. "date DATETIME NOT NULL,"
	. "flag TINYINT(1) NOT NULL DEFAULT 0"
	. ")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	
	$stmt =$pdo -> query($sql);
?>