<?php

class BaseDao {

    public $conn;

    /**
    * constructor of dao class
    */
    public function __construct(){
        try {

          $host = "db-mysql-nyc1-13993-do-user-3246313-0.b.db.ondigitalocean.com";
          $port = 25060;
          $dbname = "final-midterm2-2023";
          $user = "doadmin";
          $pass = "AVNS_z6PG_c6BSn-5dB0CG5S";


        /*options array neccessary to enable ssl mode - do not change*/
        $options = array(
        	PDO::MYSQL_ATTR_SSL_CA => 'https://drive.google.com/file/d/1zqyqk92mI4A4cAW43nhnCWxEveGSkY7k/view?usp=sharing',
        	PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,

        );

        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass, $options);

        // set the PDO error mode to exception
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

}
?>
