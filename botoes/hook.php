<?php

function plugin_botoes_install() {
    return true;
}

function plugin_botoes_uninstall() {
    return true;
}

function plugin_botoes_post_item_form(array $params) {
    if ($params['item'] instanceof Ticket) {
        $ticket_id = $params['item']->getID();
        
        echo "<div class='custom-buttons' style='margin-top: 10px;'>";
        
        // Botão Atender
        echo "<button onclick='assignTicket(" . $ticket_id . ")' class='btn btn-success' style='margin-right: 5px; width: auto; padding: 4px 8px;'>
                <i class='fas fa-play' style='margin-right: 5px;'></i>Atender
              </button>";
        
        // Botão Pendente
        echo "<button onclick='pauseTicket(" . $ticket_id . ")' class='btn btn-warning' style='margin-right: 5px; width: auto; padding: 4px 8px;'>
                <i class='fas fa-pause' style='margin-right: 5px;'></i>Pendente
              </button>";

        // Botão Solucionar
        echo "<button onclick='showSolutionModal(event, " . $ticket_id . ")' class='btn btn-primary' style='width: auto; padding: 4px 8px; background-color: #9bd2e9;'>
                <i class='fas fa-check' style='margin-right: 5px;'></i>Solucionar
              </button>";
        
        echo "</div>";
        
        // JavaScript para manipular as ações dos botões
        echo "<script type='text/javascript'>
            function assignTicket(ticketId) {
                $.ajax({
                    url: '../plugins/botoes/ajax/assign_ticket.php',
                    type: 'POST',
                    data: {
                        ticket_id: ticketId,
                        action: 'assign'
                    },
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }
            
            function pauseTicket(ticketId) {
                $.ajax({
                    url: '../plugins/botoes/ajax/assign_ticket.php',
                    type: 'POST',
                    data: {
                        ticket_id: ticketId,
                        action: 'pause'
                    },
                    success: function(response) {
                        window.location.reload();
                    }
                });
            }

            function showSolutionModal(event, ticketId) {
                event.preventDefault();
                event.stopPropagation();
                
                // Simula o clique no botão de solução
                const solutionButton = document.querySelector('button.ms-2.mb-2.btn.btn-primary.answer-action.action-solution');
                if (solutionButton) {
                    solutionButton.click();
                }
                
                return false;
            }
        </script>";
    }
}