<?php
require_once("../lib/util.php");
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
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/b18bae849e.js" crossorigin="anonymous"></script>
        <title></title>
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
            <li>
                <a href="menu.html">戻る</a>
            </li>
        </ul>
            <!-- 入力フォーム -->
            <form method="POST" action="insert_after.php">
                <div class="item">　</div>
                <div class="item">
                    <label>日付：
                    <input type="date" name="date" class="date">
                    </label>
                </div>
                <div class="item">
                    <label>やること：
                    <input type="text" name="item" placeholder="例：パンを買う" class="text">
                    </label>
                </div>
                <div class="item">
                    <label>備考：
                    <input type="text" name="remarks" placeholder="例：食パン1個、菓子パン3個" class="text">
                    </label>
                </div>
                <div><input type="submit" value="追加する" class="button"></div>
            </form>

            <!-- データベース接続 -->
            <?php
            try{
                $pdo = new PDO($dsn, $user, $password);
                // プリペアドステートメントのエミュレーションを無効にする
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                // 例外がスローされる設定にする
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "データベース{$dbName}に接続しました。", "<br>";
                // SQL文を作る
                $sql = "SELECT * FROM datalist ORDER BY date";
                // プリペアドステートメントを作る
                $stm = $pdo->prepare($sql);
                // SQL文を実行する
                $stm->execute();
                //レコードの数を取得するSQL文
                $sql = "SELECT COUNT(id) FROM datalist";
                // プリペアドステートメントを作る
                $count = $pdo->prepare($sql);
                // SQL文を実行する
                $count->execute();
                // 結果の取得（連想配列で返す）
                $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                    // テーブルのタイトル行
                echo "<table>";
                echo "<thead><tr>";
                echo "<th class='date'>", "日付", "</th>";
                echo "<th class='item'>", "やること", "</th>";
                echo "<th class='remarks'>", "備考", "</th>";
                echo "</tr></thead>";
                // 値を取り出して行に表示する
                echo "<tbody>";
                foreach ($result as $row){
                // １行ずつテーブルに入れる
                    echo "<tr>";
                    echo "<td>", es($row['date']), "</td>";
                    echo "<td>", es($row['item']), "</td>";
                    echo "<td>", es($row['remarks']), "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } catch (Exception $e) {
                echo '<span class="error">エラーがありました。</span><br>';
                echo $e->getMessage();
                exit();
            }
            ?>
        </div>
    </body>
</html>
