<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

require_once(APP_ROOT."/_include/inc_error.php");

class FTP_ACCOUNT extends MYDB
{

    private $role='nobody';
    private $my_web_userid;
    private $my_web_passwd;

    public $ftpa_error_num;
    public $ftpa_error_desc;

    protected $ftp_root;

    function __construct(array $db_cfg, array $global_cfg, $my_web_userid, $my_web_passwd)
    {
            
            parent::__construct($db_cfg);

            $this->my_web_userid = $my_web_userid;
            $this->my_web_passwd = md5($my_web_userid.$my_web_passwd);

            $this->connect();
            $this->set_encoding($db_cfg['encoding']);
            $this->select_db($this->dbname);

            if (!$this->sql_p_exec('SELECT id, role FROM webuser WHERE userid="%s" AND passwd="%s" AND enabled="Y"', array ($this->my_web_userid,$this->my_web_passwd))) {
                   $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem.';
                   $this->ftpa_error_num=$this->my_error_num;
                   $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                   return false;
            }
            $row=mysql_fetch_object($this->result);
            $this->role=$row->role;
            unset($row);

            $this->ftp_root = $global_cfg['ftp_root'];
    }

    function ftpa_create()
    {
        $userid = func_get_arg(0);
        $fullname = func_get_arg(1);

        if (($this->role=='admin') OR ($this->role=='superuser')) {
                $owner = (func_num_args()==3) ? func_get_arg(2) : $this->my_web_userid;
                $result = $this->sql_p_exec('INSERT INTO ftpuser VALUES ("","%s", "%s","___________NOT_SET___________", %d, "%s", "/sbin/nologin","%s", 0, "0000-00-00 00:00:00",now(), "N", "%s")', array ($owner, $userid, $this->web_user_id, $this->ftp_root.$userid, $fullname, $this->my_web_userid));
        } elseif ($this->role=='user') {
                $owner = $this->my_web_userid;
                $result = $this->sql_p_exec('INSERT INTO ftpuser VALUES ("","%s", "%s","___________NOT_SET___________", %d, "%s", "/sbin/nologin","%s", 0, "0000-00-00 00:00:00",now(), "N", "%s")', array ($owner, $userid, $this->web_user_id, $this->ftp_root.$userid, $fullname, $this->my_web_userid));
        }
        if (!$result) {
            if ($this->my_error_num==1062) {
                $this->ftpa_error_desc='Konto o podanej nazwie juz istnieje.';
                $this->ftpa_error_num=$this->my_error_num;
            } else {
                $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->ftpa_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
            }
                return false;
        }
        return true;
    }

