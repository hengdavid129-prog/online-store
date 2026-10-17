<?php
require_once 'classes.php'
?>
<h3>Registration Form</h3>
<?php if (!isset($_POST['regbtn'])): ?>
<form action="index.php?page=3" method="post" enctype="multipart/form-data">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="login" name="login" placeholder="login">
        <label for="login">Login</label>
    </div>
    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="pass1" name="pass1" placeholder="password">
        <label for="pass1">Password</label>
    </div>
    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="pass2" name="pass2" placeholder="Confirm Password">
        <label for="pass2">Confirm Password</label>
    </div>
    <div class="mb-3">
        <label for="imagepath" class="form-label">Select image</label>
        <input type="file" class="form-control" id="imagepath" name="imagepath">
    </div>
    <button type="submit" class="btn btn-primary" name="regbtn">Register</button>
</form>
<?php else:
    $path = '';
    if (is_uploaded_file($_FILES['imagepath']['tmp_name'] ?? '')) {
        $path = 'images/' . basename($_FILES['imagepath']['name']);
        move_uploaded_file($_FILES['imagepath']['tmp_name'], $path);
    }

    if (Tools::register($_POST['login'] ?? '', $_POST['pass1'] ?? '', $path)) {
        echo "<h3 style='color:green;'>New User Added!</h3>";
    }
 endif; ?>