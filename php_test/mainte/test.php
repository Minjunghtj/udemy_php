<?php
//パスワードを記録したファイルの場所
echo __FILE__;
//C:\xampp\htdocs\php_test\mainte\test.php

echo'<br>';
//パスワード(暗号化)
//方法：password_hash→ password_bcrypt
echo(password_hash('password123', PASSWORD_BCRYPT));
//$2y$10$AXzmwxkXlZJjBrJjnLHoaur2q7nULGDbwGSbLIP6loDE8Vq16GEwK
echo'<br>';


$contactFile = '.contact.dat';

//ファイル丸ごと読み込み
//$fileContents = file_get_contents($contactFile);
//ファイル名指定
//echo $fileContents;



//ファイルに書き込み（上書き）
//file_put_contents($contactFile, 'テストです');

//$addText = 'テストです' . "\n";



//ファイルに書き込み（追記）
//file_put_contents($contactFile, $addText, FILE_APPEND);

//配列 file, 区切る　explode, foreach

/*$allData = file($contactFile);

foreach($allData as $lineData){
    $lines = explode(',', $lineData);
    echo $lines[0]. '<br>';
    echo $lines[1]. '<br>';
    echo $lines[2]. '<br>';
}*/



$contents = fopen($contactFile, 'a+');//追記

$addText = '1行追記' . "\n";

fwrite($contents, $addText);

fclose($contents);


?>