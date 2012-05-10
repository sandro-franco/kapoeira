<?php
class Conexao {        
        
        public function __construct() {
            $this->conexao = new PDO('pgsql:host=serprosdr-psc01;dbname=kapoeira_dev', "postgres", "");
            $this->sth = null;
        }
        
        public function getConexao()
        {
            return $this->conexao;
        }


        public function consultaDados($sql, $fetchAll = true) 
        {
            $this->executeQuery($sql);
            
            if (!$fetchAll)
            {
                $result = $this->sth->fetch(PDO::FETCH_ASSOC);
            }
            else 
            {
                 $result = $this->sth->fetchAll();
            }
            
            return $result;
        }
        
        public function executeQuery($sql) 
        {
            try {
                $this->sth = $this->conexao->prepare($sql);
                $this->sth->execute();
                
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }
    }
?>
