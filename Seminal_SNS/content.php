<?php
	// データベース接続
	require_once("db.php");
	$dbh = db_connect();
	
	// お問い合わせ用のテーブル
	$sql = "CREATE TABLE member"
	. "("
	. "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
	. "account VARCHAR(50) NOT NULL,"
	. "mail VARCHAR(50) NOT NULL,"
	. "tel VARCHAR(50) NOT NULL,"
	. "sex VARCHAR(10) NOT NULL,"
	. "item VARCHAR(50) NOT NULL,"
	. "comment TEXT NOT NULL,"
	.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
	
	$stmt =$dbh -> query($sql);
?>