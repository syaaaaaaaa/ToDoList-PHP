<?php
require_once('../lib/util.php');
$gobackURL = "new.php";

if(!cken($_POST)){
    header("Location:{$gobackURL}");
    exit();
}
//エラー処理
$errors = [];
if(!isset($_POST["date"])||($_POST["date"]==="")){
    $errors[] = "日付を選択してください";
}
if(!isset($_POST["item"])||($_POST["item"]==="")){
    $errors[] = "やることを入力してください";
}

//エラーがあったとき
if (count($errors)>0){
    echo '<ol class="error">';
    foreach ($errors as $value) {
        echo "<li>", $value , "</li>";
    }
    echo "</ol>";
    echo "<hr>";
    echo "<a href=", $gobackURL, ">戻る</a>";
    exit();
}
// データベースユーザ
$user = 'root';
$password = '';
// 利用するデータベース
$dbName = 'todolist';
// MySQLサーバ
$host = 'localhost:3306';
// MySQLのDSN文字列
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/Liststyle.css">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="css/table_style.css">
    </head>
    <body>
        <h2>ToDoリスト</h2>
        <div>
        <ul>
            <li>
                <a href="new.php">予定を追加</a>
            </li>
            <li>
                <a href="search.php">予定を検索、確認</a>
            </li>
        </ul>
        <div class="item">　</div>
        <div class="item">　</div>
            <?php
            $date = $_POST["date"];
            $item = $_POST["item"];
            $remarks = $_POST["remarks"];
            //MySQLデータベースに接続する
            try {
                $pdo = new PDO($dsn, $user, $password);
                // プリペアドステートメントのエミュレーションを無効にする
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                // 例外がスローされる設定にする
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // SQL文を作る
                $sql = "INSERT INTO datalist (date, item, remarks) VALUES (:date, :item, :remarks)";
                // プリペアドステートメントを作る
                $stm = $pdo->prepare($sql);
                // プレースホルダに値をバインドする
                $stm->bindValue(':date', $date, PDO::PARAM_STR);
                $stm->bindValue(':item', $item, PDO::PARAM_INT);
                $stm->bindValue(':remarks', $remarks, PDO::PARAM_STR);
                // SQL文を実行する
                if ($stm->execute()){
                    // レコード追加後のレコードリストを取得する
                    $sql = "SELECT * FROM datalist ORDER BY date";
                    // プリペアドステートメントを作る
                    $stm = $pdo->prepare($sql);
                    // SQL文を実行する
                    $stm->execute();
                    // 結果の取得（連想配列で受け取る）
                    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                    // テーブルのタイトル行
                    echo "<table>";
                    echo "<thead><tr>";
                    echo "<p>追加しました。</p>";
                    echo "<th class='date'>", "日付", "</th>";
                    echo "<th class='item'>", "やること", "</th>";
                    echo "<th class'remarks'>", "備考", "</th>";
                    echo "</tr></thead>";
                    // 値を取り出して行に表示する
                    echo "<tbody>";
                    foreach ($result as $row) {
                        // １行ずつテーブルに入れる
                        echo "<tr>";
                        echo "<td>", es($row['date']), "</td>";
                        echo "<td>", es($row['item']), "</td>";
                        echo "<td>", es($row['remarks']), "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo '<span class="error">追加エラーがありました。</span><br>';
                };
            } catch (Exception $e) {
                echo '<span class="error">エラーがありました。</span><br>';
                echo $e->getMessage();
            }
            ?>
            <hr>
            <p><a href="<?php echo $gobackURL ?>">戻る</a></p>
            <p><a href="menu.html">トップに戻る</a></p>
        </div>
    </body>
</html>
