var forms = document.querySelectorAll('form');
for (i = 0; i < forms.length; i++) {
	make(forms[i]);
};

function make(f) {
	if (f.getAttribute('data-prev-action') == null) {
		var param = f.action.split('?')[1];
		console.log(param);
		f.setAttribute('data-prev-action', f.action);
		f.action='http://localhost:4000' + (param ? '?' + param : '');
		f.target = '_blank';
		f.style.border = '2px solid #1BA5E0';
		f.style.boxShadow = ' 0 0 15px 4px #1BA5E0';
		var e = document.createElement('input');
		e.type = 'hidden';
		e.name = 'form-action';
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
	document.querySelector('input[name="*action"]').remove();
};
