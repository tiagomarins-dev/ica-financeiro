<?php
class Relatorios
{
    private $host;
    private $user;
    private $pass;
    private $banco;
	
	private $mesAno;
	private $totalBruto;
	private $totalDescontos;
	private $totalDescntoCartao;
	private $totalComNota;
	private $totalSemNota;
	private $totalLiquido;
	private $totalLiquidoImposto;
	private $totalDespesas;
	private $totalSaldo;
	
	private $dataInicio;
	private $dataFim;
	private $cliente;
	private $paciente;
	
	private $anestesista;
	private $porcentagem;
	private $totalValorLiquido;
	private $totalValorFinal;

	public $totalRegPag = 20;

    public function __construct()
    {
        $conn = new conexao();
        $infoConn = $conn->getInfoConn();

        $this->host = $infoConn['host'];
        $this->user = $infoConn['user'];
        $this->pass = $infoConn['pass'];
        $this->banco = $infoConn['database'];
    }
	
	private function PegarPost()
	{
		$cadastro = new cadastro();
		$this->dataInicio = (isset($_POST['txtDataInicio'])) ? $cadastro->converteData($_POST['txtDataInicio']) : '';
		$this->dataFim = (isset($_POST['txtDataFim'])) ? $cadastro->converteData($_POST['txtDataFim']) : '';
		$this->cliente = (isset($_POST['txtCliente'])) ? addslashes($_POST['txtCliente']) : '';
		$this->paciente = (isset($_POST['txtPaciente'])) ? addslashes($_POST['txtPaciente']) : '';
	}
	
	private function CalculaSaldo($bruto,$descontoCartao,$despesas)
	{		
		$total = $bruto - ($descontoCartao + $despesas);
		return $total;
	}
	
	private function CalculaSaldo2($somaNota, $somaSemNota, $somaCartao, $descontos, $despesas)
	{
		
		//echo '<p>' . $liquido . ' - ' . $despesas . '</p>';
		
		$total = ($somaNota + $somaSemNota) - ($somaCartao + $descontos + $despesas);
		return $total;
	}
	
	public function RetornaResumo()
	{
		$mysqli = conexao::pegar();

		// Query direta otimizada - substitui call retorna_soma_valores() (~4900ms) por agregacao em 1 passo (~200ms)
		// Versao SEM as 3 subqueries correlacionadas com DATE_FORMAT() que travavam a procedure original
		$sql = "
			SELECT s_data.ano, s_data.mes,
			       s_data.total_bruto, s_data.total_descontos, s_data.total_desconto_cartao,
			       s_data.total_com_nota, s_data.total_sem_nota, s_data.total_liquido,
			       COALESCE(d.total_despesas, 0) AS total_despesas
			FROM (
			    SELECT YEAR(s.data_servico) AS ano, MONTH(s.data_servico) AS mes,
			           COALESCE(SUM(p.valor_bruto),0) AS total_bruto,
			           COALESCE(SUM(s.valor_desconto),0) AS total_descontos,
			           COALESCE(SUM(p.valor_bruto),0) - COALESCE(SUM(p.valor_final),0) - COALESCE(SUM(s.valor_desconto),0) AS total_desconto_cartao,
			           COALESCE(SUM(CASE WHEN p.nota='S' THEN p.valor_bruto ELSE 0 END),0) AS total_com_nota,
			           COALESCE(SUM(CASE WHEN p.nota='N' THEN p.valor_bruto ELSE 0 END),0) AS total_sem_nota,
			           COALESCE(SUM(p.valor_final),0) AS total_liquido
			    FROM servicos s LEFT JOIN pagamentos p ON p.id_servico = s.id
			    GROUP BY YEAR(s.data_servico), MONTH(s.data_servico)
			) s_data
			LEFT JOIN (
			    SELECT YEAR(data_despesa) AS ano, MONTH(data_despesa) AS mes, SUM(valor_despesa) AS total_despesas
			    FROM despesas_mensais
			    GROUP BY YEAR(data_despesa), MONTH(data_despesa)
			) d ON d.ano = s_data.ano AND d.mes = s_data.mes
			ORDER BY s_data.ano DESC, s_data.mes DESC LIMIT 13";
		$result = $mysqli->query($sql);
		if($result && $result->num_rows > 0)
		{
			echo '
				<div class="overflow-x-auto">
				<table class="w-full text-sm">
				<thead class="bg-slate-50 border-b border-slate-200">
				<tr class="text-left text-xs font-medium text-slate-600 uppercase tracking-wide">
					<th class="px-4 py-3">Mês / Ano</th>
					<th class="px-4 py-3 text-right">Notas Emitidas</th>
					<th class="px-4 py-3 text-right">Gratuidade</th>
					<th class="px-4 py-3 text-right">Faturamento</th>
					<th class="px-4 py-3 text-right">Desc. Cartão</th>
					<th class="px-4 py-3 text-right">Despesas</th>
					<th class="px-4 py-3 text-right">Líquido</th>
				</tr>
				</thead>
				<tbody class="divide-y divide-slate-100">
			';

			while($rows = $result->fetch_assoc())
			{
				$cadastro = new cadastro();
				$this->mesAno = $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']);
				$this->totalBruto = $cadastro->converteValorSite($rows['total_bruto']);
				$this->totalDescontos = $cadastro->converteValorSite($rows['total_descontos']);
				$this->totalDescntoCartao = $cadastro->converteValorSite($rows['total_desconto_cartao']);
				$this->totalComNota = $cadastro->converteValorSite($rows['total_com_nota']);
				$this->totalSemNota = $cadastro->converteValorSite($rows['total_sem_nota']);
				$this->totalLiquido = $cadastro->converteValorSite($rows['total_liquido']);
				$this->totalDespesas = $cadastro->converteValorSite($rows['total_despesas']);
				$this->totalSaldo = $this->CalculaSaldo($rows['total_bruto'],$rows['total_desconto_cartao'],$rows['total_despesas']);
				$this->totalSaldo = $cadastro->converteValorSiteSaldo($this->totalSaldo);

				echo '
					<tr class="hover:bg-slate-50 transition-colors">
						<td class="px-4 py-3 font-medium text-slate-900">'.$this->mesAno.'</td>
						<td class="px-4 py-3 text-right tabular-nums text-slate-700">'.$this->totalComNota.'</td>
						<td class="px-4 py-3 text-right tabular-nums text-slate-700">'.$this->totalSemNota.'</td>
						<td class="px-4 py-3 text-right tabular-nums font-semibold text-slate-900">'.$this->totalBruto.'</td>
						<td class="px-4 py-3 text-right tabular-nums text-slate-600">'.$this->totalDescntoCartao.'</td>
						<td class="px-4 py-3 text-right tabular-nums text-slate-600">'.$this->totalDespesas.'</td>
						<td class="px-4 py-3 text-right tabular-nums font-semibold text-emerald-700">'.$this->totalSaldo.'</td>
					</tr>
				';
			}

			echo '
				</tbody>
				</table>
				</div>
			';
		}
		else
		{
			echo '<div class="px-5 py-10 text-center text-sm text-slate-500">Sem registros</div>';
		}
	}

