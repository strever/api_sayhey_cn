<?php
/************************************
 * Project:      yaf
 * FileName:     Connect.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/12 16:30
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */


namespace Strever\Db\Mongo;
use Yaf\Registry;

class Connect
{
    /**
     * @var  Mongo  $connection  MongoDB Connection instance
     */
    protected $connection;

    /**
     * @var boolean $connected If there is a current connection
     */
    protected $is_connected = false;

    /**
     * @var \MongoDB
     */
    protected $mongodb;

    /**
     * Establishes a MongoDB connection
     *
     * @param string $server  mongo dns
     * @param array  $options connection options
     */
    public function __construct($server = null, array $options = array())
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
                $this->connection = new \MongoClient($server, $option);
                if ($option['db']) {
                    $this->mongodb = $this->connection->selectDB($option['db']);
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
     * Connection injector
     *
     * @param object $connection MongoClient instance
     *
     * @return object $this
     */
    public function setConnection(MongoClient $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Retrieve the MongoConnection.
     *
     * @return object Mongo instance
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return \MongoDB
     */
    public function getMongoDb() {
        return $this->mongodb;
    }

    public function setMongDb($dbName) {
        return $this->connection->{$dbName};
    }

    /**
     * Connect to the database.
     *
     * @return boolean connection result
     */
    public function connect()
    {
        if ($this->connection->connect()) {
            $this->is_connected = true;

            return true;
        }

        return false;
    }

    /**
     * Disconnect from a mongo database.
     *
     * @return boolean disconnect result
     */
    public function disconnect()
    {
        if ($this->connection->close()) {
            $this->is_connected = false;

            return true;
        }

        return false;
    }

    /**
     * Returns whether the connection is connection.
     *
     * @return bool whether there is a connection
     */
    public function isConnected()
    {
        return $this->is_connected;
    }

    /**
     * Drops a database.
     *
     * @param string $database database name
     *
     * @return boolean whether the database was dropped successfully
     */
    public function dropDatabase($database)
    {
        $result = $this->connection->{$database}->command(array('dropDatabase' => 1));

        return (bool) $result['ok'];
    }

    /**
     * Returns whether a database exists.
     *
     * @param boolean $name database name
     *
     * @return boolean whether the database exists
     */
    public function hasDatabase($name)
    {
        return in_array($name, $this->listDatabases(false));
    }

    /**
     * Returns a list of databases.
     *
     * @param boolean $detailed return detailed information
     *
     * @return array array containing database name or info arrays
     */
    public function listDatabases($detailed = false)
    {
        $result = $this->connection->listDBs();

        if ($detailed) {
            return $result;
        }

        return array_map(
            function ($database) {
                return $database['name'];
            },
            $result['databases']
        );
    }
}
