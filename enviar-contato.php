<?php
// RDM Consultoria Imobiliária - envio do formulário de contato
// Destinatário fixo solicitado pelo cliente.
$destinatario = 'contato@rdmconsultoriaimobiliaria.com.br';

function responder($ok, $message, $status = 200) {
    http_response_code($status);
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    if (stripos($accept, 'application/json') !== false) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    } else {
        header('Content-Type: text/html; charset=utf-8');
        $titulo = $ok ? 'Mensagem enviada' : 'Erro no envio';
        echo '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.$titulo.'</title><style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#F4F2F8;color:#16121C;display:grid;place-items:center;min-height:100vh;margin:0}.card{max-width:560px;background:#fff;border-radius:24px;padding:28px;box-shadow:0 16px 40px rgba(22,18,28,.12)}a{color:#A91599;font-weight:700}</style></head><body><main class="card"><h1>'.$titulo.'</h1><p>'.htmlspecialchars($message, ENT_QUOTES, 'UTF-8').'</p><p><a href="index.html">Voltar para o site</a></p></main></body></html>';
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(false, 'Método não permitido.', 405);
}

// Campo honeypot anti-spam. Se preenchido, bloqueia silenciosamente.
if (!empty($_POST['empresa'] ?? '')) {
    responder(true, 'Mensagem enviada com sucesso.');
}

$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$assunto = trim($_POST['assunto'] ?? 'Contato pelo site');
$mensagem = trim($_POST['mensagem'] ?? '');

if ($nome === '' || $telefone === '') {
    responder(false, 'Preencha nome e telefone para enviar sua mensagem.', 400);
}

$limpar = function($valor) {
    $valor = strip_tags($valor);
    $valor = str_replace(["\r", "\n"], ' ', $valor);
    return trim($valor);
};

$nomeLimpo = $limpar($nome);
$telefoneLimpo = $limpar($telefone);
$assuntoLimpo = $limpar($assunto);
$mensagemLimpa = trim(strip_tags($mensagem));

$tituloEmail = 'Novo contato pelo site - RDM Consultoria Imobiliária';
$corpo = "Novo contato recebido pelo site da RDM Consultoria Imobiliária:\n\n";
$corpo .= "Nome: {$nomeLimpo}\n";
$corpo .= "Telefone/WhatsApp: {$telefoneLimpo}\n";
$corpo .= "Assunto: {$assuntoLimpo}\n";
$corpo .= "Mensagem: " . ($mensagemLimpa !== '' ? $mensagemLimpa : 'Não informada') . "\n\n";
$corpo .= "Origem: formulário do site\n";
$corpo .= "Data/Hora: " . date('d/m/Y H:i:s') . "\n";

$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: RDM Consultoria Imobiliária <contato@rdmconsultoriaimobiliaria.com.br>';
$headers[] = 'Reply-To: contato@rdmconsultoriaimobiliaria.com.br';
$headers[] = 'X-Mailer: PHP/' . phpversion();

$enviado = @mail($destinatario, '=?UTF-8?B?'.base64_encode($tituloEmail).'?=', $corpo, implode("\r\n", $headers));

if ($enviado) {
    responder(true, 'Mensagem enviada com sucesso para contato@rdmconsultoriaimobiliaria.com.br.');
}

responder(false, 'O servidor não conseguiu enviar o e-mail. Verifique se a função mail() está habilitada na hospedagem ou configure SMTP.', 500);
