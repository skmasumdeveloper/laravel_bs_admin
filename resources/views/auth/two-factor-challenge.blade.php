<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Two Factor Challenge</title>
</head>
<body>
<div style="max-width:700px;margin:40px auto;padding:20px;border:1px solid #eee;border-radius:6px;">
    <h3>Two Factor Authentication</h3>
    <p>Enter your two-factor authentication code.</p>
    <form method="POST" action="<?php echo route('two-factor.login'); ?>">
        <?php echo csrf_field(); ?>
        <div>
            <label for="code">Code</label>
            <input id="code" name="code" type="text">
        </div>
        <div style="margin-top:10px"><button type="submit">Verify</button></div>
    </form>
</div>
</body>
</html>
