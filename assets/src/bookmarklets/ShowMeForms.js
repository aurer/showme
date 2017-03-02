(function() {
    var forms = document.querySelectorAll('form');
    for (i = 0; i < forms.length; i++) {
        make(forms[i]);
    }

    function make(f) {
        if (f.getAttribute('data-prev-action') == null) {
            f.setAttribute('data-prev-action', f.getAttribute('action'));
            f.setAttribute('action', 'http://showme.aurer.co.uk');
            f.target = '_blank';
            f.style.outline = '3px solid #1BA5E0';
            f.style.boxShadow = ' 0 0 15px 4px #1BA5E0';
            var e = document.createElement('input');
            e.setAttribute('type', 'hidden');
            e.setAttribute('name', 'Original-form-action');
            e.setAttribute('value', f.getAttribute('data-prev-action'));
            f.appendChild(e);
        } else {
            unmake(f);
        }
    }

    function unmake(f) {
        f.setAttribute('action', f.getAttribute('data-prev-action'));
        f.removeAttribute('data-prev-action');
        f.removeAttribute('target');
        f.style.outline = 'none';
        f.style.boxShadow = 'none';
        document.querySelector('input[name="*action"]').remove();
    }
})();
