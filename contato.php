<?php
// Formulário de contato - RDM Consultoria Imobiliária
// Envia os dados recebidos no site para o e-mail configurado abaixo.

$destinatario = 'Contato@rdmconsultoriaimobiliaria.com.br';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html#contato');
    exit;
}

function limpar($valor) {
    $valor = trim((string)$valor);
    $valor = strip_tags($valor);
    return preg_replace('/[\r\n]+/', ' ', $valor);
}

$nome = limpar($_POST['nome'] ?? '');
$telefone = limpar($_POST['telefone'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$assunto = limpar($_POST['assunto'] ?? 'Contato pelo site');
$mensagem = trim(strip_tags($_POST['mensagem'] ?? ''));
$origem = limpar($_POST['origem'] ?? 'Site RDM Consultoria Imobiliária');

if ($nome === '' || $telefone === '' || $assunto === '') {
    header('Location: index.html?erro=1#contato');
    exit;
}

$titulo = 'Novo contato pelo site RDM - ' . $assunto;
$corpo = "Novo contato recebido pelo site\n\n";
$corpo .= "Origem: {$origem}\n";
$corpo .= "Nome: {$nome}\n";
$corpo .= "Telefone/WhatsApp: {$telefone}\n";
$corpo .= "E-mail: " . ($email !== '' ? $email : 'Não informado') . "\n";
$corpo .= "Assunto: {$assunto}\n";
$corpo .= "Mensagem:\n" . ($mensagem !== '' ? $mensagem : 'Não informada') . "\n\n";
$corpo .= "Enviado em: " . date('d/m/Y H:i:s') . "\n";

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: Site RDM <no-reply@rdmconsultoriaimobiliaria.com>';
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $headers[] = 'Reply-To: ' . $email;
}

$enviado = mail($destinatario, '=?UTF-8?B?' . base64_encode($titulo) . '?=', $corpo, implode("\r\n", $headers));

if ($enviado) {
    header('Location: index.html?enviado=1#contato');
} else {
    header('Location: index.html?erro=1#contato');
}
exit;
?>
