<?php
class DataMysql
{
	private $conId;	
	
	private $host;
	
	private $usr;
	
	private $password;
	
	private $database;
	
	const OPTIONS=4;	
	
	public function __construct($options=array()){

		if(count($options)!=self::OPTIONS){
			throw new Exception('Invalid number of connection parameters');
		}

		foreach($options as $parameter=>$value){
			
			if(!$value){
				throw new Exception('Invalid parameter '.$parameter);
			}
			$this->{$parameter}=$value;

		}
		$this->connectDB();
	}

	
	private function connectDB() {
		if(!$this->conId = mysql_connect($this->host, $this->usr, $this->password)) {
			throw new Exception ('Error connecting to server');
		}
		if(!mysql_select_db($this->database, $this->conId)){
			throw new Exception ('Error selecting database');
		}
	}
}
?>