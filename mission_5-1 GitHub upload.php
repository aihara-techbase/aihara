<?php
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	$editNumber = "";
	$editName = "";
	$editComment = "";
	//新規投稿
	if((!empty($_POST['name']))&&(!empty($_POST['comment']))&&(!empty($_POST['password']))&&empty($_POST['edit_post'])){
			$name = $_POST['name'];
			$comment = $_POST['comment'];
			$date = date("Y/m/d H:i:s");
			$pass = $_POST['password'];
			$sql = "CREATE TABLE IF NOT EXISTS keijiban"
			." ("
			."id INT AUTO_INCREMENT PRIMARY KEY,"
			."name char(32),"
			."comment TEXT,"
			."post_date datetime,"
			."password TEXT"
			.");";
			$stmt = $pdo->query($sql);
			$stmt = $pdo->prepare("INSERT INTO keijiban (name, comment, post_date, password) VALUES(:name, :comment, :post_date, :password)");
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt -> bindParam(':post_date',$date, PDO::PARAM_STR);
			$stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
			$stmt-> execute();
		}
?>

<?php
	//削除機能
	if(!empty($_POST['deletenumber'])){
		$id = $_POST['deletenumber'];
		$pass = $_POST['password'];
		$sql = 'delete from keijiban where id=:id and password=:password';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->bindParam(':password', $pass, PDO::PARAM_STR);
		$stmt->execute();
	}
?>

<?php
	//編集機能
	if(!empty($_POST['edit_number'])){
		$editNumber = $_POST['edit_number']; //変更する投稿番号
		$pass = $_POST['password'];
		$sql = 'select name, comment from keijiban where id=:id and password=:password';
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':id', $editNumber, PDO::PARAM_INT);
		$stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
		$stmt -> execute();
		$results = $stmt->fetch();
		$editName = $results['name'];
		$editComment = $results['comment'];
	}
?>

<?php
	if((!empty($_POST['edit_post']))&&(!empty($_POST['name']))&&(!empty($_POST['comment']))&&(!empty($_POST['password']))){	
		$id = $_POST['edit_post'];
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$pass = $_POST['password'];
		$sql = 'update keijiban set name=:name,comment=:comment where id=:id and password=:password';
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
		$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
		$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt -> execute();
	}
	
?>

<html>
	<head> 
	<title>mission_5-1</title>
	<meta charset=“utf-8”>
		<body>
		
	 <form method="post" action="mission_5-1.php">  <!-- post送信でactionは送信先-->
			 名前 : <input type="text" name="name" value="<?php echo $editName;?>"><br>
 			 コメント : <input type="text" name="comment" value="<?php echo $editComment;?>"><br>
 			<input type = "hidden" name = "edit_post" value="<?php echo $editNumber;?>">
 			パスワード : <input tyep = "text" name="password" value =""><br>
  			<input type="submit" name="submit" value="送信"><br>
  			<br>
  			</form>
  			<form method="post" action="mission_5-1.php" > 
  			削除対象番号 : <input type="text" name="deletenumber" value=""><br> <!--入力フォームと並べて「削除番号指定用フォーム」を用意-->
  			パスワード : <input tyep = "text" name="password" value =""><br>
  			<!--「削除対象番号」の入力と「削除」ボタンが1つある-->
  			<input type = "submit" name = "sakujo" value = "削除"><br>
  			 <br>
  			 </form>
  			 <form method="post" action="mission_5-1.php" > 
  			編集対象番号 : <input type = "text" name = "edit_number" value""><br>
  			パスワード : <input tyep = "text" name="password" value =""><br>
  			<input type = "submit" name = "edit" value = "編集"><br>
			</form>
		</body>
</html>					

<?php
	//表示フォーム
	$sql = 'SELECT * FROM keijiban';
	$stmt = $pdo->query($sql);
	$results = $stmt -> fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].'  '.$row['name'].'  '.$row['comment'].'  '.$row['post_date'].'<br>';
		echo "<hr>";
	}
?>
