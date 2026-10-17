<?php
declare(strict_types=1);
require_once __DIR__ . '/../pages/classes.php';
header('Content-Type: application/json');

$itemname = trim($_POST['itemname'] ?? '');
$catid = (int) ($_POST['catid'] ?? 0);
$pricein = (int) ($_POST['pricein'] ?? 0);
$pricesale = (int) ($_POST['pricesale'] ?? 0);
$info = trim($_POST['info'] ?? '');
$path = '';
if (is_uploaded_file($_FILES['imagepath']['tmp_name'] ?? '')) {
    $name = basename($_FILES['imagepath']['name']);
    if (move_uploaded_file($_FILES['imagepath']['tmp_name'], __DIR__ . "/../images/$name")) {
        $path = "images/$name";
    }
}

if ($catid === 0) {
    $answer = [
        'ok' => false,
        'message' => 'Select Category'
    ];
} elseif ($itemname === '') {
    $answer = [
        'ok' => false,
        'message' => 'Enter Item Name!'
    ];
} elseif ($pricein === 0) {
    $answer = [
        'ok' => false,
        'message' => 'Enter Price In!'
    ];
} elseif ($pricesale === 0) {
    $answer = [
        'ok' => false,
        'message' => 'Enter Price Sale'
    ];
} else {
    $item = new Item($itemname, $catid, $pricein, $pricesale, $info, $path);
    $err = $item->intoDb();

    if ($err === true) {
        $answer = ['ok' => true, 'message' => 'Item Added'];
    } else {
        error_log("item.php: insert failed: $err");
        $answer = ['ok' => false, 'message' => "Could not add the item. Please try again."];
    }
}

echo json_encode($answer);