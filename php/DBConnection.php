<?php

class DBAccess
{

    // TODO: definire username, password, database_name
    private const HOST_DB = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "";
    private const DATABASE_NAME = "my_betweenthelines";

    private $connection = null;

    public function openDBConnection()
    {
        $this->connection = mysqli_connect(DBACCESS::HOST_DB, DBACCESS::USERNAME, DBACCESS::PASSWORD, DBACCESS::DATABASE_NAME);
        if (!$this->connection) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * @param $query
     * @return
    */
    /**
     * @param  $query : the query text
     * @return null in case of query error, otherwise returns an array with the output of the query (can be an empty array if the query produces no output)
     */
    public function queryDB($query)
    {
        $result = null;
        if ($queryResult = $this->connection->query($query)) {
            $result = array();
            if ($queryResult && $queryResult->num_rows > 0) {
                while ($row = $queryResult->fetch_array(MYSQLI_ASSOC)) {
                    array_push($result, $row);
                }
            }
            //TODO: CHECK IF THIS IS USEFUL
            $queryResult->close();
        }
        return $result;
    }

    /**
     * @param $query string the query text
     * @return bool true if the query is successful otherwise false
     */
    public function insertDB($query)
    {
        $this->connection->query($query);
        if($this->connection->affected_rows>0) {
            return true;
        }
        return false;
    }

    public function closeDBConnection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * @param string $string to escape
     * @return string the string escaped for a SQL query
     */
    public function escape_string($string)
    {
        return $this->connection->real_escape_string($string);
    }


    public function insert_and_get_id($query)
    {
        if (mysqli_query($this->connection, $query)) {
          $last_id = mysqli_insert_id($this->connection);
          return $last_id;
        } else {
          return -1;
        }
    }
}

?>
