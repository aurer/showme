<!doctype html>
<html>
<head><title>ShowMe</title>
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
<style type="text/css">
body{
	background:#2A3038;
	color:#fff;
	padding:20px;
	font-family: Helvetica, Arial, sans-serif;
}
a{
	color:#00ff00;
	text-decoration:none;
}
h1{
	font-size:3em;
	font-family: 'Reenie Beanie';
	margin: 10px 0 10px -0.5em;
	color: #FF6347;
}
pre{
	font-size:.95em;
	font-family: inherit;
}
.page{
	width:80%;
	margin:0 auto;
}
table{
	width:100%;
	font-size:.8em;
	border-collapse:collapse;
	margin:0 0 30px;
	box-shadow: 0 1px 20px rgba(0, 0, 0, 0.2);
}
table td{
	padding:.5em .8em;
	border:1px solid #12161B;
	font-family: Helvetica;
}
table tr:nth-child(odd) td{
	background: rgba(255,255,255,0.03);
}
.name{
	width:200px;
	font-weight: bold;
}
</style>
</head>
<body>
	<div class="page">
		<h1>ShowMe</h1>
		<?php include 'functions.php' ?>
		<?php $input = array_merge($_GET, $_POST) ?>
		<?php ksort($input) ?>
		<?php if( count($input) > 0): ?>
			<table cellpadding="0" cellspacing="0">
				<?php	foreach($input as $name => $value): ?>
					<tr>
						<td class="name"><?=display($name)?></td>
						<td class="value"><?=nl2br(display($value))?></td>
					</tr>
				<?php	endforeach ?>
			</table>
		<?php endif ?>
	</div>
</body>
</html>
