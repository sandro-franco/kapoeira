<?php
require_once 'conexao/conexao.php';
class CarregaDados {        
        
        public function __construct() {
            $this->conexao = new Conexao();
        }
        
        public function carregaProjeto($idProjeto = null)
        {
            if (isset($idProjeto)) {
                $sql = "SELECT * FROM projetos WHERE codigo = $idProjeto AND ativo = 't' ORDER BY descricao";                
            }
            else {
                $sql = "SELECT * FROM projetos WHERE ativo = 't' ORDER BY descricao";                
            }
            return $this->conexao->consultaDados($sql);
            
        }
        
        public function carregasituacao()
        {
            $situacao = null;
            $result = $this->conexao->consultaDados("SELECT * FROM situacao ORDER BY ordem");
            
            foreach ($result as $key => $value) 
            {
                $situacao .= utf8_decode(trim($value['chave_situacao']).",".trim($value['descricao']).chr(13));
            }
            return $situacao;
        }
        
        public function carregaAtividades($idProjeto)
        {
            $atividades = null;
            
            $sql = "SELECT a.codigo, a.descricao AS descricao_atividade, p.descricao, chave_situacao, concluida, esforco, e.descricao AS desc_esforco
                    FROM atividades a
                    INNER JOIN projetos p ON cod_projeto = p.codigo
                    INNER JOIN situacao c ON cod_situacao = c.codigo
                    INNER JOIN esforco e ON cod_esforco = e.codigo
                    WHERE ativa = 't' AND p.codigo = $idProjeto
                    ORDER BY a.codigo";
            $result = $this->conexao->consultaDados($sql);
            foreach ($result as $key => $value) 
            {
                $descAtividade = $value['descricao_atividade']."<br/>".$value['esforco']." ".$value['desc_esforco'];
                $atividades .= utf8_decode($value['chave_situacao'].",".$value['codigo'].",".$descAtividade.",".$value['concluida'].chr(13));
            }

            return $atividades;
        }

    }
?>
