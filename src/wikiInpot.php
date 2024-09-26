<?php

const LANGUAGE = 0;
const PAGE_NAME = 1;
const REQUEST_TIME = 2;
const BYTES = 3;

$mysqli = new mysqli('db', 'root', 'pass', 'wiki_log');
if ($mysqli->connect_error) {
    echo '接続失敗'.PHP_EOL;
    exit();
} else {
    echo '接続成功'.PHP_EOL;
}

$fileName = 'pageviews-20211201-000000';
$fp = fopen($fileName, 'r');

$inportCount = 0;
while( $read_str = fgets($fp) ) {
    $explodeStr =  explode(' ', $read_str);

    $stmt = $mysqli->prepare("INSERT INTO wiki_log_2021_1201_12 
    (language, page_name, request_times, page_byte) VALUES(?,?,?,?)");

    $stmt->bind_param('ssii', $explodeStr[LANGUAGE], $explodeStr[PAGE_NAME], $explodeStr[REQUEST_TIME], $explodeStr[BYTES]);

    if ($stmt->execute()) {
        $inportCount++;
        echo $inportCount . PHP_EOL;
    } else {
        echo "エラー: " . $stmt->error;
    }
}