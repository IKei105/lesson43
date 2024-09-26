<?php

const LANGUAGE = 0;
const PAGE_NAME = 1;
const REQUEST_TIME = 2;
const BYTES = 3;

$mysqli = new mysqli('db', 'root', 'pass', 'wikipedia_log');
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

    $stmt = $mysqli->prepare("INSERT INTO log_data_20211201_0120 
    (language, page_name, request_times, page_byte) VALUES(?,?,?,?)");

    $stmt->bind_param('ssii', $explodeStr[LANGUAGE], $explodeStr[PAGE_NAME], $explodeStr[REQUEST_TIME], $explodeStr[BYTES]);

    if ($stmt->execute()) {
        echo "データの挿入が成功しました。";
    } else {
        echo "エラー: " . $stmt->error;
    }
}