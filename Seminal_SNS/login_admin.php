<?php
	session_start();
	
	header("Content-type: text/html; charset=utf-8");
	
	// ログイン状態のチェック
	if (!isset($_SESSION["account"])) {
		header("Location: login_form.php");
		exit();
	}
	
	$account = $_SESSION['account'];
	
	//データベース接続
	require_once("db.php");
	$dbh = db_connect();
	
	$sql_select = 'SELECT account, tweet FROM tweet ORDER BY id';
	$result_select = $dbh -> query($sql_select);

?>
<!DOCTYPE html>
<html>
	<head>
		<title>ゼミナールSNS</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>メイン画面<h1>
		<h2>ログイン名：<?= $_SESSION['account']; ?></h2>
		<div align=”right”>
			<table>
				<tr>
					<td><?= "<a href='newslist.html'>ニュース</a>" ?></td>
					<td><?= "<a href='toi_input.php'>お問い合わせ</a>" ?></td>
					<td><?= "<a href='tweet_input.php'>投稿</a>" ?></td>
					<td><?="<a href='logout.php'>ログアウト</a>" ?></td>
				</tr>
			</table>
		</div>
		<div>
			<!--ツイートを表示-->
			<p>投稿</p>
			<?php foreach($result_select as $row) : ?>
			<?= $row['account'].'<>'.$row['tweet'].'<br >'; ?>
			<?php endforeach; ?>
		</div>
		<form action="tweet.php" method="post">
			<input type="hidden" name="name" value=<?= $_SESSION['account']; ?>>
		</form>
	</body>
</html>