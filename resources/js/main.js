import '../css/loading-button.css';
import '../css/loading.css';
import '../css/spinner.css';
import '../css/main.css';

import axios from 'axios';
window.axios = axios;

$('.select2.no-search-box').select2({
    minimumResultsForSearch: Infinity
});

$('.select2').on('select2:open', function (e) {
    document.querySelector('.select2-search__field').focus();
});

$('.select2.validate-on-change').on('change', function (e) {
    // ## penyesuaian perlakuan untuk jquery validation di select2
    if ($(this).valid()) {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").remove();
        $(this).parent().find('.select2-container').removeClass('select2-container--error');
    }
});