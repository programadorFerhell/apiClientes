<?php

require_once "Config.php";

class Conexao
{
    public static function abreConexao()
    {
        try { 
            $sql = new PDO('mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
            $sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
            return $sql;
        } catch(PDOException $e) { 
            throw new Exception("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
       
    }

    public static function fechaConexao()
    {
        $sql = null; // Fecha a Conexão

        return $sql;
    }
    
}