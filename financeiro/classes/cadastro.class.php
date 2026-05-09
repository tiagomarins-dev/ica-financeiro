<?php
class cadastro
{
	private $host;
	private $user;
	private $pass;
	private $banco;
	
#	Variáveis do Sistema

	private $idServico;
	private $dataCadastro;
	private $cliente;
	private $atendimento1;
	private $atendimento2;
	private $paciente;
	private $cirurgia;
	private	$valorServico;
	private	$valorDesconto;
	private $nota;
	private $recebido;
	private $depositado;
	private $tipoPagamento;
	private $observacoes;
    private $dataPrevisaoDeposito;
	
	private $dataDepositado;
	private $dataRecebido;
	
	public $dadosServico = array();
	
	private $dataDespesa;
	private $nomeDespesa;
	private $descDespesa;
	private $valorDespesa;
	private $vencimento;
    
	private $idPagamento;
    private $formaPagamento;
    private $formaPagamentoEdit;
    private $diasPagamento;
    private $descFormaPagamento;
    private $pDescontoFormaPagamento;
	
	private $idCliente;
	private $nomeCliente;
	private $descCliente;
	
	private $idDespesaFixa = array();
	private $anoImport;
	private $mesImport;
	
	private $idApagaDespesaFixa;
	
	private $totalDespesaMensal;
	
	private $nomeUsuario;
	private $sobreNomeUsuario;
	private $loginUsuario;
	private $senhaUsuario;
	private $senhaUsuarioOriginal;
	private $senhaUsuarioOriginal2;
	private $novaSenha1;
	private $novaSenha2;
	private $idUsuario;
	
	public $dadosUsuario = array();
	
	private $motivo;
	private $bancoCliente;
	private $nCheque;
	private $solucao;
	private $dataSolucao;
	private $usuarioSolucao;
	
	public $dadosCheque = array();
	
	private $idServicoCartao = array();
	private $depositadoCartao = array();

	private $idsCartoes = array(3,4,7,8,9,10,11,12,13,14,15,17);


	public function __construct()
    {
		$conn = new conexao();
		$infoConn = $conn->getInfoConn();

		$this->host = $infoConn['host'];
		$this->user = $infoConn['user'];
		$this->pass = $infoConn['pass'];
		$this->banco = $infoConn['database'];
    }


    public function converteData($data)
	{
		$data = str_replace(' ','',$data);
				
		if(substr_count($data,'-') == 2)
		{
			return $data;
		}
		else
		{
			
			if(strlen($data) == 10)
			{
				$dia = substr($data, 0, 2);
				$mes = substr($data, 3, 2);
				$ano = substr($data, 6, 4);
				
				$data2 = $ano . "-" . $mes . "-" . $dia;
				
				return $data2;
			}
			else
			{
				return '';
			}
		}
	}

	public function converteValorBanco($valor) {

		$output = str_replace(",",".",$valor);
		return $output;
	}
	
	public function converteValor($valor)
	{
		$nPonto = substr_count($valor,'.');
		$nVirgula = substr_count($valor,',');
		$nCaracteres = strlen($valor);
		
		$valorFinal = '';
		
		if(($nPonto > 0) || ($nVirgula > 0))
		{
		
			$i = 0;
			$n = $nCaracteres;
			
			while($i < $n)
			{
				$caracter = substr($valor,$i,1);
				
				$padrao1 = $nCaracteres - 3;
				$padrao2 = $nCaracteres - 2;
				
				if(($i == $padrao1) || ($i == $padrao2))
				{
					if(!is_numeric($caracter))
					{
						$valorFinal .= '.';
					}
					else
					{
						$valorFinal .= $caracter;
					}
				}
				else
				{
					if(is_numeric($caracter))
					{
						$valorFinal .= $caracter;
					}
				}
				
				$i++;
			}
			
			return $valorFinal;
		}
		else
		{
			return $valor;
		}
	}
	
	public function converteValorSite($valor)
	{
		// Coerção para string evita deprecation warning quando $valor é null (PHP 8.1+)
		if(strlen((string)$valor) > 0)
		{
			$valor = number_format($valor,2,',','.');
			$valor = 'R$ '.$valor;
		}
		else
		{
			$valor = '';
		}
		return $valor;
	}

	public function converteValorSiteSaldo($valor)
	{
		if(strlen((string)$valor) > 0)
		{
			$valor = number_format($valor,2,',','.');
			//$valor = 'R$ '.$valor;
		}
		else
		{
			$valor = 0;
		}		
		
		if($valor < 0)
		{
			$valor = '<span style="color:#f00">R$ '.$valor.'</span>';
		}
		else
		{
			$valor = '<span style="color:#00f">R$ '.$valor.'</span>';
		}
		
		return $valor;
	}
	
	public function RenomeiaMeses($mes,$ano)
	{
		switch($mes)
		{
			case '01';
			$nomeMes = 'Janeiro';
			break;
			
			case '02';
			$nomeMes = 'Fevereiro';
			break;
			
			case '03';
			$nomeMes = 'Março';
			break;
			
			case '04';
			$nomeMes = 'Abril';
			break;
			
			case '05';
			$nomeMes = 'Maio';
			break;
			
			case '06';
			$nomeMes = 'Junho';
			break;
			
			case '07';
			$nomeMes = 'Julho';
			break;
			
			case '08';
			$nomeMes = 'Agosto';
			break;
			
			case '09';
			$nomeMes = 'Setembro';
			break;
			
			case '10';
			$nomeMes = 'Outubro';
			break;
			
			case '11';
			$nomeMes = 'Novembro';
			break;
			
			case '12';
			$nomeMes = 'Dezembro';
			break;
			
		}
		
		return $nomeMes . ' / ' . $ano;
	}
	
