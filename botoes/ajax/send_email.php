<?php

include ('../../../inc/includes.php');
Session::checkLoginUser();

$response = ['success' => false, 'message' => ''];

try {
    if (!isset($_POST['ticket_id']) || !isset($_POST['content'])) {
        throw new Exception('Parâmetros inválidos');
    }

    $ticket_id = intval($_POST['ticket_id']);
    $content = $_POST['content'];

    // Busca o ticket
    $ticket = new Ticket();
    if (!$ticket->getFromDB($ticket_id)) {
        throw new Exception('Ticket não encontrado');
    }

    // Busca o email do requerente
    $query = "SELECT DISTINCT tu.alternative_email 
              FROM glpi_tickets_users tu
              WHERE tu.tickets_id = " . $ticket_id . "
              AND tu.type = " . CommonITILActor::REQUESTER;

    $result = $DB->query($query);
    $requester_email = '';
    
    if ($DB->numrows($result) > 0) {
        $data = $DB->fetchAssoc($result);
        $requester_email = $data['alternative_email'];
    }

    if (empty($requester_email)) {
        throw new Exception('Email do requerente não encontrado');
    }

    // Busca o email do técnico atribuído
    $query_tech = "SELECT u.email, u.realname, u.firstname 
                  FROM glpi_tickets_users tu
                  LEFT JOIN glpi_users u ON u.id = tu.users_id
                  WHERE tu.tickets_id = " . $ticket_id . "
                  AND tu.type = " . CommonITILActor::ASSIGN;

    $result_tech = $DB->query($query_tech);
    
    if ($DB->numrows($result_tech) === 0) {
        throw new Exception('Técnico atribuído não encontrado');
    }

    $tech_data = $DB->fetchAssoc($result_tech);
    $tech_email = $tech_data['email'];
    $tech_name = trim($tech_data['firstname'] . ' ' . $tech_data['realname']);

    if (empty($tech_email)) {
        throw new Exception('Email do técnico não encontrado');
    }

    // Configuração e envio do email
    $email = new PHPMailer(true);
    
    try {
        $email->isSMTP();
        $email->Host = 'mail.grupossit.com.br';
        $email->SMTPAuth = true;
        $email->Username = 'central@grupossit.com.br';
        $email->Password = 'L74I&V&$(z)@241#';
        $email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $email->Port = 465;
        $email->CharSet = 'UTF-8';

        // Configura o remetente como o técnico atribuído
        $email->setFrom($tech_email, $tech_name);
        $email->addAddress($requester_email);
        $email->Subject = "[GLPI #" . $ticket_id . "] Comunicação sobre seu chamado";
        $email->Body = $content;
        $email->isHTML(true);

        if (!$email->send()) {
            throw new Exception('Erro ao enviar email: ' . $email->ErrorInfo);
        }

        // Adiciona um followup para registro
        $followup = new ITILFollowup();
        $followup_input = [
            'items_id' => $ticket_id,
            'itemtype' => 'Ticket',
            'users_id' => Session::getLoginUserID(),
            'content'  => "Um e-mail foi enviado por " . $tech_name . " para " . $requester_email . ":\n\n" . $content,
            'is_private' => 0
        ];

        if (!$followup->add($followup_input)) {
            throw new Exception('Email enviado mas erro ao registrar no histórico');
        }

        $response['success'] = true;
        $response['message'] = 'Email enviado com sucesso para ' . $requester_email;

    } catch (Exception $e) {
        throw new Exception('Erro ao enviar email: ' . $e->getMessage());
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('Erro no plugin Botoes: ' . $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($response);
exit();