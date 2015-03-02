<?php

/************************************
 * Project:      yaf
 * FileName:     Mongo.php
 * Description:
 * Author:       Strever Fang
 * CreateTime:   2015/1/6 14:56
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

namespace Strever\Db\Mongo;

use Strever\Db;
use Yaf\Registry;
use Strever\API\Response;
use League\Monga;

class Mongo extends Db
{
    private static $_instance;
    protected $is_connected;
    protected $conn;               //当前连接对象
    protected $mongodb;            //mongoDB 数据库对象
    protected $dbname;
    protected $collection;         //mongoDB 集合对象

    private function __construct()
    {
        if (!$this->is_connected) {
            $this->connect();
        }
    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * @param $config array('host'=>'127.0.0.1','port'=>'27017','option'=>array('username'=>'username','password'=>'password','db'=>'dbname'))
     * @return string
     */
    public function connect($config = '')
    {
        if (!$this->is_connected) {
            //mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
            $option = array();
            if (empty($config)) {
                $config = Registry::get('config')->mongodb->toArray();
                if (empty($config)) {
                    $server = 'mongodb://localhost:27017';
                    $option = array();
                } else {
                    $server = 'mongodb://' . $config['host'] . ($config['port'] ? ':' . $config['port'] : '27017');
                    $option = $config['option'];
                }
            } else {
                $server = 'mongodb://' . $config['host'] . $config['port'] ? $config['port'] : '27017';
                $option = $config['option'];
            }

            try {
                $this->conn = new \MongoClient($server, $option);
                if ($option['db']) {
                    $this->mongodb = $this->conn->selectDB($option['db']);
                    $this->dbname = $option['db'];
                }
                $this->is_connected = true;
            } catch (MongoConnectionException $e) {
                $retVal['code'] = $e->getCode();
                $retVal['msg'] = $e->getMessage();
                echo json_encode($retVal);
            }
        }
    }

    /**
     * @param $config array('host'=>'127.0.0.1','port'=>'27017','option'=>array('username'=>'username','password'=>'password','db'=>'dbname'))
     * @return mongadb object
     */
    public function connectMonga($config = '')
    {
        //mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
        $option = array();
        if (empty($config)) {
            $proj_config = Registry::get('config')->mongodb->toArray();
            if (empty($proj_config)) {
                $server = 'mongodb://localhost:27017';
                $option = array();
            } else {
                $server = 'mongodb://' . $proj_config['host'] . ($proj_config['port'] ? ':' . $proj_config['port'] : '27017');
                $option = $proj_config['option'];
            }
        }else {
            $server = 'mongodb://' . $config['host'] . $config['port'] ? $config['port'] : '27017';
            $option = $config['option'];
        }

        try {
            $conn = Monga::connection($server, $option);
            if ($option['db']) {
                return $mongodb = $conn->database($option['db']);
            }
        } catch (Exception $e) {
            Response::error($e->getCode(), $e->getMessage());
        }
    }

    public function getConn()
    {
        return $this->conn;
    }

    public function getMongoDb()
    {
        return $this->mongodb;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function switchCollection($collection, $dbname = '', $master = true)
    {
        if (!empty($dbname)) {
            if ($this->dbname != $dbname) {
                $this->mongodb = $this->conn->selectDb($dbname);
                $this->dbname = $dbname;
            }
        }
        $this->collection = $this->mongodb->selectCollection($collection);
        return $this->collection;
    }

    public function insert($document)
    {
        try {
            $this->collection->insert($document);
        } catch (Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            Response::error($code, $msg, Registry::get('response_data_format'));
        }

    }

    public function find($limit = 5)
    {
        $cursor = $this->collection->find()->limit($limit);
        return iterator_to_array($cursor);
    }

    public function findOne()
    {
        return $this->collection->findOne();
    }

    public function fetchAll($condition = '', $limit = '', $sort = '')
    {
        $arr = array();
        if (!empty($condition)) {
            $cursor = $this->collection->find($condition);
        } else $cursor = $this->collection->find();

        while ($cursor->hasNext()) {
            $arr[] = $cursor->getNext();
        }
        foreach ($cursor as $document) {
            $arr[] = $document;
        }
        return $arr;
    }

    /**
     * 查找记录
     * @access public
     * @param array $options 表达式 array('collection'=>'','where'=>arrayy(),field
     * @return iterator
     */
    public function _find($options = array())
    {
        if (isset($options['table'])) {
            $this->switchCollection($options['table'], '', false);
        }
        $cache = isset($options['cache']) ? $options['cache'] : false;
        if ($cache) { // 查询缓存检测
            $key = is_string($cache['key']) ? $cache['key'] : md5(serialize($options));
            $value = S($key, '', '', $cache['type']);
            if (false !== $value) {
                return $value;
            }
        }
        $this->model = $options['model'];
        $query = $this->parseWhere($options['where']);
        $field = $this->parseField($options['field']);
        try {
            $_cursor = $this->_collection->find($query, $field);
            if ($options['order']) {
                $order = $this->parseOrder($options['order']);
                if (C('DB_SQL_LOG')) {
                    $this->queryStr .= '.sort(' . json_encode($order) . ')';
                }
                $_cursor = $_cursor->sort($order);
            }
            if (isset($options['page'])) { // 根据页数计算limit
                if (strpos($options['page'], ',')) {
                    list($page, $length) = explode(',', $options['page']);
                } else {
                    $page = $options['page'];
                }
                $page = $page ? $page : 1;
                $length = isset($length) ? $length : (is_numeric($options['limit']) ? $options['limit'] : 20);
                $offset = $length * ((int)$page - 1);
                $options['limit'] = $offset . ',' . $length;
            }
            if (isset($options['limit'])) {
                list($offset, $length) = $this->parseLimit($options['limit']);
                if (!empty($offset)) {
                    if (C('DB_SQL_LOG')) {
                        $this->queryStr .= '.skip(' . intval($offset) . ')';
                    }
                    $_cursor = $_cursor->skip(intval($offset));
                }
                if (C('DB_SQL_LOG')) {
                    $this->queryStr .= '.limit(' . intval($length) . ')';
                }
                $_cursor = $_cursor->limit(intval($length));
            }
            $this->debug();
            $this->_cursor = $_cursor;
            $resultSet = iterator_to_array($_cursor);
            if ($cache && $resultSet) { // 查询缓存写入
                S($key, $resultSet, $cache['expire'], $cache['type']);
            }
            return $resultSet;
        } catch (\MongoCursorException $e) {
            E($e->getMessage());
        }
    }


}