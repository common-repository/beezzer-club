// JavaScript Document

/* Geral */
function displayBeezzerForm() {
	var display = document.getElementById('beezzer_form').style.display;
	if (display == 'block')
		document.getElementById('beezzer_form').style.display = 'none';
	else
		document.getElementById('beezzer_form').style.display = 'block';
}

/* beezzer_club_template_clube.php */
function focusInputEmail(focus) {
	if (focus) {
		if (document.getElementById('ParticiparUsuarioEmail').value == 'Digite seu e-mail')
			document.getElementById('ParticiparUsuarioEmail').value = '';
	}
	else {
		if (document.getElementById('ParticiparUsuarioEmail').value == '')
			document.getElementById('ParticiparUsuarioEmail').value = 'Digite seu e-mail';
	}
}

function clickJoinClub(join) {
	if (join) {
		document.getElementById('join-club-form').style.display = 'block';
		document.getElementById('join-club-link').style.display = 'none';
	}
	else {			
		document.getElementById('join-club-form').style.display = 'none';
		document.getElementById('join-club-link').style.display = 'block';
	}
	return false;
}