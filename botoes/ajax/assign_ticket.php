<?php

include ('../../../inc/includes.php');
Session::checkLoginUser();

if (isset($_POST['ticket_id']) && isset($_POST['action'])) {
    $ticket_id = $_POST['ticket_id'];
    $action = $_POST['action'];
    
    switch ($action) {
        case 'assign':
            // Atribui o usuário ao ticket
            $ticket_user = new Ticket_User();
            $ticket_user->add([
                'tickets_id' => $ticket_id,
                'users_id' => Session::getLoginUserID(),
                'type' => CommonITILActor::ASSIGN
            ]);

            // Busca e atribui o grupo do usuário
            $group_user = new Group_User();
            $user_groups = $group_user->getUserGroups(Session::getLoginUserID());
            
            if (!empty($user_groups)) {
                // Atribui o primeiro grupo do usuário ao ticket
                $ticket_group = new Group_Ticket();
                $ticket_group->add([
                    'tickets_id' => $ticket_id,
                    'groups_id' => $user_groups[0]['id'],
                    'type' => CommonITILActor::ASSIGN
                ]);
            }

            // Atualiza o status para Processando (2)
            $ticket = new Ticket();
            $ticket->getFromDB($ticket_id);
            $ticket->update([
                'id' => $ticket_id,
                'status' => 2
            ]);
            break;
            
        case 'pause':
            // Atualiza o status para Pendente (4)
            $ticket = new Ticket();
            $ticket->getFromDB($ticket_id);
            $ticket->update([
                'id' => $ticket_id,
                'status' => 4
            ]);
            break;
    }
}

Html::back();