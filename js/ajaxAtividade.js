/*
 * SimpleModal Contact Form
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 */
jQuery.noConflict();
var $ = jQuery.noConflict();
var executaRefresh = true;

jQuery(function ($) {
    
    $("#board_link").hide();
    $("#data_output").hide();
    var arrayAtividades = new Array();

    /*Incializa as situacoes dos projetos */
    var init_states = function(states_input) {
        var states = {}
            var states_order = []
            for ( var i=0, len=states_input.length; i<len; i++ ) {
                    var state = states_input[i].split(",");

                    if (state.length == 2) {
                        var state_0 = trim(state[0])
                        var state_1 = trim(state[1])
                            states[state_0] = state_1;
                            states_order.push(state_0);
                    }
            }
        return {states: states, states_order: states_order};
    }
    
    
    /*Inicializar atividades */
    var init_board = function(stories) {
            var board = {};
            for ( var i=0, len=stories.length; i<len; i++ ) {
                    var story = stories[i].split(",");
                    var state = trim(story[0]);
                    if (! board[state]) {
                            board[state] = [];
                    }
                    board[state].push(story);
            }
            return board;
    }
    
    /*Cria as atividades associadas as situacoes */
    var create_list = function(board, state) { 
           var x = arrayAtividades.length;
           var list = $("<ul class=\"state\" id=\"" + state + "\"></ul>");
           if (board[state]) {
             for (var i=0, len=board[state].length; i<len; i++) {
                 var id_task = board[state][i][1];
                 
                 arrayAtividades[x] = id_task;
                 x++;
                 
                 var story_element = $("<li id=\"myDiv_"+id_task+"\"><div id=\""+id_task+"\" class=\"box box_" + state  + "\">" + board[state][i][2] + "</div></li>");
                 story_element.data("story",  board[state][i]);
                 list.append(story_element);
             }
           }
           return list
    }

    /*Cria as situacoes e situacoes concluidas (EX: Testes -> Testes Concluidos) */
    var create_column = function(board, state, headline) {
               var state_column = $("<div class=\"dp10" + ((! /_FINISH$/.test(state)) ? "" : " queue_column")+ "\"></div>")
               state_column.append($("<div class=\"headline\">" + headline + "</div>"));
               state_column.append(create_list(board, state));
               state_column.data("state", state);
               return state_column;
    }
    
    /*Monta a tela com as situacoes e atividades criadas */
    var create_board = function(app_data) {
            var table = $("<div id=\"board\"></div>");
            var ids = "";

            var state_s = "";        
            
            for (j=0; j< app_data.states_order.length; j++) {
                    var state = app_data.states_order[j];
//                    var state = app_data.states_order[j].replace(/^\s+|\s+$/g,"");
                    state_s += state;
                    ids += "#" + state + ",";
                    var state_column = create_column(app_data.board, state, app_data.states[state])
                    table.append(state_column)
            }

            $(ids, table).dragsort(
            { 
                dragBetween: true , 
                dragSelector: "div",
                dragEnd: atualizarSituacaoAtividade
            });
            
            /*Atualiza a situacao da atividade quando ela eh arrastada entre as situacoes */
            function atualizarSituacaoAtividade() 
            {
                var situacao = new Array();
                var i = 1;
                var x = 0;

                $("#board .dp10 div").map(function() 
                { 
                    var chaveSituacao = $(this).parent().find("ul").attr("id");
                    var idAtividade = $(this).attr("id");
                    
                    /*Monta array com atividades atualizadas onde a chave eh a situacao */
                    if (chaveSituacao)
                    {
                        situacao[x] = new Array();
                        situacao[x][0] = chaveSituacao;
                        x++;
                        i = 1;
                    }

                    if (idAtividade)
                    {
                        situacao[x-1][i] = idAtividade;
                        i++;
                    }

                }).get();            


                for(i = 0; i < situacao.length; i++)
                {
                    if (situacao[i].length > 1)
                    {
                        for(x = 0; x < situacao[i].length; x++){
                            if (x == 0)
                            {
                                var chaveSituacao = situacao[i][x];
                            }
                            else
                            {
                                var idAtividade = situacao[i][x];
                                $.post("ajax/ajax.php", {"acao": "atualizarSituacaoAtividade", "idAtividade": idAtividade, "chaveSituacao": chaveSituacao}
//                                ,
//                                function(data) {
//                                    alert(data+" " + chaveSituacao);
//                                }

                                );                            
                            }
                        }                
                    }
                }            
            };

            return table;
    }
    
    $("#board_link").click(function () {
      var state_data = init_states($("#states").text().split("\n"));
      var app_data = {board: init_board($("#data_output").val().split("\n")), states: state_data.states, states_order: state_data.states_order}
      $("#output").empty();
      $("#output").append(create_board(app_data));

      $("#output").show();
      $("#data_link").show();
      $("#data_output").hide();
      $("#board_link").hide();
    })

    $("#data_link").click(function () {
      var data = "";
      $("#board .dp10").each(function() {
        var state = $(this).data("state");
            $(this).find("li").each( function() {
               var story = $(this).data("story");
               data += state + "," + story[1] + "," + story[2] + "\n";
            });
      });
      $("#data_output").val(data);
      $("#output").hide();
      $("#data_link").hide();
      $("#data_output").show();
      $("#board_link").show();
    });
    
    var state_data = init_states($("#states").text().split("\n"));
    var app_data = {board: init_board($("#stories").text().split("\n")), states: state_data.states, states_order: state_data.states_order}
    $("#output").empty();
    $("#output").append(create_board(app_data));    
    
    /*Atribui a cada atividade um menu dropdown*/
    if (arrayAtividades) 
    {
         for(x = 0; x < arrayAtividades.length; x++)
         {
             var id_task = arrayAtividades[x];
             $("#myDiv_"+id_task).contextMenu({menu: 'myMenu', leftButton: true},
                 function(action, el) {
                          var idAtividade = $(el).attr('id');
                          idAtividade = idAtividade.substring(6,idAtividade.length);
                          
                          if (action == "editar")
                          {
                              executaRefresh = false;
                                // load the ajax form using ajax
                                $.get("data/formAtividade.php?acao=atualizarAtividade&idAtividade="+idAtividade+"&idProjeto="+$("#idProjeto").val(), function(data){
                                        // create a modal dialog with the data
                                        $(data).modal({
                                                closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                                                position: ["15%",],
                                                overlayId: 'ajax-overlay',
                                                containerId: 'ajax-container',
                                                onOpen: ajax.open,
                                                onShow: ajax.show,
                                                onClose: ajax.close
                                        });
                                });
                              
                          }
                          else if (action == "deletar")
                          {
                              executaRefresh = false;
                              jConfirm('Deseja realmente excluir esta atividade?', 'Excluir Atividade', function(del) {
                                  if (del){
                                    $.post("ajax/ajax.php", {"acao": "deletarAtividade", "idAtividade": idAtividade});
                                    jAlert('Atividade excluida com sucesso!', 'Excluir Atividade', function(){
                                        location.reload();
                                    });
                                  } 
                                  else {
                                      executaRefresh = true;
                                  }
                              });
                          }
                    });
         }
    }
    
//    $(".confirm").easyconfirm();
//	$("#alert").click(function() {
//		alert("You approved the action");
//    });    
    
    
    var ajax = {

            message: null,
            init: function () {

                    $('#ajax-form input.ajax, #ajax-form a.ajax').click(function (e) {
                            executaRefresh = false;
                            e.preventDefault();
                            
                            // load the ajax form using ajax
                            $.get("data/formAtividade.php?acao=incluirAtividade&idProjeto="+$("#idProjeto").val(), function(data){
                                    // create a modal dialog with the data
                                    $(data).modal({
                                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                                            position: ["15%",],
                                            overlayId: 'ajax-overlay',
                                            containerId: 'ajax-container',
                                            onOpen: ajax.open,
                                            onShow: ajax.show,
                                            onClose: ajax.close
                                    });
                            });
                    });
            },
            open: function (dialog) {
                    // add padding to the buttons in firefox/mozilla
                    if ($.browser.mozilla) {
                            $('#ajax-container .ajax-button').css({
                                    'padding-bottom': '2px'
                            });
                    }
                    // input field font size
                    if ($.browser.safari) {
                            $('#ajax-container .ajax-input').css({
                                    'font-size': '.9em'
                            });
                    }

                    // dynamically determine height
                    var h = 250;
                    if ($('#ajax-subject').length) {
                            h += 26;
                    }
                    if ($('#ajax-cc').length) {
                            h += 22;
                    }

                    var title = $('#ajax-container .ajax-title').html();
                    $('#ajax-container .ajax-title').html('Loading...');
                    dialog.overlay.fadeIn(100, function () {
                            dialog.container.fadeIn(100, function () {
                                    dialog.data.fadeIn(100, function () {
                                            $('#ajax-container .ajax-content').animate({
                                                    height: h
                                            }, function () {
                                                    $('#ajax-container .ajax-title').html(title);
                                                    $('#ajax-container form').fadeIn(100, function () {
                                                            $('#ajax-container #atividade').focus();

                                                            $('#ajax-container .ajax-cc').click(function () {
                                                                    var cc = $('#ajax-container #ajax-cc');
                                                                    cc.is(':checked') ? cc.attr('checked', '') : cc.attr('checked', 'checked');
                                                            });

                                                            // fix png's for IE 6
                                                            if ($.browser.msie && $.browser.version < 7) {
                                                                    $('#ajax-container .ajax-button').each(function () {
                                                                            if ($(this).css('backgroundImage').match(/^url[("']+(.*\.png)[)"']+$/i)) {
                                                                                    var src = RegExp.$1;
                                                                                    $(this).css({
                                                                                            backgroundImage: 'none',
                                                                                            filter: 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' +  src + '", sizingMethod="crop")'
                                                                                    });
                                                                            }
                                                                    });
                                                            }
                                                    });
                                            });
                                    });
                            });
                    });
            },
            show: function (dialog) {
                    $('#ajax-container .ajax-send').click(function (e) {
                            e.preventDefault();
                            var msg = "";
                            // validate form
                            if (ajax.validate()) {
                                    msg = $('#ajax-container .ajax-message');
                                    msg.fadeOut(function () {
                                            msg.removeClass('ajax-error').empty();
                                    });
                                    $('#ajax-container .ajax-title').html('Sending...');
                                    $('#ajax-container form').fadeOut(100);
                                    $('#ajax-container .ajax-content').animate({
                                            height: '80px'
                                    }, function () {
                                            $('#ajax-container .ajax-loading').fadeIn(100, function () {
                                                    var urcAjax = "";
                                                    var acao  = $("#acao").val();
                                                    var projeto  = ($("#projeto").find('option:selected').val());
                                                    var situacao = ($("#situacao").find('option:selected').val());
                                                    var tp_esforco = ($("#tp_esforco").find('option:selected').val());
                                                    
                                                    if (acao == "incluirAtividade")
                                                    {
                                                        urcAjax = '&acao='+acao+'&projeto='+projeto+'&situacao='+situacao+'&tipo_esforco='+tp_esforco;
                                                    }
                                                    else if (acao == "atualizarAtividade")
                                                    {
                                                        var idAtividade  = $("#idAtividade").val();
                                                        urcAjax = '&acao='+acao+'&projeto='+projeto+'&situacao='+situacao+'&tipo_esforco='+tp_esforco+'&idAtividade='+idAtividade;
                                                    }
                                                    $.ajax({
                                                            url: 'ajax/ajax.php',
                                                            data: $('#ajax-container form').serialize() + urcAjax,
                                                            type: 'post',
                                                            cache: false,
                                                            dataType: 'html',
                                                            success: function (/*data*/) {
                                                                    $('#ajax-container .ajax-title').html('Atividade Cadastrada com Sucesso');
//                                                                    alert(data);
                                                                    ajax.close(dialog);
                                                                    location.reload();
                                                            },
                                                            error: function(xhr)
                                                            {   
                                                                ajax.error(xhr);
    //                                                            ajax.close(dialog);
                                                            }
                                                    });
                                            });
                                    });
                            }
                            else {
                                    if ($('#ajax-container .ajax-message:visible').length > 0) {
                                            msg = $('#ajax-container .ajax-message div');
                                            msg.fadeOut(100, function () {
                                                    msg.empty();
                                                    ajax.showError();
                                                    msg.fadeIn(100);
                                            });
                                    }
                                    else {
                                            $('#ajax-container .ajax-message').animate({
                                                    height: '30px'
                                            }, ajax.showError);
                                    }

                            }
                    });
            },
            close: function (dialog) {

                    $('#ajax-container .ajax-message').fadeOut();
                    $('#ajax-container form').fadeOut(200);
                    $('#ajax-container .ajax-content').animate({height: 40}, 
                        function () {
                            dialog.data.fadeOut(200, function () {
                                    dialog.container.fadeOut(200, function () {
                                            dialog.overlay.fadeOut(200, function () {
                                                    $.modal.close();
                                                    executaRefresh = true;
                                            });
                                    });
                            });
                    });
            },
            error: function (xhr) {
                $('#ajax-container .ajax-title').html('Erro ao cadastrar atividade!!<br/> Erro: '+xhr.statusText);
            },
            validate: function () {
                    ajax.message = '';
                    if (!$('#ajax-container #atividade').val()) {
                            ajax.message += 'Informe a Atividade.<br/>';
                            return false;
                    }

                    if (!$('#ajax-container #esforco').val()) {
                            ajax.message += 'Informe o Esfor&ccedil;o.<br/>';
                            return false
                    }

                    if (isNaN($('#ajax-container #esforco').val()) ) {
                            ajax.message += 'Informe apenas numeros para o Esfor&ccedil;o.<br/>';
                            return false
                    }
                    return true;
            },

            showError: function () {
                    $('#ajax-container .ajax-message')
                            .html($('<div class="ajax-error"></div>').append(ajax.message))
                            .fadeIn(100);
            }
    };

    ajax.init();
    
    $(document).ready(function() 
    {
        setInterval(function(){
            
            if (executaRefresh){
                location.reload();
            }
                
        }, 10000000);
        
        $("#cbxProjeto").change(function()
        {
            var idProjeto = $("#cbxProjeto").find('option:selected').val();
            if(idProjeto < 0)
            {
                jAlert('Favor escolher um projeto da lista!', 'Selecionar Projeto', function(){
                    location.href = "index.php";
                })
                
            }
            else {
                location.href = "index.php?idProjeto="+idProjeto;
            }
        });
        
    });

});

function trim(str)
{
    return str.replace(/^\s+|\s+$/g,"");
}
