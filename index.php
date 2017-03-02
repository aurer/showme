<?php 
require('Requests.php');
$requests = new Requests;
$parameters = $requests->getAll();
?>
<!doctype html>
<html>
<head><title>Show Me</title>
<link rel="stylesheet" href="/assets/dist/css/app.css">
</head>
<body>
	<div class="page">
		<?php if( count($parameters) > 0): ?>
			<table cellpadding="0" cellspacing="0">
				<thead>
					<th>Name</th>
					<th>Value</th>
				</thead>
				<tbody>
					<?php	foreach($parameters as $param): ?>
						<tr>
							<td class="name">
								<?=$param->name?>
								<small><?=$param->type?></small>
							</td>
							<td class="value">
								<?php if (is_array($param->value)) : ?>
									<?php foreach($param->value as $key => $val) : ?>
										<?= $key ?> = <?= $val  ?>
									<?php endforeach ?>
								<?php else : ?>
									<?= $param->value ?>
								<?php endif ?>
							</td>
						</tr>
					<?php	endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>
</body>
</html>
