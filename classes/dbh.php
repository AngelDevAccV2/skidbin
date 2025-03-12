<?php

/*class Dbh {

    public function connect() {*/
try {
     $username = "root";
    $password = "";
    $dbh = new PDO('mysql:host=localhost;dbname=skidildc_web', $username, $password);
    return $dbh;
} catch (PDOException $e) {
    print "Error!: " .$e->getMessage() . "<br/>"; // CHANGE THIS ON PRODUCTION!!!!
    die();
}
/*    }

}*/