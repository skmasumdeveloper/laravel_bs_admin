<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Forgot Password</title>
</head>
<body>
<div style="max-width:700px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:6px;">
    <h3>Forgot Password</h3>
    <p>Enter your email to receive a password reset link.</p>
    <form method="POST" action="<?php echo route('password.email'); ?>">
        <?php echo csrf_field(); ?>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required>
        </div>
        <div style="margin-top:10px"><button type="submit">Send Reset Link</button></div>
    </form>
</div>
</body>
</html>