	public function RetornaValoresCompletos($inicio)
	{
		$mysqli = conexao::pegar();


		if($inicio == 1) {

			$inicio = 0;
		}
		else {

			$inicio = ($inicio - 1) * $this->totalRegPag;
		}



		
		$sql = "call retorna_soma_valores_completos($inicio,$this->totalRegPag)";		
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
						<table class="w-full text-sm">
							<thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-4 py-3 text-left">Mês/Ano</th>
									<th class="px-4 py-3 text-right">Notas Emitidas</th>
									<th class="px-4 py-3 text-right">Gratuidade</th>
									<th class="px-4 py-3 text-right">Faturamento</th>
									<th class="px-4 py-3 text-right">Desc. Cartão</th>
									<th class="px-4 py-3 text-right">Despesas</th>
									<th class="px-4 py-3 text-right">Líquido</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100 text-slate-700">
				';

				while($rows = $result->fetch_assoc())
				{
					$cadastro = new cadastro();
					$this->mesAno = $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']);
					$this->totalBruto = $cadastro->converteValorSite($rows['SomaValorBruto']);
					$this->totalDescontos = $cadastro->converteValorSite($rows['SomaValorDescontos']);
					$this->totalDescntoCartao = $cadastro->converteValorSite($rows['SomaDescontoCartao']);
					$this->totalComNota = $cadastro->converteValorSite($rows['SomaValorNota']);
					$this->totalSemNota = $cadastro->converteValorSite($rows['SomaValorSemNota']);
					$this->totalLiquido = $cadastro->converteValorSite($rows['SomaValorFinal']);
					$this->totalDespesas = $cadastro->converteValorSite($rows['SomaDespesasMes']);
					$this->totalSaldo = $this->CalculaSaldo($rows['SomaValorBruto'],$rows['SomaDescontoCartao'],$rows['SomaDespesasMes']);
					$this->totalSaldo = $cadastro->converteValorSiteSaldo($this->totalSaldo);

					echo '<tr class="hover:bg-slate-50">
								<td class="px-4 py-2.5 font-medium text-slate-900">'.$this->mesAno.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums">'.$this->totalComNota.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums text-slate-600">'.$this->totalSemNota.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums font-semibold text-slate-900">'.$this->totalBruto.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums text-slate-600">'.$this->totalDescntoCartao.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums text-slate-600">'.$this->totalDespesas.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums font-semibold text-brand-700">'.$this->totalSaldo.'</td>
							</tr>';
				}

				echo '
							</tbody>
						</table>
					</div>
				';
			}
			else
			{
				echo '<p class="px-4 py-6 text-sm text-slate-500">Sem registros.</p>';
			}
		}		
	}
	
	public function RetornaSomaEquipeChart($mes,$ano)
	{
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_equipe_periodo('$mes','$ano')";		
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo "
							<script type=\"text/javascript\">

							// Load the Visualization API and the piechart package.
							google.load('visualization', '1.0', {'packages':['corechart']});

							// Set a callback to run when the Google Visualization API is loaded.
							google.setOnLoadCallback(drawChart);

							// Callback that creates and populates a data table,
							// instantiates the pie chart, passes in the data and
							// draws it.
							function drawChart() {

							// Create the data table.
							var data = new google.visualization.DataTable();
							data.addColumn('string', 'Topping');
							data.addColumn('number', 'Slices');
							data.addRows([			
				";
				
				while($rows = $result->fetch_assoc())
				{
					echo "
							['".$rows['usuario']."', ".$rows['totalValorFinal']."],
					";
				}
				
				echo "
							]);
							// Set chart options
							var options = {'title':'Por Anestesista',
										   /*'width':600,
										   'height':600,*/
										   chartArea: {left:20,top:25,width:'70%',height:'70%'},
										   fontSize: '11'
										   
										   
										   };
								

							// Instantiate and draw our chart, passing in some options.
							var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
							chart.draw(data, options);
							  }
							</script>			
				";
			}
			
		}
		
	}
	
	public function RetornaSomaCirurgiaChart($dataInicio,$dataFim)
	{
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_cirurgia_periodo('$dataInicio','$dataFim','');";		
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo "
							<script type=\"text/javascript\">

							// Load the Visualization API and the piechart package.
							google.load('visualization', '1.0', {'packages':['corechart']});

							// Set a callback to run when the Google Visualization API is loaded.
							google.setOnLoadCallback(drawChart);

							// Callback that creates and populates a data table,
							// instantiates the pie chart, passes in the data and
							// draws it.
							function drawChart() {

							// Create the data table.
							var data = new google.visualization.DataTable();
							data.addColumn('string', 'Topping');
							data.addColumn('number', 'Slices');
							data.addRows([			
				";
				
				while($rows = $result->fetch_assoc())
				{
					echo "
							['".$rows['cirurgia']."', ".$rows['totalValor']."],
					";
				}
				
				echo "
							]);
							// Set chart options
							var options = {'title':'Por Anestesista',
										   /*'width':600,
										   'height':600,*/
										   chartArea: {left:20,top:25,width:'70%',height:'70%'},
										   fontSize: '11'
										   
										   
										   };
								

							// Instantiate and draw our chart, passing in some options.
							var chart = new google.visualization.PieChart(document.getElementById('chart_cirurgia'));
							chart.draw(data, options);
							  }
							</script>			
				";
			}
			
		}
		
	}
	
	public function RetornaSomaClientesChart($mes,$ano)
	{
		$mysqli = conexao::pegar();
		
		$limit = 10;
		
		$sql = "call retorna_soma_clientes_mes('$mes','$ano',$limit)";		
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo "
							<script type=\"text/javascript\">
								  google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
							google.setOnLoadCallback(drawChart);
							function drawChart() {

							  var data = google.visualization.arrayToDataTable([
								['Cliente', 'Total'],
		
				";
				
				while($rows = $result->fetch_assoc())
				{
					echo "
							['".$rows['cliente']."',  ".$rows['total']."],

					";
				}
				
				echo "
							]);

							  var options = {
								title: 'Top ".$limit." - Clientes (Cirurgiões)',
								hAxis: {title: 'Clientes', titleTextStyle: {color: 'red'}},
								/* chartArea: {left:20,top:10,width:'60%',height:'30%'} */
								fontSize: '11'
							  };

							  var chart = new google.visualization.ColumnChart(document.getElementById('chart_cliente'));

							  chart.draw(data, options);

							}
								</script>			
				";
			}
			
		}
		
	}

	public function BuscaServico()
	{
		$this->PegarPost();
		
		$mysqli = conexao::pegar();
		
		$sql = "call busca_servico('$this->dataInicio','$this->dataFim','$this->cliente','$this->paciente');";
		$result = $mysqli->query($sql);
		echo $sql;
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0)
			{
				echo '
						<h1 class="page-header">Resultado</h1>
						<div class="table-responsive">
						<table class="table table-striped table-hover" style="font-size:12px;">
						<thead>
						<tr>
						<th>Data</th>
						<th>Cliente</th>
						<th>Atendimento</th>
						<th>Paciente</th>
						<!-- <th>Cirurgia</th> -->
						<!-- <th style="text-align:center">Valor Bruto</th> -->
						<!-- <th style="text-align:center">Valor NF</th> -->
						<th style="text-align:center">Valor Final</th>
						<!-- <th style="text-align:center">Desconto</th> -->
						<th style="text-align:center">Forma de Pagamento</th>
						<th width="2%" style="text-align:center">Recebido</th>
						<!--<th width="2%" style="text-align:center">Depositado</th>-->
						<th width="5%"></th>
						<th width="5%"></th>
						</tr>
						</thead>
						<tbody>				
				';			
				
				while($rows = $result->fetch_assoc())
				{
					
					$cadastro = new cadastro();
					$valorServico = $cadastro->converteValorSite($rows['valor_bruto']);
					$valorFinal = $cadastro->converteValorSite($rows['valor_final']);
					$valorDesconto = $cadastro->converteValorSite($rows['valor_desconto']);
					
					$recebido = ($rows['recebido'] == 'S') ? '<span class="glyphicon glyphicon-ok" style="color:#4cae4c; font-size:16px;"></span>' : '';
					$depositado = ($rows['depositado'] == 'S') ? '<span class="glyphicon glyphicon-ok" style="color:#4cae4c; font-size:16px;"></span>' : '';
					
					$compensado = ($rows['compensado'] == 'N') ? '<span class="glyphicon glyphicon-remove" style="color:#c12e2a; font-size:12px;"></span>' : '';
					
					echo '
						  <tr style="vertical-align: middle;">
						  <td>'.$rows['data_servico'].'</td>
						  <td valign="middle">'.$rows['cliente'].'</td>
						  <td valign="middle">'.$rows['atendimento'].'</td>
						  <td valign="middle">'.$rows['nome_paciente'].'</td>
						  <!-- <td>'.$rows['cirurgia'].'</td> -->
						  <!-- <td align="center">'.$valorServico.'</td> -->
						  <!-- <td align="center">'.$valorFinal.'</td> -->
						  <td align="center">'.$valorFinal.'</td>
						  <!-- <td align="center">'.$valorDesconto.'</td> -->
						  <td align="center">'.$rows['tipoPagamento'].' '.$compensado.'</td>
						  <td align="center">'.$recebido.'</td>
						  <!--<td align="center">'.$depositado.'</td>-->
						  <td align="center">
						  	<a href="?s=detalhes&id='.md5($rows['id']).'">
								<button type="submit" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit"></span>&nbsp&nbspDetalhes</button>
							</a>
						</td>
						  <td align="center">
						  	<form name="delServico" action="index.php" method="post" onsubmit="return ConfirmaDelete();">
						  		<input type="hidden" name="txtIdServico" value="'.$rows['id'].'" />
						  		<button type="submit" class="btn btn-danger btn-xs" name="btnDelServico"><span class="glyphicon glyphicon-trash"></span>&nbsp&nbspExcluir</button>
						    </form>
						  </td>
						  </tr>
					
					';
				}				
				
				echo '
						</tbody>
						</table>
				';
			}
			else
			{
				echo 'Sem registros';
			}
		}
		else
		{
			echo 'Erro';
		}
	}

	public function Pendentes($tipo,$usuario)
	{
		$this->PegarPost();
		
		$mysqli = conexao::pegar();
		
		if($tipo == 'recebido')
		{
			$sql = "call lista_nao_recebido()";
		}
		elseif($tipo == 'compensado')
		{
			$sql = "call lista_nao_compensado()";
		}
		else
		{
			$sql = "call lista_deposito_pendente('$usuario');";
		}		
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
					<table class="w-full text-sm">
					<thead class="bg-slate-50 border-b border-slate-200">
					<tr class="text-left text-xs font-medium text-slate-600 uppercase tracking-wide">
						<th class="px-4 py-3">Data</th>
						<th class="px-4 py-3">Cliente</th>
						<th class="px-4 py-3">Atendimento</th>
						<th class="px-4 py-3">Paciente</th>
						<th class="px-4 py-3 text-right">Valor</th>
						<th class="px-4 py-3">Pagamento</th>
						<th class="px-4 py-3 text-center">Recebido</th>
						<th class="px-4 py-3"></th>
					</tr>
					</thead>
					<tbody class="divide-y divide-slate-100">
				';

				while($rows = $result->fetch_assoc())
				{
					$cadastro = new cadastro();
					$valorFinal = $cadastro->converteValorSite($rows['valor_final']);

					$recebido = ($rows['recebido'] == 'S')
						? '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg></span>'
						: '<span class="inline-block w-6 h-6 rounded-full bg-slate-100 ring-1 ring-slate-200"></span>';

					$compensado = ($rows['compensado'] == 'N')
						? '<span class="ml-1 inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium rounded bg-red-50 text-red-700 ring-1 ring-red-200">Não comp.</span>'
						: '';

					echo '
						<tr class="hover:bg-slate-50 transition-colors">
							<td class="px-4 py-3 whitespace-nowrap text-slate-700">'.$rows['data_servico'].'</td>
							<td class="px-4 py-3 text-slate-900 font-medium">'.$rows['cliente'].'</td>
							<td class="px-4 py-3 text-slate-700">'.$rows['atendimento'].'</td>
							<td class="px-4 py-3 text-slate-700">'.$rows['nome_paciente'].'</td>
							<td class="px-4 py-3 text-right tabular-nums font-semibold text-slate-900">'.$valorFinal.'</td>
							<td class="px-4 py-3 text-slate-600">'.$rows['tipoPagamento'].' '.$compensado.'</td>
							<td class="px-4 py-3 text-center">'.$recebido.'</td>
							<td class="px-4 py-3">
								<div class="flex items-center justify-end gap-1.5">
									<a href="?s=detalhes&id='.md5($rows['id']).'" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 rounded ring-1 ring-brand-200 cursor-pointer transition-colors">Detalhes</a>
									<form action="index.php" method="post" onsubmit="return ConfirmaDelete();" class="inline">
										<input type="hidden" name="txtIdServico" value="'.$rows['id'].'" />
										<button type="submit" name="btnDelServico" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded ring-1 ring-red-200 cursor-pointer transition-colors">Excluir</button>
									</form>
								</div>
							</td>
						</tr>
					';
				}

				echo '
					</tbody>
					</table>
					</div>
				';
			}
			else
			{
				echo '<div class="px-5 py-10 text-center text-sm text-slate-500">Sem registros</div>';
			}
		}
		else
		{
			echo '<div class="px-5 py-4 text-sm text-red-600">Erro ao carregar a lista</div>';
		}
	}

	public function DepositosRealizados($dataInicio, $dataFim)
	{
		$cadastro = new cadastro();
		
		$dataInicio = $cadastro->converteData($dataInicio);
		$dataFim = $cadastro->converteData($dataFim);

		$mysqli = conexao::pegar();
		
		
		$sql = "Call lista_depositos_feitos('$dataInicio','$dataFim')";

		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
					<table class="w-full text-sm">
					<thead class="bg-slate-50 border-b border-slate-200">
					<tr class="text-left text-xs font-medium text-slate-600 uppercase tracking-wide">
						<th class="px-4 py-3">Data Depósito</th>
						<th class="px-4 py-3">Cliente</th>
						<th class="px-4 py-3">Atendimento</th>
						<th class="px-4 py-3">Paciente</th>
						<th class="px-4 py-3 text-right">Valor</th>
						<th class="px-4 py-3">Pagamento</th>
						<th class="px-4 py-3 text-center">Recebido</th>
						<th class="px-4 py-3"></th>
					</tr>
					</thead>
					<tbody class="divide-y divide-slate-100">
				';

				while($rows = $result->fetch_assoc())
				{
					$cadastro = new cadastro();
					$valorFinal = $cadastro->converteValorSite($rows['valor_final']);

					$recebido = ($rows['recebido'] == 'S')
						? '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg></span>'
						: '<span class="inline-block w-6 h-6 rounded-full bg-slate-100 ring-1 ring-slate-200"></span>';

					$compensado = ($rows['compensado'] == 'N')
						? '<span class="ml-1 inline-flex items-center px-1.5 py-0.5 text-[10px] font-medium rounded bg-red-50 text-red-700 ring-1 ring-red-200">Não comp.</span>'
						: '';

					echo '
						<tr class="hover:bg-slate-50 transition-colors">
							<td class="px-4 py-3 whitespace-nowrap text-slate-700">'.$rows['dataDeposito'].'</td>
							<td class="px-4 py-3 text-slate-900 font-medium">'.$rows['cliente'].'</td>
							<td class="px-4 py-3 text-slate-700">'.$rows['atendimento'].'</td>
							<td class="px-4 py-3 text-slate-700">'.$rows['nome_paciente'].'</td>
							<td class="px-4 py-3 text-right tabular-nums font-semibold text-slate-900">'.$valorFinal.'</td>
							<td class="px-4 py-3 text-slate-600">'.$rows['tipoPagamento'].' '.$compensado.'</td>
							<td class="px-4 py-3 text-center">'.$recebido.'</td>
							<td class="px-4 py-3">
								<div class="flex items-center justify-end gap-1.5">
									<a href="?s=detalhes&id='.md5($rows['id']).'" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 rounded ring-1 ring-brand-200 cursor-pointer transition-colors">Detalhes</a>
									<form action="index.php" method="post" onsubmit="return ConfirmaDelete();" class="inline">
										<input type="hidden" name="txtIdServico" value="'.$rows['id'].'" />
										<button type="submit" name="btnDelServico" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded ring-1 ring-red-200 cursor-pointer transition-colors">Excluir</button>
									</form>
								</div>
							</td>
						</tr>
					';
				}

				echo '
					</tbody>
					</table>
					</div>
				';
			}
			else
			{
				echo '<div class="px-5 py-10 text-center text-sm text-slate-500">Sem registros</div>';
			}
		}
		else
		{
			echo '<div class="px-5 py-4 text-sm text-red-600">Erro ao carregar a lista</div>';
		}
	}

	public function DepositosRealizadosResumo($dataInicio, $dataFim)
	{
		$cadastro = new cadastro();
		
		$dataInicio = $cadastro->converteData($dataInicio);
		$dataFim = $cadastro->converteData($dataFim);

		$mysqli = conexao::pegar();
		
		
		$sql = "select
				  (select tipo from tipo_pagamento where id = p.id_pagamento) tipoPagamento,
				  sum(p.valor_final) total
				  from pagamentos p
				where p.data_depositado >= '$dataInicio' and p.data_depositado <= '$dataFim'
				and (id_pagamento in (1,2,6))
				Group by p.id_pagamento order by 2 desc;";

		//echo $sql;

		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
					<table class="w-full text-sm">
					<thead class="bg-slate-50 border-b border-slate-200">
					<tr class="text-left text-xs font-medium text-slate-600 uppercase tracking-wide">
						<th class="px-4 py-3">Tipo de Pagamento</th>
						<th class="px-4 py-3 text-right">Total</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-slate-100">
				';

				while($rows = $result->fetch_assoc())
				{
					$cadastro = new cadastro();
					$total = $cadastro->converteValorSite($rows['total']);

					echo '
						<tr class="hover:bg-slate-50 transition-colors">
							<td class="px-4 py-3 text-slate-700">'.$rows['tipoPagamento'].'</td>
							<td class="px-4 py-3 text-right tabular-nums font-semibold text-slate-900">'.$total.'</td>
						</tr>
					';
				}

				echo '
					</tbody>
					</table>
					</div>
				';
			}
			else
			{
				echo '<div class="px-5 py-10 text-center text-sm text-slate-500">Sem registros</div>';
			}
		}
		else
		{
			echo '<div class="px-5 py-4 text-sm text-red-600">Erro</div>';
		}
	}


	public function TotalPendentePorUsuario()
	{
		$this->PegarPost();
		
		$mysqli = conexao::pegar();
		
		$sql = "select
						  u.nome_usuario,
						  sum(p.valor_final) total,
						  a.id_usuario
				  From servicos s
				  Left Join pagamentos p On p.id_servico = s.id
				  Left Join atendimento_servico a On a.id_servico = s.id
				  Left Join usuario_atendimento u On u.id = a.id_usuario
				  Left Join clientes c On c.id = s.cliente
				  where p.depositado = 'N' and (p.id_pagamento = 1 or p.id_pagamento = 2 or p.id_pagamento = 6)
				  group by u.nome_usuario order by 2 desc;";
		
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
					<table class="w-full text-sm">
					<thead class="bg-slate-50 border-b border-slate-200">
					<tr class="text-left text-xs font-medium text-slate-600 uppercase tracking-wide">
						<th class="px-4 py-3">Anestesista</th>
						<th class="px-4 py-3 text-right">Total</th>
					</tr>
					</thead>
					<tbody class="divide-y divide-slate-100">
				';

				while($rows = $result->fetch_assoc())
				{
					$cadastro = new cadastro();
					$total = $cadastro->converteValorSite($rows['total']);

					echo '
						<tr class="hover:bg-slate-50 transition-colors">
							<td class="px-4 py-3">
								<a href="?s=depositoPendente&u='.$rows['id_usuario'].'" class="text-brand-700 hover:text-brand-900 font-medium cursor-pointer">'.$rows['nome_usuario'].'</a>
							</td>
							<td class="px-4 py-3 text-right tabular-nums font-semibold text-slate-900">'.$total.'</td>
						</tr>
					';
				}

				echo '
					</tbody>
					</table>
					</div>
				';
			}
			else
			{
				echo '<div class="px-5 py-10 text-center text-sm text-slate-500">Sem registros</div>';
			}
		}
		else
		{
			echo 'Erro';
		}
	}
	
	
	// Cache em memoria para evitar 2 queries quando a home tambem chama RetornaSomaDepositoPendente
	private static $cacheDepositoPendente = null;

	private static function carregarDepositoPendente() {
		if (self::$cacheDepositoPendente !== null) return self::$cacheDepositoPendente;
		$mysqli = conexao::pegar();
		// 1 query unica retorna count e sum - evita rodar 2 procedures (conta_deposito_pendente + retorna_soma_depositoPendente)
		$sql = "select count(s.id) total_count, COALESCE(sum(p.valor_final),0) total_soma
		        from servicos s
		        left join pagamentos p on p.id_servico = s.id
		        where p.depositado = 'N' and p.id_pagamento in (1,2,6)";
		$result = $mysqli->query($sql);
		$row = $result ? $result->fetch_assoc() : ['total_count'=>0,'total_soma'=>0];
		self::$cacheDepositoPendente = $row;
		return $row;
	}

	public function ContaDepositoPendente()
	{
		$row = self::carregarDepositoPendente();
		$total = $row['total_count'];
		
		return $total;
		
	}

    public function RelatorioPorAnestesista($mes,$ano)
    {
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_equipe_periodo('$mes','$ano');";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
						<table class="w-full text-sm">
							<thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-4 py-3 text-left">Anestesista</th>
									<th class="px-4 py-3 text-right">Porcentagem</th>
									<th class="px-4 py-3 text-right">Total Valor Líquido</th>
									<th class="px-4 py-3 text-right">Total Valor Final</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100 text-slate-700">
				';

				while($rows = $result->fetch_assoc())
				{
					$cadastro = new cadastro();
					$this->anestesista = $rows['usuario'];
					$this->porcentagem = $rows['Porcentagem'] . '%';
					$this->totalValorLiquido = $cadastro->converteValorSite($rows['total']);
					$this->totalValorFinal = $cadastro->converteValorSite($rows['totalValorFinal']);

					echo '<tr class="hover:bg-slate-50">
								<td class="px-4 py-2.5 font-medium">'.$this->anestesista.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums text-slate-600">'.$this->porcentagem.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums">'.$this->totalValorLiquido.'</td>
								<td class="px-4 py-2.5 text-right tabular-nums font-semibold text-brand-700">'.$this->totalValorFinal.'</td>
							</tr>';
				}

				echo '
							</tbody>
						</table>
					</div>
				';
			}
		}
		
    }
	
	public function RetornaSomaClientesPeriodo($dataInicio,$dataFim,$cliente)
	{
		$cadastro = new cadastro();
		
		$dataInicio = $cadastro->converteData($dataInicio);
		$dataFim = $cadastro->converteData($dataFim);
		
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_clientes_periodo('$dataInicio','$dataFim','$cliente');";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
						<table class="w-full text-sm">
							<thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-4 py-3 text-left">Cliente (Cirurgião)</th>
									<th class="px-4 py-3 text-right">Qtde</th>
									<th class="px-4 py-3 text-right">Valor total</th>
									<th class="px-4 py-3 text-right w-32"></th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100 text-slate-700">
				';

				$grupoAnt = '';
				$i = 1000;
				// Modais sao acumulados e renderizados depois da tabela (evita <div> dentro de <tbody>)
				$modaisHtml = '';

				while($rows = $result->fetch_assoc())
				{
					if($rows['periodoServico'] != $grupoAnt)
					{
						echo '<tr class="bg-slate-50">
								<th colspan="4" class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">'. $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']) .'</th>
							</tr>';
					}

					$modalId = 'modalCirurgiasCliente' . $i;
					echo '<tr class="hover:bg-slate-50">
								<td class="px-4 py-2.5 font-medium">'.$rows['nome_cliente'].'</td>
								<td class="px-4 py-2.5 text-right tabular-nums">'.$rows['quantidade'].'</td>
								<td class="px-4 py-2.5 text-right tabular-nums font-medium">R$ '.$rows['totalValor'].'</td>
								<td class="px-4 py-2.5 text-right">
									<button type="button" onclick="document.getElementById(\''.$modalId.'\')?.classList.remove(\'hidden\')" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-brand-700 bg-brand-50 hover:bg-brand-100 ring-1 ring-brand-200 rounded-md cursor-pointer transition-colors">
										<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
										Cirurgias
									</button>
								</td>
							</tr>';

					$modaisHtml .= '
						<div id="'.$modalId.'" role="dialog" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
							<div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" data-modal-close="'.$modalId.'"></div>
							<div class="relative w-full max-w-3xl bg-white rounded-xl shadow-xl ring-1 ring-slate-200">
								<div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
									<h3 class="text-base font-semibold text-slate-900">Cirurgias por Cliente — '.$rows['nome_cliente'].'</h3>
									<button type="button" data-modal-close="'.$modalId.'" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer transition-colors">
										<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
									</button>
								</div>
								<div class="p-2">
									<iframe src="cirurgiasPorCliente.php?c='.urlencode($rows['nome_cliente']).'&i='.$dataInicio.'&f='.$dataFim.'&t='.$rows['totalValor'].'" class="w-full h-[500px] border-0 rounded-lg"></iframe>
								</div>
							</div>
						</div>';

					$i++;
					$grupoAnt = $rows['periodoServico'];
				}

				echo '
							</tbody>
						</table>
					</div>
				';
				echo $modaisHtml;
				echo '<script>
					(function(){
						if (window.__cirurgiasModalBound) return;
						window.__cirurgiasModalBound = true;
						document.addEventListener("click", function(e){
							var c = e.target.closest("[data-modal-close]");
							if (c) document.getElementById(c.getAttribute("data-modal-close"))?.classList.add("hidden");
						});
						document.addEventListener("keydown", function(e){
							if (e.key === "Escape") document.querySelectorAll("[role=\'dialog\']:not(.hidden)").forEach(function(m){ m.classList.add("hidden"); });
						});
					})();
				</script>';
			}		
		}
		
	}
	
	public function RetornaCirurgiaPorCliente($dataInicio, $dataFim, $cliente, $total) {
	
		$mysqli = conexao::pegar();
		
		$sql = "select
                s.cirurgia,
        		sum(p.valor_final) totalValor
				From servicos s
				Left Join pagamentos p On p.id_servico = s.id
				Left Join clientes c On c.id = s.cliente
				where (s.data_servico >= '$dataInicio' and s.data_servico <= '$dataFim')
				And c.nome_cliente like '$cliente'
				Group by s.cirurgia order by 2 desc;";
		$result = $mysqli->query($sql);
		
		//echo $sql;
		
		if($result) {
		
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0) {
				echo '
					<div class="overflow-x-auto">
						<table class="w-full text-sm">
							<thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-4 py-3 text-left">Cirurgia</th>
									<th class="px-4 py-3 text-right">Valor Total</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100 text-slate-700">
				';

				while($rows = $result->fetch_assoc()) {
					echo '<tr class="hover:bg-slate-50">
								<td class="px-4 py-2.5">'.$rows['cirurgia'].'</td>
								<td class="px-4 py-2.5 text-right tabular-nums font-medium">R$ '.$rows['totalValor'].'</td>
							</tr>';
				}

				echo '<tr class="bg-slate-50">
							<td class="px-4 py-2.5 font-semibold text-slate-900">Total</td>
							<td class="px-4 py-2.5 text-right tabular-nums font-semibold text-brand-700">R$ '.$total.'</td>
						</tr>';

				echo '	</tbody>
						</table>
					</div>
				';
			}
			
		}
	
	}
	
	public function RetornaSomaCirurgiaPeriodo($dataInicio,$dataFim,$cirurgia)
	{
		$cadastro = new cadastro();
		
		$dataInicio = $cadastro->converteData($dataInicio);
		$dataFim = $cadastro->converteData($dataFim);
		
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_cirurgia_periodo('$dataInicio','$dataFim','$cirurgia');";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
						<table class="w-full text-sm">
							<thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-4 py-3 text-left">Cirurgia</th>
									<th class="px-4 py-3 text-right">Qtde</th>
									<th class="px-4 py-3 text-right">Valor total</th>
									<th class="px-4 py-3 text-right">Valor médio</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100 text-slate-700">
				';

				$grupoAnt = '';

				while($rows = $result->fetch_assoc())
				{
					if($rows['periodoServico'] != $grupoAnt)
					{
						echo '<tr class="bg-slate-50">
								<th colspan="4" class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">'. $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']) .'</th>
							</tr>';
					}

					echo '<tr class="hover:bg-slate-50">
								<td class="px-4 py-2.5">'.$rows['cirurgia'].'</td>
								<td class="px-4 py-2.5 text-right tabular-nums">'.$rows['quantidade'].'</td>
								<td class="px-4 py-2.5 text-right tabular-nums font-medium">'.$cadastro->converteValorSite($rows['totalValor']).'</td>
								<td class="px-4 py-2.5 text-right tabular-nums">'.$cadastro->converteValorSite($rows['valorMedio']).'</td>
							</tr>';

					$grupoAnt = $rows['periodoServico'];
				}

				echo '
							</tbody>
						</table>
					</div>
				';
			}		
		}
		
	}
	
	public function RetornaSomaPagamentoPeriodo($dataInicio,$dataFim,$pagamento)
	{
		$cadastro = new cadastro();
		
		$dataInicio = $cadastro->converteData($dataInicio);
		$dataFim = $cadastro->converteData($dataFim);
		
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_formaPagamento_periodo('$dataInicio','$dataFim','$pagamento');";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
						<table class="w-full text-sm">
							<thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-4 py-3 text-left">Forma de Pagamento</th>
									<th class="px-4 py-3 text-right">Qtde</th>
									<th class="px-4 py-3 text-right">Valor total</th>
									<th class="px-4 py-3 text-right">Valor médio</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100 text-slate-700">
				';

				$grupoAnt = '';

				while($rows = $result->fetch_assoc())
				{
					if($rows['periodoServico'] != $grupoAnt)
					{
						echo '<tr class="bg-slate-50">
								<th colspan="4" class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">'. $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']) .'</th>
							</tr>';
					}

					echo '<tr class="hover:bg-slate-50">
								<td class="px-4 py-2.5">'.$rows['tipo'].'</td>
								<td class="px-4 py-2.5 text-right tabular-nums">'.$rows['quantidade'].'</td>
								<td class="px-4 py-2.5 text-right tabular-nums font-medium">'.$cadastro->converteValorSite($rows['totalValor']).'</td>
								<td class="px-4 py-2.5 text-right tabular-nums">'.$cadastro->converteValorSite($rows['valorMedio']).'</td>
							</tr>';

					$grupoAnt = $rows['periodoServico'];
				}

				echo '
							</tbody>
						</table>
					</div>
				';
			}		
		}
		
	}
	
	
	public function RetornaSomaImpostos($dataInicio,$dataFim)
	{
		$cadastro = new cadastro();
		
		$dataInicio = $cadastro->converteData($dataInicio);
		$dataFim = $cadastro->converteData($dataFim);
		
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_impostos_periodo('$dataInicio','$dataFim');";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
					<div class="overflow-x-auto">
						<table class="w-full text-sm">
							<thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
								<tr>
									<th class="px-3 py-3 text-left">Mês/Ano</th>
									<th class="px-3 py-3 text-right">Notas Emitidas</th>
									<th class="px-3 py-3 text-right">PIS</th>
									<th class="px-3 py-3 text-right">COFINS</th>
									<th class="px-3 py-3 text-right">ISS</th>
									<th class="px-3 py-3 text-right">IR</th>
									<th class="px-3 py-3 text-right">CS</th>
									<th class="px-3 py-3 text-right">Descontos</th>
									<th class="px-3 py-3 text-right">Total</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-slate-100 text-slate-700">
				';


				while($rows = $result->fetch_assoc())
				{
					echo '<tr class="hover:bg-slate-50">
								<td class="px-3 py-2.5 font-medium">'.$cadastro->RenomeiaMeses($rows['mes'],$rows['ano']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums">'.$cadastro->converteValorSite($rows['totalNotas']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums text-slate-600">'.$cadastro->converteValorSite($rows['PIS']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums text-slate-600">'.$cadastro->converteValorSite($rows['COFINS']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums text-slate-600">'.$cadastro->converteValorSite($rows['ISS']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums text-slate-600">'.$cadastro->converteValorSite($rows['IR']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums text-slate-600">'.$cadastro->converteValorSite($rows['CS']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums text-slate-600">'.$cadastro->converteValorSite($rows['totalDescontos']).'</td>
								<td class="px-3 py-2.5 text-right tabular-nums font-semibold text-brand-700">'.$cadastro->converteValorSite($rows['total']).'</td>
							</tr>';
					
			
				}
				
				echo '
						</tbody>
					</table>
				</div>
				';
			}
		}

	}

	public function RetornaSomaDepositoPendente()
	{
		// Reusa o cache de carregarDepositoPendente() - evita 2a query quando ContaDepositoPendente ja foi chamada
		$row = self::carregarDepositoPendente();
		$cadastro = new cadastro();
		return $cadastro->converteValorSite($row['total_soma']);
		// codigo abaixo eh dead code (mantido para diff minimo)
		if(false) {

			$RecordCount = 0;



			if($RecordCount > 0) {

				$rows = [];

				return $cadastro->converteValorSite($rows['total']);

			}
			else {

				return $cadastro->converteValorSite('0');

			}
		
		}		
	}

	private function getTotalMeses() {

		$total = 0;

		$mysqli = conexao::pegar();
		$sql = "select distinct date_format(data_servico, '%Y%m') from servicos";
		$result = $mysqli->query($sql);
		if($result) {

			$total = $result->num_rows;
		}

		return $total;

	}

	public function PaginacaoValores($pagAtual) {

		$total = $this->getTotalMeses();
		$regPag = ceil($total / $this->totalRegPag);

		if($pagAtual == 0) {
			$pagAtual = 1;
		}

		$base = 'inline-flex items-center justify-center min-w-9 h-9 px-3 text-sm font-medium rounded-lg cursor-pointer transition-colors';
		$inactive = ' text-slate-600 hover:text-slate-900 hover:bg-slate-100';
		$active = ' bg-brand-600 text-white';

		$i = 1;
		$n = $regPag;

		echo '<li><a href="index.php?s=historico&p='.$i.'" aria-label="Primeira" class="'.$base.$inactive.'">&laquo;</a></li>';

		while($i <= $n) {
			$cls = $base . (($pagAtual == $i) ? $active : $inactive);
			echo '<li><a href="index.php?s=historico&p='.$i.'" class="'.$cls.'">'.$i.'</a></li>';
			$i++;
		}

		echo '<li><a href="index.php?s=historico&p='.$n.'" aria-label="Última" class="'.$base.$inactive.'">&raquo;</a></li>';
	}
	
	
}
?>
