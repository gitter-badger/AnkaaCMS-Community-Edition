<?php
ini_set('error_reporting', 1);
function settings($section, $setting){
        $sCWD = $_SERVER['DOCUMENT_ROOT'];
        $aCWD = explode(DIRECTORY_SEPARATOR, $sCWD);
        for($i=0;$i<count($aCWD);$i++){
            $path = implode($aCWD, DIRECTORY_SEPARATOR);
            if(file_exists($path.DIRECTORY_SEPARATOR.'webdata')){
                $webdata = $path.DIRECTORY_SEPARATOR.'webdata';
                break;
            } else { 
                array_pop($aCWD);
            }
        }
        try{
            $iniPath = $webdata.DIRECTORY_SEPARATOR.'config.ini';
            if(file_exists($iniPath)){
                $parsedIni = parse_ini_file($iniPath, true);
                $parsedIni;
                if(isset($parsedIni[$section][$setting])){
                    return $parsedIni[$section][$setting];
                } else {
                    return '';
                }
                
            } else {
                throw new Exception(_('No config file present'));
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
  
$sql = file_get_contents('./database.sql');

if(!settings('database', 'hostname')){
    echo _('Please fill your config.ini file first!');
}

$dsn = 'mysql:host='.settings('database','hostname').';port='.settings('database','port').';';
try{
    $db = new PDO($dsn, settings('database','username'), settings('database','password'));
    try{
        $db->exec('USE '.settings('database', 'database'));
        try{
            $db->exec($sql);
            echo _('Done');
        } catch (PDOException $e) {
            echo _('Database content could not be imported');
        }
    } catch (PDOException $e) {
        try{
            $db->exec('CREATE DATABASE '.settings('database', 'database').'; USE '.settings('database', 'database'));
            $db->exec($SQL);
            echo _('Done');
        } catch(PDOException $e){
            echo _('Database does not exist and could not create one.');
        }
    }
} catch(PDOException $e){
    echo $e->getMessage();
    //header('Location: /install/');
}

?>