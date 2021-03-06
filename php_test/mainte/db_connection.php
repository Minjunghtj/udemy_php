<?php

const DB_HOST = 'mysql:dbname=udemy_php;host=127.0.0.1;charset=utf8';
const DB_USER = 'php_user';
const DB_PASSWORD = 'password123';
//定数


$pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD);
//PDO使うためクラス実体化
//Creates a PDO instance representing a connection to a database


//　例外処理 Exception(データベースにつながっているかどうかのチェック)
//　お決まりの書き方
try{
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD,[//[]はオプション
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        //連想配列（DBに接続、帰ってくる値を連想配列で表示）
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//例外を表示
        PDO::ATTR_EMULATE_PREPARES => false, //SQLインジェクション対策
    ]);
    echo '接続成功';

} catch(PDOExeption $e){
    echo '接続失敗' . $e->getMessage() . "\n";
    exit();//接続失敗の場合、処理を抜ける
}

?>
