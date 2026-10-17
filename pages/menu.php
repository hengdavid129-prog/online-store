<?php
declare(strict_types=1);

$items = [
    1 => 'Catalog',
    2 => 'My Cart',
    3 => 'Registration',
    4 => 'Admin',
    5 => 'Report'
];
$page = (int)($_GET['page'] ?? 1);
?>

<ul class="nav nav-pills nav-fill">
    <?php foreach ($items as $n => $title): ?>
        <li class="nav-item">
            <a class="nav-link <?= $page === $n ? 'active' : '' ?>" href="index.php?page=<?= $n ?>"><?= $title ?></a>
        </li>
    <?php endforeach; ?>
</ul>