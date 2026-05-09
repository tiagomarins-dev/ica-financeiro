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
		
		$sql = "call retorna_soma_valores()";		
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
						<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
						<th>Mês/Ano</th>	
						
						<th>Notas Emitidas</th>
						<!-- <th>Notas Emitidas Com Impostos</th> -->
						<th>Gratuidade</th>
						<th>Faturamento bruto</th>
						<!-- <th>Total Descontos Promocionais</th> -->
						<th>Total Desconto Cartão</th>						
						
						
						<th>Despesas</th>
						<!-- <th>Saldo</th> -->
						<th>Líquido</th>
						</tr>
						</thead>
						<tbody>			
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
					//$this->totalLiquidoImposto = $cadastro->converteValorSite($rows['SomaValorFinalImposto']);
					$this->totalDespesas = $cadastro->converteValorSite($rows['SomaDespesasMes']);
					$this->totalSaldo = $this->CalculaSaldo($rows['SomaValorBruto'],$rows['SomaDescontoCartao'],$rows['SomaDespesasMes']);
					//$this->totalSaldo = $this->CalculaSaldo2($rows['SomaValorFinalImposto'],$rows['SomaValorSemNota'],$rows['SomaDescontoCartao'],$rows['SomaValorDescontos'],$rows['SomaDespesasMes']);
					$this->totalSaldo = $cadastro->converteValorSiteSaldo($this->totalSaldo);
					
					echo '
							<tr>
							<td>'.$this->mesAno.'</td>
							<td>'.$this->totalComNota.'</td>
							<!-- <td>'.$this->totalLiquidoImposto.'</td> -->
							<td>'.$this->totalSemNota.'</td>
							<td><strong>'.$this->totalBruto.'</strong></td>
							<!-- <td>'.$this->totalDescontos.'</td> -->
							<td>'.$this->totalDescntoCartao.'</td>
							
														
							<td>'.$this->totalDespesas.'</td>
							<!-- <td>'.$this->totalSaldo.'</td> -->
							<td>'.$this->totalSaldo.'</td>
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
				echo 'Sem Registros';
			}		
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
						<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
						<th>Mês/Ano</th>	
						
						<th>Notas Emitidas</th>
						<!-- <th>Notas Emitidas Com Impostos</th> -->
						<th>Gratuidade</th>
						<th>Faturamento bruto</th>
						<!-- <th>Total Descontos Promocionais</th> -->
						<th>Total Desconto Cartão</th>						
						
						
						<th>Despesas</th>
						<!-- <th>Saldo</th> -->
						<th>Líquido</th>
						</tr>
						</thead>
						<tbody>			
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
					//$this->totalLiquidoImposto = $cadastro->converteValorSite($rows['SomaValorFinalImposto']);
					$this->totalDespesas = $cadastro->converteValorSite($rows['SomaDespesasMes']);
					$this->totalSaldo = $this->CalculaSaldo($rows['SomaValorBruto'],$rows['SomaDescontoCartao'],$rows['SomaDespesasMes']);
					//$this->totalSaldo = $this->CalculaSaldo2($rows['SomaValorFinalImposto'],$rows['SomaValorSemNota'],$rows['SomaDescontoCartao'],$rows['SomaValorDescontos'],$rows['SomaDespesasMes']);
					$this->totalSaldo = $cadastro->converteValorSiteSaldo($this->totalSaldo);
					
					echo '
							<tr>
							<td>'.$this->mesAno.'</td>
							<td>'.$this->totalComNota.'</td>
							<!-- <td>'.$this->totalLiquidoImposto.'</td> -->
							<td>'.$this->totalSemNota.'</td>
							<td><strong>'.$this->totalBruto.'</strong></td>
							<!-- <td>'.$this->totalDescontos.'</td> -->
							<td>'.$this->totalDescntoCartao.'</td>
							
														
							<td>'.$this->totalDespesas.'</td>
							<!-- <td>'.$this->totalSaldo.'</td> -->
							<td>'.$this->totalSaldo.'</td>
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
				echo 'Sem Registros';
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
						
						<div class="table-responsive">
						<table class="table table-striped table-hover" style="font-size:12px;">
						<thead>
						<tr>
						<th>Data de Depósito</th>
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
						  <td>'.$rows['dataDeposito'].'</td>
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
						</div>
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
						
						<div class="table-responsive">
						<table class="table table-striped table-hover" style="font-size:12px;">
						<thead>
						<tr>
						<th>Tipo de Pagamento</th>
						<th>Total</th>
						</tr>
						</thead>
						<tbody>				
				';			
				
				while($rows = $result->fetch_assoc())
				{
					
					$cadastro = new cadastro();
					$total = $cadastro->converteValorSite($rows['total']);

					echo '
						  <tr style="vertical-align: middle;">
						  <td>'.$rows['tipoPagamento'].'</td>
						  <td valign="middle">'.$total.'</td>
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
				echo 'Sem registros';
			}
		}
		else
		{
			echo 'Erro';
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
						
						<div class="table-responsive">
						<table class="table table-striped table-hover" style="font-size:12px; width:50%;">
						<thead>
						<tr>
						<th>Anestesista</th>
						<th>Total</th>
						</tr>
						</thead>
						<tbody>				
				';			
				
				while($rows = $result->fetch_assoc())
				{
					
					$cadastro = new cadastro();
					$total = $cadastro->converteValorSite($rows['total']);
					
					echo '
						  
						  <tr style="vertical-align: middle;">
							<td><a href="?s=depositoPendente&u='.$rows['id_usuario'].'">'.$rows['nome_usuario'].'</a></td>
							<td>'.$total.'</td>
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
	
	
	public function ContaDepositoPendente()
	{
		$mysqli = conexao::pegar();
		
		$sql = "call conta_deposito_pendente();";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				$total = $rows['total'];
			}
			else
			{
				$total = '';
			}
		}
		else
		{
			$total = '';
		}
		
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
						<h3>Por Anestesista</h3>
						<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
						<th>Anestesista</th>	
						<th>Porcentagem</th>
						<th>Total Valor Líquido</th>
						<th>Total Valor Final</th>
						</tr>
						</thead>
						<tbody>			
				';
				
				while($rows = $result->fetch_assoc())
				{
					$cadastro = new cadastro();
					$this->anestesista = $rows['usuario'];
					$this->porcentagem = $rows['Porcentagem'] . '%';
					$this->totalValorLiquido = $cadastro->converteValorSite($rows['total']);
					$this->totalValorFinal = $cadastro->converteValorSite($rows['totalValorFinal']);
					
					echo '
							<tr>
							<td>'.$this->anestesista.'</td>
							<td>'.$this->porcentagem.'</td>
							<td>'.$this->totalValorLiquido.'</td>
							<td>'.$this->totalValorFinal.'</td>
							</tr>					
					';
				}
				
				echo '
						</tbody>
						</table>
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
						<h3>Por Cirurgião</h3>
						<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
						<th>Cliente (Cirurgião)</th>	
						<th>Quantidade</th>
						<th>Valor total</th>
						<th width="5%"></th>
						</tr>
						</thead>
						<tbody>			
				';
				
				$grupoAnt = '';
				$i = 1000;
				
				while($rows = $result->fetch_assoc())
				{					
					
					if($rows['periodoServico'] != $grupoAnt)
					{
						echo 	'<tr>
									<th colspan="4" style="background:#f0f0f0;">'. $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']) .'</th>
								</tr>';
					}
					
					echo 	'
								<tr>
									<td>'.$rows['nome_cliente'].'</td>
									<td>'.$rows['quantidade'].'</td>
									<td>R$ '.$rows['totalValor'].'</td>
									<td><a class="btn btn-sm btn-default" data-toggle="modal" data-target=".bs-example-modal-lg'.$i.'"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;Cirurgias</a></td>
								</tr>
					';
					
					//MODAL COM AS CIRURGIAS
					echo '
					
					<div class="modal fade bs-example-modal-lg'.$i.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
					<div class="modal-content">
					
					<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myLargeModalLabel">Cirurgias Por Cliente</h4>
					</div>
					<div class="modal-body">
					
					
					<iframe src="cirurgiasPorCliente.php?c='.$rows['nome_cliente'].'&i='.$dataInicio.'&f='.$dataFim.'&t='.$rows['totalValor'].'" style="zoom:0.60" width="99.6%" height="500" frameborder="0"></iframe>	
					
					
					
					</div>
					</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->';
									
					
					$i++;
					$grupoAnt = $rows['periodoServico'];
				}
				
				echo '
						</tbody>
						</table>
				';				
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
			
				echo '<table class="table">
						<tr>
        					<th>Cirurgia</th>
            				<th>Valor Total</th>
        				</tr>
				';
				
				while($rows = $result->fetch_assoc()) {
				
					echo '<tr>
        					<td>'.$rows['cirurgia'].'</td>
            				<td>R$ '.$rows['totalValor'].'</td>
        					</tr>';
				
				}
				
				echo '<tr>
				<td><strong>Total</strong></td>
				<td><strong>R$ ' . $total .'</strong></td>
				</tr>';
				
				echo '</table>';
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
						<h3>Por Cirurgia</h3>
						<table class="table table-striped table-bordered table-hover" style="width:100%;">
						<thead>
						<tr>
						<th>Cirurgia</th>	
						<th align="center">Quantidade</th>
						<th align="center">Valor total</th>
						<th align="center">Valor médio</th>
						</tr>
						</thead>
						<tbody>			
				';
				
				$grupoAnt = '';
				
				while($rows = $result->fetch_assoc())
				{					
					
					if($rows['periodoServico'] != $grupoAnt)
					{
						echo 	'<tr>
									<th colspan="4" style="background:#f0f0f0;">'. $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']) .'</th>
								</tr>';
					}
					
					echo 	'
								<tr>
									<td>'.$rows['cirurgia'].'</td>
									<td align="center">'.$rows['quantidade'].'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['totalValor']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['valorMedio']).'</td>
								</tr>
					';					
					
					$grupoAnt = $rows['periodoServico'];
				}
				
				echo '
						</tbody>
						</table>
						<div id="chart_cirurgia" style="width: 40%;"></div>
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
						<h3>Por Forma de Pagamento</h3>
						<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr>
						<th>Forma de Pagamento</th>	
						<th align="center">Quantidade</th>
						<th align="center">Valor total</th>
						<th align="center">Valor médio</th>
						</tr>
						</thead>
						<tbody>			
				';
				
				$grupoAnt = '';
				
				while($rows = $result->fetch_assoc())
				{					
					
					if($rows['periodoServico'] != $grupoAnt)
					{
						echo 	'<tr>
									<th colspan="4" style="background:#f0f0f0;">'. $cadastro->RenomeiaMeses($rows['mes'],$rows['ano']) .'</th>
								</tr>';
					}
					
					echo 	'
								<tr>
									<td>'.$rows['tipo'].'</td>
									<td align="center">'.$rows['quantidade'].'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['totalValor']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['valorMedio']).'</td>
								</tr>
					';					
					
					$grupoAnt = $rows['periodoServico'];
				}
				
				echo '
						</tbody>
						</table>
						<div id="chart_cirurgia" style="width: 40%;"></div>
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
						<h3>Por Impostos</h3>
						<table class="table table-striped table-bordered table-hover">
						<thead>
						<tr style="background:#d0d0d0; font-weight:bold;">
						<td align="center">Mês/Ano</td>	
						<td align="center">Total de Notas Emitidas</td>
						<td align="center">Total PIS</td>
						<td align="center">Total COFINS</td>
						<td align="center">Total ISS</td>
						<td align="center">Total IR</td>
						<td align="center">Total CS</td>
						<td align="center">Total de Descontos</td>
						<td align="center">Total</td>
						</tr>
						</thead>
						<tbody>			
				';
				
				
				while($rows = $result->fetch_assoc())
				{					
					
					
					echo 	'
								<tr>
									<td align="center">'.$cadastro->RenomeiaMeses($rows['mes'],$rows['ano']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['totalNotas']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['PIS']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['COFINS']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['ISS']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['IR']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['CS']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['totalDescontos']).'</td>
									<td align="center">'.$cadastro->converteValorSite($rows['total']).'</td>
								</tr>
					';					
					
			
				}
				
				echo '
						</tbody>
						</table>
				';				
			}		
		}
		
	}
	
	public function RetornaSomaDepositoPendente()
	{
		$mysqli = conexao::pegar();
		
		$sql = "call retorna_soma_depositoPendente('')";
		$result = $mysqli->query($sql);
		
		if($result) {
		
			$RecordCount = $result->num_rows;
			
			$cadastro = new cadastro();
			
			if($RecordCount > 0) {
				
				$rows = $result->fetch_assoc();
				
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


		$i = 1;
		$n = $regPag;

		echo '<li><a href="index.php?s=historico&p='.$i.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';

		while($i <= $n) {

			$activePagAtual = ($pagAtual == $i) ? ' class="active" ' : ''; 

			echo '<li'.$activePagAtual.'><a href="index.php?s=historico&p='.$i.'">'.$i.'</a></li>';

			$i++;
		}

		echo '<li><a href="index.php?s=historico&p='.$n.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
	}
	
	
}
?>
