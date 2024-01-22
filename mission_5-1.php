<?php
// データベース接続情報
$dsn = 'mysql:dbname=tb250628db;host=localhost'; // 修正されたデータベース接続情報
$user = 'tb-250628';
$password = 'sBuSC7Khrw';

try {
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    // テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name CHAR(32),
                comment TEXT,
                post_date DATETIME,
                password TEXT
            );";
    $pdo->exec($sql);
    
} catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
}

// 新規投稿処理
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $password = $_POST['password'];
    $date = date("Y/m/d H:i:s");

    $stmt = $pdo->prepare("INSERT INTO posts (name, comment, post_date, password) VALUES (:name, :comment, :date, :password)");
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
}

// 削除処理
if (isset($_POST['delete'])) {
    $deleteID = $_POST['deleteID'];
    $deletePassword = $_POST['deletePassword'];

    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id AND password = :password");
    $stmt->bindParam(':id', $deleteID, PDO::PARAM_INT);
    $stmt->bindParam(':password', $deletePassword, PDO::PARAM_STR);
    $stmt->execute();
}

// 編集処理
if (isset($_POST['edit'])) {
    $editID = $_POST['editID'];
    $editPassword = $_POST['editPassword'];
    $name = $_POST['editName'];
    $comment = $_POST['editComment'];

    $stmt = $pdo->prepare("UPDATE posts SET name = :name, comment = :comment WHERE id = :id AND password = :password");
    $stmt->bindParam(':id', $editID, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':password', $editPassword, PDO::PARAM_STR);
    $stmt->execute();
}
?>

<!-- 投稿フォーム -->
<form action="" method="post">
    <input type="text" name="name" placeholder="名前">
    <input type="text" name="comment" placeholder="コメント">
    <input type="password" name="password" placeholder="パスワード">
    <input type="submit" name="submit" value="送信">
</form>

<!-- 削除フォーム -->
<form action="" method="post">
    <input type="number" name="deleteID" placeholder="削除対象番号">
    <input type="password" name="deletePassword" placeholder="パスワード">
    <input type="submit" name="delete" value="削除">
</form>

<!-- 編集フォーム -->
<form action="" method="post">
    <input type="number" name="editID" placeholder="編集対象番号">
    <input type="text" name="editName" placeholder="新しい名前">
    <input type="text" name="editComment" placeholder="新しいコメント">
    <input type="password" name="editPassword" placeholder="パスワード">
    <input type="submit" name="edit" value="編集">
</form>

<?php
// 投稿の表示
$stmt = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
while ($row = $stmt->fetch()) {
    echo $row['id'].' '.$row['name'].' '.$row['comment'].' '.$row['post_date'].'<br>';
}
?>

