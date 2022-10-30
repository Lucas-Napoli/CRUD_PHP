<?php

namespace App\Db;

use \PDO;
use \PDOException;


class Database{

  /**
   * Host de conexão com o banco de dados
   * @var string
   */
  const HOST = 'localhost';

  /**
   * Nome do banco de dados
   * @var string
   */
  const NAME = 'wdev_vagas';

  /**
   * Usuário do banco
   * @var string
   */
  const USER = 'root';

  /**
   * Senha de acesso ao banco de dados
   * @var string
   */
  const PASS = '';

  /**
   * Nome da tabela a ser manipulada
   * @var string
   */
  private $table;

  /**
   * Instancia de conexão com o banco de dados
   * @var PDO
   */
  private $connection;

  /**
   * Define a tabela e instancia e conexão
   * @param string $table
   */
  public function __construct($table = null){
    $this->table = $table;
    $this->setConnection();
  }

  /**
   * Método responsável por criar uma conexão com o banco de dados
   */
  private function setConnection(){
    try{
      $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
      die('ERROR: '.$e->getMessage());
    }
  }


  /**
   * Metodo responsavel por executar queries dentro do banco de dados
   * @param string $query
   * @param array $params
   * @return PDOstatement
   */
  public function execute($query,$params = []){
    try{
        $statement = $this->connection->prepare($query);
        $statement->execute($params);
        return $statement;
      }catch(PDOException $e){
        die('ERROR: '.$e->getMessage());
      }
  }


  /**
   * Metodo responsavel por inserir dados no banco
   * @param array $values [Field => value]
   * @return integer ID inserido
   */
  public function insert($values){

    // Dados da query
    $fields = array_keys($values);
    $binds = array_pad([],count($fields),'?');

    

    //Monta a query
    $query = 'INSERT INTO '.$this->table.' ('.implode(',',$fields).') VALUES ('.implode(',',$binds).')';

    //executa o insert
    $this->execute($query,array_values($values));

    // retorna o ID inserido
    return $this->connection->lastInsertId();
  }


  /**
   * Metodo responsavel por consultar o banco
   * @param string $where
   * @param string $order
   * @param string $limit
   * @param string $fields
   * @return PDOStatement
   */
  public function select($where = null, $order = null, $limit = null, $fields = '*'){
    //DADOS DA QUERY
    $where = strlen($where) ? 'WHERE '.$where : '';
    $order = strlen($order) ? 'ORDER BY '.$order : '';
    $limit = strlen($limit) ? 'LIMIT '.$limit : '';

    //MONTA A QUERY
    $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

    //EXECUTA A QUERY
    return $this->execute($query);
    }


    /**
     * Metodo responsavel por executar atualizações no banco
     * @param string $where
     * @param string $array [field => value]
     * @return boolean 
     */
    public function update($where,$values){

        //dados da query
        $fields = array_keys($values);



         //monta a query
        $query = 'UPDATE '.$this->table. ' SET '.implode('=?,',$fields).'=? WHERE '.$where;
        
        // executar a query
        $this->execute($query,array_values($values));

        return true;
    }


    /**
     * Metodo responsavel por excluir dados do banco
     * @param string $where 
     * @return boolean 
     */
    public function delete($where){

        //monta a query
        $query = 'DELETE FROM ' .$this->table.' WHERE '.$where;

        //executa a query
        $this->execute($query);

        return true;
    }
}