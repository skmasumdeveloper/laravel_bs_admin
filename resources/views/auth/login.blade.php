<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Sign In</h4>

                    <div id="alert-placeholder"></div>

                    <form id="login-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" type="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input id="remember" name="remember" type="checkbox" class="form-check-input">
                            <label for="remember" class="form-check-label">Remember me</label>
                        </div>

                        <button id="submit-btn" type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function($){
        $(function(){
                // Allow normal form submit as a fallback (non-AJAX).
                // If JavaScript is enabled, submit via AJAX but ensure CSRF token and cookies are sent.
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                $('#login-form').on('submit', function(e){
                    e.preventDefault();
                    $('#submit-btn').attr('disabled', true).text('Signing in...');
                    $('#alert-placeholder').empty();

                    var $form = $(this);
                    var data = $form.serialize();

                    $.post($form.attr('action'), data)
                        .done(function(){
                            // On success Fortify usually redirects; follow location if provided.
                            window.location = '/';
                        })
                        .fail(function(xhr){
                            var msg = 'Login failed';
                            try {
                                var json = JSON.parse(xhr.responseText);
                                if (json && json.errors) {
                                    var parts = [];
                                    Object.values(json.errors).forEach(function(v){ parts.push(v.join('<br>')); });
                                    msg = parts.join('<br>');
                                } else if (json && json.message) {
                                    msg = json.message;
                                }
                            } catch (e) {
                                msg = xhr.responseText || msg;
                            }

                            $('#alert-placeholder').html('<div class="alert alert-danger">'+msg+'</div>');
                        })
                        .always(function(){
                            $('#submit-btn').attr('disabled', false).text('Login');
                        });
                });
        });
    })(jQuery);
</script>
</body>
</html>
