<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Confirm Password</title>
</head>
<body>
<div style="max-width:700px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:6px;">
    <h3>Confirm Password</h3>
    <p>For your security, please confirm your password to continue.</p>
    <form method="POST" action="<?php echo route('password.confirm'); ?>">
        <?php echo csrf_field(); ?>
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password">
        </div>
        <div style="margin-top:10px"><button type="submit">Confirm</button></div>
    </form>
</div>
</body>
</html>
