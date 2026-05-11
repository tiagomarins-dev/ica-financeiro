<?php
	#Abre Sessão
    session_save_path("/tmp");
	session_name('login');
	session_start();

#   Função autoload
    require_once __DIR__ . '/classes/autoload.php';
    $cadastro = new cadastro();
	$relatorio = new relatorios();
	$login = new login();

	$erroLogin = false;
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
	    if($login->LogarSistema() === true)
	    {
	        header("Location: index.php");
	        exit;
	    }
	    else
	    {
	        $erroLogin = true;
	    }
	}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ICA — Login</title>
<link rel="shortcut icon" type="image/x-icon" href="Imagens/favicon.ico">
<link rel="icon" href="Imagens/favicon.ico" />
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
</style>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 flex items-center justify-center p-4 antialiased">

	<div class="w-full max-w-md">

		<!-- Header -->
		<div class="text-center mb-8">
			<div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-brand-600 shadow-lg shadow-brand-600/20 mb-4">
				<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 14l4-4 4 4 6-6"/>
				</svg>
			</div>
			<h1 class="text-2xl font-semibold text-slate-900">Controle Financeiro</h1>
			<p class="text-sm text-slate-500 mt-1">Instituto Carioca de Anestesiologia</p>
		</div>

		<!-- Card -->
		<div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 ring-1 ring-slate-200 p-8">

			<?php if ($erroLogin): ?>
			<div class="mb-6 flex items-start gap-3 rounded-lg bg-red-50 border border-red-200 px-4 py-3" role="alert">
				<svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
				</svg>
				<div class="text-sm text-red-800">
					<p class="font-medium">Não foi possível entrar</p>
					<p class="text-red-700">Verifique usuário e senha e tente novamente.</p>
				</div>
			</div>
			<?php endif; ?>

			<form action="login.php" method="post" class="space-y-5">

				<div>
					<label for="login" class="block text-sm font-medium text-slate-700 mb-1.5">Usuário</label>
					<div class="relative">
						<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
							<svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
							</svg>
						</div>
						<input type="text" id="login" name="login" required autofocus
							class="block w-full pl-10 pr-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
							placeholder="seu.usuario" />
					</div>
				</div>

				<div>
					<label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Senha</label>
					<div class="relative">
						<div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
							<svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
							</svg>
						</div>
						<input type="password" id="password" name="password" required
							class="block w-full pl-10 pr-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
							placeholder="••••••••" />
					</div>
				</div>

				<button type="submit"
					class="w-full cursor-pointer inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 rounded-lg shadow-sm transition-colors duration-200">
					Entrar
					<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
					</svg>
				</button>

			</form>
		</div>

		<!-- Footer -->
		<p class="text-center text-xs text-slate-400 mt-6">
			© <?php echo date('Y'); ?> ICA · Sistema Financeiro
		</p>

	</div>

</body>
</html>
