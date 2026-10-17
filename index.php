<?php
declare(strict_types=1);
require_once 'pages/classes.php';

$page = (int)($_GET['page'] ?? 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header class="d-flex justify-content-between align-items-center mb-3">
            <h2>PHP Online Store</h2>
            <?php include_once __DIR__ . '/pages/login.php' ?>
        </header>
        <nav><?php include_once __DIR__ . '/pages/menu.php' ?></nav>
        <section>
            <?php
            $file = match($page) {
                1 => 'catalog.php',
                2 => 'cart.php',
                3 => 'registration.php',
                4 => 'admin.php',
                5 => 'report.php',
            };

            if ($file !== null) {
                 require_once __DIR__ . "/pages/$file";
            }
            ?>
        </section>
        <footer class="text-center">Step Academy &copy;</footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>