    function ftpa_destroy($userid)
    {
            if (($this->role=='admin') OR ($this->role=='superuser')) {
                $result = $this->sql_p_exec('DELETE FROM ftpuser WHERE userid="%s" AND enabled="N"', array($userid));
            } else {
                $result = $this->sql_p_exec('DELETE FROM ftpuser WHERE userid="%s" AND enabled="N" AND owner="%s"', array($userid, $this->my_web_userid));
            }
                if (!$result) {
                    $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->ftpa_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
         return true;
    }
    
    function ftpa_passwd($userid, $passwd)
    {
            if (($this->role=='admin')&&($this->role=='superuser')) {
                $result = $this->sql_p_exec('UPDATE ftpuser SET passwd=OLD_PASSWORD("%s"), modified=now(), who="%s" WHERE userid="%s"', array($passwd, $this->my_web_userid, $userid));
            } else {
                $result = $this->sql_p_exec('UPDATE ftpuser SET passwd=OLD_PASSWORD("%s"), modified=now(), who="%s" WHERE userid="%s" AND owner="%s"', array($passwd, $this->my_web_userid, $userid, $this->my_web_userid));
            }
                if (!$result) {
                    $this->ftpa_error_desc='';
                    $this->ftpa_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            return true;
    }

    function ftpa_modify($userid, $enabled, $role, $fullname)
    {
            if ($this->role=='admin') {
                if (!$this->sql_p_exec('UPDATE webuser SET enabled="%s", role="%s", fullname="%s", modified=now(), who="%s" WHERE userid="%s"', array($enabled, $role, $fullname, $this->my_web_userid, $userid))) {
                    $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->ftpa_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
            }else {
                $this->ftpa_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->ftpa_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
    }

    function ftpa_change_role($userid, $role)
    {
            if ($this->role=='admin') {
                if (!$this->sql_p_exec('UPDATE webuser SET role="%s", modified=now(), who="%s" WHERE userid="%s"', array($role, $this->my_web_userid, $userid))) {
                   $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                   $this->ftpa_error_num=$this->my_error_num;
                   $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                   return false;
                }
            }else {
                $this->ftpa_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
                $this->ftpa_error_num=69;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }

    }

    function ftpa_list()
    {
        if (($this->role=='admin') OR ($this->role=='superuser')) {
                $result = $this->sql_exec_fast('SELECT userid, owner, enabled, accessed, modified, fullname, IF ((select userid webuser_owner from webuser where ftpuser.owner=userid)!="", "Y", "N") as valid_owner FROM ftpuser ORDER BY userid;');
            } elseif ($this->role=='user') {
                $result = $this->sql_p_exec('SELECT userid, owner, enabled, accessed, modified, fullname FROM ftpuser WHERE owner="%s" ORDER BY userid', array($this->my_web_userid));
            }
                if (!$result) {
                    $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->ftpa_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
         return true;
    }

    function ftpa_list_valid_owners()
    {
        if (($this->role=='admin') OR ($this->role=='superuser')) {
                $result = $this->sql_exec_fast('SELECT userid FROM webuser ORDER BY userid;');
            }
                if (!$result) {
                    $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                    $this->ftpa_error_num=$this->my_error_num;
                    $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                    return false;
                }
         return true;
    }

    function ftpa_enable($userid)
    {
         if (($this->role=='admin') OR ($this->role=='superuser')) {
                $result = $this->sql_p_exec('UPDATE ftpuser SET enabled="Y", modified=now(), who="%s" WHERE userid="%s"', array($this->my_web_userid, $userid));
                } else {
                $result = $this->sql_p_exec('UPDATE ftpuser SET enabled="Y", modified=now(), who="%s" WHERE userid="%s" AND owner="%s"', array($this->my_web_userid, $userid, $this->my_web_userid));
            }
            if (!$result) {
                $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->ftpa_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function ftpa_disable($userid)
    {
         if (($this->role=='admin') OR ($this->role=='superuser')) {
                $result = $this->sql_p_exec('UPDATE ftpuser SET enabled="N", modified=now(), who="%s" WHERE userid="%s"', array($this->my_web_userid, $userid));
                } else {
                $result = $this->sql_p_exec('UPDATE ftpuser SET enabled="N", modified=now(), who="%s" WHERE userid="%s" AND owner="%s"', array($this->my_web_userid, $userid, $this->my_web_userid));
            }
            if (!$result) {
                $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->ftpa_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

     function ftpa_change_owner($userid, $owner)
    {
            if ($this->role=='admin') $result = $this->sql_p_exec('UPDATE ftpuser SET owner="%s", modified=now(), who="%s" WHERE userid="%s"', array($owner, $this->my_web_userid, $userid));
            if (!$result) {
                $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->ftpa_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
            return true;
    }

    function web_log($per_page, $offset)
    {
        if ($this->role=='admin') {
             if (!$this->sql_p_exec('SELECT * FROM webuser_log ORDER BY modified DESC LIMIT %d OFFSET %d', array(((int) $per_page),((int) $offset)))) {
                $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->ftpa_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
        } else {
            $this->ftpa_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
            $this->ftpa_error_num=69;
            $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
            return false;
        }
    }
    
     function web_log_count()
    {
        if ($this->role=='admin') {
            if (!$this->sql_exec_fast('SELECT count(*) AS count FROM webuser_log')) {
                $this->ftpa_error_desc='Operacja zakonczyła się niepowodzeniem';
                $this->ftpa_error_num=$this->my_error_num;
                $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
                return false;
            }
        } else {
            $this->ftpa_error_desc='Nie masz uprawnien do przeprowadzenia tej operacji.';
            $this->ftpa_error_num=69;
            $this->logger( __METHOD__, __LINE__, $this->my_error_num, $this->my_error_desc);
            return false;
        }
        return true;
    }

        function ftp_log($per_page, $offset)
    {
        if (($this->role=='admin') OR ($this->role=='superuser')) {
             if (!$this->sql_p_exec('SELECT * FROM ftpxferlog ORDER BY date DESC LIMIT %d OFFSET %d', array(((int) $per_page),((int) $offset)))) {
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

    function ftp_log_count()
    {
        if (($this->role=='admin') OR ($this->role=='superuser')) {
            if (!$this->sql_exec_fast('SELECT count(*) AS count FROM ftpxferlog')) {
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
