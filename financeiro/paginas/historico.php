<?php
	$pagAtual = (isset($_GET['p'])) ? $_GET['p'] : 1;
?>

<div class="space-y-6">

	<header>
		<h1 class="text-xl font-semibold text-slate-900">Histórico Completo</h1>
		<p class="mt-1 text-sm text-slate-500">Faturamento mensal histórico</p>
	</header>

	<section class="bg-white rounded-xl ring-1 ring-slate-200 overflow-hidden">
		<?php $relatorio->RetornaValoresCompletos($pagAtual); ?>
	</section>

	<nav class="flex justify-center">
		<ul class="inline-flex items-center gap-1">
			<?php $relatorio->PaginacaoValores($pagAtual); ?>
		</ul>
	</nav>

</div>