	public function ListaMeses($mes,$ano)
	{
		$AnoMes = $ano . '-' . $mes;
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select 
						date_format(data_servico,'%Y-%m') mesesServico, 
						date_format(data_servico,'%m') mes, 
						date_format(data_servico,'%Y') ano  
				from servicos group by 1 order by 1 desc;";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$mesAnoSite = $this->RenomeiaMeses($mes,$ano);
				echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?m='.$mes.'&a='.$ano.'">'.$mesAnoSite.'</a></li>';
				
				while($rows = $result->fetch_assoc())
				{
					$data = $this->RenomeiaMeses($rows['mes'],$rows['ano']);
					
					if($rows['mesesServico'] != $AnoMes)
					{
						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?m='.$rows['mes'].'&a='.$rows['ano'].'">'.$data.'</a></li>';
					}					
				}
			}
			else
			{
				
			}
		}		
		$mysqli->close();
	}	
	
	public function ListaMesesStats($mes,$ano)
	{
		$AnoMes = $ano . '-' . $mes;
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select 
						date_format(data_servico,'%Y-%m') mesesServico, 
						date_format(data_servico,'%m') mes, 
						date_format(data_servico,'%Y') ano  
				from servicos group by 1 order by 1 desc;";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$mesAnoSite = $this->RenomeiaMeses($mes,$ano);
				echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="?s=estatisticas&m='.$mes.'&a='.$ano.'">'.$mesAnoSite.'</a></li>';
				
				while($rows = $result->fetch_assoc())
				{
					$data = $this->RenomeiaMeses($rows['mes'],$rows['ano']);
					
					if($rows['mesesServico'] != $AnoMes)
					{
						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="?s=estatisticas&m='.$rows['mes'].'&a='.$rows['ano'].'">'.$data.'</a></li>';
					}					
				}
			}
			else
			{
				
			}
		}		
		$mysqli->close();
	}
	
	public function ListaMesesCartoes($mes,$ano)
	{
		$AnoMes = $ano . '-' . $mes;
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select 
						date_format(data_servico,'%Y-%m') mesesServico, 
						date_format(data_servico,'%m') mes, 
						date_format(data_servico,'%Y') ano  
				from servicos group by 1 order by 1 desc;";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$mesAnoSite = $this->RenomeiaMeses($mes,$ano);
				echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="?s=checkCartao&m='.$mes.'&a='.$ano.'">'.$mesAnoSite.'</a></li>';
				
				while($rows = $result->fetch_assoc())
				{
					$data = $this->RenomeiaMeses($rows['mes'],$rows['ano']);
					
					if($rows['mesesServico'] != $AnoMes)
					{
						echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="?s=checkCartao&m='.$rows['mes'].'&a='.$rows['ano'].'">'.$data.'</a></li>';
					}					
				}
			}
			else
			{
				
			}
		}		
		$mysqli->close();
	}
	
	private function TratamentoValor($valor)
	{
		$valorFinal = str_replace('.',',',$valor);
		
		return 'R$ '.$valorFinal;
	}
	
	private function PegarPost()
	{
		/************************* CADASTRO SERVIÇO ***************************************************/
		
		$this->dataCadastro = (isset($_POST['txtData'])) ? $this->converteData($_POST['txtData']) : '';
		$this->cliente = (isset($_POST['txtCliente'])) ? addslashes($_POST['txtCliente']) : '';
		$this->atendimento1 = (isset($_POST['txtAtendimento1'])) ? $_POST['txtAtendimento1'] : '';
		$this->atendimento2 = (isset($_POST['txtAtendimento2'])) ? $_POST['txtAtendimento2'] : '';		
		$this->paciente = (isset($_POST['txtPaciente'])) ? addslashes($_POST['txtPaciente']) : '';
		$this->cirurgia = (isset($_POST['txtCirurgia'])) ? addslashes($_POST['txtCirurgia']) : '';
		$this->valorServico = (isset($_POST['txtValorBruto'])) ? $this->converteValor($_POST['txtValorBruto']) : '';
		$this->valorDesconto = (isset($_POST['txtDesconto'])) ? $this->converteValor($_POST['txtDesconto']) : '';
		$this->recebido = (isset($_POST['chkRecebido'])) ? 'S' : 'N';
		$this->depositado = (isset($_POST['chkDepositado'])) ? 'S' : 'N';
		$this->nota = (isset($_POST['chkNota'])) ? $_POST['chkNota'] : 'S';
		$this->tipoPagamento = (isset($_POST['txtPagamento'])) ? $_POST['txtPagamento'] : '';
		$this->observacoes = (isset($_POST['txtObs'])) ? addslashes($_POST['txtObs']) : '';
        $this->dataPrevisaoDeposito = (isset($_POST['txtDataPrevisao'])) ? $this->converteData($_POST['txtDataPrevisao']) : '';
		
		$this->dataDepositado = (isset($_POST['txtDataDeposito'])) ? $this->converteData($_POST['txtDataDeposito']) : '';
		$this->dataRecebido = (isset($_POST['txtDataRecebimento'])) ? $this->converteData($_POST['txtDataRecebimento']) : '';
		
		/************************* APAGAR SERVIÇO ***************************************************/
		
		$this->idServico = (isset($_POST['txtIdServico'])) ? $_POST['txtIdServico'] : '';
		
		/************************* CADASTRO DESPESA ***************************************************/
		
		$this->dataDespesa = (isset($_POST['txtDataDespesa'])) ? $this->converteData($_POST['txtDataDespesa']) : '';
		$this->nomeDespesa = (isset($_POST['txtDespesa'])) ? addslashes($_POST['txtDespesa']) : '';
		$this->descDespesa = (isset($_POST['txtDescricao'])) ? addslashes($_POST['txtDescricao']) : '';
		$this->valorDespesa = (isset($_POST['txtValorDespesa'])) ? $this->converteValor($_POST['txtValorDespesa']) : '';
		$this->vencimento = (isset($_POST['txtDiaVencimento'])) ? $this->TratamentoDiaVencimento($_POST['txtDiaVencimento']) : '';
		$this->idDespesaFixa = (isset($_POST['chkDespesa'])) ? $_POST['chkDespesa'] : '';
		$this->anoImport = (isset($_POST['txtAnoImport'])) ? $_POST['txtAnoImport'] : '';
		$this->mesImport = (isset($_POST['txtMesImport'])) ? $_POST['txtMesImport'] : '';
        
        /************************* FORMA DE PAGAMENTO ***************************************************/
        
        $this->idPagamento = (isset($_POST['txtIdPagamento'])) ? $_POST['txtIdPagamento'] : '';
        $this->formaPagamento = (isset($_POST['txtTipoDesconto'])) ? addslashes($_POST['txtTipoDesconto']) : '';
        $this->formaPagamentoEdit = (isset($_POST['txtTipoDescontoEdit'])) ? addslashes($_POST['txtTipoDescontoEdit']) : '';
        $this->diasPagamento = (isset($_POST['txtDiasRecebimento'])) ? $_POST['txtDiasRecebimento'] : '';
        $this->descFormaPagamento = (isset($_POST['txtDescricaoDesconto'])) ? addslashes($_POST['txtDescricaoDesconto']) : '';
        $this->pDescontoFormaPagamento = (isset($_POST['txtValorDesconto'])) ? $this->converteValorBanco($_POST['txtValorDesconto']) : '';
		
		/************************* CLIENTES ***************************************************/
		
		$this->idCliente = (isset($_POST['txtIdCliente'])) ? $_POST['txtIdCliente'] : '';
		$this->nomeCliente = (isset($_POST['txtNomeCliente'])) ? addslashes($_POST['txtNomeCliente']) : '';
		$this->descCliente = (isset($_POST['txtDescricaoDesconto'])) ? addslashes($_POST['txtDescricaoDesconto']) : '';
		
		$this->idApagaDespesaFixa = (isset($_POST['txtIdDespesaFixa'])) ? $_POST['txtIdDespesaFixa'] : '';
		
		/************************* USUÁRIOS ***************************************************/
		
		$this->nomeUsuario = (isset($_POST['txtNomeUsuario'])) ? addslashes($_POST['txtNomeUsuario']) : '';
		$this->sobreNomeUsuario = (isset($_POST['txtSobreNomeUsuario'])) ? addslashes($_POST['txtSobreNomeUsuario']) : '';
		$this->loginUsuario = (isset($_POST['txtLoginUsuario'])) ? $_POST['txtLoginUsuario'] : '';
		$this->senhaUsuarioOriginal = (isset($_POST['txtSenhaUsuario'])) ? $_POST['txtSenhaUsuario'] : '';
		$this->senhaUsuarioOriginal2 = (isset($_POST['txtSenhaUsuario2'])) ? $_POST['txtSenhaUsuario2'] : '';
		$this->senhaUsuario = (isset($_POST['txtSenhaUsuario'])) ? md5($_POST['txtSenhaUsuario']) : '';
		$this->idUsuario = (isset($_POST['txtidUsuario'])) ? $_POST['txtidUsuario'] : '';
		
		$this->novaSenha1 = (isset($_POST['txtSenhaUsuario'])) ? md5($_POST['txtSenhaUsuario']) : '';
		$this->novaSenha2 = (isset($_POST['txtSenhaUsuario2'])) ? md5($_POST['txtSenhaUsuario2']) : '';
		
		/************************* SOLUCAO COMPENSADO ***************************************************/
		
		$this->motivo = (isset($_POST['txtMotivo'])) ? addslashes($_POST['txtMotivo']) : '';
		$this->bancoCliente = (isset($_POST['txtBanco'])) ? addslashes($_POST['txtBanco']) : '';
		$this->nCheque = (isset($_POST['txtCheque'])) ? $_POST['txtCheque'] : '';
		$this->solucao = (isset($_POST['txtSolucao'])) ? addslashes($_POST['txtSolucao']) : '';
		$this->dataSolucao = (isset($_POST['txtDataSolucao'])) ? $this->converteData($_POST['txtDataSolucao']) : '';
		$this->usuarioSolucao = (isset($_POST['txtRespSolucao'])) ? $_POST['txtRespSolucao'] : ''; 

		$this->idServicoCartao = (isset($_POST['idServicoCartao'])) ? $_POST['idServicoCartao'] : array();
		$this->depositadoCartao = (isset($_POST['depositadoCartao'])) ? $_POST['depositadoCartao'] : array();
	}
	
	private function LimpaPost()
	{
		unset($_POST['txtData']);
		unset($_POST['txtCliente']);
		unset($_POST['txtAtendimento']);
		unset($_POST['txtPaciente']);
		unset($_POST['txtValorBruto']);
		unset($_POST['txtDesconto']);
		unset($_POST['chkRecebido']);
		unset($_POST['chkDepositado']);
		unset($_POST['chkNota']);
		unset($_POST['txtPagamento']);
		unset($_POST['txtObs']);
		unset($_POST['btnInserirAnestesia']);
	}
	
	public function RetornaUsuariosEdit($idUsuarioAtual)
	{
		if(strlen($idUsuarioAtual) < 1) {
		
			$idUsuarioAtual = '';
			
		}
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "SELECT id, nome_usuario FROM usuario_atendimento u where id != '$idUsuarioAtual' and ativo = 'S';";
		
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['id'].'">'.$rows['nome_usuario'].'</option>';
				}
			}
			else
			{
				echo '<option value="0">----</option>';	
			}
			
		}
		$mysqli->close();
	}
	
	/************************* SERVIÇOS ***********************************/
	
	public function Cadastrar()
	{
		$this->PegarPost();
		
		$idUser = (isset($_SESSION['idUser'])) ? $_SESSION['idUser'] : '';
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call insere_servico(
										'$this->dataCadastro', -- DataCadastro
										'$this->cliente', -- Cliente
										'$this->atendimento1', -- atendimento
										'$this->atendimento2', -- atendimento
										'$this->paciente', -- paciente
										'$this->cirurgia', -- cirurgia
										'$this->valorServico', -- valor serviço
										'$this->valorDesconto', -- valor desconto
										'$this->recebido', -- recebido
										'$this->nota', -- nota
										'$this->tipoPagamento', -- tipo pagamento
										'$this->observacoes', -- observacoes
                                        '$this->dataPrevisaoDeposito',
										'$idUser');";
		if(
			(strlen($this->dataCadastro) == 10) &&
			(strlen($this->cliente) > 0) &&
			((strlen($this->atendimento1) > 0) || (strlen($this->atendimento2) > 0)) &&
			(strlen($this->paciente) > 3) &&
			(strlen($this->cirurgia) > 0) &&
			(strlen($this->valorServico) > 0) &&
			(strlen($this->tipoPagamento) > 0)
			)
		{
			$result = $mysqli->query($sql);
			if($result)
			{
				//$linha = $result->fetch_assoc();
				/*
				echo '<div class="alert alert-success fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>Serviço cadastrado com sucesso!!!</h4>
						<p>'.$this->paciente.'</p>
					</div>';
				*/
				
				echo '	<script>
						alert("Serviço cadastrado com sucesso!");
						document.location.href = "?s=home";			
						</script>';	
			}
			else
			{
				$erro = $mysqli->errno;
				
				if($erro == 1062)
				{
					$msgErro = "Esse registro já existe! Atualize os dados e tente novamente!";
				}
				else
				{
					$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
					echo '<pre>';
					echo $sql;
					echo '</pre>';
				}
				
				echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
			}
		}
		else
		{
			$msgErro = "Alguns campos obrigatórios, não foram preenchidos. Insira novamente!";
			
			echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
		}
		$mysqli->close();
	}
	
	public function EditarServico() {
	
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		
		//echo $this->dataPrevisaoDeposito;
		
		$sql = "call update_servico
		
			(
				'$this->idServico', 
				'$this->dataCadastro',
				'$this->cliente', 
				'$this->atendimento1', 
				'$this->atendimento2', 
				'$this->paciente', 
				'$this->cirurgia', 
				'$this->valorServico', 
				'$this->valorDesconto', 
				'$this->recebido', 
				'$this->nota', 
				'$this->tipoPagamento', 
				'$this->observacoes', 
				'$this->dataPrevisaoDeposito',
				'$this->dataDepositado');		
		";
		
		//echo $sql;
		
		if(($this->dataCadastro > '2001-01-01') || (strlen($this->dataCadastro) == '10')) {
		
			$result = $mysqli->query($sql);
		
			$idSite = md5($this->idServico);
		
			if($result) {
				echo '	<script>
							alert("Serviço editado com sucesso!");
							document.location.href = "?s=detalhes&id='.$idSite.'";			
							</script>';	
			}
			else {
				echo '	<script>
							alert("Houve um erro ao editar. Verifique as informações e tente novamente!");
							document.location.href = "?s=editarServico&id='.$idSite.'";			
							</script>';
			}
		}
		else {
		
			$msgErro = "Alguns campos obrigatórios, não foram preenchidos. Insira novamente!";
			
			echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
		
		}
		
		/*
		echo '<pre>';
		echo $sql;
		echo '</pre>';
		*/
		
		$mysqli->close();
	
	}
	
	public function ApagarServico()
	{
		$this->PegarPost();	
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "delete from servicos where id = '$this->idServico'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<script>alert("O serviço foi excluído com sucesso!");</script>';
		}
		$mysqli->close();
	}
	
	public function ApagarDespesaMensal()
	{
		$this->PegarPost();	
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "delete from despesas_mensais where id = '$this->idServico'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<script>alert("A despesa foi excluída com sucesso!");</script>';
		}
		$mysqli->close();
	}
	
	public function CadastrarDespesa()
	{
		$this->PegarPost();
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		if(
			(strlen($this->dataDespesa) == '10') && (strlen($this->nomeDespesa) > 3) && (strlen($this->valorDespesa) > 2)
			)
		{
			$sql = "call insere_despesa_mensal('$this->dataDespesa','$this->nomeDespesa','$this->descDespesa','$this->valorDespesa')";
			$result = $mysqli->query($sql);
			if($result)
			{
				echo '<div class="alert alert-success fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>Despesa cadastrada com sucesso!!!</h4>
						<!-- <p></p> -->
					</div>';
			}
			else
			{
				$erro = $mysqli->errno;
				
				if($erro == 1062)
				{
					$msgErro = "Esse registro já existe! Atualize os dados e tente novamente!";
				}
				else
				{
					$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar a despesa. Por favor, tente novamente!";
				}
				
				echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
					
				$mysqli->close();
			}
		}
		else
		{
			$msgErro = "Alguns campos obrigatórios, não foram preenchidos. Insira novamente!";
			
			echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
		}
		
	}
	
	private function TratamentoDiaVencimento($dia)
	{
		if($dia < 1)
		{ 
			$dia = 1; 
		}
		elseif($dia > 31)
		{
			$dia = 31;
		}
		
		return $dia;
	}
	
	public function CadastrarDespesaFixa()
	{
		$this->PegarPost();
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		if(
			(strlen($this->vencimento) > 0) && (strlen($this->nomeDespesa) > 3) && (strlen($this->valorDespesa) > 2)
			)
		{		
			$sql = "call insere_despesa_fixa('$this->vencimento','$this->nomeDespesa','$this->descDespesa','$this->valorDespesa')";
			$result = $mysqli->query($sql);
			if($result)
			{
				echo '<div class="alert alert-success fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>Despesa cadastrada com sucesso!!!</h4>
						<!-- <p></p> -->
					</div>';
			}
			else
			{
				$erro = $mysqli->errno;
				
				if($erro == 1062)
				{
					$msgErro = "Esse registro já existe! Atualize os dados e tente novamente!";
				}
				else
				{
					$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar a despesa. Por favor, tente novamente!";
				}
				
				echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
			}
		}
		else
		{
			$msgErro = "Alguns campos obrigatórios, não foram preenchidos. Insira novamente!";
			
			echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
		}
		$mysqli->close();
	}
	
	public function ListaServico($mes,$ano)
	{
		$periodo = $ano . '-' . $mes;

		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call lista_servicos('$periodo');";
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
					
					$valorServico = $this->converteValorSite($rows['valor_bruto']);
					$valorFinal = $this->converteValorSite($rows['valor_final']);
					$valorDesconto = $this->converteValorSite($rows['valor_desconto']);
					
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
				echo '<a name="final" />';
			}
			else
			{
				echo '<h3 style="margin:20px;">Sem registros</h3>';
			}
		}
		else
		{
            echo $mysqli->error;
			echo 'Erro';
		}
		$mysqli->close();
	}

	public function ListaServicoCartao_OLD($mes,$ano)
	{
		$periodo = $ano . '-' . $mes;
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call lista_cartoes('$periodo');";

		$result = $mysqli->query($sql);
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0)
			{
				echo '
						<form name="frmCartoes" action="?s=checkCartao" method="post">
						<div class="table-responsive">
						<table class="table table-striped table-hover" style="font-size:12px;">
						<thead>
						<tr>
						<th>Data</th>
						<th>Recebido em</th>
						<th>Cliente</th>
						<th>Atendimento</th>
						<th>Paciente</th>
						
						<th style="text-align:center">Valor Final</th>
						
						<th style="text-align:center">Forma de Pagamento</th>
						<th width="2%" style="text-align:center">Conferido</th>
						
						
						</tr>
						</thead>
						<tbody>				
				';
				
			
				
				while($rows = $result->fetch_assoc())
				{
					
					$valorServico = $this->converteValorSite($rows['valor_bruto']);
					$valorFinal = $this->converteValorSite($rows['valor_final']);
					$valorDesconto = $this->converteValorSite($rows['valor_desconto']);
					
					$recebido = ($rows['recebido'] == 'S') ? '<span class="glyphicon glyphicon-ok" style="color:#4cae4c; font-size:16px;"></span>' : '';
					$depositado = ($rows['depositado'] == 'S') ? ' checked ' : '';
					
					$compensado = ($rows['compensado'] == 'N') ? '<span class="glyphicon glyphicon-remove" style="color:#c12e2a; font-size:12px;"></span>' : '';
					
					echo '
						  <input type="hidden" name="idServicoCartao[]" value="' . $rows['id'] . '" />
						  <tr style="vertical-align: middle;">
						  <td>'.$rows['data_servico'].'</td>
						  <td>'.$rows['data_recebido'].'</td>
						  <td valign="middle">' . $rows['cliente'].'</td>
						  <td valign="middle">'.$rows['atendimento'].'</td>
						  <td valign="middle">'.$rows['nome_paciente'].'</td>
						 
						  <td align="center">'.$valorFinal.'</td>
						 
						  <td align="center">'.$rows['tipoPagamento'].' '.$compensado.'</td>
						  <td align="center"><input type="checkbox" id="switch-size" name="depositadoCartao[]" value="'. $rows['id'] . '" '. $depositado . ' data-size="small"></td>
						 
						 
						  </tr>
					
					';
				}	

				
				
				echo '
						</tbody>
						</table>
						<button type="submit" class="btn btn-primary" name="btnSalvarCartao" style="float:right; margin-top:20px; margin-bottom:100px;"><span class="glyphicon glyphicon-floppy-saved"></span>&nbsp; Salvar</button>
						
						</form>
				';
				echo '<a name="final" />';
			}
			else
			{
				echo '<h3 style="margin:20px;">Sem registros</h3>';
			}
		}
		else
		{
			echo 'Erro';
		}
		$mysqli->close();
	}

    public function ListaServicoCartao($data_inicio, $data_fim, $id_cartao)
    {
		$data_inicio = $this->converteData($data_inicio);
		$data_fim = $this->converteData($data_fim);



		if($id_cartao == 0) {


            $id_cartao = implode(",", $this->idsCartoes);
		}
		else {

            $id_cartao = $id_cartao;
		}

        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
        $mysqli->set_charset('utf8');

        $sql = "select
				  s.id,
				  date_format(s.data_servico, '%d/%m/%Y') data_servico,
				  group_concat(u.nome_usuario separator ' / ') atendimento,
				  c.nome_cliente cliente,
				  s.nome_paciente,
				  s.cirurgia,
				  p.valor_bruto,
				  p.valor_final,
				  nota,
				  (select tipo from tipo_pagamento where id = p.id_pagamento) tipoPagamento,
				  p.recebido,
				  date_format(p.data_recebido,'%d/%m/%Y') data_recebido,
				  p.depositado,
				  p.compensado,
				  p.observacoes,
				  s.valor_desconto
			  From servicos s
			  Left Join pagamentos p On p.id_servico = s.id
			  Left Join atendimento_servico a On a.id_servico = s.id
			  Left Join usuario_atendimento u On u.id = a.id_usuario
			  Left Join clientes c On c.id = s.cliente
			  where
			  p.id_pagamento in ($id_cartao)
			  and (p.data_recebido >= '$data_inicio' and p.data_recebido <= '$data_fim') 
			  group by s.id
			  Order by s.data_servico asc, c.nome_cliente asc, s.nome_paciente asc;";

        $result = $mysqli->query($sql);

        if($result)
        {
            $RecordCount = $result->num_rows;

            if($RecordCount > 0)
            {
                echo '
						<form name="frmCartoes" action="?s=checkCartao" method="post">
						<div class="table-responsive">
						<table class="table table-striped table-hover" style="font-size:12px;">
						<thead>
						<tr>
						<th>Data</th>
						<th>Recebido em</th>
						<th>Cliente</th>
						<th>Atendimento</th>
						<th>Paciente</th>
						
						<th style="text-align:center">Valor Final</th>
						
						<th style="text-align:center">Forma de Pagamento</th>
						<th width="2%" style="text-align:center">Conferido</th>
						
						
						</tr>
						</thead>
						<tbody>				
				';



                while($rows = $result->fetch_assoc())
                {

                    $valorServico = $this->converteValorSite($rows['valor_bruto']);
                    $valorFinal = $this->converteValorSite($rows['valor_final']);
                    $valorDesconto = $this->converteValorSite($rows['valor_desconto']);

                    $recebido = ($rows['recebido'] == 'S') ? '<span class="glyphicon glyphicon-ok" style="color:#4cae4c; font-size:16px;"></span>' : '';
                    $depositado = ($rows['depositado'] == 'S') ? ' checked ' : '';

                    $compensado = ($rows['compensado'] == 'N') ? '<span class="glyphicon glyphicon-remove" style="color:#c12e2a; font-size:12px;"></span>' : '';

                    echo '
						  <input type="hidden" name="idServicoCartao[]" value="' . $rows['id'] . '" />
						  <tr style="vertical-align: middle;">
						  <td>'.$rows['data_servico'].'</td>
						  <td>'.$rows['data_recebido'].'</td>
						  <td valign="middle">' . $rows['cliente'].'</td>
						  <td valign="middle">'.$rows['atendimento'].'</td>
						  <td valign="middle">'.$rows['nome_paciente'].'</td>
						 
						  <td align="center">'.$valorFinal.'</td>
						 
						  <td align="center">'.$rows['tipoPagamento'].' '.$compensado.'</td>
						  <td align="center"><input type="checkbox" id="switch-size" name="depositadoCartao[]" value="'. $rows['id'] . '" '. $depositado . ' data-size="small"></td>
						 
						 
						  </tr>
					
					';
                }



                echo '
						</tbody>
						</table>
						<button type="submit" class="btn btn-primary" name="btnSalvarCartao" style="float:right; margin-top:20px; margin-bottom:100px;"><span class="glyphicon glyphicon-floppy-saved"></span>&nbsp; Salvar</button>
						
						</form>
				';
                echo '<a name="final" />';
            }
            else
            {
                echo '<h3 style="margin:20px;">Sem registros</h3>';
            }
        }
        else
        {
            echo 'Erro';
        }
        $mysqli->close();
    }


	
	public function RetornaResumoCartao($mes, $ano) {
		
		$periodo = $ano . '-' . $mes;
		
		$valor = array();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call retorna_soma_resumoCartao('$periodo');";
		$result = $mysqli->query($sql);
		
		if($result) {
			
			$RecordCount = $result->num_rows;
			
			if($RecordCount > 0) {
				
				$rows = $result->fetch_assoc();
				
				$valor['receber'] = $this->converteValorSite($rows['receber']);
				$valor['recebido'] = $this->converteValorSite($rows['recebido']);
			}
			
		}
		
		return $valor;
		
		$mysqli->close();
		
	}
	
	public function DetalhesServico($id,$typeData)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call detalhes_servico('$id');";
		$result = $mysqli->query($sql);	
		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				$this->dadosServico['id'] = $rows['id'];
				$this->dadosServico['dataServico'] = $rows['dataServico'];
				$this->dadosServico['nome_usuario'] = $rows['nome_usuario'];
				$this->dadosServico['atendimento1'] = $rows['atendimento1'];
				$this->dadosServico['nomeAtendimento1'] = $rows['nomeAtendimento1'];
				$this->dadosServico['atendimento2'] = $rows['atendimento2'];
				$this->dadosServico['nomeAtendimento2'] = $rows['nomeAtendimento2'];
				$this->dadosServico['idCliente'] = $rows['idCliente'];
				$this->dadosServico['nome_cliente'] = $rows['nome_cliente'];
				$this->dadosServico['nome_paciente'] = $rows['nome_paciente'];
				$this->dadosServico['cirurgia'] = $rows['cirurgia'];
				$this->dadosServico['valor_desconto'] = $rows['valor_desconto'];
				$this->dadosServico['valor_bruto'] = $rows['valor_bruto'];
				$this->dadosServico['valor_final'] = $rows['valor_final'];
				$this->dadosServico['nota'] = $rows['nota'];
				$this->dadosServico['recebido'] = $rows['recebido'];
				$this->dadosServico['data_recebido'] = $rows['dataRecebimento'];
				$this->dadosServico['idPagamento'] = $rows['idPagamento'];
				$this->dadosServico['tipoPagamento'] = $rows['tipoPagamento'];
				$this->dadosServico['valor_desconto_pagamento'] = $rows['valor_desconto_pagamento'];
				$this->dadosServico['p_desconto'] = $rows['p_desconto'];	
				$this->dadosServico['dataPrevisao'] = $rows['dataPrevisao'];
				$this->dadosServico['depositado'] = $rows['depositado'];
				$this->dadosServico['dataDeposito'] = $rows['dataDeposito'];
				$this->dadosServico['observacoes'] = $rows['observacoes'];
				$this->dadosServico['compensado'] = $rows['compensado'];
				
				
				if($this->dadosServico['nota'] == 'N')
				{
					$this->dadosServico['nota'] = '<span class="glyphicon glyphicon-ok"></span>';
					$this->dadosServico['notaSim'] = 'checked';
					$this->dadosServico['notaNao'] = '';
				}
				else
				{
					$this->dadosServico['nota'] = '<span class="glyphicon glyphicon-remove"></span>';$this->dadosServico['notaSim'] = '';
					$this->dadosServico['notaNao'] = 'checked';		
				}
				
				if($this->dadosServico['recebido'] == 'S')
				{
					$this->dadosServico['recebidoCheck'] = '<span class="glyphicon glyphicon-ok"></span>';	
				}
				else
				{
					$this->dadosServico['recebidoCheck'] = '<span class="glyphicon glyphicon-remove"></span>';		
				}
				
				if($this->dadosServico['depositado'] == 'S')
				{
					//$this->dadosServico['depositado'] = '<span class="glyphicon glyphicon-ok"></span>';
					//$this->dadosServico['depositadoButton'] = '<button class="btn btn-success" disabled>
					#											<span class="glyphicon glyphicon-ok"></span>
					#											&nbsp;Depositado</button>';
					$this->dadosServico['depositadoButton'] = '';
				}
				else
				{
					//$this->dadosServico['depositado'] = '<span class="glyphicon glyphicon-remove"></span>';	
					$this->dadosServico['depositadoButton'] = '
								<div class="col-sm-2 padInput" style="margin:0;">
								<input type="'.$typeData.'" data-provide="datepicker" class="form-control" onkeyup="Formatadata(this,event)" id="txtDataDeposito" name="txtDataDeposito" placeholder="" />
								</div>
								<button type="submit" name="btnDepositado" class="btn btn-default ">
									<span class="glyphicon glyphicon-envelope"></span>
									&nbsp;Marcar como depositado
								</button>
																	';
				}
				
				
				if($this->dadosServico['compensado'] == 'S')
				{
					//$this->dadosServico['depositado'] = '<span class="glyphicon glyphicon-ok"></span>';
					//$this->dadosServico['compensadoButton'] = '<input type="submit" name="btnNCompensado" class="btn btn-default" value="Marcar como Não Compensado" />';
					$this->dadosServico['compensadoButton'] = '<button type="submit" name="btnNCompensado" class="btn btn-default ">
																	<span class="glyphicon glyphicon-remove"></span>
																	&nbsp;Marcar como Não Compensado
																</button>
																	';
				}
				else
				{
					//$this->dadosServico['depositado'] = '<span class="glyphicon glyphicon-remove"></span>';
					$this->dadosServico['compensadoButton'] = '<button class="btn btn-danger" disabled>
																<span class="glyphicon glyphicon-remove"></span>
																&nbsp;Não Compensado</button>';
					//$this->dadosServico['compensadoButton'] = '';					
				}				
			}
		}
		$mysqli->close();
	}
	
	public function MarcarDepositado($id)
	{
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		// Prepared statement - $id é hash md5 vindo da URL
		$stmt = $mysqli->prepare("update pagamentos set depositado = 'S', data_depositado = ? where md5(id_servico) = ?");
		$stmt->bind_param('ss', $this->dataDepositado, $id);
		$result = $stmt->execute();
		if($result)
		{
			echo '	<script>
					<!-- alert("Troca confirmada com Sucesso!"); -->
					document.location.href = "?s=detalhes&id=' . $id . '";			
					</script>';		
		}
		
		$mysqli->close();
	}
	
	public function MarcarRecebido($id)
	{
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		//$sql = "update pagamentos set recebido = 'S', data_recebido = '$this->dataRecebido' where md5(id_servico) = '$id';";
		
		// Stored procedure parametrizada - $id é hash md5 vindo da URL
		$stmt = $mysqli->prepare("call update_recebido(?, ?, ?)");
		$stmt->bind_param('sss', $this->dataRecebido, $this->dataPrevisaoDeposito, $id);
		$result = $stmt->execute();
		if($result)
		{
			echo '	<script>
					<!-- alert("Troca confirmada com Sucesso!"); -->
					document.location.href = "?s=detalhes&id=' . $id . '";			
					</script>';		
		}
		
		$mysqli->close();
	}
	
	
	public function MarcarNaoCompensado($id)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		// Prepared statement - $id é hash md5 vindo da URL
		$stmt = $mysqli->prepare("update pagamentos set compensado = 'N' where md5(id_servico) = ?");
		$stmt->bind_param('s', $id);
		$result = $stmt->execute();
		if($result)
		{
			echo '	<script>
					<!-- alert("Troca confirmada com Sucesso!"); -->
					document.location.href = "?s=detalhes&id=' . $id . '";			
					</script>';		
		}
		
		$mysqli->close();
	}
	
	/************************* DESPESAS ***********************************/
	
	public function ListaDespesas($mes,$ano)
	{
		$dataBusca = $ano . "-" . $mes; //Data para Busca no banco de dados
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select d.id, date_format(d.data_despesa, '%d/%m/%Y') dataDespesa, d.despesa, d.descricao, d.valor_despesa from despesas_mensais d where d.data_despesa like '$dataBusca%' order by d.data_despesa asc";
		//echo $sql;
		
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo "
						<div class=\"table-responsive\">
            			<table class=\"table table-striped table-hover\">
              			<thead>
                		<tr>
						<th>Data</th>
	              		<th>Despesa</th>	
                  		<th>Total</th>
						<th></th>
                		</tr>
              			</thead>
              			<tbody>				
				";
				
				while($rows = $result->fetch_assoc())
				{
					echo '
							<tr>
							<td>'.$rows['dataDespesa'].'</td>
                  			<td><strong>'.$rows['despesa'].'</strong> <br /><i>'.$rows['descricao'].'</i></td>
                 			<td>'.$rows['valor_despesa'].'</td>
							<td>
						 	<form name="delServico" action="index.php?m='.$mes.'&a='.$ano.'" method="post" onsubmit="return ConfirmaDelete();">
						  	<input type="hidden" name="txtIdServico" value="'.$rows['id'].'" />
						  	<input type="submit" class="btn btn-danger btn-xs" name="btnDelDespesa" value="Excluir" />
						  	</form>
						 	</td>
                			</tr>
              				<tr>
					';
				}
				
				echo "
						</tbody>
            			</table>
						</div>
				";
			}
			else
			{
				echo "<h3 style=\"margin:20px;\">Sem despesas cadastradas</h3>";
			}
			
		}
		$mysqli->close();
	}

	public function ListaDespesasFixas()
	{
		//$dataBusca = $ano . "-" . $mes	. "-01"; //Data para Busca no banco de dados
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "SELECT id, despesa, descricao, valor_despesa, dia_vencimento FROM despesas_fixas d order by dia_vencimento asc;";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo "
						<div class=\"table-responsive\" style=\"margin-top:20px;\">
            			<table class=\"table table-striped table-bordered table-hover\">
              			<thead>
                		<tr>
						<th width=\"5%\" style=\"text-align:center;\">Dia de Vencimento</th>
	              		<th>Despesa</th>
						<th>Descrição</th>
                  		<th>Valor</th>
						<th width=\"5%\"></th>
						<th width=\"5%\"></th>
                		</tr>
              			</thead>
              			<tbody>				
				";
				
				while($rows = $result->fetch_assoc())
				{
					echo '
							<tr>
							<td>'.$rows['dia_vencimento'].'</td>
                  			<td>'.$rows['despesa'].'</td>
							<td>'.$rows['descricao'].'</td>
                 			<td>'.$rows['valor_despesa'].'</td>
							<td><a href="?s=despesas&e=S&id='.$rows['id'].'"><button class="btn btn-success btn-xs">Editar</button></a></td>
							<td align="center">
						 		<form name="delServico" action="?s=despesas" method="post" onsubmit="return ConfirmaDelete();">
						  		<input type="hidden" name="txtIdDespesaFixa" value="'.$rows['id'].'" />
						  		<button type="submit" class="btn btn-danger btn-xs" name="btnDelDespesa"><span class="glyphicon glyphicon-trash"></span>&nbsp&nbspExcluir</button>
						 		</form>
						  	</td>						
							</tr>
					';
				}
				
				echo "
						</tbody>
            			</table>
						</div>
				";
			}
			else
			{
				echo "Sem despesas cadastradas";
			}			
		}
		$mysqli->close();		
	}
	
	public function RetornaDadosDespesa($id)
	{
		$dados = array();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, despesa, descricao, valor_despesa, dia_vencimento from despesas_fixas where id = '$id'";
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				$dados['id'] = $rows['id'];
				$dados['despesa'] = $rows['despesa'];
				$dados['descricao'] = $rows['descricao'];	
				$dados['valor_despesa'] = $rows['valor_despesa'];
				$dados['dia_vencimento'] = $rows['dia_vencimento'];	
			}
		}		
		return $dados;
		
		$mysqli->close();
	}
	
	public function EditarDespesaFixa()
    {
        $this->PegarPost();
        
        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		//$sql = "call editar_formaPagamento('$this->idPagamento','$this->formaPagamento','$this->descFormaPagamento','$this->pDescontoFormaPagamento');";
		$sql = "update despesas_fixas set 
						despesa = '$this->nomeDespesa', 
						descricao = '$this->descDespesa', 
						valor_despesa = '$this->valorDespesa',
						dia_vencimento = '$this->vencimento'						
						where id = '$this->idDespesaFixa'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<div class="alert alert-success fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>Despesa alterada com sucesso.</h4>
					<!-- <p></p> -->
				</div>';
        }
        else
        {
			
				$msgErro = "Desculpe! Ocorreu um erro na hora de alterar. Por favor, tente novamente!";
            
			
			echo '<div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>'.$msgErro.'</h4>
				</div>';
				
				echo $sql;
		}
		
		$mysqli->close();
    }
	
	public function ApagarDespesaFixa()
	{
		$this->PegarPost();	
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "delete from despesas_fixas where id = '$this->idApagaDespesaFixa'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<script>alert("A despesa foi excluída com sucesso!");</script>';
		}
		$mysqli->close();
	}

	public function ListaDespesasFixasFormulario()
	{		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, dia_vencimento Vencimento, d.despesa, d.descricao, d.valor_despesa from despesas_fixas d where d.ativo = 'S'";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo "
						<div class=\"table-responsive\">
            			<table class=\"table table-striped table-bordered table-hover\">
              			<thead>
                		<tr>
						<th width=\"1%\"></th>
	              		<th>Despesa</th>	
                  		<th>Descrição</th>
						<th width=\"5%\" align=\"center\">Vencimento</th>
						<th width=\"10%\" align=\"center\">Valor</th>
                		</tr>
              			</thead>
              			<tbody>				
				";
				
				while($rows = $result->fetch_assoc())
				{
					echo "
							<tr>
							<td><input type=\"checkbox\" name=\"chkDespesa[]\" value=\"".$rows['id']."\" /></td>
                  			<td>".$rows['despesa']."</td>
							<td>".$rows['descricao']."</td>
							<td align=\"center\">".$rows['Vencimento']."</td>
                 			<td align=\"center\">".$rows['valor_despesa']."</td>
                			</tr>
              				<tr>
					";
				}
				
				echo "
						</tbody>
            			</table>
						</div>
				";
			}
			else
			{
				echo "Sem despesas cadastradas";
			}
			
		}
		$mysqli->close();		
	}
	
	private function ImportarDespesa($id,$ano,$mes)
	{		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call importa_despesa('$id','$ano','$mes');";
		$result = $mysqli->query($sql);
		if(!$result)
		{
			$erro = $mysqli->errno;
			
			if($erro == 1062)
			{
				$msgErro = "Essa despesa já foi importada! Atualize os dados e tente novamente!";
			}
			else
			{
				$msgErro = "Desculpe! Ocorreu um erro na hora de importar. Por favor, tente novamente!";
			}
			
			echo '<div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<p>'.$msgErro.'</p>
				</div>';
		}	
		$mysqli->close();
	}
	
	public function ImportacaoDespesas()
	{
		$this->PegarPost();
		
		$i = 0;
		$n = count($this->idDespesaFixa);
		
		while($i < $n)
		{
			$this->ImportarDespesa($this->idDespesaFixa[$i],$this->anoImport,$this->mesImport);
			$i++;
		}
	}
	
	public function SomaDespesaMes($mes,$ano)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call soma_despesa_mes('$ano','$mes');";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				$this->totalDespesaMensal = $rows['total'];
			}
			else
			{
				$this->totalDespesaMensal = '0,00';
			}
		}
		else
		{
			echo $mysqli->errno;
		}
		
		if(strlen($this->totalDespesaMensal) < 1)
		{
			$this->totalDespesaMensal = '0,00';
		}
		return $this->totalDespesaMensal;	
		
		$mysqli->close();
	}
    
    /************************* FORMA DE PAGAMENTO ***********************************/
    
    public function CadastrarFormaPagamento()
    {
        $this->PegarPost();
        
        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
//		$sql = "call insere_formaPagamento('$this->formaPagamento','$this->descFormaPagamento','$this->pDescontoFormaPagamento');";

		$sql = "insert into tipo_pagamento (tipo, dias, descricao, p_desconto) 
				Values (upper('$this->formaPagamento'), $this->diasPagamento, '$this->descFormaPagamento', '$this->pDescontoFormaPagamento');";

		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<div class="alert alert-success fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>Forma de pagamento cadastrada com sucesso.</h4>
					<!-- <p></p> -->
				</div>';
        }
        else
        {
			$erro = $mysqli->errno;
			
			if($erro == 1062)
			{
				$msgErro = "Esse registro já existe! Atualize os dados e tente novamente!";
			}
			else
			{
				$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
                echo $sql;
			}
			
			echo '<div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>'.$msgErro.'</h4>
				</div>';
		}
		$mysqli->close();
    }
    
    public function EditarFormaPagamento()
    {
        $this->PegarPost();

        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');

		$this->pDescontoFormaPagamento = $this->converteValorBanco($this->pDescontoFormaPagamento);
		
//		$sql = "call editar_formaPagamento('$this->idPagamento','$this->formaPagamentoEdit','$this->descFormaPagamento','$this->pDescontoFormaPagamento');";

		$sql = "update tipo_pagamento set tipo = '$this->formaPagamentoEdit', 
				dias = $this->diasPagamento, 
				descricao = '$this->descFormaPagamento', 
				p_desconto = '$this->pDescontoFormaPagamento' 
				where id = $this->idPagamento;";

		$result = $mysqli->query($sql);

		if($result)
		{
			echo '<div class="alert alert-success fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>Forma de pagamento alterada com sucesso.</h4>
				</div>';
        }
        else
        {
			
				$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
            
			
			echo '<div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>'.$msgErro.'</h4>
				</div>';
				
				echo $sql;
		}
		$mysqli->close();
    }
    
    public function RetornaDadosFormaPagamento($id)
    {
		$dados = array();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, tipo, dias, descricao, p_desconto from tipo_pagamento where id = '$id'";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				$dados['id'] = $rows['id'];
				$dados['tipo'] = $rows['tipo'];
				$dados['dias'] = $rows['dias'];
				$dados['descricao'] = $rows['descricao'];
				$dados['p_desconto'] = $rows['p_desconto'];
				
			}
		}
		
		return $dados;
		$mysqli->close();
	}
	
	public function ListaFormaPagamento()
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, tipo, dias, descricao, p_desconto from tipo_pagamento order by id asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
						<div class="table-responsive" style="margin-top:20px;">
						<table class="table table-striped table-hover table-bordered" style="font-size:12px;">
						<thead>
						<tr>
						<!-- <th></th> -->
						<th>Forma de Pagamento</th>
						<th>Descrição</th>
						<th style="text-align:center" width="10%">(%) do Desconto</th>
						<th style="text-align:center" width="10%">Dias para recebimento</th>
						<th style="text-align:center" width="5%"></th>
						<th style="text-align:center" width="5%"></th>
						</tr>
						</thead>
						<tbody>				
				';
				
				while($rows = $result->fetch_assoc())
				{
					echo '
							<tr>
							<!-- <td></td> -->
							<td>'.$rows['tipo'].'</td>
							<td>'.$rows['descricao'].'</td>
							<td align="center">'.$rows['p_desconto'].'</td>
							<td align="center">'.$rows['dias'].'</td>
							<td style="text-align:center"><a href="?s=descontos&e=S&id='.$rows['id'].'"><button class="btn btn-success btn-xs">Editar</button></a></td>';

					if($this->verificaPagamentoUsado($rows['id']) == 0) {

                        echo '
							<td style="text-align:center">
								<form name="frmExcluirPagamento" action="?s=descontos" method="post" onsubmit="return ConfirmaDelete();">
								<input type="hidden" name="txtIdPagamento" value="'.$rows['id'].'" />
								<input type="submit" class="btn btn-danger btn-xs" name="btnExcluirFormaPagamento" value="Excluir">
								</form>
							</td>
							
							</tr>
						';
					}
					else {


                        echo '
							<td>
								
							</td>
							
							</tr>
						';
					}



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
		$mysqli->close();
	}
	
	public function ListaFormaPagamentoForm()
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, tipo from tipo_pagamento order by tipo asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				
				
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['id'].'">'.$rows['tipo'].'</option>';
				}
				
				
				
			}
			else
			{
				echo 'Sem registros';
			}
		}
		$mysqli->close();
	}

	public function ListaFormaPagamentoFormEdit($pagamento)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, tipo from tipo_pagamento where id != '$pagamento' order by tipo asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{				
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['id'].'">'.$rows['tipo'].'</option>';
				}
				
				echo '<option value="0"> --- </option>';				
			}
			else
			{
				echo 'Sem registros';
			}
		}
		$mysqli->close();
	}
	
	public function ListaFormaPagamentoFormRelat($pagamento)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select tipo from tipo_pagamento where tipo != '$pagamento' order by tipo asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{				
				if(strlen($pagamento) > 0) {
				
				echo '<option value="'.$pagamento.'">'.$pagamento.'</option>';
				echo '<option value="">TODOS</option>';
				
				}
				else {
					
					echo '<option value="">TODOS</option>';
				}				
				
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['tipo'].'">'.$rows['tipo'].'</option>';
				}
							
			}
			else
			{
				//echo 'Sem registros';
			}
		}
		$mysqli->close();
	}
	
	public function ApagarFormaPagamento()
	{
		$this->PegarPost();	
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "delete from tipo_pagamento where id = '$this->idPagamento'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<script>alert("O tipo de pagamento foi excluído com sucesso!");</script>';
		}
		$mysqli->close();
	}
	
	/************************* CLIENTES ***********************************/
	
	public function CadastrarCliente()
	{
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		if(strlen($this->nomeCliente) > 1)
		{
			$sql = "insert into clientes (nome_cliente,descricao) Values (upper('$this->nomeCliente'),'$this->descCliente')";
			$result = $mysqli->query($sql);
			if($result)
			{
				echo '<div class="alert alert-success fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>O cliente foi cadastrado com sucesso!</h4>
						<!-- <p></p> -->
					</div>';
			}
			else
			{
				$erro = $mysqli->errno;
				
				if($erro == 1062)
				{
					$msgErro = "Esse registro já existe! Atualize os dados e tente novamente!";
				}
				else
				{
					$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
					echo $sql;
				}
				
				echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
			}	
		}
		else
		{
			$msgErro = "Alguns campos obrigatórios, não foram preenchidos. Insira novamente!";
			
			echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
		}
		
		$mysqli->close();
	}
	
	public function RetornaDadosCliente($id)
    {
		$dados = array();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, nome_cliente, descricao from clientes where id = '$id'";
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				$dados['id'] = $rows['id'];
				$dados['nome_cliente'] = $rows['nome_cliente'];
				$dados['descricao'] = $rows['descricao'];				
			}
		}		
		return $dados;
		
		$mysqli->close();
	}
	
	public function ListaClientes()
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, nome_cliente, descricao from clientes order by nome_cliente asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
						<div class="table-responsive" style="margin-top:20px;">
						<table class="table table-striped table-hover" style="font-size:12px;">
						<thead>
						<tr>
						<!-- <th></th> -->
						<th>Cliente</th>
						<th>Descrição</th>
						<th style="text-align:center" width="5%"></th>
						<th style="text-align:center" width="5%"></th>
						</tr>
						</thead>
						<tbody>				
				';
				
				while($rows = $result->fetch_assoc())
				{
					echo '
							<tr>
							<!-- <td></td> -->
							<td>'.$rows['nome_cliente'].'</td>
							<td>'.$rows['descricao'].'</td>
							<td><a href="?s=clientes&e=S&id='.$rows['id'].'"><button class="btn btn-success btn-xs">Editar</button></a></td>
							<td>
								<form name="frmExcluirCliente" action="?s=clientes" method="post" onsubmit="return ConfirmaDelete();">
								<input type="hidden" name="txtIdCliente" value="'.$rows['id'].'" />
								<input type="submit" class="btn btn-danger btn-xs" name="btnExcluirCliente" value="Excluir">
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
		$mysqli->close();
	}
	
	public function EditarCliente()
    {
        $this->PegarPost();
        
        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		//$sql = "call editar_formaPagamento('$this->idPagamento','$this->formaPagamento','$this->descFormaPagamento','$this->pDescontoFormaPagamento');";
		$sql = "update clientes set nome_cliente = upper('$this->nomeCliente'), descricao = '$this->descCliente' where id = '$this->idCliente'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<div class="alert alert-success fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>Cliente alterado com sucesso.</h4>
					<!-- <p></p> -->
				</div>';
        }
        else
        {
			
				$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
            
			
			echo '<div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>'.$msgErro.'</h4>
				</div>';
				
				echo $sql;
		}
		
		$mysqli->close();
    }
	
	public function ListaClientesForm()
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, nome_cliente from clientes order by nome_cliente asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{			
				
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['id'].'">'.$rows['nome_cliente'].'</option>';
				}				
				
			}
			else
			{
				echo '<option value=""></option>';
			}
		}
		
		$mysqli->close();
	}
	
	public function ListaCirurgiaForm($cirurgia)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select distinct cirurgia from servicos where cirurgia not like '$cirurgia' order by cirurgia asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			
			if(strlen($cirurgia) > 0) {
				
				echo '<option value="'.$cirurgia.'">'.$cirurgia.'</option>';
				echo '<option value="">TODOS</option>';
				
			}
			else {
				
				echo '<option value="">TODOS</option>';
			}
			
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{				
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['cirurgia'].'">'.$rows['cirurgia'].'</option>';
				}				
				
			}
			else
			{
				echo '<option value=""></option>';
			}
		}
		
		$mysqli->close();
	}
	
	public function ListaClientesFormEdit($cliente)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, nome_cliente from clientes where id != '$cliente' order by nome_cliente asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{			
				
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['id'].'">'.$rows['nome_cliente'].'</option>';
				}				
				
			}
			else
			{
				echo '<option value=""></option>';
			}
		}
		
		$mysqli->close();
	}
	
	public function ApagarCliente()
	{
		$this->PegarPost();	
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "delete from clientes where id = '$this->idCliente'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<script>alert("O cliente foi excluído com sucesso!");</script>';
		}
		
		$mysqli->close();
	}
	
	/************************* USUÁRIOS ***********************************/
	
	public function RetornaUsuariosAtendimento()
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "SELECT id, nome_usuario FROM usuario_atendimento u where ativo = 'S';";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				while($rows = $result->fetch_assoc())
				{
					echo '<option value="'.$rows['id'].'">'.$rows['nome_usuario'].'</option>';
				}
			}			
		}
		
		$mysqli->close();
	}
	
	public function CadastrarUsuario()
	{
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		if(
			(strlen($this->nomeUsuario) > 3) && 
			((strlen($this->loginUsuario) > 3) && (strlen($this->loginUsuario) <= 16)) &&
			((strlen($this->senhaUsuarioOriginal) > 3) && (strlen($this->senhaUsuarioOriginal) <= 8))
			)
		{
			
			if($this->senhaUsuarioOriginal != $this->senhaUsuarioOriginal2)
			{
				echo '	<script>
						alert("As senhas digitadas não são iguais! Tente novamente...");
						document.location.href = "?s=usuarios";			
						</script>';
			}
			else
			{
				$sql = "call insere_usuario('$this->nomeUsuario','$this->sobreNomeUsuario','$this->loginUsuario','$this->senhaUsuario')";
				$result = $mysqli->query($sql);
				
				if($result)
				{
					echo '<div class="alert alert-success fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
							<h4>O usuário foi cadastrado com sucesso!</h4>
							<!-- <p></p> -->
						</div>';
				}
				else
				{
					$erro = $mysqli->errno;
					
					if($erro == 1062)
					{
						$msgErro = "Esse registro já existe! Atualize os dados e tente novamente!";
					}
					else
					{
						$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
						echo $sql;
					}
					
					echo '<div class="alert alert-danger fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
							<h4>'.$msgErro.'</h4>
						</div>';
				}
			
			}
			
			
		}
		else
		{
			if(strlen($this->nomeUsuario) <= 3)
			{
				$msgErro = "Insira o nome do usuário!";
			}
			elseif((strlen($this->loginUsuario) <= 3) || (strlen($this->loginUsuario) > 16))
			{
				$msgErro = "O login deve ter entre 4 e 16 caracteres";
			}
			elseif((strlen($this->senhaUsuarioOriginal) <= 3) || (strlen($this->senhaUsuarioOriginal) > 8))
			{
				$msgErro = "O senha deve ter entre 4 e 8 caracteres";
			}
			else
			{
				$msgErro = "";
			}			
			
			echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
		
		}
		
		$mysqli->close();
		
	}
	
	public function ListaUsuarios()
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, concat(nome_usuario,' ',sobrenome_usuario) nome, login_usuario from usuario_atendimento where ativo = 'S' order by id asc";
		$result = $mysqli->query($sql);
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				echo '
						<div class="table-responsive" style="margin-top:20px;">
						<table class="table table-striped table-hover table-bordered" style="font-size:12px;">
						<thead>
						<tr>
						<!-- <th></th> -->
						<th>Nome</th>
						<th style="text-align:center" width="10%">Login</th>
						<th style="text-align:center" width="5%"></th>
						<th style="text-align:center" width="5%"></th>
						</tr>
						</thead>
						<tbody>				
				';
				
				while($rows = $result->fetch_assoc())
				{
					echo '
							<tr>
							<!-- <td></td> -->
							<td>'.$rows['nome'].'</td>
							<td align="center">'.$rows['login_usuario'].'</td>
							<td><a href="?s=usuarios&e=S&id='.$rows['id'].'"><button class="btn btn-success btn-xs">Editar</button></a></td>
							<td>
								<form name="frmExcluirUsuario" action="?s=usuarios" method="post" onsubmit="return ConfirmaDelete();">
								<input type="hidden" name="txtidUsuario" value="'.$rows['id'].'" />
								<input type="submit" class="btn btn-danger btn-xs" name="btnExcluirUsuario" value="Excluir">
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
				echo 'Sem Usuários Cadastrados';
			}
		}
		
		$mysqli->close();
	}	
	
	public function RetornaDadosUsuario($id)
    {
		$dados = array();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "select id, nome_usuario, sobrenome_usuario, login_usuario from usuario_atendimento where id = '$id'";
		$result = $mysqli->query($sql);		
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				$this->dadosUsuario['id'] = $rows['id'];
				$this->dadosUsuario['nome_usuario'] = $rows['nome_usuario'];
				$this->dadosUsuario['sobrenome_usuario'] = $rows['sobrenome_usuario'];
				$this->dadosUsuario['login_usuario'] = $rows['login_usuario'];		
			}
			else
			{
				$this->dadosUsuario['id'] = '';
				$this->dadosUsuario['nome_usuario'] = '';
				$this->dadosUsuario['sobrenome_usuario'] = '';
				$this->dadosUsuario['login_usuario'] = '';
			}
		}
		else
		{
			$this->dadosUsuario['id'] = '';
			$this->dadosUsuario['nome_usuario'] = '';
			$this->dadosUsuario['sobrenome_usuario'] = '';
			$this->dadosUsuario['login_usuario'] = '';
		}
		
		$mysqli->close();
	}
	
	public function EditarUsuario()
    {
        $this->PegarPost();
        
        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		//$sql = "call editar_formaPagamento('$this->idPagamento','$this->formaPagamento','$this->descFormaPagamento','$this->pDescontoFormaPagamento');";
		$sql = "update usuario_atendimento set 
					nome_usuario = '$this->nomeUsuario', 
					sobrenome_usuario = '$this->sobreNomeUsuario',
					login_usuario = '$this->loginUsuario'					
					where id = '$this->idUsuario'";
		$result = $mysqli->query($sql);
		if($result)
		{
			echo '<div class="alert alert-success fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>Usuário alterado com sucesso.</h4>
					<!-- <p></p> -->
				</div>';
        }
        else
        {
			
				$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
            
			
			echo '<div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>'.$msgErro.'</h4>
				</div>';
				
				//echo $sql;
		}
		
		$mysqli->close();
    }
	
	public function AlterarSenhaUsuario()
	{
		$this->PegarPost();
		
		if($this->novaSenha1 == $this->novaSenha2)
		{
			$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
			$mysqli->set_charset('utf8');
			
			$sql = "update usuario_atendimento set senha_usuario = md5('$this->novaSenha') where id = '$this->idUsuario';";
			$result = $mysqli->query($sql);
		}
		else
		{
			echo '<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
						<h4>'.$msgErro.'</h4>
					</div>';
		}	
		
	}
	
	public function ApagarUsuario()
	{
		$this->PegarPost();			
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');

		$sql = "update usuario_atendimento set ativo = 'N' where id = '$this->idUsuario'";
		//$sql = "delete from usuario_atendimento where id = '$this->idUsuario'";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			echo '<script>alert("O usuário foi excluído com sucesso!");</script>';
		}
		
		$mysqli->close();
		
	}
	
	/************************* SOLUCAO COMPENSADO ***********************************/
	
	public function CadastrarSolucao($id)
	{
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call insere_solucao_compensado(
					'$id','$this->motivo','$this->bancoCliente','$this->nCheque','$this->solucao','$this->dataSolucao','$this->usuarioSolucao')";
		$result = $mysqli->query($sql);
		
		if($result)
		{
			/*
			
			echo '<div class="alert alert-success fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>O usuário foi cadastrado com sucesso!</h4>
					<!-- <p></p> -->
				</div>';
				
			*/
			
			echo '	<script>
					<!-- alert("Troca confirmada com Sucesso!"); -->
					document.location.href = "?s=detalhes&id=' . $id . '";			
					</script>';	
        }
        else
        {
			$erro = $mysqli->errno;
			
			if($erro == 1062)
			{
				$msgErro = "Esse registro já existe! Atualize os dados e tente novamente!";
			}
			else
			{
				$msgErro = "Desculpe! Ocorreu um erro na hora de cadastrar o serviço. Por favor, tente novamente!";
                echo $sql;
			}
			
			echo '<div class="alert alert-danger fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>'.$msgErro.'</h4>
				</div>';
		}
		
		$mysqli->close();
		
	}
	
	public function RetornaDadosCheque($id)
	{
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		// Prepared statement - $id é hash md5 vindo da URL
		$stmt = $mysqli->prepare("select c.id, c.motivo, c.banco, c.n_cheque, c.solucao, date_format(c.data_solucao,'%d/%m/%Y') data_solucao, c.resp_usuario, u.nome_usuario
				from dados_cheque c
				Left Join usuario_atendimento u On u.id = c.resp_usuario
				where md5(c.id_servico) = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$result = $stmt->get_result();

		if($result)
		{
			//$this->dadosCheque['count'] = 0;
			$this->dadosCheque['id'] = '';
			$this->dadosCheque['motivo'] = '';
			$this->dadosCheque['banco'] = '';
			$this->dadosCheque['n_cheque'] = '';
			$this->dadosCheque['solucao'] = '';
			$this->dadosCheque['data_solucao'] = '';
			$this->dadosCheque['resp_usuario'] = '';
			$this->dadosCheque['nome_usuario'] = '';
			
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				$this->dadosCheque['count'] = $RecordCount;
				$this->dadosCheque['id'] = $rows['id'];
				$this->dadosCheque['motivo'] = $rows['motivo'];
				$this->dadosCheque['banco'] = $rows['banco'];
				$this->dadosCheque['n_cheque'] = $rows['n_cheque'];
				$this->dadosCheque['solucao'] = $rows['solucao'];
				$this->dadosCheque['data_solucao'] = $rows['data_solucao'];
				$this->dadosCheque['resp_usuario'] = $rows['resp_usuario'];
				$this->dadosCheque['nome_usuario'] = $rows['nome_usuario'];
				
			}
			else
			{
				$this->dadosCheque['count'] = 0;
				$this->dadosCheque['id'] = '';
				$this->dadosCheque['motivo'] = '';
				$this->dadosCheque['banco'] = '';
				$this->dadosCheque['n_cheque'] = '';
				$this->dadosCheque['solucao'] = '';
				$this->dadosCheque['data_solucao'] = '';
				$this->dadosCheque['resp_usuario'] = '';
				$this->dadosCheque['nome_usuario'] = '';
			}
		}

		$mysqli->close();		
	}
	
	public function EditarSolucao($id)
	{
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call editar_solucao_compensado(
					'$id','$this->motivo','$this->bancoCliente','$this->nCheque','$this->solucao','$this->dataSolucao','$this->usuarioSolucao')";
		
		$result = $mysqli->query($sql);		
		if($result)
		{
			echo '	<script>
					<!-- alert("Troca confirmada com Sucesso!"); -->
					document.location.href = "?s=detalhes&id=' . $id . '";			
					</script>';		
		}
		
		$mysqli->close();
	}
	
	public function EditarDepositoCartao() {
		
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
	
		$i = 0;
		$n = count($this->idServicoCartao);
		
		while($i < $n) {
			
			
			$idServico = $this->idServicoCartao[$i];
			$valorCheck = $this->VerificaStatusCartao($idServico);
			
			$sql = "update pagamentos set depositado = '$valorCheck' where id_servico = '$idServico'";
			$result = $mysqli->query($sql);

			$i++;
			
		}
		
		
		
	}
	
	private function VerificaStatusCartao($valor) {
		
		if(in_array($valor,$this->depositadoCartao)) {
			
			return 'S';
			
		}
		else {
			
			return 'N';
			
		}
		
	}
	
	public function InsereVisitas($usuario, $ip, $sistema, $pagina) {
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call insere_visita('$usuario','$ip','$sistema','$pagina')";
		$result = $mysqli->query($sql);
	
	}
	
	public function VerificarDispositivo($sistema) {
	
		if(substr_count($sistema,'Mobile') > 0) {
			
			return 'Mobile';
		
		}
		else {
			
			return 'Desktop';
		
		}
	
	}

	public function RetornaTotalRecebido($data_inicio, $data_fim, $id_cartao) {

		$totalRecebido = 0.00;

		$data_inicio = $this->converteData($data_inicio);
		$data_fim = $this->converteData($data_fim);

        $listCartoes = implode(",", $this->idsCartoes);

		if($id_cartao == 0) {

			$id_cartao = $listCartoes;
		}


        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
        $mysqli->set_charset('utf8');

        $sql = "select 
					sum(p.valor_final) totalReceber 
				From servicos s 
				Left Join pagamentos p On p.id_servico = s.id
				where (p.data_recebido >= '$data_inicio' and p.data_recebido <= '$data_fim')
				and p.depositado = 'S'
				and p.id_pagamento in ($id_cartao);";
        $result = $mysqli->query($sql);

        if($result) {

			$row = $result->fetch_assoc();

			$totalRecebido = $this->converteValorSite($row['totalReceber']);
		}

		return $totalRecebido;
	}

    public function RetornaTotalReceber($data_inicio, $data_fim, $id_cartao) {

        $totalRecebido = 0.00;

        $data_inicio = $this->converteData($data_inicio);
        $data_fim = $this->converteData($data_fim);

        $listCartoes = implode(",", $this->idsCartoes);

        if($id_cartao == 0) {

            $id_cartao = $listCartoes;
        }


        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
        $mysqli->set_charset('utf8');

        $sql = "select 
					sum(p.valor_final) totalReceber 
				From servicos s 
				Left Join pagamentos p On p.id_servico = s.id
				where (p.data_recebido >= '$data_inicio' and p.data_recebido <= '$data_fim') 
				and p.depositado = 'N'
				and p.id_pagamento in ($id_cartao);";
        $result = $mysqli->query($sql);

        if($result) {

            $row = $result->fetch_assoc();

            $totalRecebido = $this->converteValorSite($row['totalReceber']);
        }

        return $totalRecebido;
    }

	public function listaCartoesSelect() {

		$cartoes = array();

        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
        $mysqli->set_charset('utf8');

		$sql = "select id, tipo from tipo_pagamento where id in (3,4,7,8,9,10,11,12,14,15) order by id asc;";
		$result = $mysqli->query($sql);

		if($result) {

			while ($row = $result->fetch_assoc()) {

				$cartoes[] = $row;
			}
		}

		return $cartoes;
	}

	public function getNomeCartao($id_cartao) {

		$nome_cartao = '';

		if($id_cartao > 0) {

            $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
            $mysqli->set_charset('utf8');

            $sql = "select tipo from tipo_pagamento where id = $id_cartao;";
            $result = $mysqli->query($sql);

            if($result) {

                $row = $result->fetch_assoc();

                $nome_cartao = $row['tipo'];
            }
		}
		else {

			$nome_cartao = 'Todos';
		}

		return $nome_cartao;
	}

	public function verificaPagamentoUsado($id_pagamento) {

		$total = 0;

        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
        $mysqli->set_charset('utf8');

        $sql = "select count(*) total from pagamentos where id_pagamento = $id_pagamento;";
        $result = $mysqli->query($sql);

        if($result) {

        	$row = $result->fetch_assoc();

        	$total = $row['total'];
		}

		return $total;
	}


	public function apagarPagamento($id) {

        $mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
        $mysqli->set_charset('utf8');

        // Prepared statement - $id vem de POST (ver script/apagarPagamento.php)
        $stmt = $mysqli->prepare("delete from tipo_pagamento where id = ?");
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();

        if($result) {

        	return true;
		}

		return false;
	}

	
}
?>
