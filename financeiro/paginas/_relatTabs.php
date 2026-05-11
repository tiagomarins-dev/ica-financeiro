<?php
// Helper compartilhado entre as paginas de relatorio - sub-navegacao
if (!function_exists('renderRelatTabs')) {
	function renderRelatTabs($ativo = '') {
		$tabs = [
			'relatorios'    => ['Busca', 'm21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z'],
			'estatisticas'  => ['Anestesistas', 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
			'relatCirurgia' => ['Cirurgias', 'M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z'],
			'relatClientes' => ['Clientes', 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21'],
			'naoCompensado' => ['Não Compensados', 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z'],
			'naoRecebido'   => ['Não Recebidos', 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'],
		];

		echo '<nav class="flex flex-wrap gap-1 bg-white rounded-xl ring-1 ring-slate-200 p-1">';
		foreach ($tabs as $key => [$label, $iconPath]) {
			$isActive = ($key === $ativo);
			$classes = $isActive
				? 'inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg cursor-pointer bg-brand-50 text-brand-700 ring-1 ring-brand-100'
				: 'inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg cursor-pointer text-slate-600 hover:text-slate-900 hover:bg-slate-100';
			echo '<a href="?s='.$key.'" class="'.$classes.'">'
				.'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="'.$iconPath.'"/></svg>'
				.$label
				.'</a>';
		}
		echo '</nav>';
	}
}
?>
