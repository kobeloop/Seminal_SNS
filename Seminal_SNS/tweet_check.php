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
		header("Location: tweet_input.php");
		exit();
	} else{
		//POSTされたデータを各変数に入れる
		$account = isset($_POST['account']) ? $_POST['account'] : NULL;
		$inquiry = isset($_POST['inquiry']) ? $_POST['inquiry'] : NULL;
	
		//前後にある半角全角スペースを削除
		$account = spaceTrim($account);
		$mail = spaceTrim($mail);
		$inquiry = spaceTrim($inquiry);

		//アカウント入力判定
		
		if ($account == ''):
			$errors['account'] = "学生証番号が入力されていません。";
		elseif(mb_strlen($account)>10):
			$errors['account_length'] = "学生証番号は10文字以内で入力して下さい。";
		endif;
	
		// 投稿内容入力判定
		if ($inquiry == ''){
			$errors['inquiry'] = "投稿内容が入力されていません。";
		}
		
	}

	//エラーが無ければセッションに登録
	if(count($errors) === 0){
		$_SESSION['account'] = $account;
		$_SESSION['inquiry'] = $inquiry;
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>投稿内容確認画面</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>投稿内容確認画面</h1>
 
		<?php if (count($errors) === 0): ?>


		<form action="tweet_insert.php" method="post">
			<table align="center">
				<tr>
					<td>学生証番号</td>
					<td><?=htmlspecialchars($account, ENT_QUOTES); ?></td>
				</tr>
				<tr>
					<td>投稿内容</td>
					<td><?=htmlspecialchars($_SESSION['inquiry'], ENT_QUOTES); ?></td>
				</tr>
				<tr>
					<td><input type="button" value="戻る" onClick="history.back()"style = "width:100%;padding:10px;font-size:30px;background:#6699CC;color:white"></td>
					<td><input type="submit" value="登録する" style = "width:100%;padding:10px;font-size:30px;background:#6699CC;color:white"></td>
				</tr>
			</table>

			<center></center>
			<center></center>
			
			<input type="hidden" name="token" value="<?=$_POST['token']; ?>">

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