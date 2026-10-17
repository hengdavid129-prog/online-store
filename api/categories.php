<?php
// This file prints NO html. It answers JavaScript, not the browser.
declare(strict_types=1);
require_once __DIR__ . '/../pages/classes.php';
header('Content-Type: application/json');

if (($_POST['action'] ?? '') === 'delete') {
    $ids = (array) ($_POST['ids'] ?? []);
    $deleted = Category::deleteByIds($ids);

    $answer = $deleted > 0
        ? ['ok' => true, 'message' => "$deleted category(ies) deleted"]
        : ['ok' => false, 'message' => 'Nothing was selected'];


} else if (($_GET['action'] ?? '') === 'all') {
    $categories = Category::getCategories();
    $answer = [
        'ok' => true,
        'categories' => $categories
    ];
} else {
    $name = trim($_POST['category'] ?? '');

    if ($name === '') {
        $answer = [
            'ok' => false, 
            'message' => 'Enter a category name!'
        ];
    } else {
        $cat = new Category($name);
        $err = $cat->intoDb();

        $answer = match (true) {
            $err === true => [
                'ok' => true,  
                'message' => 'Category added',
                'id' => $cat->id, 
                'category' => $cat->category
            ],
            $err === 1062 => [
                'ok' => false, 
                'message' => "\"$name\" already exists!"
            ],
            default => [
                'ok' => false, 'message' => 
                "Error: $err"
            ],
        };
    }
}

// Turn the php array into text JavaScript understands, and send it.
echo json_encode($answer);
