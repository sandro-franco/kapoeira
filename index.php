<?php header('Content-Type: text/html; charset=iso-8859-1');?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
        <title>Kapoeira - DE505</title>
        <script type='text/javascript' src='js/jquery-refresh.js'></script>
        <script type='text/javascript' src='js/jquery-1.4.2.min.js'></script>
        <script type='text/javascript' src='js/jquery.simplemodal.js'></script>
        <script type='text/javascript' src='js/ajaxAtividade.js'></script>
        <script type='text/javascript' src='js/jquery.contextMenu.js'></script>
        <script type='text/javascript' src='js/jquery.alerts.js'></script>
        
        <!-- CSS files -->
        <link type='text/css' href='css/ajax.css' rel='stylesheet' media='screen' />
        <link type='text/css' href='css/style.css' rel='stylesheet' media='screen' />        
        <link type='text/css' href='css/jquery.contextMenu.css' rel='stylesheet' media='screen' />        
        <link type='text/css' href='css/jquery.alerts.css' rel='stylesheet' media='screen' />        
    </head>
<body>
<?php
    require_once 'data/carrega_dados.php';
    $carregaDados = new CarregaDados();
    
    $idProjeto = (isset($_GET['idProjeto'])) ? ($_GET['idProjeto']) : "";
    
    $arrayProjetos = $carregaDados->carregaProjeto();

//    echo base64_encode(2)."<br/>";

    $combo_projetos = '<div id="divProjeto">';
    $combo_projetos .= '    <select id="cbxProjeto" class="combo">';
    $combo_projetos .= '        <option value="-1">-- Selecione --</option>';
    foreach ($arrayProjetos as $key => $value)
    {
        $codigo = base64_encode($value['codigo']);
        
        if ($idProjeto == $codigo)
        {
            $combo_projetos .= '    <option value="'.$codigo.'" selected>'.$value['descricao'].'</option>';
        }
        else
        {
            $combo_projetos .= '    <option value="'.$codigo.'">'.$value['descricao'].'</option>';
        }
    }
    $combo_projetos .= '    </select>';
    $combo_projetos .= '</div>';

    echo $combo_projetos;
    
    if($idProjeto){
  ?>
    <div id='container'>
	<div id='content'>
		<div id='ajax-form'>
                        <a id="" class="ajax button" href="#" style="float:right;">Nova Atividade</a>
		</div>
		<!-- preload the images -->
		<div style='display:none'>
			<img src='img/ajax/loading.gif' alt='' />
		</div>
	</div>
    </div>
    <!-- The data for the stories -->
    <textarea rows="1" name="stories" id="stories" style="visibility: hidden;">
      <?php echo $carregaDados->carregaAtividades(base64_decode($idProjeto)); ?>
    </textarea>
    
    
    <textarea rows="1" name="states" id="states" style="visibility: hidden;">
      <?php echo $carregaDados->carregasituacao();  ?>
    </textarea>

    <!-- The HTML starts here -->
<!--    <div id="title_projeto" class="projeto" style="float:left;">
        <?php //$projeto = $carregaDados->carregaProjeto($idProjeto);?></b>
        <b>KANBAN <?php //echo $projeto[0]['descricao'];?></b>
    </div>-->

    <br/>
    <br/>

    <a id="board_link" class="button" href="#" style="float:right;">Painel</a>
    <div id="output"></div>
    <div class="clear"></div>

    <textarea id="data_output" rows="20" cols="100"></textarea>
    <div class="clear"></div>
    <ul id="myMenu" class="contextMenu">
        <li class="edit"><div id="ajax-form"><a class="ajax button" href="#editar">Editar</a></div></li>
<!--            <li class="cut separator"><a href="#recortar">Recortar</a></li>-->
<!--            <li class="copy"><a href="#copiar">Copiar</a></li>-->
<!--            <li class="paste"><a href="#colar">Colar</a></li>-->
        <li class="delete"><a href="#deletar">Deletar</a></li>
<!--        <li class="quit separator"><a href="#sair">Sair</a></li>-->
    </ul>
    <input type='hidden' id='idProjeto' value='<?php echo base64_decode($idProjeto)?>'/>
<?php } ?>
    
    </body>
</html>
