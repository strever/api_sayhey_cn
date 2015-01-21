<?php
/************************************
 * Project:      yaf
 * FileName:     PDOMysql.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 15:07
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */


namespace Strever\Db\Mysql;

use Strever\API\Response;
use Strever\Db;
use Yaf\Registry;

class PDOMysql extends Db {
    private static $_instance;
    protected $isConnected = false;
    protected $dbh;
    protected $table;
    protected $stmt;

    private function __construct() {
        if(!$this->isConnected) {
            $this->connect();
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        }
    }

    private function __clone() {}

    public static function getInstance() {
        if(!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    //mysql://username:passwd@localhost:3306/DbName?param1=val1&param2=val2#utf8
    public function connect($dsn = null)
    {
        if (empty($dsn)) {
            $config = Registry::get('config')->mysql->toArray();
            $dsn = "mysql:host=" . $config['host'] . ";port=" . ($config['port'] ? $config['port'] : "3306") . ";dbname=" . $config['dbname'] . ";charset=" . ($config['charset']?$config['charset']:'utf8');
            try{
                $this->dbh = new \PDO($dsn, $config['user'], $config['pass']);
                $this->isConnected = true;
            }catch (\PDOException $e) {
                Response::error($e->getCode(),$e->getMessage());
            }
        }
    }

    public function getDbh() {
        return $this->dbh;
    }
}