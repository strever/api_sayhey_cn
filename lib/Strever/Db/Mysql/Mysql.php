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
    protected $_primary = 'id';

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
            if(count($args) == 1) {
                $where = array(strtolower($column) => $args[0]);
            }else $where = array('in' =>array(strtolower($column)=>$args));

            return $rings = $this->fetchAll('',$where,'',20);
        }elseif(preg_match('/get(.*)By(.*)$/',$method,$matches)) {
            $field = strtolower($matches[1]);
            $column = strtolower($matches[2]);
            if(count($args) == 1) {
                $where = array($column => $args[0]);
            }else $where = array('in' =>array($column=>$args));
            $order = self::orderBy('DL_NUM');
            return $this->fetchAll($field,$where,$order,20);
        } else return false;
    }

    /**
     * 用于SELECT查询
     * @param $sql
     * @return mixed 返回关联数组结果集
     */
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

    /**
     * 用于SELECT查询
     * @param $sql
     * @return mixed 返回关联数组结果集的第一条记录
     */
    public static function fetch($sql) {
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
            return self::$stmt->fetch(\PDO::FETCH_ASSOC);
        }else throw new \Exception("没有符合条件的记录; Query String:". self::$sql,433);//Response::error('433','没有符合条件的记录; Query String: ' . self::$sql );

    }

    public function find($condition,$fields = '') {
        $fields = !empty($fields)?self::parseFields($fields):'*';
        if(is_numeric($condition)) {
            $sql = "SELECT $fields FROM " . $this->_table . " WHERE " . $this->_primary . " = {$condition}";
        }elseif(is_array($condition)) {
            $sql = "SELECT $fields FROM " . $this->_table . " WHERE `" . key($condition) . "` = '" . $condition[key($condition)] . "'";
        }
        return self::fetch($sql);
    }

    /**
     * 用于执行UPDATE、INSERT、DELETE
     * @param $sql
     * @return mixed 成功时返回true；失败返回false
     */
    public static function execute($sql) {
        if(!is_null(self::$stmt)) {
            self::free();
        }
        self::$sql = $sql;
        try{
            self::$stmt = self::$dbh->prepare($sql);
            return self::$stmt->execute();
        }catch (\PDOException $e) {
            Response::error($e->getCode(),$e->getMessage() . ',"Query String: "' . self::$sql);  //测试用,部署时记得注释掉
            //Response::error($e->getCode(),$e->getMessage());
        }

    }

    /**
     * 用于执行UPDATE、INSERT、DELETE
     * @param $sql
     * @return mixed 成功时返回true；失败返回false
     */
    public static function exec($sql) {
        if(!is_null(self::$stmt)) {
            self::free();
        }
        self::$sql = $sql;
        try{
            self::$rowCount = self::$dbh->exec($sql);
            return self::$rowCount;
        }catch (\PDOException $e) {
            Response::error($e->getCode(),$e->getMessage() . ',"Query String: "' . self::$sql);  //测试用,部署时记得注释掉
            //Response::error($e->getCode(),$e->getMessage());
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
                }else $whereStr .= "`$k`='$v' AND ";
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
    public function fetchAll($fields = '', $where = '', $order = '', $count = '', $offset = '') {
        $fields = !empty($fields)?self::parseFields($fields):'*';
        $whereStr = self::parseWhere($where);
        $order = ($order) ? " ORDER BY $order" : '';
        $limit = (($count && $offset) ? " LIMIT $offset,$count" :($count?" LIMIT $count":''));
        $sql = "SELECT $fields FROM " . $this->_table . $whereStr . $order . $limit;
        //echo $sql;
        return self::query($sql);
    }

    public function fetchRow($fields,$where = '') {
        $fields = !empty($fields)?self::parseFields($fields):'*';
        $whereStr = self::parseWhere($where);
        $sql = "SELECT $fields FROM " . $this->_table . $whereStr . ' LIMIT 1';
        return self::fetch($sql);
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

    public function save($values) {
        if(array_key_exists($this->_primary,$values)) {
            $update = 'UPDATE TABLE `' . $this->_table . '` SET ';
            $whereStr = ' WHERE ' . $this->_primary . " = " . $values[$this->_primary];
            unset($values[$this->_primary]);
            foreach($values as $k => $v) {
                $update .= '`' . $k . "` = '" . $v . "',";
            }
            $update = substr($update,-1,1);
            $update .= $whereStr;
            echo $update;
        }else {
            $insert = $insert = 'INSERT INTO `' . $this->_table . '`';
            $insert .= '(`' . implode('`, `',array_keys($values)) . '`) VALUES(\'' . implode('\', \'',array_values($values)) . '\')';
            echo $insert;
        }
    }

    /**
     * @param array $set
     * @param string $where
     * @return bool 成功返回受影响的行数，失败返回false
     */
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

    /**
     * @param string $where
     * @return mixed 成功返回受影响的行数，失败返回false
     */
    public function delete($where = '') {
        $whereStr = self::parseWhere($where);
        $sql = "DELETE FROM " . $this->_table . $whereStr;
        if(self::execute($sql)) {
            $affectedRow = self::$dbh->exec($sql);
            return $affectedRow;
        }else return false;

    }

    public function count($where='') {
        $whereStr = self::parseWhere($where);
        $sql = "SELECT count(*) as count FROM " . $this->_table . $whereStr . ' LIMIT 1';
        $row = self::fetch($sql);
        return $row['count'];
    }

    public function paginator($fields = '*', $where = '',$currentPage = 1, $order = '', $perPageRowCount = 20) {
        $totalRowCount = $this->count($where);
        $totalPage = ceil ( $totalRowCount / $perPageRowCount );
        $currentPage = ($currentPage > $totalPage)?$totalPage:$currentPage;
        $prevPage = ($currentPage > 1)?($currentPage - 1):1;
        $nextPage = ($currentPage < $totalPage)?($currentPage + 1):$totalPage;

        $offset = ($currentPage - 1) * $perPageRowCount;
        $currentPageRows = $this->fetchAll($fields, $where,$order,$perPageRowCount,$offset);
        $currentPageRowsCount = count($currentPageRows);

        $retVal = array(
            'rowOffset'           =>   $offset,
            'totalRowCount'       =>   $totalRowCount,
            'perPageRowCount'     =>   $perPageRowCount,
            'totalPage'           =>   $totalPage,
            'prevPage'            =>   $prevPage,
            'currentPage'         =>   $currentPage,
            'nextPage'            =>   $nextPage,
            'currentPageRowsCount'=>   $currentPageRowsCount,
            'currentPageRows'     =>   $currentPageRows,
        );
        return $retVal;
    }

    public static function free() {
        self::$stmt = null;
    }

    public static function close() {
        unset(self::$dbh);
    }
}