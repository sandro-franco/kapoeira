<?php

require_once '../conexao/conexao.php';


class Ajax {

    function __construct() {
        $this->conexao = new Conexao();
    }
    
    public function atualizarSituacaoAtividade($arrayPost) 
    {
        if (strpos($arrayPost['chaveSituacao'], 'FINISH'))
        {
            $sql = "UPDATE atividades SET 
                    concluida = 't', 
                    cod_situacao = (SELECT codigo FROM situacao WHERE chave_situacao = '".$arrayPost['chaveSituacao']."'),
                    data_fim = CURRENT_DATE
                    WHERE codigo = ".$arrayPost['idAtividade'];
        }
        else {
            $sql = "UPDATE atividades SET 
                    concluida = 'F', 
                    cod_situacao = (SELECT codigo FROM situacao WHERE chave_situacao = '".$arrayPost['chaveSituacao']."'),
                    data_inicio = CURRENT_DATE,
                    data_fim = NULL
                    WHERE codigo = ".$arrayPost['idAtividade'];
        }
         
        $this->conexao->executeQuery($sql);
    }
    
    public function atualizarAtividade($arrayPost) 
    {
        $sql = "UPDATE atividades SET 
                descricao = '".$arrayPost['atividade']."', 
                esforco = '".$arrayPost['esforco']."', 
                cod_projeto = '".$arrayPost['projeto']."', 
                cod_situacao = '".$arrayPost['situacao']."', 
                cod_esforco = '".$arrayPost['tipo_esforco']."'
                WHERE codigo = ".$arrayPost['idAtividade'];
         echo $sql;
        $this->conexao->executeQuery($sql);
    }
    
    public function incluirAtividade($arrayPost) 
    {
        $sql = "INSERT INTO atividades (descricao, cod_projeto, cod_situacao, data_inicio, cod_esforco, esforco) 
                VALUES ('".$arrayPost['atividade']."', ".$arrayPost['projeto'].", ".$arrayPost['situacao'].", CURRENT_DATE, ".$arrayPost['tipo_esforco'].", ".$arrayPost['esforco'].");";
        
        $this->conexao->executeQuery($sql);
    }
    
    public function deletarAtividade($arrayPost) 
    {
        $sql = "UPDATE atividades SET ativa = 'f' WHERE codigo = ".$arrayPost['idAtividade'].";";
        $this->conexao->executeQuery($sql);
    }
}

if (isset($_POST))
{
    $ajax = new Ajax();
    
    if ($_POST['acao'] == "atualizarSituacaoAtividade")
    {
        $ajax->atualizarSituacaoAtividade($_POST);
    }
    else if ($_POST['acao'] == "incluirAtividade")
    {
        $ajax->incluirAtividade($_POST);
    }
    else if ($_POST['acao'] == "deletarAtividade")
    {
        $ajax->deletarAtividade($_POST);
    }
    else if ($_POST['acao'] == "atualizarAtividade")
    {
        $ajax->atualizarAtividade($_POST);
    }
    
    
    
    
    
}




/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
