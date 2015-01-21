<?php
/************************************
 * Project:      yaf
 * FileName:     Model.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 15:09
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

namespace Strever\Db\Mysql;

use Strever\API\Response;
use Strever\Db\Mysql\PDOMysql;

class Mysql {
    protected  $_table;

    protected static $dbh;

    protected static $stmt;

    protected static $sql;

    protected static $rowCount = 0;

    public function __construct() {
        self::$dbh = PDOMysql::getInstance()->getDbh();
        if(empty($this->_table)) {
            Response::error(478,'没有定义的表名');
        }

    }

    /**
     * 为model注册getByColumn(),getColumnByColumn()方法
     * @param $method
     * @param $args
     * @return bool
     */
    public function __call($method,$args) {
        if(strpos($method,'getBy') !== false) {
            $column =  str_replace('getBy','',$method);
            $where = array('in' =>array(strtolower($column)=>$args));
            return $rings = $this->fetchAll('',$where,'',20);
        }elseif(preg_match('/get(.*)By(.*)$/',$method,$matches)) {
            $field = strtolower($matches[1]);
            $where = array('in' =>array(strtolower($matches[2])=>$args));
            $order = self::orderBy('DL_NUM');
            return $this->fetchAll($field,$where,$order,20);

        } else return false;
    }

    public static function query($sql) {
        if(!is_null(self::$stmt)) {
            self::free();
        }
        self::$sql = $sql;
        try{
            self::$stmt = self::$dbh->query(self::$sql);
        }catch (\PDOException $e) {
            Response::error($e->getCode(),$e->getMessage() . ', Query String: ' . self::$sql);
        }
        self::$rowCount = self::$stmt->rowCount();
        if(self::$rowCount >= 1) {
            return self::$stmt->fetchAll(\PDO::FETCH_ASSOC);
        }else Response::error('433','没有符合条件的记录; Query String: ' . self::$sql );

    }

    public static function execute($sql) {
        if(!is_null(self::$stmt)) {
            self::free();
        }
        self::$sql = $sql;
        try{
            self::$stmt = self::$dbh->prepare($sql);
            return self::$stmt->execute();
        }catch (\PDOException $e) {
            Response::error($e->getCode(),$e->getMessage() . ',"Query String: "' . self::$sql);
        }

    }

    public static function parseWhere($where) {
        $whereStr = '';
        if(is_array($where)) {
            $whereStr = " WHERE ";
            foreach($where as $k => $v) {
                if(in_array($k,array('lt','gt','eq','neq','gteq','lteq','in'))) {
                    switch($k) {
                        case 'lt'  :foreach($v as $key=>$val){$whereStr .= "`$key`<$val AND ";}break;   //或者 $whereStr .= parseWhere($v)
                        case 'gt'  :$whereStr .= '`' .key($v) . '`>' . $v[key($v)] . 'AND ';break;
                        case 'eq'  :$whereStr .= '`' .key($v) . '`=' . $v[key($v)] . 'AND ';break;
                        case 'neq' :$whereStr .= '`' .key($v) . '`!=' . $v[key($v)] . 'AND ';break;
                        case 'lteq':$whereStr .= '`' .key($v) . '`<=' . $v[key($v)] . 'AND ';break;
                        case 'in'  :$whereStr .= '`' .key($v) . '` IN (' . join(',',$v[key($v)]) . ') AND ';break;
                        case 'gteq':$whereStr .= '`' .key($v) . '`>=' . $v[key($v)] . 'AND ';break;
                    }
                }else $whereStr .= "`$k`=$v AND ";
            }
            $whereStr = substr($whereStr,0,-5);
        }elseif(is_string($where)) {
            $whereStr = ($where) ? " WHERE $where" : '';
        }
        return $whereStr;
    }

    public static function backquoteInto($str) {
        if(strpos('`',$str) === false) {
            return $str = '`' . str_replace(',','`, `',$str) . '`';
        }
    }

    public static function parseFields($fields) {
        if(!empty($fields) && is_string($fields)) {
            return self::backquoteInto($fields);
        }elseif(is_array($fields)) {
            $fieldsStr = join(',',$fields);
            return self::backquoteInto($fieldsStr);
        }
    }

    // 完成查询功能的函数
    public function fetchAll($fields = '*', $where = '', $order = '', $count = '', $offset = '') {
        $fields = !empty($fields)?self::parseFields($fields):'*';
        $whereStr = self::parseWhere($where);
        $order = ($order) ? " ORDER BY $order" : '';
        $limit = (($count && $offset) ? " LIMIT $offset,$count" :($count?" LIMIT $count":''));
        $sql = "SELECT $fields FROM " . $this->_table . $whereStr . $order . $limit;
        self::$sql = $sql;
        //echo $sql;
        return self::query($sql);
    }

    public function fetchRow($where = '') {
        $whereStr = self::parseWhere($where);
        $sql = "SELECT * FROM " . $this->_table . $whereStr;
        echo $sql;
        return self::query($sql);
    }

    public function insert($values) {
        $dimension = fang_get_array_dimension($values);
        $insert = 'INSERT INTO ' . $this->_table;
        if($dimension === 1) {
            $insert .= ' (`' . implode('`, `',array_keys($values)) . '`) VALUES(\'' . implode('\', \'',array_values($values)) . '\')';
            echo $insert;
        }elseif($dimension === 2) {
            //$insert .=
        }

        echo "\n";
        $cols = array ();
        $vals = array ();
        foreach ( $values as $col => $val ) {
            $cols [] = $col;
            $vals [] = $val;
            unset ( $values [$col] );
        }
        echo $sql = "INSERT INTO " . $this->_table . ' (`' . implode ( '`, `', $cols ) . '`) ' . 'VALUES (\'' . implode ( '\', \'', $vals ) . '\')';
        die;
    }

    public function update(array $set, $where = '') {
        $arr = array ();
        foreach ( $set as $col => $val ) {
            $arr [] = '`' . $col . "` = '" . $val . "'";
        }
        $whereStr = self::parseWhere($where);

        echo $sql = "UPDATE `" . $this->_table . '` SET ' . implode ( ', ', $arr ) . $whereStr;
        if(self::execute($sql)) {
            $affectedRows = self::$stmt->rowCount;
            return $affectedRows;
        }else return false;
    }

    public function delete($where = '') {
        $whereStr = self::parseWhere($where);
        $sql = "DELETE FROM " . $this->_table . $whereStr;
        if(self::execute($sql))
        echo $affectedRow = self::$dbh->exec($sql);
        return $affectedRow;
    }

    public static function free() {
        self::$stmt = null;
    }

    public static function close() {
        unset(self::$dbh);
    }
}