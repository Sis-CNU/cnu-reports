<?php
namespace Controller;

class Session{

    use Singleton;

    public function start($nameUser): bool{

        $started = session_start();
        $_SESSION["count"] = $nameUser;

        return $started;

    }

    public function getId(){

        session_start();
        return session_id();

    }

    public function close(string $session_id_to_close = NUll): bool{

        if($session_id_to_close == NULL){
            session_start();
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
        }else{
            session_id($session_id_to_close);
            session_start();
        }
         
        return session_destroy();


    }
    
    public function check(): bool{
        
        session_start();
        $check = isset($_SESSION["count"]);
        //$check = true;

        return $check;

    }
}

