<?php

if(!isset($_POST['send'])){
    echo '
        Please fill in your database settings to import the database file.
        <form method="POST" action="" name="database">
            <input type="text" placeholder="User" name="user" />
            <input type="text" placeholder="Pass" name="pass" />
            <input type="text" placeholder="Database" name="dbase" />
            <input type="text" placeholder="Host" name="host" />
            <input type="submit" value="Install" name="send" />
        </form>
        ';
} elseif($_POST['send']){
    $user  = $_POST['user'];
    $pass  = $_POST['pass'];
    $host  = $_POST['host'];
    $dbase = $_POST['dbase'];
    
    try{
        $dsn = 'mysql:host='.$host.';dbname='.$dbase;
        $db = new PDO($dsn, $user, $pass);
        try{
            $sql = file_get_contents('./database.sql');
            if( $iDB = $db->exec($sql) ){
                echo 'Installed succesfully';
            }
        } catch(PDOException $e){
            echo $e->getMessage();
        }
    } catch(PDOException $e){
        try{
            $dsn = 'mysql:host='.$host.';';
            $db = new PDO($dsn, $user, $pass);
            $prep = $db->prepare("CREATE DATABASE :dbase;
                            GRANT ALL ON :dbase.* TO :user@:host;
                            FLUSH PRIVILEGES;");
            $prep->execute(array(':dbase'=>$dbase, ':user'=>$user, ':host'=>$host));
            } catch(PDOException $e){
                echo $e->getMessage();
                echo '
                    Please fill in your database settings to import the database file.
                    <form method="POST" action="" name="database">
                        <input type="text" placeholder="User" name="user" />
                        <input type="text" placeholder="Pass" name="pass" />
                        <input type="text" placeholder="Database" name="dbase" />
                        <input type="text" placeholder="Host" name="host" />
                        <input type="submit" value="Install" name="send" />
                    </form>
                    ';
            }
    }
}

?>