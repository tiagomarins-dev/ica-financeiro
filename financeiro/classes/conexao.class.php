<?php

class conexao
{
	public $status;
	private $conn;
	private $host;
	private $user;
	private $pass;
	private $database;

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
        // Prefixo "p:" ativa conexão persistente do mysqli - reutiliza handshake TCP/auth entre requests
        // Ganho grande aqui porque o MySQL é remoto (~450ms por conexão nova)
        $this->host = "";
        $this->user = "";
        $this->pass = "";
        $this->database = "";
	
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
