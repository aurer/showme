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
				<tbody>
					<?php	foreach($parameters as $param): ?>
						<tr>
							<th class="name">
								<?=$param->name?>
								<small><?=$param->type?></small>
							</th>
							<td class="value type-<?=$param->type?>">
								<?php if (is_array($param->value)) : ?>
									<table>
										<?php foreach($param->value as $key => $val) : ?>
											<tr>
												<th><?= $key ?></th>
												<td><?= $val  ?></td>
											</tr>
										<?php endforeach ?>
									</table>
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
