<?php

    //class de connetion : POO
    class Database
        {
            private static $dbHost = 'localhost';
            private static $dbName = 'mamy_burger';
            private static $dbUser = 'root';
            private static $dbUserPw = '';

            private static $connection = null;

            public static function connect()
                {
                    try
                        {
                            self::$connection = new PDO("mysql: host=".self::$dbHost."; dbname=".self::$dbName, self::$dbUser, self::$dbUserPw);
                            // $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        }

                    catch(PDOException $e)
                        {
                            die('ERREUR: '.$e->getMessage());
                        }

                    return self::$connection;
                }

                public static function disconnect()
                    {
                        self::$connection = null;
                    }

        }

        //connection 
    Database::connect();

?>