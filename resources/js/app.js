import 'bootstrap';
import $ from 'jquery';
window.$ = window.jQuery = $;

// CSRF token setup for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
