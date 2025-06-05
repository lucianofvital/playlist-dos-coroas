<?php
// Dados do seu Mercado Pago
$access_token = 'APP_USR-2809921708630207-060514-4a21a82278a2d11d7db0c1013297795f-460406015'; // Pega no painel do Mercado Pago

// Dados do pedido
$valor = 19.90; // valor em reais
$descricao = "Compra do Produto XYZ";

// Cria o pagamento
$url = "https://api.mercadopago.com/instore/orders/qr/seller/collectors/YOUR_USER_ID_POS/pos/YOUR_POS_ID/qrs";

$data = [
    "items" => [[
        "title" => $descricao,
        "quantity" => 1,
        "currency_id" => "BRL",
        "unit_price" => (float)$valor
    ]],
    
    "notification_url" => "https://seu-site.com/webhook.php",
    "external_reference" => "pedido123"
];

$headers = [
    "Authorization: Bearer $access_token",
    "Content-Type: application/json"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/instore/orders/qr/seller/collectors/460406015/pos/2809921708630207/qrs");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Erro: ' . curl_error($ch);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);

// Exibir QR Code
if(isset($result['qr_data'])){
    echo "<h1>Escaneie o QR Code abaixo para pagar:</h1>";
    echo "<img src='https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=".$result['qr_data']."'>";
    echo "<p>Aguardando pagamento...</p>";
} else {
    echo "Erro ao gerar pagamento: ";
    print_r($result);
}
?>
