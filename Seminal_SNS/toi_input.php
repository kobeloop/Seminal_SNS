<?php
	session_start();
	header("Content - type: text/html; charset = utf-8");
	
	// クロスサイトリクエストフォージェリ(CSRF)対策
	for($i = 0; $i < 64; $i++){
		$_SESSION['token'] = $_SESSION['token']. substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1); 
	}
// 	$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
	$token = $_SESSION['token'];
	
	// クリックジャッキング対策
	header('X - FRAME - OPTIONS: SAMEORIGN');
	
?>
<!DOCTYPE html>
<html lang = "ja">
<html>
	<head>
		<meta charset = "UTF-8">
		<title>メール登録画面	</title>
	</head>
	<body>
		<h1>お問い合わせフォーム</h1>
		<form action ="toi_check.php" method = "POST">
			<table align="center">
				<tr>
					<td>学生証番号</td>
					<td><input type = "text" name = "account" size = "50" placeholder="例）6bss○○○○"></td>
				</tr>
				<tr>
					<td>メールアドレス</td>
					<td><input type = "text" name = "mail" size = "50" placeholder="例）abcde@efg.com"></td>
				</tr>
				<tr>
					<td>問い合わせ内容</td>
					<td><textarea name="inquiry" cols="50" rows="5"></textarea></td>
				</tr>
			</table>
			<div style = "margin-left:10%;margin-right:10%;">
				<center><input type="submit" value="確認する" style = "padding:10px;font-size:30px;background:#6699CC;color:white"></center>
			</div>
			<input type="hidden" name="token" value="<?=$token ?>">
		</form>
	</body>
</html>