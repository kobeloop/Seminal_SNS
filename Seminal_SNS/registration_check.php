<?php
	session_start();

	header("Content-type: text/html; charset=utf-8");

	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
	if ($_POST['token'] != $_SESSION['token']){
		echo "不正アクセスの可能性あり";
		exit();
	}

	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');
	
	//データベース接続
	require_once("db.php");
	$dbh = db_connect();

	//前後にある半角全角スペースを削除する関数
	function spaceTrim ($str) {
		// 行頭
		$str = preg_replace('/^[ 　]+/u', '', $str);
		// 末尾
		$str = preg_replace('/[ 　]+$/u', '', $str);
		return $str;
	}

	//エラーメッセージの初期化
	$errors = array();

	if(empty($_POST)) {
		header("Location: registration_mail_form.php");
		exit();
	}else{
		//POSTされたデータを各変数に入れる
		$account = isset($_POST['account']) ? $_POST['account'] : NULL;
		$password = isset($_POST['password']) ? $_POST['password'] : NULL;
	
		//前後にある半角全角スペースを削除
		$account = spaceTrim($account);
		$password = spaceTrim($password);

		//アカウント入力判定
		
		if ($account == ''):
			$errors['account'] = "学生証番号が入力されていません。";
		elseif(mb_strlen($account)>10):
			$errors['account_length'] = "学生証番号は10文字以内で入力して下さい。";
		endif;
	
		//パスワード入力判定
		if ($password == ''):
			$errors['password'] = "パスワードが入力されていません。";
		elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
			$errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力して下さい。";
		else:
			$password_hide = str_repeat('*', strlen($password));
		endif;
	
	}

	//エラーが無ければセッションに登録
	if(count($errors) === 0){
		$_SESSION['account'] = $account;
		$_SESSION['password'] = $password;
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>会員登録確認画面</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>会員登録確認画面</h1>
 
		<?php if (count($errors) === 0): ?>


		<form action="registration_insert.php" method="post">
			<table>
				<tr>
					<td>メールアドレス</td>
					<td><?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></td>
				</tr>
				<tr>
					<td>学生証番号</td>
					<td><?=htmlspecialchars($account, ENT_QUOTES)?></td>
				</tr>
				<tr>
					<td>パスワード</td>
					<td><?=$password_hide?></td>
				</tr>
			</table>

<!-- 			<p>メールアドレス：<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></p> -->
<!-- 			<p>アカウント名：<?=htmlspecialchars($account, ENT_QUOTES)?></p> -->
<!-- 			<p>パスワード：<?=$password_hide?></p> -->

			<input type="button" value="戻る" onClick="history.back()"style = "padding:10px;font-size:20px;background:#6699CC; color:white">
			<input type="hidden" name="token" value="<?=$_POST['token']?>"style = "padding:10px;font-size:20px;background:#6699CC; color:white">
			<input type="submit" value="登録する">

		</form>

		<?php elseif(count($errors) > 0): ?>

		<?php
			foreach($errors as $value){
				echo "<p>".$value."</p>";
			}
		?>

		<input type="button" value="戻る" onClick="history.back()"style = "padding:10px;font-size:20px;background:#6699CC; color:white">

		<?php endif; ?>
 
	</body>
</html>