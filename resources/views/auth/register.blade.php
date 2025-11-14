<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register</title>
</head>
<body>
<div style="max-width:700px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:6px;">
    <h3>Create an Account</h3>
    <form method="POST" action="<?php echo route('register'); ?>">
        <?php echo csrf_field(); ?>
        <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>
        <div style="margin-top:10px"><button type="submit">Register</button></div>
    </form>
</div>
</body>
</html>
