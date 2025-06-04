<?php
include('config.php');

$id = $_GET['id'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/v1/payments/' . $id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . ACCESS_TOKEN
]);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$data = json_decode($result, true);

echo json_encode([
    'status' => $data['status']
]);
?>
