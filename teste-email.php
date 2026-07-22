<?php
header('Content-Type: text/html; charset=utf-8');
$destinatario = 'contato@rdmconsultoriaimobiliaria.com.br';
$assunto = 'Teste de envio - RDM Consultoria Imobiliária';
$mensagem = "Teste de envio do site RDM em " . date('d/m/Y H:i:s') . "\nSe você recebeu este e-mail, o PHP mail() da hospedagem está funcionando.";
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: RDM Consultoria Imobiliária <contato@rdmconsultoriaimobiliaria.com.br>';
$headers[] = 'Reply-To: contato@rdmconsultoriaimobiliaria.com.br';
$ok = @mail($destinatario, '=?UTF-8?B?'.base64_encode($assunto).'?=', $mensagem, implode("\r\n", $headers));
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Teste de e-mail RDM</title>
<style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f4f2f8;color:#16121c;display:grid;place-items:center;min-height:100vh;margin:0}.card{max-width:680px;background:#fff;border-radius:24px;padding:28px;box-shadow:0 16px 40px rgba(22,18,28,.12)}.ok{color:#167331}.erro{color:#a02020}code{background:#f4f2f8;padding:2px 6px;border-radius:8px}</style>
</head>
<body><main class="card">
<h1>Teste de envio de e-mail</h1>
<?php if($ok): ?>
<p class="ok"><strong>O servidor aceitou o envio.</strong></p>
<p>Verifique se a mensagem chegou em <code><?php echo htmlspecialchars($destinatario); ?></code>. Confira também spam/lixo eletrônico.</p>
<?php else: ?>
<p class="erro"><strong>O servidor não conseguiu enviar o e-mail usando PHP mail().</strong></p>
<p>Nesse caso, será necessário configurar SMTP da Hostinger ou verificar se o e-mail <code><?php echo htmlspecialchars($destinatario); ?></code> existe na hospedagem.</p>
<?php endif; ?>
<p><a href="index.html">Voltar para o site</a></p>
</main></body></html>
