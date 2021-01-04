<?php
session_start();

checkPostNumber($_POST['number']);

if(!isset($_SESSION['rand'])) {
    $_SESSION['rand'] = createRandNumber();
}

$_POST['number'] = sprintf("%04d", $_POST['number']); 
//字串切割
$randarr = str_split($_SESSION['rand'], 1);
$userarr = str_split($_POST['number'], 1);
//var_dump($randarr);
$A = 0;
$B = 0;
for($i = 0; $i < count($userarr); $i++) {
    if ($userarr[$i] == $randarr[$i]){
        $A++;
    } elseif (in_array($randarr[$i], $userarr)) {
        $B++;
    }
}

if($A == 4) {
    unset($_SESSION['rand']);
    unset($_SESSION['his']);
} else {
    $_SESSION['his'] .= $_POST['number'] . ' ' . $A . 'A' . $B . 'B<br>';
}

echo $A.'A'.$B.'B<br>';
echo $_SESSION['his'];

//認證輸入的數字
function checkPostNumber($postData)
{
    $data = str_split($postData, 1);
    if(count($data) != 4 ) {
        echo '請輸入4位數字';
        exit;
    }
}

//認證隨機數無相同數字
function checkRandNumber($randNumber)
{
    $randNumberq = str_split($randNumber, 1);
    for($i = 0; $i < count($randNumberq); $i++) {
        for($j = $i+1; $j < count($randNumberq); $j++) {
            if ($randNumberq[$i] == $randNumberq[$j]) {
                $randNumber = createRandNumber();
            }
        }
    }
    return $randNumber;

}
//創造隨機數
function createRandNumber()
{
    $rand = rand(0000,9999);
    $rand = sprintf("%04d", $rand);
    $checkedNum = checkRandNumber($rand);
    return $checkedNum;
}
?>