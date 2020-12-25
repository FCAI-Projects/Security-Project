<?php

class connectionmodel
{
    private $dsn = "mysql:host=localhost;dbname=pass";
    private $user = "root";
    private $pass = "";
    private $con = null;

    private $method = "AES-128-CBC";
    private $key = "FCAIH";
    private $options = 0;
    private $iv = "123456789asdfghj";
    private $tag = null;

    private $option = array(
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    );
    private $users_table = "users";
    private $table = "store";

    public function getconnection()
    {
        try {
            $this->con = new \PDO($this->dsn, $this->user, $this->pass, $this->option);
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function closecon()
    {
        try {
            $this->con = null;
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function checkuser($users, $pass)
    {
        try {
            $stmt = $this->con->prepare("select id, username, password from " . $this->users_table . " where username = ?");
            $stmt->execute(array($users));
            $row = $stmt->fetch();
            if ($this->showPassword($row["password"]) == $pass) {
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                return 1;
            }
            return 0;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function registeration($user, $pass)
    {
        try {
            $stmt = $this->con->prepare("INSERT INTO $this->users_table (username, password) VALUES (?, ?)");
            $encPass = openssl_encrypt($pass, $this->method, $this->key, $this->options, $this->iv);
            $stmt->execute(array($user, $encPass));
            session_start();
            $_SESSION['id'] = $this->con->lastInsertId();
            $_SESSION['username'] = $user;
            return 1;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function showPassword($pass)
    {
        try {
            return openssl_decrypt($pass, $this->method, $this->key, $this->options, $this->iv);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function get_table()
    {
        try {
            $id = $_SESSION["id"];
            $stmt = $this->con->prepare("SELECT * FROM `$this->table` WHERE user_id = $id");
            $stmt->execute(array());
            $row = $stmt->fetchAll();
            return $row;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function add_site($site, $pass)
    {
        try {
            $encryptPass = openssl_encrypt($pass, $this->method, $this->key, $this->options, $this->iv);
            $stmt = $this->con->prepare("INSERT IGNORE INTO `$this->table` (`site`, `password`, `user_id`) VALUES ( ?, ?, ?)");
            $stmt->execute(array($site, $encryptPass, $_SESSION['id']));
            $count = $stmt->rowCount();
            if ($count > 0) return 1;
            else return 0;
        } catch (PDOException $e) {
            echo $e;
        }

    }

    public function deleteSite($id)
    {
        try {
            $stmt = $this->con->prepare("delete from `$this->table` where id = ?");
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            if ($count > 0) return 1;
            else return 0;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function editSite($id, $site, $pass)
    {
        try {
            $encryptPass = openssl_encrypt($pass, $this->method, $this->key, $this->options, $this->iv);;
            $stmt = $this->con->prepare("UPDATE $this->table SET site = ?, password = ? WHERE id = ?");
            $stmt->execute(array($site, $encryptPass, $id));
            $count = $stmt->rowCount();
            if ($count > 0) return 1;
            else return 0;

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function checkAdmin() {
        try {
            $id = $_SESSION["id"];
            $stmt = $this->con->prepare("SELECT 'admin' FROM `$this->users_table` WHERE id = $id");
            $stmt->execute(array());
            $row = $stmt->fetch();
            if ($row["admin"] == 1) return true;
            else return false;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getAllUsers() {
        $stmt = $this->con->prepare("SELECT * FROM `$this->users_table`");
        $stmt->execute(array());
        $rows = $stmt->fetchAll();
        return $rows;
    }

}

    
    
    