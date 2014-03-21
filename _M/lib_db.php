<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

require_once(APP_ROOT."/_include/inc_error.php");


class MYDB
{

	protected $host;
	protected $port;
	protected $userid;
	protected $passwd;
        protected $dbname;

	private $dblink;

	private $mysql_query;

	public $result;
        public $my_error_num;
        public $my_error_desc;

	public $encoding;

        function __construct(array $db_cfg)
	{
		$this->host = $db_cfg['host'];
		$this->port = $db_cfg['port'];
		$this->userid = $db_cfg['user'];
		$this->passwd = $db_cfg['pass'];
                $this->dbname = $db_cfg['dbname'];
	}

	function connect()
	{
            $this->dblink = mysql_connect($this->host.':'.$this->port, $this->userid, $this->passwd);
            if (!$this->dblink) {
                $this->my_error_num = mysql_errno();
                $this->my_error_desc = mysql_error();
                return false;
            }
                return true;
	}

        function show_public()
        {
            echo $this->public;
        }

        function select_db()
        {
             $dbname = (func_num_args()==1) ? func_get_arg(0) : $this->dbname;

            if (mysql_select_db($dbname, $this->dblink)) {
                    $this->dbname = $dbname;
                    return true;
            } else {
                    $this->my_error_num = mysql_errno();
                    $this->my_error_desc = mysql_error();
                    return false;
            }
        }

	function disconnect()
	{
		mysql_close($this->dblink);
	}

	function get_encoding()
	{
		$this->encoding=mysql_client_encoding($this->dblink);
		
	}

	function set_encoding($encoding)
        {
                $this->result=mysql_set_charset($encoding, $this->dblink);
		$this->get_encoding();
	}
	
	function sql_prepare($query, array $params)
	{
                array_map('mysql_real_escape_string', $params);
                array_unshift($params, $query);
                $this->mysql_query = call_user_func_array('sprintf', $params);
                // debug SQL query:
                // echo $this->mysql_query;
	}

	function sql_exec()
	{
		$this->result = mysql_query($this->mysql_query);
                if (!$this->result) {
                    $this->my_error_num = mysql_errno();
                    $this->my_error_desc = mysql_error();
                    return false;
                }
                return true;
	}

        function sql_p_exec($query, array $params)
        {
            $this->sql_prepare($query, $params);
            return $this->sql_exec();
        }

	function sql_exec_fast($query)
	{
		// tylko dla zapytan bez zmiennych parametrow (sql injection !)
		$this->result = mysql_query($query);
                $this->mysql_query = $query;
                if (!$this->result) {
                    $this->my_error_num = mysql_errno();
                    $this->my_error_desc = mysql_error();
                    return false;
                }
                return true;
	}

        function logger()
        {
            $log = array();
            $log['GET'] = ($_GET);
            $log['POST'] = ($_POST);
            $log['SESSION'] = ($_SESSION);
            $log['SQL'] = $this->mysql_query;
            $log['MSG'] = func_get_args();

            error_log(json_encode($log));
        }
	
}		

?>
