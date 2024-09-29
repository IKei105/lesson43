<?php

$limit = 1;

$mysqli = new mysqli('db', 'root', 'pass', 'wiki_log');
if ($mysqli->connect_error) {
    echo '接続失敗'.PHP_EOL;
    exit();
} else {
    echo '接続成功'.PHP_EOL;
}

$input = userInput();

if (is_numeric($input[0])) {
    ///これは数字だからlimitに入れることにな
    $sql = <<<EOT
    SELECT language, page_name, request_times
    FROM wiki_log_2021_1201_12
    LIMIT ?;
EOT;
    $stmt = $mysqli->prepare($sql);
    $limitNum = (int)$input[0];
    $stmt->bind_param('i', $limitNum);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = mysqli_fetch_assoc($result)) {
            //echo "Page Name: " . $row['page_name'] . ", Language: " . $row['language'] . PHP_EOL;
            echo '"' . $row['language'] . '","Main_page,"' .  $row['page_name'] . PHP_EOL;
        }
    } else {
        echo "エラー: " . $stmt->error;
    }
} else {
    //これは文字列だからselectに使うことになる
    //配列の数だけSQLを実行する
    $sql = <<<EOT
    SELECT language, page_name, request_times
    FROM wiki_log_2021_1201_12
    WHERE language = ?
    ORDER BY language
    LIMIT 1;
EOT;
    foreach($input as $domain) {
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $domain);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = mysqli_fetch_assoc($result)) {
                //echo "Page Name: " . $row['page_name'] . ", Language: " . $row['language'] . PHP_EOL;
                echo '"' . $row['language'] . '"' .  $row['request_times'] . PHP_EOL;
            }
        } else {
            echo "エラー: " . $stmt->error;
        }   
    }
}

mysqli_close($mysqli);

function userInput(): array
{
    echo '数字を入力したらビュー数の多い順に入力した数字分表示します。' . PHP_EOL;
    echo 'ドメインを入力したら入力したドメインのビュー数の最も多い記事を表示します。(例:en jp)' . PHP_EOL;
    $input = explode(' ', trim(fgets(STDIN)));
    return $input;
}





?>