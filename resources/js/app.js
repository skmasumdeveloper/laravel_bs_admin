import * as bootstrap from 'bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;
window.bootstrap = bootstrap;

// CSRF token setup for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
