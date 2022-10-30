<?php

namespace App\Entity;

use App\Db\Database;
use \PDO;

class Vaga{


    /**
     * Identificador único da vaga
     * @var integer
     */
    public $id;

    /**
     * Titulo da vaga
     * @var string
     */
    public $titulo;


    /**
     * Descrição da vaga(pode conter html)
     * @var string
     */
    public $descricao;

    /**
     * Define se a Vaga está ativa
     * @var string(s/n)
     */
    public $ativo;

    /**
     * Data da publicação da vaga
     * @var string
     */
    public $data;



    /**
     * Metodo responsavel por cadastrar uma nova vaga no banco
     * @return boolean
     */
    public function cadastrar(){
        // Definir a data
        $this->data = date('Y-m-d H:i:s');
        // inserir a vaga
        $obDatabase = new Database('vagas');
        $this->id = $obDatabase->insert([
            'titulo' => $this-> titulo,
            'descricao' => $this-> descricao,
            'ativo' => $this->ativo,
            'data' => $this-> data
        ]);

        
        // retornar sucesso

        return true;
    }

    /**
     * Metodo responsavel por pegar as vagas no banco
     * @param   string $where
     * @param   string $order
     * @param   string $limit 
     * @return  array
     */
    public static function getVagas($where = null, $order = null, $limit = null){
        return(new Database('vagas'))->select($where,$order,$limit)
        ->fetchAll(PDO::FETCH_CLASS,self::class);
    }


    /**
     * Metodo responsavel por atualizar a vaga no banco
     * @return boolean
     * */ 
    public function atualizar(){
        return (new Database('vagas'))->update('id = '.$this->id,[
            'titulo' => $this-> titulo,
            'descricao' => $this-> descricao,
            'ativo' => $this->ativo,
            'data' => $this-> data
        ]);
    }


    /**
     * Metodo responsavel por excluir a vaga
     * @return boolean
     */
    public function excluir(){
        return (new Database('vagas'))->delete('id= '.$this->id);
    }

    /**
     * Metodo responsavel por buscar uma vaga com base no ID
     * @param integer $id
     * @return Vaga
     */
    public static function getVaga($id){
        return(new Database('vagas'))->select('id= '.$id)
        ->fetchObject(self::class);
    }



}