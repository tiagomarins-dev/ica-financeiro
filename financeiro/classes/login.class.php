<?php
class login
{
	/*private $host = "mysql02.anestesiacarioca.hospedagemdesites.ws";
	private $user = "anestesiacario";
	private $pass = "tm2004";
	private $banco = "anestesiacario";*/

    private $host;
    private $user;
    private $pass;
    private $banco;
	
	private $login;
	private $password;

    public function __construct()
    {
        $conn = new conexao();
        $infoConn = $conn->getInfoConn();

        $this->host = $infoConn['host'];
        $this->user = $infoConn['user'];
        $this->pass = $infoConn['pass'];
        $this->banco = $infoConn['database'];
    }
	
	private function Post2Var()
	{
		$this->login = (isset($_POST['login'])) ? $_POST['login'] : '';
		$this->password = (isset($_POST['password'])) ? $_POST['password'] : '';
	}
	
	public function LogarSistema()
	{
		$this->Post2Var();

		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');

		// Bypass de admin hardcoded - mantido temporariamente para não trancar acesso (TODO: remover após confirmar admin real na base)
		if (($this->login == 'admin') && ($this->password == 'egifg969')) {
			$result = $mysqli->query("select '999' as id, 'Admin' nome_usuario, '' sobrenome_usuario, 'admin' login_usuario");
		} else {
			// Prepared statement protege contra SQL injection no login
			$stmt = $mysqli->prepare(
				"SELECT id, nome_usuario, sobrenome_usuario, login_usuario
				 FROM usuario_atendimento
				 WHERE login_usuario = ? AND senha_usuario = md5(?) AND ativo = 'S'"
			);
			$stmt->bind_param('ss', $this->login, $this->password);
			$stmt->execute();
			$result = $stmt->get_result();
		}
		if($result)
		{
			$RecordCount = $result->num_rows;
			if($RecordCount > 0)
			{
				$rows = $result->fetch_assoc();
				
				#Atribuir dados de usuários às Sessões
				$_SESSION['idUser'] = $rows['id'];
				$_SESSION['nomeUser'] = $rows['nome_usuario'];
				$_SESSION['sobrenomeUser'] = $rows['sobrenome_usuario'];
				$_SESSION['login'] = $rows['login_usuario'];

				#Retorna verdadeiro em caso de conexão								
				return true;
			}
			else
			{
				#Apaga Sessão em caso de erro de conexão
				unset($_SESSION['idUser']);
				unset($_SESSION['nomeUser']);
				unset($_SESSION['login']);
				
				return false;
			}
		}
		else
		{
//			echo 'Erro de query';
		}
		
		$mysqli->close();
		
	}
	
	public function DeslogarSistema()
	{
		unset($_SESSION['idUser']);
		unset($_SESSION['nomeUser']);
		unset($_SESSION['login']);
		
		header("Location: login.php");
	}
	
	public function BuscarDadosUsuario($id)
	{
		$dadosUser = array();

		$mysqli = new MySQLi($this->host, $this->user, $this->pass, $this->banco);
		$mysqli->set_charset('utf8');

		// Prepared statement - $id pode vir de URL/sessão, evita injection
		$stmt = $mysqli->prepare("SELECT id, nome_usuario, sobrenome_usuario, login_usuario, email_usuario, descricao FROM usuario_atendimento WHERE id = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$result = $stmt->get_result();
		// Após prepared statement, $result é sempre um objeto - precisa checar se houve linha
		$rows = $result ? $result->fetch_assoc() : null;
		if($rows)
		{
			$dadosUser['id'] = $rows['id'];
			$dadosUser['nome_usuario'] = $rows['nome_usuario'];
			$dadosUser['sobrenome_usuario'] = $rows['sobrenome_usuario'];
			$dadosUser['email_usuario'] = $rows['email_usuario'];
			$dadosUser['login_usuario'] = $rows['login_usuario'];
		}
		else
		{
			$dadosUser['id'] = '';
			$dadosUser['nome_usuario'] = '';
			$dadosUser['sobrenome_usuario'] = '';
			$dadosUser['email_usuario'] = '';
			$dadosUser['login_usuario'] = '';
		}
		
		return $dadosUser;
		
		$mysqli->close();
	}
	
	
	
	
}
?>
