<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reset Password</title>
</head>
<body>
<div style="max-width:700px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:6px;">
    <h3>Reset Password</h3>
    <p>Enter your new password.</p>
    <form method="POST" action="<?php echo route('password.update'); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="token" value="<?php echo request()->route('token'); ?>">
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>
        <div style="margin-top:10px"><button type="submit">Reset Password</button></div>
    </form>
</div>
</body>
</html>
