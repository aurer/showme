var forms = document.querySelectorAll('form');
for (i = 0; i < forms.length; i++) {
	make(forms[i]);
};

function make(f) {
	if (f.getAttribute('data-prev-action') == null) {
		var querystring = f.getAttribute('action').split('?')[1];
		f.setAttribute('data-prev-action', f.action);
		f.action='https://showme.aurer.co.uk' + (querystring ? '?' + querystring : '');
		f.target = '_blank';
		f.style.outline = '1px solid #75d5ff';
		f.style.boxShadow = ' 0 0 15px 4px #1BA5E0';
		var e = document.createElement('input');
		e.type = 'hidden';
		e.name = 'formSubmitsTo';
		e.value = f.getAttribute('data-prev-action');
		f.appendChild(e);
	} else {
		unmake(f);
	}
};

function unmake(f) {
	f.action = f.getAttribute('data-prev-action');
	f.removeAttribute('data-prev-action');
	f.removeAttribute('target');
	f.style.outline = 'none';
	f.style.boxShadow = 'none';
	f.querySelector('input[name="formSubmitsTo"]').remove;
};
