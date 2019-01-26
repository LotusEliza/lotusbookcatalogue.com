
<?php

//Select connection type (PDO/mysqli)

//$con_type = "PDO";

class Singleton{

    protected function __construct() {}

    //************* Singleton (PDO connection) *********

    protected static $instance;
    public $contype;

    public static function getInstance() {
        if(empty(self::$instance)) {

            $configs=include ('config.php');
            $host=$configs['db']['host'];
            $username=$configs['db']['username'];
            $passwd=$configs['db']['passwd'];
            $name=$configs['db']['dbname'];
            $dbport=$configs['db']['dbport'];

                try {
                    self::$instance = new PDO("mysql:host=" . $host . ';port=' . $dbport . ';dbname=' . $name, $username, $passwd);
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                    self::$instance->query('SET NAMES utf8');
                    self::$instance->query('SET CHARACTER SET utf8');
                } catch (PDOException $error) {
                    echo $error->getMessage();
                }
        }
        return self::$instance;
    }

    public static function setCharsetEncoding() {
        if (self::$instance == null) {
            self::connect();
        }
        self::$instance->exec(
            "SET NAMES 'utf8';
			SET character_set_connection=utf8;
			SET character_set_client=utf8;
			SET character_set_results=utf8");
    }

//    //**************** MySQLi connection **********************
//
//    function connect()
//    {
//        $configs=include ('config.php');
//        $host=$configs['db']['host'];
//        $username=$configs['db']['username'];
//        $passwd=$configs['db']['passwd'];
//        $name=$configs['db']['dbname'];
//
//        $db = new mysqli("$host", "$username", "$passwd", "$name");
//        return $db;
//    }
}
