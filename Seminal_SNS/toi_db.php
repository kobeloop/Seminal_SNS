<?php
	//データベース接続
	require_once("db.php");
	$dbh = db_connect();
	
	// 本登録用のテーブル
	$sql = "CREATE TABLE toi"
	. "("
	. "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
	. "account VARCHAR(50) NOT NULL,"
	. "mail VARCHAR(50) NOT NULL,"
	. "inquiry TEXT NOT NULL"
	.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	
	$stmt =$dbh -> query($sql);
?>