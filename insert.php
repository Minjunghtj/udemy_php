<?php //フォーム値をDBに保存

// DB接続 PDO
function insertContact($request){



require 'db_connection.php';


// 入力 DB保存 prepare, execute(配列(すべて文字列)→ bindは不要)


$params = [
    'id' => null,
    'your_name' => $request['your_name'],
    'email' => $request['email'],
    'url' => $request['url'],
    'gender' => $request['gender'],
    'age' => $request['age'],
    'contact'=> $request['contact'],
    'created_at'=> null
];



/*
$params = [
    'id' => null,
    'your_name' => 'なまえ123',
    'email' => 'test@test.com',
    'url' => 'http://test.com',
    'gender' => '1',
    'age' => '2',
    'contact'=> 'いいい',
    'created_at'=> 'NOW()'
];
*/


$count = 0;
$columns = '';
$values = '';

foreach(array_keys($params) as $key){ // array=keys→ 連想配列の左側のkey
    if($count++>0){//countが0より大きくなったら[,]で区切る
        $columns .= ',';
        $values .= ',';
    }
    $columns .= $key;
    $values .= ':'.$key;

}

$sql = 'insert into contacts ('. $columns . ')values('. $values .')'; //名前付きプレースホルダ(自由に変えれる状態)
// insert into テーブル名

//var_dump($sql);
$stmt = $pdo->prepare($sql);//prepareメソッド
$stmt->execute($params); //実行

}

