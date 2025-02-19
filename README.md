# plugin-glpi-botoes-play-pause-solucao
Vou descrever o plugin "Botoes" em detalhes:
Visão Geral do Plugin
O plugin "Botoes" é um complemento para o sistema GLPI (Gestão de Serviços de TI) que adiciona funcionalidades personalizadas de gerenciamento de tickets, com foco em facilitar a atribuição e alteração de status de chamados.
Componentes do Plugin
1. Configurações Básicas (setup.php)

Nome do Plugin: Botoes
Versão: 1.0.0
Autor: Adriano Marinho
Licença: GPLv2+
Requisitos: GLPI versão 10.0.0 a 10.0.99

2. Funcionalidades Principais (hook.php)
Regras de Acesso

Limitação de acesso a usuários com perfis específicos
Perfis autorizados:
[4, 24, 28, 30, 31, 33, 34, 35, 36, 37, 38, 39, 172, 176, 180]

Botões Personalizados
Dois botões são adicionados ao formulário de ticket para usuários autorizados:

Botão "Atender"

Ação: Atribuir ticket ao usuário atual
Ícone: Botão verde com ícone de play
Função: assignTicket()


Botão "Pendente"

Ação: Alterar status do ticket para pendente
Ícone: Botão amarelo com ícone de pause
Função: pauseTicket()



3. Processamento de Ações (assign_ticket.php)
Ação de Atender (assign)
Quando o botão "Atender" é clicado, o plugin realiza:

Atribui o usuário atual ao ticket
Adiciona o primeiro grupo do usuário ao ticket
Altera o status para "Processando" (status 2)
Adiciona um followup informando o início do atendimento

Ação de Pausar (pause)
Quando o botão "Pendente" é clicado, o plugin:

Altera o status do ticket para "Pendente" (status 4)
Adiciona um followup informando a mudança de status

4. Funcionalidade Extra (send_email.php)
Um script adicional para envio de e-mails relacionados ao ticket, que:

Busca o e-mail do requerente
Identifica o técnico atribuído
Envia e-mail personalizado
Registra o envio como followup no ticket

Tecnologias Utilizadas

Linguagem: PHP
Framework: GLPI
Bibliotecas:

PHPMailer (para envio de e-mails)
Bibliotecas nativas do GLPI para manipulação de tickets



Recursos de Segurança

Verificação de sessão de login
Restrição de acesso por perfil
Proteção CSRF habilitada
Tratamento de erros com exceções

Casos de Uso

Técnicos de suporte podem rapidamente:

Assumir um ticket
Marcar ticket como pendente
Comunicar-se com o requerente via e-mail



Considerações Importantes

O plugin é específico para a versão 10.x do GLPI
Requer configuração de e-mail SMTP específica
Funciona apenas para usuários com perfis predefinidos

Melhorias Potenciais

Adicionar mais opções de status
Personalizar os perfis de acesso
Implementar mais ações rápidas
Adicionar log de auditoria mais detalhado

O plugin "Botoes" simplifica e agiliza o processo de gerenciamento de tickets no GLPI, oferecendo uma interface rápida e intuitiva para técnicos de suporte.
