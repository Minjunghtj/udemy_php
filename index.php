<?php

require 'db_connection.php';


//DB中のデータ修飾するため、SQL分打つ方法

//ユーザー入力なし(毎回同じ表示) queryメソッド利用

/*
$sql = 'select * from contacts where id = 3'; //sql
$stmt = $pdo->query($sql); //sql実行　ステートメント
$result = $stmt-> fetchall(); //sql結果表示
echo '<pre>';
var_dump($result);
echo '</pre>';
*/


//ユーザー入力あり(検索、お問い合わせフォーム,ユーザーが入力) prepare, bind, execute 
//悪意ユーザー delete *するとまずい→ SQLインジェクション対策(SQL文が打たれないうように対策)
$sql = 'select * from contacts where id = :id'; //名前付きプレースホルダ(自由に変えれる状態)
$stmt = $pdo->prepare($sql); //prepareメソッド
$stmt->bindValue('id', 4, PDO::PARAM_INT);//実際の値を紐付ける
$stmt->execute(); //実行

$result = $stmt-> fetchall();
echo '<pre>';
var_dump($result);
echo '</pre>';





//　トランザクション　まとまって処理 beginTransaction, commit, rollback
//　ex)銀行　残高を確認 → Aさんから引き落とし → Bさんに振り込み

$pdo->beginTransaction();

try{

//sql処理
$stmt = $pdo->prepare($sql);//prepareメソッド
$stmt->bindValue('id', 3, PDO::PARAM_INT);//実際の値を紐付ける
$stmt->execute(); //実行

$pdo->commit();

}catch(PDOException $e){//例外が発生したら

    $pdo->rollback();//更新のキャンセル

}

?>














