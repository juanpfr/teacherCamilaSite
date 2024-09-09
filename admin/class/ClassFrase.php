<?php

require_once('Conexao.php');

class ClassFrase{

    //Atributos
    public $idFrase;
    public $nomeFrase;
    public $textoFrase;
    public $statusFrase;
    

    public function __construct($id = false)
    {
        if($id) //se colocar $id = true ele não vai
        {
            $this->idFrase = $id;
            $this->Carregar();
        }
    }

    //Metodos da class

        //Listar
        
        public function Listar(){ //criação do método Listar sempre com letra maiúscula para melhor entendimento do código (não é obrigatório)
            $sql = "select * from tbl_frase where statusFrase = 'ATIVO' order by textoFrase desc;"; //comando do banco de dados // no caso esse é para listar tudo da tabela servico

            $conn = Conexao::LigarConexao(); //variavel que recebe os parametros de ligar conexão feito na ClassConexao

            $resultado = $conn->query($sql); //executa o comando (ctrl + enter no banco de dados)

            $lista = $resultado->fetchAll(); //deixa em linhas e colunas igual aparece no banco de dados

            return $lista;
        }

        public function Inserir(){
            $sql = "INSERT INTO tbl_frase (nomeFrase, textoFrase, statusFrase)
            VALUES ('$this->nomeFrase','$this->textoFrase',  '$this->statusFrase')";

            $conn = Conexao::LigarConexao(); // Ligando a conexão do arquivo Conexao.php

            $conn->exec($sql); // Exec é do PDO, executa uma instrução SQL e retorna o número de linhas afetadas

            echo"<script> document.location='index.php?p=frase' </script>";
            
        }

        //Carregar
        public function Carregar(){
            $sql = "SELECT * from tbl_frase WHERE idFrase = $this->idFrase";
            $conn = Conexao::LigarConexao();
            $resultado = $conn->query($sql);
            $frase = $resultado->fetch();
            
        //atributos da class / colunas do banco
            $this->idFrase = $frase['idFrase'];
            $this->nomeFrase = $frase['nomeFrase'];
            $this->textoFrase = $frase['textoFrase'];
            $this->statusFrase = $frase['statusFrase'];
        }

        public function Atualizar(){
            $sql = "UPDATE tbl_frase set nomeFrase = '".$this->nomeFrase."', textoFrase = '".$this->textoFrase."', statusFrase = '".$this->statusFrase."' WHERE idFrase = $this->idFrase;";
            $conn = Conexao::LigarConexao();
            $conn->exec($sql);

            echo"<script> document.location='index.php?p=frase' </script>";
        }

         //DESATIVAR 

        public function Desativar($id){
        $sql = "UPDATE tbl_frase set statusFrase = 'INATIVO' where idFrase = $id";
        $conn = Conexao::LigarConexao();
        $resultado = $conn->query($sql);

        echo"<script> document.location='index.php?p=frase' </script>";
        }

        // Método para obter uma frase aleatória do banco de dados
        public function obterFraseAleatoria() {
            // Conecta ao banco de dados
            $conn = Conexao::LigarConexao();
        
            // Verifica a última frase e a hora em que foi armazenada
            $query = "SELECT textoFrase, dataHora FROM tbl_frase_aleatoria ORDER BY dataHora DESC LIMIT 1";
            $stmt = $conn->query($query);
        
            if ($stmt === false) {
                // Se a consulta falhar, retorna uma mensagem de erro ou 'sem frase.'
                return 'sem frase.';
            }
        
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($row === false) {
                // Se não houver resultados, retorna 'sem frase.'
                return 'sem frase.';
            }
        
            $ultimaFrase = $row['textoFrase'];
            $ultimaDataHora = new DateTime($row['dataHora']);
            $dataHoraAtual = new DateTime();
        
            // Verifica se a frase foi armazenada há mais de 24 horas
            $intervalo = $dataHoraAtual->diff($ultimaDataHora);
        
            if ($intervalo->days >= 1) {
                // Seleciona uma nova frase aleatória
                $query = "SELECT textoFrase FROM tbl_frase ORDER BY RAND() LIMIT 1";
                $stmt = $conn->query($query);
        
                if ($stmt === false) {
                    // Se a consulta falhar, retorna 'sem frase.'
                    return 'sem frase.';
                }
        
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($row === false) {
                    // Se não houver resultados, retorna 'sem frase.'
                    return 'sem frase.';
                }
        
                $novaFrase = $row['textoFrase'];
        
                // Atualiza a tabela com a nova frase e a data/hora atual
                $query = "REPLACE INTO tbl_frase_aleatoria (id, textoFrase, dataHora) VALUES (1, :textoFrase, :dataHora)";
                $stmt = $conn->prepare($query);
        
                if ($stmt === false) {
                    // Se a preparação da consulta falhar, retorna 'sem frase.'
                    return 'sem frase.';
                }
        
                $stmt->execute([
                    ':textoFrase' => $novaFrase,
                    ':dataHora' => $dataHoraAtual->format('Y-m-d H:i:s')
                ]);
        
                return $novaFrase;
            } else {
                return $ultimaFrase;
            }
        }
        
        
        
}