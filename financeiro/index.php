<?php
	#Abre Sessão
    session_save_path("/tmp");
	session_name('login');
	session_start();


	#Verifica se o usuario está logado, se não tiver volta para a página de login
	if(!isset($_SESSION['idUser']) || $_SESSION['idUser'] < 1)
	{
		header("Location: login.php");
		exit;
	}


#   Função autoload
    require_once __DIR__ . '/classes/autoload.php';
    $cadastro = new cadastro();
	$relatorio = new relatorios();
	$login = new login();

	$usuario = $_SESSION['idUser'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$sistema = $_SERVER['HTTP_USER_AGENT'];
	$pagina = $_SERVER['REQUEST_URI'];

	$tipoSistema = $cadastro->VerificarDispositivo($sistema);

	if($tipoSistema == 'Mobile') {
		$typeData = 'date';
	}
	else {
		$typeData = 'text';
	}


	// Desativado por motivo de performance - tabela visitas e write-only e nao e consultada em lugar algum.
	// Cada chamada custava ~140ms de roundtrip ao MySQL remoto. Para reativar, descomente a linha abaixo.
	// $cadastro->InsereVisitas($usuario, $ip, $sistema, $pagina);

	$TotalDepPendente = $relatorio->ContaDepositoPendente();

	$paginaAtiva = isset($_GET['s']) ? $_GET['s'] : 'home';

	// Funcao auxiliar de classes do menu (ativo vs normal) - sem alterar logica original
	function navClasses($paginaAtiva, $matches) {
		$paginas = is_array($matches) ? $matches : [$matches];
		$ativo = in_array($paginaAtiva, $paginas, true);
		$base = 'inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 cursor-pointer';
		return $ativo
			? $base . ' bg-brand-50 text-brand-700 ring-1 ring-brand-100'
			: $base . ' text-slate-600 hover:text-slate-900 hover:bg-slate-100';
	}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ICA — Financeiro</title>
<link rel="shortcut icon" type="image/x-icon" href="Imagens/favicon.ico">
<link rel="icon" href="Imagens/favicon.ico" />

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
	tailwind.config = {
		theme: {
			extend: {
				fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
				colors: {
					brand: {
						50:  '#eff6ff',
						100: '#dbeafe',
						200: '#bfdbfe',
						500: '#3b82f6',
						600: '#2563eb',
						700: '#1d4ed8',
						900: '#1e3a8a',
					}
				}
			}
		}
	}
</script>
<style>
	body { font-family: 'Inter', system-ui, sans-serif; }
	/* compatibilidade com componentes legacy (bootstrap-datepicker, jquery-ui) */
</style>

<!-- jQuery + UI necessarios para datepicker, autocomplete, mask -->
<script src="autocomplete/jquery-1.6.2.js"></script>
<script src="autocomplete/jquery.ui.core.js"></script>
<script src="autocomplete/jquery.ui.widget.js"></script>
<script src="autocomplete/jquery.ui.position.js"></script>
<script src="autocomplete/jquery.ui.autocomplete.js"></script>
<script type="text/javascript" src="script/autocomplete_ica.js"></script>

<script src="jquery2/development-bundle/jquery-1.7.1.js"></script>
<script src="jquery2/development-bundle/ui/jquery.ui.core.js"></script>
<script src="jquery2/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="jquery2/development-bundle/ui/jquery.ui.accordion.js"></script>
<script src="jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="jquery/development-bundle/themes/base/jquery.ui.all.css">

<?php if($tipoSistema != 'Mobile') {  ?>
<script>
$(function() {
	$( "#txtData" ).datepicker();
	$( "#txtDataPrevisao" ).datepicker();
	$( "#txtDataDeposito" ).datepicker();
	$( "#txtDataRecebimento" ).datepicker();
	$( "#txtDataSolucao" ).datepicker();
	$( "#txtDataInicio" ).datepicker();
	$( "#txtDataFim" ).datepicker();
});
</script>
<?php } ?>

</head>

<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">

	<!-- Top Navigation -->
	<header class="bg-white border-b border-slate-200 sticky top-0 z-30">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex items-center justify-between h-16">

				<!-- Brand -->
				<a href="?s=home" class="flex items-center gap-2.5 cursor-pointer">
					<div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-brand-600 shadow-sm shadow-brand-600/30">
						<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 14l4-4 4 4 6-6"/>
						</svg>
					</div>
					<div class="hidden sm:block">
						<div class="text-sm font-semibold text-slate-900 leading-tight">ICA Financeiro</div>
						<div class="text-xs text-slate-500 leading-tight">Inst. Carioca Anestesiologia</div>
					</div>
				</a>

				<!-- Navigation -->
				<nav class="hidden lg:flex items-center gap-1">
					<a href="?s=home" class="<?php echo navClasses($paginaAtiva, 'home'); ?>">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
						</svg>
						Home
					</a>

					<a href="?s=relatorios" class="<?php echo navClasses($paginaAtiva, ['relatorios', 'estatisticas']); ?>">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5"/>
						</svg>
						Relatórios
					</a>

					<a href="?s=depositoPendente" class="<?php echo navClasses($paginaAtiva, ['depositoPendente', 'naoRecebido', 'naoCompensado']); ?>">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
						</svg>
						Depósitos Pendentes
						<?php if ($TotalDepPendente > 0): ?>
						<span class="inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 ring-1 ring-amber-200">
							<?php echo $TotalDepPendente; ?>
						</span>
						<?php endif; ?>
					</a>

					<a href="?s=relatDepositos" class="<?php echo navClasses($paginaAtiva, 'relatDepositos'); ?>">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
						</svg>
						Depósitos Feitos
					</a>

					<a href="?s=checkCartao" class="<?php echo navClasses($paginaAtiva, 'checkCartao'); ?>">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
						</svg>
						Cartões
					</a>
				</nav>

				<!-- Right actions -->
				<div class="flex items-center gap-1">
					<!-- Mobile nav toggle -->
					<button type="button" id="mobileMenuToggle" class="lg:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 cursor-pointer">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
						</svg>
					</button>

					<a href="?s=minhaConta" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 cursor-pointer" title="Minha Conta">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
						</svg>
					</a>

					<a href="?s=config" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 cursor-pointer" title="Configurações">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
						</svg>
					</a>

					<div class="w-px h-6 bg-slate-200 mx-1"></div>

					<a href="logoff.php" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg cursor-pointer transition-colors duration-150" title="Sair">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
						</svg>
						<span class="hidden sm:inline">Sair</span>
					</a>
				</div>
			</div>

			<!-- Mobile menu -->
			<nav id="mobileMenu" class="lg:hidden hidden pb-4 pt-2 space-y-1 border-t border-slate-100">
				<a href="?s=home" class="<?php echo navClasses($paginaAtiva, 'home'); ?> w-full">Home</a>
				<a href="?s=relatorios" class="<?php echo navClasses($paginaAtiva, ['relatorios', 'estatisticas']); ?> w-full">Relatórios</a>
				<a href="?s=depositoPendente" class="<?php echo navClasses($paginaAtiva, ['depositoPendente', 'naoRecebido', 'naoCompensado']); ?> w-full">Depósitos Pendentes <?php if ($TotalDepPendente > 0): ?><span class="ml-1 inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800"><?php echo $TotalDepPendente; ?></span><?php endif; ?></a>
				<a href="?s=relatDepositos" class="<?php echo navClasses($paginaAtiva, 'relatDepositos'); ?> w-full">Depósitos Feitos</a>
				<a href="?s=checkCartao" class="<?php echo navClasses($paginaAtiva, 'checkCartao'); ?> w-full">Cartões</a>
				<a href="?s=minhaConta" class="<?php echo navClasses($paginaAtiva, 'minhaConta'); ?> w-full">Minha Conta</a>
				<a href="?s=config" class="<?php echo navClasses($paginaAtiva, ['config', 'usuarios', 'despesas', 'descontos', 'cliente']); ?> w-full">Configurações</a>
			</nav>
		</div>
	</header>

	<script>
		document.getElementById('mobileMenuToggle')?.addEventListener('click', function(){
			document.getElementById('mobileMenu').classList.toggle('hidden');
		});
	</script>

	<!-- Main content -->
	<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

<?php

	if(isset($_GET['s']))
	{
		$sessao = $_GET['s'];

		if(file_exists('paginas/' . $sessao .'.php'))
		{
			include_once('paginas/' . $sessao . '.php');
		}
		elseif(file_exists('paginas/' . $sessao .'.php'))
		{
			include_once('paginas/' . $sessao . '.php');
		}
		else
		{
			include_once("paginas/home.php");
		}

	}
	else
	{
		include_once("paginas/home.php");
	}

?>

	</main>

	<?php include_once("modal/inserirAnestesia.php"); ?>
	<?php include_once("modal/inserirDespesa.php"); ?>
	<?php include_once("modal/importaDespesa.php"); ?>


	<!-- Scripts legados necessarios (maskedinput + validacao) -->
	<script type="text/javascript" src="estilos/js/jquery.maskedinput.js"></script>

	<script type="text/javascript">
		$(function() {
			$('.date').mask('00/00/0000');
			$('.time').mask('00:00:00');
			$('.date_time').mask('00/00/0000 00:00:00');
			$('.cep').mask('00000-000');
			$('.phone').mask('0000-0000');
			$('.phone_with_ddd').mask('(00) 0000-0000');
			$('.phone_us').mask('(000) 000-0000');
			$('.mixed').mask('AAA 000-S0S');
			$('.ip_address').mask('099.099.099.099');
			$('.percent').mask('##0,00%', {reverse: true});
			$('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
			$('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
			$('.fallback').mask("00r00r0000", {
				translation: {
					'r': { pattern: /[\/]/, fallback: '/' },
					placeholder: "__/__/____"
				}
			});
			$('.cpf').mask('000.000.000-00', {reverse: true});
			$('.money').mask('#.##0,00', {reverse: true});
			$(".sp_celphones").mask('(00) 00009-0000');
		});
	</script>

	<script src="script/pagamento.js?atualiza=<?php echo date('s'); ?>"></script>

	<script type="text/javascript">
		function Formatadata(Campo, teclapres) {
			var tecla = teclapres.keyCode;
			var vr = new String(Campo.value);
			vr = vr.replace("/", "");
			vr = vr.replace("/", "");
			vr = vr.replace("/", "");
			tam = vr.length + 1;
			if (tecla != 8) {
				if (tam > 0 && tam < 2)
					Campo.value = vr.substr(0, 2);
				if (tam > 2 && tam < 4)
					Campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2);
				if (tam > 4 && tam < 7)
					Campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2) + '/' + vr.substr(4, 7);
			}
		}

		function ConfirmaDelete() {
			return confirm("Tem certeza que deseja apagar esse registro?");
		}

		function validaFormServico() {
			var dataServico = document.getElementById("txtData");
			var cliente = document.getElementById("txtCliente");
			var atendimento1 = document.getElementById("txtAtendimento1");
			var atendimento2 = document.getElementById("txtAtendimento2");
			var paciente = document.getElementById("txtPaciente");
			var cirurgia = document.getElementById("txtCirurgia");
			var valor = document.getElementById("txtValorBruto");
			var pagamento = document.getElementById("txtPagamento");

			if (dataServico.value.length < 10) { alert("Informe a Data do Serviço."); dataServico.focus(); return false; }
			if (cliente.value.length < 1) { alert("Informe o cliente."); cliente.focus(); return false; }
			if ((atendimento1.value.length < 1) && (atendimento2.value.length < 1)) { alert("Informe pelo menos um anestesista."); atendimento1.focus(); return false; }
			if (paciente.value.length <= 3) { alert("Informe nome do paciente."); paciente.focus(); return false; }
			if (cirurgia.value.length < 1) { alert("Informe a cirurgia."); cirurgia.focus(); return false; }
			if (valor.value.length < 1) { alert("Informe o valor do serviço."); valor.focus(); return false; }
			if (pagamento.value.length < 1) { alert("Informe o tipo de pagamento."); pagamento.focus(); return false; }
		}
	</script>

</body>
</html>
