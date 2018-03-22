<!doctype html>
<html>
<head><title>Show me</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Ubuntu|Inconsolata" rel="stylesheet">
<link rel="stylesheet" href="/assets/dist/css/app.css">
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-31871536-6"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-31871536-6');
</script>
</head>
<body>
	<?php if (count($parameters) > 0): ?>
		<section class="main">
			<header>
				<h1>Showme</h1>
				<?php if ($requests->getReferer()): ?>
					<div class="referer">
						<b>Referer:</b> <span><?= $requests->getReferer() ?></span>
					</div>
				<?php endif ?>
			</header>
			<main>
				<table cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Name</th>
							<th>Value</th>
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
										<?= ''/*$param->value*/ ?>
									<?php endif ?>
								</td>
							</tr>
						<?php	endforeach ?>
					</tbody>
					<?php if (!empty($action)) : ?>
						<tfoot>
							<tr>
								<td>Form action</td>
								<td><a target="_blank" href="<?= $action ?>"><?= $action ?></a></td>
							</tr>
						</tfoot>
					<?php endif ?>
				</table>
			</main>
			<footer>
				<?php if (!$requests->isCache()): ?>
					<a href="/<?= $requests->save() ?>">Share</a>
					&nbsp;&bullet;&nbsp;
				<?php endif ?>
				<a href="https://aurer.co.uk" target="_blank">&copy;aurer <?= date("Y") ?></a>
				&nbsp;&bullet;&nbsp;
				<a href="https://github.com/aurer/showme" target="_blank">Github</a>
			</footer>
		</section>
	<?php else: ?>
		<section class="intro">
			<h1>Showme</h1>
			<p>Send a POST or <a href="?Something=Like+this...">GET</a> request here to inspect it's values.</p>
			<p><a href="https://github.com/aurer/showme/tree/master/assets/dist/bookmarklets" target="_blank">These bookmarklets</a> might help.</p>
		</section>
	<?php endif ?>
</body>
</html>
