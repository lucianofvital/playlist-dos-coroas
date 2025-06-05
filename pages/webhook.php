<?php
// Recebe dados do Mercado Pago
$dados = file_get_contents('php://input');
file_put_contents('logs.txt', $dados . "\n", FILE_APPEND); // salva para debug

$info = json_decode($dados, true);

// Verifica status do pagamento
if(isset($info['data']['id'])){
    $payment_id = $info['data']['id'];

    // Verificar o status do pagamento na API
    $access_token = 'APP_USR-2809921708630207-060514-4a21a82278a2d11d7db0c1013297795f-460406015'; // Pega no painel do Mercado Pago

    $url = "https://api.mercadopago.com/v1/payments/$payment_id";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if($result['status'] == 'approved'){
        file_put_contents('status.txt', 'pago');
    }
}
?>
