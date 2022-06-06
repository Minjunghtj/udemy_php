<?php

// CSRF 偽造のinput.php->悪意のあるページ、対策→本物のinputから情報が来たか偽造のinputから来たか見分ける
session_start();

require 'validation.php';

//クリックジャッキングの対策
header('X-FRAME-OPTIONS:DENY');


//　get通信見るため
// スーパーグローバル変数　php 9種類
//　連想配列

if(!empty($_POST)){//!--formに入力したのがURLバーに出てくる
    //?マークの後はクエリ
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
}



//フォームセキュリティXSS
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}




//入力、確認、完了　input.php, confirm.php, thanks.php
//input.php

$pageFlag = 0;//0→入力、１→確認、2→完了
$errors = validation($_POST);

if(!empty($_POST['btn_confirm']) && empty($errors)){
    $pageFlag = 1;
}
if(!empty($_POST['btn_submit'])){//btn_submitの値が空ではなかったら、実行
    $pageFlag = 2;
}


?>


<!doctype html>
<html lang="ja">
  <head>
      <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
<body>


<?php if($pageFlag === 1 ) : ?>
<?php if($_POST['csrf'] === $_SESSION['csrfToken']) : ?>
<!--あいことばが正しいかどうか、正しいなら確認画面のフォームを表示する-->
    
<form method="POST" action="input.php">
氏名
<?php echo h($_POST['your_name']);?>
<br>
メールアドレス
<?php echo h($_POST['email']);?>
<br>
ホームページ
<?php echo h($_POST['url']);?>
<br>
性別
<?php
    if($_POST['gender'] ===  '0'){ echo '男性'; }
    if($_POST['gender'] ===  '1'){ echo '女性'; }
?>
<br>
年齢
<?php
 if($_POST['age'] ===  '1'){ echo '~19歳';}
 if($_POST['age'] ===  '2'){ echo '20歳~29歳';}
 if($_POST['age'] ===  '3'){ echo '30歳~39歳';}
 if($_POST['age'] ===  '4'){ echo '40歳~49歳';}
 if($_POST['age'] ===  '5'){ echo '50歳~59歳';}
 if($_POST['age'] ===  '6'){ echo '60歳~';}
?>
<br>
お問い合わせ内容
<?php echo h($_POST['url']);?>
<br>

<input type="submit" name="back" value="戻る">
<!--戻るボタン、押すとbtn_confirmやsubmmitの値がないのでpageFlag0のまま流れてまた入力-->  
<!--戻るボタン押した時、値を持ち越して持ってきたい-->    
<input type="submit" name="btn_submit" value="送信する">
<input type="hidden" name="your_name" value="<?php echo h($_POST['your_name']);?>">
?>">
<!--一度通信すると中身が消えるので、表では表示されないが、画面が切り替わってもデータを持っておく-->
<input type="hidden" name="email" value="<?php echo h($_POST['email']);?>">
<input type="hidden" name="url" value="<?php echo h($_POST['url']);?>">
<input type="hidden" name="gender" value="<?php echo h($_POST['gender']);?>">
<input type="hidden" name="age" value="<?php echo h($_POST['age']);?>">
<input type="hidden" name="contact" value="<?php echo h($_POST['contact']);?>">
<input type="hidden" name="csrf" value="<?php echo h($_POST['csrf']);?>">
</form>
<?php endif; ?>
<?php endif; ?>




<?php if($pageFlag === 2 ): ?>
<?php if($_POST['csrf'] === $SESSION['csrfToken']) :?>  

    
<?php require '../mainte/insert.php';
insertContact($_POST);
?>


送信が完了しました。

<?php unset($_SESSION['csrfToken']); ?>
<?php endif; ?>
<?php endif; ?>


<?php if($pageFlag === 0 ): ?>
<?php
if(!isset($_SESSION['csrfToken'])){
/*$_SESSION(get,postみたいなスーパーグローバル変数)が'csrfToken'に設定されていなかったら実行*/
$csrfToken/*あいことば*/ = bin2hex(random_bytes(32));//CSRF対策→暗号化的に安全な、疑似ランダムなバイト列を生成する
$_SESSION['csrfToken'] = $csrfToken;
}
$token = $_SESSION['csrfToken'];
?>

<?php if(!empty($errors) && !empty($_POST['btn_confirm'])) : ?>
<?php echo '<ul>' ;?>
<?php
   foreach($errors as $error){
       echo'<li>'. $error . '</li>';
   } 
?>

<?php echo '</ul>' ;?>
<?php endif ;?>




<div class="container">
    <div class="row">
        <div class="col-md-6"> <!--gird,12の中で6→画面の半分-->
        <form method="POST" action="input.php">   
        <div class="form-group">
            <label for="your_name">氏名</label>
            <input type="text" class="form-control" id="your_name" name="your_name" value="<?php if(!empty($_POST['your_name'])){echo h($_POST['your_name']);}?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php if(!empty($_POST['email'])){echo h($_POST['email']);}?>" required>
        </div>

        <div class="form-group">
            <label for="url">ホームページ</label>
            <input type="text" class="form-control" id="url" name="url" value="<?php if(!empty($_POST['url'])){echo h($_POST['url']);}?>">
        </div>

        性別 <br>
        <div class="form-check form-check-inline">
        <input type="radio" class="form-check-input" name="gender" id="gender1" value="0">
        <?php if(isset($_POST['gender']) && $_POST['gender']==='0') {echo 'checked';}?>
    
        <label class="form-check-label" for="gender1">男性</label>
        <input type="radio" class="form-check-input" name="gender" id="gender2" value="1">
        <?php if(isset($_POST['gender']) && $_POST['gender']==='1') {echo 'checked';}?> 
       
        <label class="form-check-label" for="gender2">女性</label>
        </div> <br><br>
        <!--emptyは0でもtrueになるためNG-->

        <div class="form-group">
            <label for="age">年齢</label>
            <select class="form-control" id="age" name="age">
            <option value="">選択してください</option>
            <option value="1" selected>~19歳</option>
            <option value="2">20歳~29歳</option>
            <option value="3">30歳~39歳</option>
            <option value="4">40歳~49歳</option>
            <option value="5">50歳~59歳</option>
            <option value="6">60歳~</option>
        </select>
        </div>

        <div class="form-group">
            <label for="contact">お問い合わせ内容</label>
            <textarea class="form-control" id="contact" row="3" name="contact">
            <?php if(!empty($_POST['contact'])){echo h($_POST['contact']);} ?>
            </textarea>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="caution" name="caution" value="1">注意項目にチェックする
            <label class="form-check-label" for="caution">注意事項にチェックする</label>
        </div>

        <input class="btn btn-info" type="submit" name="btn_confirm" value="確認する">
        <input type="hidden" name="csrf" value="<?php echo $token?>">
        <br>
        <!--<input type="checkbox" name="sports[]" value="サッカー">サッカー
        <input type="checkbox" name="sports[]" value="野球">野球
        <input type="checkbox" name="sports[]" value="バスケ">バスケ   []は複数のcheckbox
        -->
        </form>

        </div><!-- .col-md-6 -->
    </div>
</div>
<?php endif ;?>


 <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
