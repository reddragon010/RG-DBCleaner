<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require('autoloader.php');
        Autoloader::register();

        Config::load(array(
            'dbconfig' => array(
                'char' => array(
                    'user' => 'root',
                    'pass' => '',
                    'dsn' => 'mysql:host=127.0.0.1;dbname=characters'
                ),
                'auth' => array(
                    'user' => 'root',
                    'pass' => '',
                    'dsn' => 'mysql:host=127.0.0.1;dbname=auth'
                )
                )));

        $accounts = Account::select('id = 1');
        foreach($accounts as $account){
            $account->delete();
        }
        
        DatabaseManager::get('auth')->queue->execute();
        DatabaseManager::get('char')->queue->execute();
        ?>
    </body>
</html>
