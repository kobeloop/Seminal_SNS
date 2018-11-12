<?php
	//データベース接続
	require_once("db.php");
	$dbh = db_connect();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>管理者画面</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>管理者画面</h1>
		<h2>pre_member</h2>
		<?php
			// データ出力
			$sql_select = 'SELECT * FROM pre_member ORDER BY id';
			$result_select = $dbh -> query($sql_select);
			foreach($result_select as $row){
				echo $row['id'].'<>'.$row['urltoken'].'<>'.$row['mail'].'<>'.$row['date'].'<>'.$row['flag'].'<br >';
			}
		?>
		<h2>member</h2>
		<?php
			// データ出力
			$sql_select = 'SELECT * FROM member ORDER BY id';
			$result_select = $dbh -> query($sql_select);
			foreach($result_select as $row){
				echo $row['id'].'<>'.$row['account'].'<>'.$row['mail'].'<>'.$row['password'].'<>'.$row['flag'].'<br >';
			}
		?>
		<h2>投稿</h2>
		<?php
			// データ出力
			$sql = 'SELECT * FROM tweet_db ORDER BY id';
			$stmt = $dbh->query( $sql );

			echo "<table>\n";
			echo "\t<tr><th>id</th><th>tweet</th></tr>\n";
			while( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
				echo "\t<tr>\n";
				 echo "\t\t<td>{$result['id']}</td>\n";
				 echo "\t\t<td>{$result['account']}</td>\n";
				 echo "\t\t<td>{$result['tweet']}</td>\n";
				 echo "\t</tr>\n";
			}
			echo "</table>\n";
		?>
	</body>
</html>