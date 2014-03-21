<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

require_once(APP_ROOT."/_include/inc_error.php");

class WEB_ACCOUNT extends MYDB
{

    private $role='nobody';
    private $my_web_userid;
    private $my_web_passwd;

    public $weba_error_num;
    public $weba_error_desc;

    function __construct(array $db_cfg, $global_cfg, $my_web_userid, $my_web_passwd)
    {
            
            parent::__construct($db_cfg);

            $this->my_web_userid = $my_web_userid;
            $this->my_web_passwd = md5($my_web_userid.$my_web_passwd);

            $this->connect();
            $this->set_encoding($db_cfg['encoding']);
            $this->select_db($this->dbname);

            if (!$this->sql_p_exec('SELECT role FROM webuser WHERE userid="%s" AND passwd="%s" AND enabled="Y"', array ($this->my_web_userid,$this->my_web_passwd))) {
                   $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem.';
                   $this->weba_error_num=$this->my_error_num;
                   $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                   return false;
            }

            $this->role=mysql_fetch_object($this->result)->role;

    }

    function weba_create($userid, $role, $fullname)
    {
            if ($this->role=='admin') {
                if (!$this->sql_p_exec('INSERT INTO webuser VALUES ("","%s","___________NOT_SET___________","N","%s","%s",now(),"%s")', array ($userid, $role, $fullname, $this->my_web_userid))){
                    $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->weba_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {                
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function weba_destroy($userid)
    {
            if ($this->role=='admin') {
                if (!$this->sql_p_exec('DELETE FROM webuser WHERE userid="%s" AND enabled="N"', array($userid))) {
                    $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->weba_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }
    
    function weba_passwd($userid, $passwd)
    {
            if ($this->role=='admin') {
                if (!$this->sql_p_exec('UPDATE webuser SET passwd="%s", modified=now(), who="%s" WHERE userid="%s"', array(md5($userid.$passwd), $this->my_web_userid, $userid))) {
                    $this->weba_error_desc='';
                    $this->weba_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function weba_modify($userid, $enabled, $role, $fullname)
    {
            if ($this->role=='admin') {
                if (!$this->sql_p_exec('UPDATE webuser SET enabled="%s", role="%s", fullname="%s", modified=now(), who="%s" WHERE userid="%s"', array($enabled, $role, $fullname, $this->my_web_userid, $userid))) {
                    $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->weba_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function weba_change_role($userid, $role)
    {
            if ($this->role=='admin') {
                if (!$this->sql_p_exec('UPDATE webuser SET role="%s", modified=now(), who="%s" WHERE userid="%s"', array($role, $this->my_web_userid, $userid))) {
                   $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                   $this->weba_error_num=$this->my_error_num;
                   $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                   return false;
                }
            }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function weba_list()
    {
         if ($this->role=='admin') {
               if (!$this->sql_exec_fast('SELECT userid, role, enabled, fullname, modified  FROM webuser ORDER BY userid')) {
                   $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                   $this->weba_error_num=$this->my_error_num;
                   $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                   return false;
               }
         }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
         }
         return true;
    }

    function weba_show($userid)
    {
         if ($this->role=='admin') {
                if (!$this->sql_p_exec('SELECT userid, role, enabled, fullname, modified FROM webuser WHERE userid="%s"', array($userid))) {
                    $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->weba_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function weba_enable($userid)
    {
         if ($this->role=='admin') {
                if (!$this->sql_p_exec('UPDATE webuser SET enabled="Y", modified=now(), who="%s" WHERE userid="%s"', array($this->my_web_userid, $userid))) {
                    $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->weba_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function weba_disable($userid)
    {
         if ($this->role=='admin') {
                if (!$this->sql_p_exec('UPDATE webuser SET enabled="N", modified=now(), who="%s" WHERE userid="%s"', array($this->my_web_userid, $userid))) {
                    $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->weba_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {
                $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->weba_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }
    
    function web_log($per_page, $offset)
    {
        if ($this->role=='admin') {
             if (!$this->sql_p_exec('SELECT * FROM webuser_log ORDER BY modified DESC LIMIT %d OFFSET %d', array(((int) $per_page),((int) $offset)))) {
                $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->weba_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
        } else {
            $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
            $this->weba_error_num=69;
            $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
            return false;
        }
        return true;
    }
    
     function web_log_count()
    {
        if ($this->role=='admin') {
            if (!$this->sql_exec_fast('SELECT count(*) AS count FROM webuser_log')) {
                $this->weba_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->weba_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
        } else {
            $this->weba_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
            $this->weba_error_num=69;
            $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
            return false;
        }
        return true;
    }
    function my_role() {
        return $this->role;
    }
}

?>
