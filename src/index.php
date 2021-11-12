<?php
/*
--ブラウザで出力するプログラム--

[問題]
任意の整数から任意の整数までの数をプリントするプログラム。
ただし、別の引数を２つ取り、
引数１つ目の倍数のときは数の代わりに[自分の名字]、
引数２つ目の倍数のときは[自分の名前]をプリントし、
１つ目と２つ目両方の倍数の場合には
[自分の氏名]([自分の名字]+[自分の名前])をプリントする事。

[フィードバック（盛り込んだ修正点）]
・仕様を満たす正常なプログラムでした
・$x , $y の値が0の時にエラーとなるのでバリデーションがあるとよりよいプログラムになると思います
・$a , $b の値は$bの方が大きい事を想定しているプログラムとなっていると思いますので
　こちらも仕様について言及があればよりよい関数となったのではないかと思います。
　引数の値について「2つの整数の差」分をループする解釈しrange関数などでとった値で
　ループするという形を仕様としてもよかったと思います。
*/


define('FIRSTNAME', '水希');
define('LASTNAME', '田村');
$mlt1 = '';
$mlt2 = '';
$start = '';
$end = '';
$errors = [];

/**
 * バリデーションの空チェックする関数
 * 
 * @param $errors　エラーメッセージを格納する配列
 * @param $check_value　調べる入力項目
 * @param $message　出力したいエラーメッセージ
 */
function emptyCheck(&$errors, $check_value, $message){
        if (($check_value !== '0') && empty($check_value)) {
            array_push($errors, $message);
        }
}

/**
 * 0をチェックする関数
 * 
 * @param $errors　エラーメッセージを格納する配列
 * @param $check_value　調べる入力項目
 * @param $message　出力したいエラーメッセージ
 */
function zeroCheck(&$errors, $check_value, $message){
    if ($check_value === '0') {
        array_push($errors, $message);
    }
}

/** 
 * 大小チェックする関数
 * 
 * @param $errors　エラーメッセージを格納する配列
 * @param $check_value　調べる入力項目
 * @param $message　出力したいエラーメッセージ
 */ 
function sizeCheck(&$errors, $check_value, $message){
    if ($check_value < 0) {
        array_push($errors, $message);
    }
}

/**
 * 題意を出力する関数
 * 
 * @param string $x 倍数１
 * @param string $y 倍数２
 * @param string $a 始点
 * @param string $b 終点
 * @return string $result　エラーがない場合$outputに格納する結果
 */
function program3($mlt1, $mlt2, $start, $end) {
    $extent = range($start, $end);
    foreach ($extent as $i) {
        if($i % ($mlt1 * $mlt2) === 0) {
            array_splice($extent, $i - 1, 1, LASTNAME.FIRSTNAME);
        }elseif($i % $mlt2 === 0) {
            array_splice($extent, $i - 1, 1, LASTNAME);
        }elseif($i % $mlt1 === 0) {
            array_splice($extent, $i - 1, 1, FIRSTNAME);
        }else{
            array_splice($extent, $i - 1, 1, $i);
        }
    }
    $result = implode('<br>', $extent);
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // パラメータ取得
    $mlt1 = $_POST['mlt1'];
    $mlt2 = $_POST['mlt2'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    // - 空チェック
    emptyCheck($errors, $mlt1, "「一つ目の倍数」を入力してください。");
    emptyCheck($errors, $mlt2, "「二つ目の倍数」を入力してください。");
    emptyCheck($errors, $start, "「いくつから表示」を入力してください。");
    emptyCheck($errors, $end, "「どこまで表示」を入力してください。");

    // - 範囲の大小チェック
    sizeCheck($errors, ((int)($end) - (int)($start)), "「いくつから表示」より「どこまで表示」が大きくなるように入力してください。");

    // - 0が入力されてないかチェック
    zeroCheck($errors, $mlt1, "「一つ目の倍数」に０が入力されています。");
    zeroCheck($errors, $mlt2, "「二つ目の倍数」に０が入力されています");
    zeroCheck($errors, $start, "「いくつから表示」に０が入力されています");
    zeroCheck($errors, $end, "「どこまで表示」に０が入力されています");

    if (empty($errors) && !empty($_POST["mlt1"]) && !empty($_POST["mlt2"]) && !empty($_POST["start"]) && !empty($_POST["end"])) {
    $output = program3($_POST["mlt1"], $_POST["mlt2"], $_POST["start"], $_POST["end"]);}

}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>Fizzbuzz出力</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php if (!isset($output)) : ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] ; ?>" method="POST">
                <p>表示する自分の名字：<?php echo LASTNAME ; ?></p>
                <p>表示する自分の名前：<?php echo FIRSTNAME ; ?></p>
                <p>一つ目の倍数：<input type="number" name="mlt1" value="<?php echo $mlt1 ; ?>"><br/></p>
                <p>二つ目の倍数：<input type="number" name="mlt2" value="<?php echo $mlt2 ; ?>"><br/></p>
                <p>いくつから表示：<input type="number" name="start" value="<?php echo $start ; ?>"><br/></p>
                <p>どこまで表示：<input type="number" name="end" value="<?php echo $end ; ?>"><br/></p>
            <?php
            if (isset($errors)) {
                echo '<div style="color:red;">';
                foreach ($errors as $error) {
                    echo "<div>{$error}</div>";
                }   
                echo '</div>';
                unset($errors);
            }   
            ?>  
                <p><input type="submit" value="送信"></p>
        </form>
    <?php else : ?>
        <div>出力結果は以下の通りです。</div>
            <div><?php echo $output; ?> </div>
        <div><a href="<?php echo $_SERVER['PHP_SELF'] ; ?>">戻る</a></div>
    <?php endif; ?>
</body>
</html>