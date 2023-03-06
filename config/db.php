<?php

namespace configuration;

use mysqli;
use Exception;
use Throwable;

class db{


    static $host = "localhost";
    static $dbname = "db_biblioteca";
    static $username = "root";
    static $passwd = "";

    protected static function connect(){
        return new mysqli(self::$host, self::$username, self::$passwd, self::$dbname);
    }

    public static function dropbox_backup(){
        $dbuser = self::$username;
        $dbpass = self::$passwd;
        $dbname = self::$dbname;
        // exec("mysqldump -u $dbuser -p$dbpass $dbname | gzip > db_backup.sql.gz");
        // exec('/Applications/XAMPP/xamppfiles/bin/mysqldump -u '.$dbuser.' --password="'.$dbpass.'" '.$dbname.' | gzip > /Applications/XAMPP/xamppfiles/htdocs/ws-sii-v2/config/db_backup.sql.gz');
        exec('mysqldump -u ' . $dbuser . ' --password="' . $dbpass . '" ' . $dbname . ' | gzip > db_backup.sql.gz');
        // exec("/Applications/XAMPP/xamppfiles/bin/mysqldump -u itsesedu_siiAdmi --password='&mAy&**nT]-R(BR0Uo' itsesedu_itsesedu_sii_2 | gzip > db_backup.sql.gz");
        // mysqldump -u itsesedu_siiAdmi -p&mAy&**nT]-R(BR0Uo itsesedu_itsesedu_sii_2 | gzip > db_backup.sql.gz
        // return 'exec("/Applications/XAMPP/xamppfiles/bin/mysqldump -u '.$dbuser.' --password="'.$dbpass.'" '.$dbname.' | gzip > db_backup.sql.gz")';
    }

    public static function query($query){
        try  {
            $db = self::connect();
            $db->set_charset("utf8");
            $result = $db->query($query);
            while ($row = $result->fetch_object()) {
                $results[] = $row;
            }
        } catch(Throwable $th) {
            $results = ["error" => true, "errno" => $th->getCode(), "message" => $th->getMessage()];
        }
        $db->close();
        return @$results;
    }



    public static function stored_procedure($querys){
        $db = self::connect();
        if ($db->connect_errno) {
            printf("Falló la conexión: %s\n", mysqli_connect_error());
            exit();
        }
        $db->set_charset("utf8");
        $db->autocommit(false);
        try {
            foreach ($querys as $i => $query) {
                $db->query($query);
            }
            $db->commit();
            $result = TRUE;
        } catch (Exception $e) {
            $db->rollback();
            $result = FALSE;
        }
        $db->close();
        return $result;
    }
}
