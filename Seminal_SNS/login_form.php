<?php
	session_start();
 
	header("Content-type: text/html; charset=utf-8");
 
	//クロスサイトリクエストフォージェリ（CSRF）対策
	for($i = 0; $i < 64; $i++){
		$_SESSION['token'] = $_SESSION['token']. substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1); 
	}
// 	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];
 
	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>
 
<!DOCTYPE html>
<html>
	<head>
		<title>ログイン</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>ログインフォーム</h1>
 
		<form action="login_check.php" method="post">
			<table>
				<tr>
					<td>学生証番号</td>
					<td><input type="text" name="account" size="50"></td>
				</tr>
				<tr>
					<td>パスワード</td>
					<td><input type="text" name="password" size="50"></td>
				</tr>
			</table>
 
<!-- 			<p>アカウント：<input type="text" name="account" size="50"></p> -->
<!-- 			<p>パスワード：<input type="text" name="password" size="50"></p> -->
 
			<input type="hidden" name="token" value="<?=$token?>">
			<input type="submit" value="ログインする" style = "padding:10px;font-size:20px;background:#6699CC; color:white">
 
		</form>
 
	</body>
</html>