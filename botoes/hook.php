<?php

function plugin_botoes_install() {
    return true;
}

function plugin_botoes_uninstall() {
    return true;
}

function plugin_botoes_post_item_form(array $params) {
    if ($params['item'] instanceof Ticket) {
        global $DB;
        
        // Get current user ID
        $current_user_id = Session::getLoginUserID();
        
        // Allowed profiles
        $allowed_profiles = [4, 24, 28, 30, 31, 33, 34, 35, 36, 37, 38, 39, 172, 176, 180];
        
        // Check if user has any of the allowed profiles
        $query = "SELECT DISTINCT profiles_id 
                 FROM glpi_profiles_users 
                 WHERE users_id = " . $current_user_id . " 
                 AND profiles_id IN (" . implode(',', $allowed_profiles) . ")";
        
        $result = $DB->query($query);
        $has_access = ($DB->numrows($result) > 0);
        
        if ($has_access) {
            $ticket_id = $params['item']->getID();
            
            echo "<div class='custom-buttons' style='margin-top: 10px;'>";
            echo "<center><button onclick='assignTicket(" . $ticket_id . ")' class='btn btn-success' style='margin-right: 5px; padding: 4px 8px;'>
                    <i class='fas fa-play' style='margin-right: 5px;'></i>Atender
                  </button>
                  <button onclick='pauseTicket(" . $ticket_id . ")' class='btn btn-warning' style='margin-right: 5px; padding: 4px 8px;'>
                    <i class='fas fa-pause' style='margin-right: 5px;'></i>Pendente
                  </button></center>";
            echo "</div>";
            
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
}