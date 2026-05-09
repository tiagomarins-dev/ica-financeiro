<?php
	#Abre Sessão
	session_name('login');
	session_start();

#   Função autoload
    require_once __DIR__ . '/classes/autoload.php';
    $relatorio = new relatorios();
	
	// Blinda acessos a $_GET para evitar warnings em PHP 8 quando os parâmetros não vêm na URL
	$cliente     = $_GET['c'] ?? '';
	$dataInicio  = $_GET['i'] ?? '';
	$dataFim     = $_GET['f'] ?? '';
	$totalValor  = $_GET['t'] ?? '';
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ICA ..:::.. Financeiro</title>
<link rel="shortcut icon" type="image/x-icon" href="Imagens/favicon.ico">
<link rel="icon" href="Imagens/favicon.ico" /> 
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="estilos/css/bootstrap.min.css">

<link rel="stylesheet" href="estilos/css/dashboard.css">
<!-- Optional theme -->
<link rel="stylesheet" href="estilos/css/bootstrap-theme.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="estilos/js/bootstrap.min.js"></script>
<!-- Estilo Principal do Site -->
<link rel="stylesheet" href="estilos/estilo.css">


<link rel="stylesheet" href="jquery/development-bundle/themes/base/jquery.ui.all.css">

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

<!-- Estilo Principal do Site -->
<link rel="stylesheet" href="estilos/estilo.css">
</head>

<body>
    <h2 style="margin-bottom:40px;"><?php echo $cliente; ?></h2>
    
    
    
    <?php $relatorio->RetornaCirurgiaPorCliente($dataInicio, $dataFim, $cliente, $totalValor) ?>
    
    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
	
	<!-- <script src="estilos/js/jquery.min.js"></script> -->
	
	<!-- <script src="http://yandex.st/highlightjs/7.3/highlight.min.js"></script> -->
	<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script> -->
	
		
	<script src="estilos/js/bootstrap.min.js"></script>
	
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
			'r': {
			  pattern: /[\/]/, 
			  fallback: '/'
			}, 
			placeholder: "__/__/____"
		  }
		});

		$('.cep_with_callback').mask('00000-000', {onComplete: function(cep) {
			console.log('Mask is done!:', cep);
		  },
		   onKeyPress: function(cep, event, currentField, options){
			console.log('An key was pressed!:', cep, ' event: ', event, 'currentField: ', currentField.attr('class'), ' options: ', options);
		  },
		  onInvalid: function(val, e, field, invalid, options){
			var error = invalid[0];
			console.log ("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
		  }
		});

		$('.crazy_cep').mask('00000-000', {onKeyPress: function(cep){
		  var masks = ['00000-000', '0-00-00-00'];
			mask = (cep.length>7) ? masks[1] : masks[0];
		  $('.crazy_cep').mask(mask, this);
		}});

		$('.cpf').mask('000.000.000-00', {reverse: true});
		$('.money').mask('#.##0,00', {reverse: true});

		var SaoPauloCelphoneMask = function(phone, e, currentField, options){
		  return phone.match(/^(\(?11\)? ?9(5[0-9]|6[0-9]|7[01234569]|8[0-9]|9[0-9])[0-9]{1})/g) ? '(00) 00000-0000' : '(00) 0000-0000';
		};

		$(".sp_celphones").mask('(00) 00009-0000');

		$(".bt-mask-it").click(function(){
		  $(".mask-on-div").mask("000.000.000-00");
		  $(".mask-on-div").fadeOut(500).fadeIn(500)
		})

		$('pre').each(function(i, e) {hljs.highlightBlock(e)});
	  });
	</script>
    
    
    <script src="estilos/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="estilos/js/ie10-viewport-bug-workaround.js"></script>

<link rel="stylesheet" href="estilos/js/validacao.js">

<script type="text/javascript">
			function Formatadata(Campo, teclapres)
			{
				var tecla = teclapres.keyCode;
				var vr = new String(Campo.value);
				vr = vr.replace("/", "");
				vr = vr.replace("/", "");
				vr = vr.replace("/", "");
				tam = vr.length + 1;
				if (tecla != 8 && tecla != 8)
				{
					if (tam > 0 && tam < 2)
						Campo.value = vr.substr(0, 2) ;
					if (tam > 2 && tam < 4)
						Campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2);
					if (tam > 4 && tam < 7)
						Campo.value = vr.substr(0, 2) + '/' + vr.substr(2, 2) + '/' + vr.substr(4, 7);
				}
			}
		</script>
	
<script type="text/javascript">
	function ConfirmaDelete(){		
		if (confirm("Tem certeza que deseja apagar esse registro?")){ 
			return true;
		} 
		else{ 
			return false; 
		}		
	}
	
	function validaFormServico(){
		//Variáveis
		var dataServico = document.getElementById("txtData");
		var cliente = document.getElementById("txtCliente");
		var atendimento1 = document.getElementById("txtAtendimento1");
		var atendimento2 = document.getElementById("txtAtendimento2");
		var paciente = document.getElementById("txtPaciente");
		var cirurgia = document.getElementById("txtCirurgia");
		var valor = document.getElementById("txtValorBruto");
		var pagamento = document.getElementById("txtPagamento");
		
		if(dataServico.value.length < 10){
			alert("Informe a Data do Serviço.");
			dataServico.focus();
			dataServico.style.backgroundColor = '#F2DDDC';
			return false;
		}
		
		if(cliente.value.length < 1){
			alert("Informe o cliente.");
			cliente.focus();
			cliente.style.backgroundColor = '#F2DDDC';
			return false;
		}
		
		if((atendimento1.value.length < 1) && (atendimento2.value.length < 1)){
			alert("Informe pelo menos um anestesista.");
			atendimento1.focus();
			atendimento1.style.backgroundColor = '#F2DDDC';
			return false;
		}
		
		if(paciente.value.length <= 3){
			alert("Informe nome do paciente.");
			paciente.focus();
			paciente.style.backgroundColor = '#F2DDDC';
			return false;
		}
		
		if(cirurgia.value.length < 1){
			alert("Informe a cirurgia.");
			cirurgia.focus();
			cirurgia.style.backgroundColor = '#F2DDDC';
			return false;
		}
		
		if(valor.value.length < 1){
			alert("Informe o valor do serviço.");
			valor.focus();
			valor.style.backgroundColor = '#F2DDDC';
			return false;
		}
		
		if(pagamento.value.length < 1){
			alert("Informe o tipo de pagamento.");
			pagamento.focus();
			pagamento.style.backgroundColor = '#F2DDDC';
			return false;
		}
	}
    </script>
    
  </body>
</body>
</html>
