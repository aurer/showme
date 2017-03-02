<?php
require('Requests.php');
$requests = new Requests;
$parameters = $requests->getAll();
?>
<!doctype html>
<html>
<head><title>Show me</title>
<link href="https://fonts.googleapis.com/css?family=Ubuntu|Inconsolata" rel="stylesheet">
<link rel="stylesheet" href="/assets/dist/css/app.css">
</head>
<body>
	<?php if( count($parameters) > 0): ?>
		<section class="main">
			<header>
				<h1>Showme</h1>
				<?php if (isset($_SERVER['HTTP_REFERER'])): ?>
					<div class="referer">
						<b>Referer:</b> <?= $_SERVER['HTTP_REFERER'] ?>
					</div>
				<?php endif ?>
			</header>
			<main>
				<table cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td colspan="2">

							</td>
						</tr>
					</thead>
					<tbody>
						<?php	foreach($parameters as $param): ?>
							<tr>
								<th class="name">
									<?=$param->name?>
									<small><?=$param->type?></small>
								</th>
								<td class="value type-<?= is_array($param->value) ? 'multi' : 'single' ?>">
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
			</main>
			<footer>
				<a href="https://github.com/aurer" target="_blank">&copy;aurer <?= date("Y") ?></a>
			</footer>
		</section>
	<?php else: ?>
		<section class="intro">
			<h1>Showme</h1>
			<p>Send a POST or <a href="?Something=Like+this...">GET</a> request here to inspect it's values.</p>
			<p><a href="https://github.com/aurer/showme/assets/dist/bookmarklets/" target="_blank">These bookmarklets</a> might help.</p>
		</section>
	<?php endif ?>
</body>
</html>
