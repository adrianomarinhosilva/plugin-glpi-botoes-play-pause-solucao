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

            // Adiciona o followup informando início do atendimento
            $followup = new ITILFollowup();
            $user = new User();
            $user->getFromDB(Session::getLoginUserID());
            $userName = $user->getFriendlyName();
            
            $followup->add([
                'items_id' => $ticket_id,
                'itemtype' => 'Ticket',
                'users_id' => Session::getLoginUserID(),
                'content'  => "O técnico " . $userName . " iniciou o atendimento do chamado.",
                'is_private' => 0
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

            // Adiciona o followup informando que o chamado foi colocado em pendente
            $followup = new ITILFollowup();
            $user = new User();
            $user->getFromDB(Session::getLoginUserID());
            $userName = $user->getFriendlyName();
            
            $followup->add([
                'items_id' => $ticket_id,
                'itemtype' => 'Ticket',
                'users_id' => Session::getLoginUserID(),
                'content'  => "O técnico " . $userName . " definiu o status do chamado como pendente.",
                'is_private' => 0
            ]);
            break;
    }
}

Html::back();