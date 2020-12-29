<?php

class DBAccess
{

	// TODO: definire username, password, database_name
	private const HOST_DB = "localhost";
	private const USERNAME = "root";
	private const PASSWORD = "root";
	private const DATABASE_NAME = "betweenthelines";

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

	/*
	 * @param $query
     * @return
	*/
    /**
     * @param  $query: the query text
     * @return null in case of query error, otherwise returns an array with the output of the query (can be an empty array if the query produces no output)
     */
    public function queryDB($query){
        $result=null;
        if($queryResult = $this->connection->query($query)){
            $result=array();
            if($queryResult && $queryResult->num_rows>0){
                while($row=$queryResult->fetch_array(MYSQLI_ASSOC)){
                    array_push($result,$row);
                }
            }
            $queryResult->close();
        }
        return $result;
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