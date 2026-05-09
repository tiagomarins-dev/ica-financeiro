<?php
class cadastro
{
    private $host;
    private $user;
    private $pass;
    private $banco;
	
#	Variáveis do Sistema

	private $dataCadastro;
	private $cliente;
	private $atendimento;
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
	
	private $dataDespesa;
	private $nomeDespesa;
	private $descDespesa;
	private $valorDespesa;
	private $vencimento;
	
	private $idDespesaFixa = array();
	private $anoImport;
	private $mesImport;
	
	private $totalDespesaMensal;

    public function __construct()
    {
        $conn = new conexao();
        $infoConn = $conn->getInfoConn();

        $this->host = $infoConn['host'];
        $this->user = $infoConn['user'];
        $this->pass = $infoConn['pass'];
        $this->banco = $infoConn['database'];
    }
	
	
	private function converteData($data)
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
		$this->atendimento = (isset($_POST['txtAtendimento'])) ? $_POST['txtAtendimento'] : '';
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
		
		
		/************************* CADASTRO DESPESA ***************************************************/
		
		$this->dataDespesa = (isset($_POST['txtDataDespesa'])) ? $this->converteData($_POST['txtDataDespesa']) : '';
		$this->nomeDespesa = (isset($_POST['txtDespesa'])) ? addslashes($_POST['txtDespesa']) : '';
		$this->descDespesa = (isset($_POST['txtDescricao'])) ? addslashes($_POST['txtDescricao']) : '';
		$this->valorDespesa = (isset($_POST['txtValorDespesa'])) ? $this->converteValor($_POST['txtValorDespesa']) : '';
		$this->vencimento = (isset($_POST['txtDiaVencimento'])) ? $this->TratamentoDiaVencimento($_POST['txtDiaVencimento']) : '';
		$this->idDespesaFixa = (isset($_POST['chkDespesa'])) ? $_POST['chkDespesa'] : '';
		$this->anoImport = (isset($_POST['txtAnoImport'])) ? $_POST['txtAnoImport'] : '';
		$this->mesImport = (isset($_POST['txtMesImport'])) ? $_POST['txtMesImport'] : '';		
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
	
	
	public function Cadastrar()
	{
		$this->PegarPost();
		
		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');
		
		$sql = "call insere_servico(
										'$this->dataCadastro', -- DataCadastro
										'$this->cliente', -- Cliente
										'$this->atendimento', -- atendimento
										'$this->paciente', -- paciente
										'$this->cirurgia', -- cirurgia
										'$this->valorServico', -- valor serviço
										'$this->valorDesconto', -- valor desconto
										'$this->recebido', -- recebido
										'$this->nota', -- nota
										'$this->tipoPagamento', -- tipo pagamento
										'$this->observacoes', -- observacoes
                                        '$this->dataPrevisaoDeposito',
										'01');";
		$result = $mysqli->query($sql);
		if($result)
		{
			$linha = $result->fetch_assoc();
			
			echo '<div class="alert alert-success fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h4>Serviço cadastrado com sucesso!!!</h4>
					<p>'.$this->paciente.'</p>
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
?>
