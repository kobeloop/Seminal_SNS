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
		<h1>メール登録画面</h1>
		<form action ="check.php" method = "POST">
			<table>
				<tr>
					<td>メールアドレス</td>
					<td><input type = "text" name = "mail" size = "50" placeholder="例）abcde@efg.com"></td>
				</tr>
			</table>
			<input type="submit" value="登録する"style = "padding:10px;font-size:20px;background:#6699CC; color:white">
			<input type="hidden" name="token" value="<?=$token ?>">
		</form>
	</body>
</html>