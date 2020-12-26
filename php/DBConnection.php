<?php

class DBAccess
{

	// TODO: definire username, password, database_name
	private const HOST_DB = "localhost";
	private const USERNAME = "";
	private const PASSWORD = "";
	private const DATABASE_NAME = "";

	private $connection = null;

	public function openDBConnection() 
	{
		$this->connection = mysqli_connect(DBACCESS::HOST_DB, DBACCESS::USERNAME, DBACCESS::PASSWORD, DBACCESS::DATABASE_NAME);
		if (!$this->connection) 
		{
			return false;
		}
		else 
		{
			return true;
		}
	}

	public function closeDBConnection() 
	{
		if ($this->connection)
		{
			$this->connection->close();
		}
	}
}


 ?>