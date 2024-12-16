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
        
        // Botão Atender e Pendente
        echo "<center><button onclick='assignTicket(" . $ticket_id . ")' class='btn btn-success' style='margin-right: 5px; padding: 4px 8px;'>
                <i class='fas fa-play' style='margin-right: 5px;'></i>Atender
              </button>
              <button onclick='pauseTicket(" . $ticket_id . ")' class='btn btn-warning' style='margin-right: 5px; padding: 4px 8px;'>
                <i class='fas fa-pause' style='margin-right: 5px;'></i>Pendente
              </button></center>
              ";
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
        </script>";
    }
}
