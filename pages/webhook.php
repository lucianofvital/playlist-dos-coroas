<?php
header('Content-Type: application/json');

if (isset($_GET['payment_id'])) {
    $access_token = 'APP_USR-2809921708630207-060514-4a21a82278a2d11d7db0c1013297795f-460406015';
    $payment_id = $_GET['payment_id'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments/" . $payment_id);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $access_token]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $payment_info = json_decode($response, true);
    
    echo json_encode(['status' => $payment_info['status']]);
} else {
    echo json_encode(['error' => 'Payment ID not provided']);
}
?>

<?php

require_once('../includes/send-email.php');

header('Content-Type: application/json');

if (isset($_GET['payment_id'])) {
    $access_token = 'APP_USR-2809921708630207-060514-4a21a82278a2d11d7db0c1013297795f-460406015';
    
    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.mercadopago.com/v1/payments/" . $_GET['payment_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $access_token]
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $payment_info = json_decode($response, true);
        
        if ($payment_info['status'] === 'approved') {
            sendDownloadEmail(
                $payment_info['payer']['first_name'],
                $payment_info['payer']['email']
            );
        }
        
        echo json_encode(['status' => $payment_info['status']]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}