<?php
include('config.php');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payments');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "transaction_amount" => 20,
    "description" => "Playlist dos Coroas",
    "payment_method_id" => "pix",
    "payer" => [
        "email" => "comprador@gmail.com", // Pode ser genérico
        "first_name" => "Cliente",
        "last_name" => "Coroas"
    ]
]));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . ACCESS_TOKEN
]);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$data = json_decode($result, true);

echo json_encode([
    'id' => $data['id'],
    'qr_code' => $data['point_of_interaction']['transaction_data']['qr_code'],
    'qr_code_base64' => $data['point_of_interaction']['transaction_data']['qr_code_base64']
]);
?>
