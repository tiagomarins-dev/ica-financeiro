<?php

class conexao
{
	// Conexao compartilhada por request - evita reabrir handshake TCP/auth em cada metodo
	private static $sharedConn = null;

	public $status;
	private $conn;
	private $host;
	private $user;
	private $pass;
	private $database;

	// Retorna conexao singleton - cria na 1a chamada, reutiliza nas seguintes dentro do mesmo request
	public static function pegar(): mysqli
	{
		if (self::$sharedConn instanceof mysqli) {
			return self::$sharedConn;
		}
		$instance = new self();
		self::$sharedConn = mysqli_connect(
			$instance->host,
			$instance->user,
			$instance->pass,
			$instance->database
		);
		mysqli_set_charset(self::$sharedConn, 'utf8');
		return self::$sharedConn;
	}

	public function __construct()
    {
//    	die();
        /*$this->host = "127.0.0.1";
        $this->user = "root";
        $this->pass = "tm2004";
        $this->database = "ica_financeiro";*/

        /*$this->host = "fin_ica.mysql.dbaas.com.br";
        $this->user = "fin_ica";
        $this->pass = "tm2004";
        $this->banco = "fin_ica";
        $this->database = "fin_ica";*/
        // Credenciais lidas de variaveis de ambiente (.env carregado pelo autoload)
        // Prefixe DB_HOST com "p:" no .env para ativar conexao persistente do mysqli
        $this->host     = getenv('DB_HOST') ?: '';
        $this->user     = getenv('DB_USER') ?: '';
        $this->pass     = getenv('DB_PASS') ?: '';
        $this->database = getenv('DB_NAME') ?: '';
	
    }

    public function getInfoConn() {

		$info = array(
			"host" => $this->host,
			"user" => $this->user,
			"pass" => $this->pass,
			"database" => $this->database
		);


		return $info;
	}



    public function Conectar()
	{
		/*$this->host = "localhost";
		$this->user = "root";
		$this->pass = "";
		$this->database = "intrasightgps";*/

		if(!$this->conn = mysqli_connect($this->host,$this->user,$this->pass,$this->database))
		{
			die("ERRO: " . mysqli_connect_error());
		}

		// set_charset usa protocolo nativo (1 mensagem) - antes eram 4 queries SET NAMES = 4 roundtrips de ~140ms cada
		mysqli_set_charset($this->conn, 'utf8');
		return $this->conn;
	}
	
	public function Desconectar()
	{
		return mysqli_close($this->Conectar());
	}
}
	
	$host = "108.167.188.85";
	$user = "inst4498_admin";
	$pass = "tm2004";
	$database = "inst4498_financeiro";
	



?>
