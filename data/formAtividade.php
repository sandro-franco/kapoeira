<?php

require_once '../conexao/conexao.php';
$conexao = new Conexao();
$acao = isset($_GET["acao"]) ? $_GET["acao"] : "";
$idProjeto = isset($_GET["idProjeto"]) ? $_GET["idProjeto"] : "";
$idAtividade = isset($_GET["idAtividade"]) ? $_GET["idAtividade"] : "";
$descricao = $esforco = $atividade = "";

if ($acao == 'atualizarAtividade')
{
    
    $sql = "SELECT a.codigo, a.descricao AS descricao_atividade, cod_projeto, cod_situacao, esforco, cod_esforco
            FROM atividades a
            INNER JOIN projetos p ON cod_projeto = p.codigo
            INNER JOIN situacao c ON cod_situacao = c.codigo
            INNER JOIN esforco e ON cod_esforco = e.codigo
            WHERE a.codigo = ".$idAtividade."
            ORDER BY a.codigo;";
    $atividade = $conexao->consultaDados($sql);
    $atividade = $atividade[0];
    $descricao = $atividade['descricao_atividade'];
    $esforco   = $atividade['esforco'];
    
}

$sql = "SELECT * FROM projetos WHERE ativo = 't' AND codigo = $idProjeto ORDER BY descricao";
$arrayProjetos = $conexao->consultaDados($sql);

$combo_projetos = '<select id="projeto" class="ajax-input">';
foreach ($arrayProjetos as $key => $value)
{
    $combo_projetos .= '<option value="'.$value['codigo'].'">'.$value['descricao'].'</option>';
}
$combo_projetos .= '</select>';


if ($acao == 'atualizarAtividade')
    $sql = "SELECT * FROM situacao ORDER BY ordem";
else
    $sql = "SELECT * FROM situacao WHERE chave_situacao not like '%FINISH%' ORDER BY ordem";

$arraySituacao = $conexao->consultaDados($sql);

$x = 0;

$combo_situacao = '<select id="situacao" class="ajax-input">';
foreach ($arraySituacao as $key => $value)
{
    if ($x == 0) //seta o 1º item como selected para que seja reconhecido quando salvar na insersao
    {
        $combo_situacao .= '<option value="'.$value['codigo'].'" selected>'.$value['descricao'].'</option>';
        $x++;
    }
    else if ( (isset($atividade)) && ($atividade['cod_situacao'] == $value['codigo']))
    {
        $combo_situacao .= '<option value="'.$value['codigo'].'" selected>'.$value['descricao'].'</option>';
    }
    else 
    {
        $combo_situacao .= '<option value="'.$value['codigo'].'">'.$value['descricao'].'</option>';
    }
}
$combo_situacao .= '</select>';

$sql = "SELECT * FROM esforco ORDER BY codigo";
$arrayEsforco = $conexao->consultaDados($sql);
$x = 0;

$combo_esforco = '<select id="tp_esforco" class="ajax-input" style="width:265px;">';
foreach ($arrayEsforco as $key => $value)
{
    if ($x == 0) //seta o 1º item como selected para que seja reconhecido quando salvar na insersao
    {
        $combo_esforco .= '<option value="'.$value['codigo'].'" selected>'.$value['descricao'].'</option>';
        $x++;
    }
    else if ( (isset($atividade)) && ($atividade['cod_esforco'] == $value['codigo']))
    {
        $combo_esforco .= '<option value="'.$value['codigo'].'" selected>'.$value['descricao'].'</option>';
    }  
    else 
    {
        $combo_esforco .= '<option value="'.$value['codigo'].'">'.$value['descricao'].'</option>';
    }
}
$combo_esforco .= '</select>';

$output = "<div style='display:none'>
<div class='ajax-top'></div>
<div class='ajax-content'>
        <h1 class='ajax-title'>Nova Atividade:</h1>
        <div class='ajax-loading' style='display:none'></div>
        <div class='ajax-message' style='display:none'></div>
        <form action='#' style='display:none'>";

                $output .= "<label for='atividade'>Atividade:</label>";
                $output .= "<textarea id='atividade' maxlength='150' class='ajax-input' name='atividade' cols='5' rows='2' tabindex='1004'>$descricao</textarea>";

                $output .= "<br/>";

                $output .= "<label for='projeto'>Projeto:</label>";
                $output .= $combo_projetos;

                $output .= "<label for='situacao'>Situa&ccedil;&atilde;o:</label>";
                $output .= $combo_situacao;

                $output .= "<label for='esforco'>Esfor&ccedil;o:</label>
                            <input type='text' id='esforco' class='ajax-input' style='width:30px;' name='esforco' tabindex='1001' value='$esforco'/>
                            $combo_esforco
                            <br/>";

                $output .= "    <br/>
                                <label>&nbsp;</label>
                                <button type='submit' class='ajax-send ajax-button' tabindex='1006'>Salvar</button>
                                <button type='submit' class='ajax-cancel ajax-button simplemodal-close' tabindex='1007'>Cancelar</button>
                                <br/>
                                <input type='hidden' id='acao' value='$acao'/>
                                <input type='hidden' id='idAtividade' value='$idAtividade'/>
        </form>
</div>
</div>";

echo $output;

?>