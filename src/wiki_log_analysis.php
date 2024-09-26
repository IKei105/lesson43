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
        while ($value = mysqli_fetch_assoc($result)) {
            var_export($value);
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
    LIMIT 5;
EOT;
    foreach($input as $domain) {
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $domain);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($resultArrays = mysqli_fetch_assoc($result)) {
                var_export($resultArrays);
            }
            foreach ($resultArrays as $array) {
                print_r($array[language] . PHP_EOL);
            }
        } else {
            echo "エラー: " . $stmt->error;
        }   
    }
}

//入力した値によって分岐する、strだったらselect文に入れるし、数字だったらlimit の値に使う
//ドメインだった場合 limiの数はcount(input)みたいな感じにする,explode()使わんといかんけどね
mysqli_close($mysqli);

function userInput(): array
{
    echo '数字を入力したらビュー数の多い順に入力した数字分表示します。' . PHP_EOL;
    echo 'ドメインを入力したら入力したドメインのビュー数の最も多い記事を表示します。(例:en jp)' . PHP_EOL;
    $input = explode(' ', trim(fgets(STDIN)));
    return $input;
}



?>