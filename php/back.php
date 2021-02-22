<?php
session_start();
//新遊戲建立答案
if (!isset($_SESSION['randNumber'])) {
    $_SESSION['randNumber'] = createRandNumber();
}
//顯示答案
if ($_POST['poi'] == true) {
    echo '答案是：' . $_SESSION['randNumber'];
    exit;
}

//檢查數字
checkPostNumber($_POST['number']);
$_POST['number'] = sprintf("%04d", $_POST['number']); 
//字串切割
$randArray = str_split($_SESSION['randNumber'], 1);
$userArray = str_split($_POST['number'], 1);
$A = $B = 0;

for ($i = 0; $i < count($userArray); $i++) {
    if ($userArray[$i] == $randArray[$i]){
        $A++;
    } elseif (in_array($randArray[$i], $userArray)) {
        $B++;
    }
}

if($A == 4) {
    //猜對後將資料清空，以進行下一局‘
    unset($_SESSION['randNumber']);
    unset($_SESSION['history']);
    echo $A.'A'.$B.'B！<br>';

} else {
    $_SESSION['history'] .= $_POST['number'] . ' ' . $A . 'A' . $B . 'B<br>';
    echo $A.'A'.$B.'B<br>';
    echo $_SESSION['history'];

}

//認證輸入的數字
function checkPostNumber($postData)
{
    $data = str_split($postData, 1);
    if (count($data) != 4 ) {
        echo '請輸入4位數字';
        exit;
    }
}
//認證隨機數無相同數字
function checkRandNumber($randNumber)
{
    $randNumberq = str_split($randNumber, 1);
    for ($i = 0; $i < count($randNumberq); $i++) {
        for ($j = $i+1; $j < count($randNumberq); $j++) {
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
