<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Mercado Pago
$access_token = 'APP_USR-2809921708630207-060514-4a21a82278a2d11d7db0c1013297795f-460406015'; // <- substitua pelo seu token real
$idempotencyKey = uniqid();
$result = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $valor = 20.00;
    $descricao = "Playlist dos Coroas";

    try {
        $data = [
            "transaction_amount" => floatval($valor),
            "description" => $descricao,
            "payment_method_id" => "pix",
            "payer" => [
                "email" => $email,
                "first_name" => $nome,
                "last_name" => "Cliente",
                "identification" => [
                    "type" => "CPF",
                    "number" => $cpf
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json",
            "X-Idempotency-Key: " . $idempotencyKey
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.mercadopago.com/v1/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Erro cURL: ' . curl_error($ch));
        }

        curl_close($ch);
        $result = json_decode($response, true);

    } catch (Exception $e) {
        $result = ['error' => $e->getMessage()];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento - Playlist dos Coroas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap e Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../styles/index.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Instruções de Pagamento -->
    
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <?php if (isset($result['point_of_interaction'])): ?>
                    <!-- Pagamento gerado -->
                    <div class="card shadow p-4">
                        <div class="text-center mb-3">
                            <img src="../assets/img/logo.png" style="max-height: 200px;" alt="Logo">
                        </div>
                        <h3 class="text-center mb-3">Pague com PIX</h3>

                        <div class="text-center mb-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode($result['point_of_interaction']['transaction_data']['qr_code']) ?>" class="img-fluid" alt="QR Code">
                        </div>

                        <div class="mb-3 text-center">
                            <h4>R$ <?= number_format(20.00, 2, ',', '.') ?></h4>
                        </div>

                        <div class="mb-3">
                            <label><strong>Código PIX:</strong></label>
                            <div class="input-group">
                                <input type="text" id="pixCode" class="form-control" readonly value="<?= $result['point_of_interaction']['transaction_data']['qr_code'] ?>">
                                <button class="btn btn-outline-secondary" onclick="copyPixCode(this)">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                            </div>
                        </div>

                        <div id="paymentStatus" class="alert alert-info mt-3">
                            <i class="fas fa-clock"></i> Aguardando pagamento...
                        </div>

                        <div id="downloadButton" class="text-center mt-3" style="display: none;">
                            <a href="https://mega.nz/folder/LAQ3kTzC#Nmch1j_w3QX7kmNDsxQT9g" class="btn btn-success btn-lg">
                                <i class="fas fa-download"></i> Baixar Playlist
                            </a>
                        </div>
                    </div>

                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <!-- Erro -->
                    <div class="alert alert-danger">
                        <h4>Erro ao gerar pagamento</h4>
                        <pre><?= print_r($result, true); ?></pre>
                    </div>

                <?php else: ?>
                    <!-- Formulário -->
                    <div class="card shadow p-4">
                        <div class="text-center mb-4">
                            <img src="../assets/img/logo.png" style="max-height: 80px;" alt="Logo">
                        </div>
                        <h3 class="mb-3 text-center">Preencha seus dados para gerar o PIX</h3>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome completo</label>
                                <input type="text" name="nome" id="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" name="cpf" id="cpf" class="form-control" required placeholder="Apenas números" maxlength="11">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-barcode"></i> Gerar PIX
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>


    <section class="payment-instructions background-gradient py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg rounded-4 border-0">
                        <div class="card-body p-4">
                            <!-- Cabeçalho -->
                            <div class="text-center mb-4">
                                
                                <h2 class="gradient-text">Como Comprar sua Playlist dos Coroas.</h2>
                                <p class="lead text-muted">Siga os passos abaixo para completar sua compra</p>
                            </div>

                            <!-- Passos -->
                            <div class="steps-container">
                                <!-- Passo 1 -->
                                <div class="step-item fade-in">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h4><i class="fas fa-user-edit text-primary"></i> Preencha seus Dados</h4>
                                        <p>Informe seu nome, e-mail e CPF para gerar o pagamento</p>
                                    </div>
                                </div>

                                <!-- Passo 2 -->
                                <div class="step-item fade-in" style="animation-delay: 0.2s;">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h4><i class="fas fa-qrcode text-primary"></i> Escaneie o QR Code</h4>
                                        <p>Use o app do seu banco para escanear o QR Code PIX que será gerado</p>
                                    </div>
                                </div>

                                <!-- Passo 3 -->
                                <div class="step-item fade-in" style="animation-delay: 0.4s;">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h4><i class="fas fa-download text-primary"></i> Baixe sua Playlist</h4>
                                        <p>Após a confirmação do pagamento, o link de download será liberado automaticamente</p>
                                    </div>
                                </div>

                                <!-- Alternativa -->
                                <div class="alternative-option fade-in" style="animation-delay: 0.6s;">
                                    <div class="alert alert-info">
                                        <h5><i class="fab fa-whatsapp"></i> Prefere enviar comprovante?</h5>
                                        <p class="mb-0">Você também pode enviar o comprovante pelo WhatsApp e receber o link direto.</p>
                                        <a href="https://wa.me/558191416115?text=Olá! Gostaria de saber mais sobre a Playlist dos Coroas" class="btn btn-success mt-2">
                                            <i class="fab fa-whatsapp"></i> Enviar Comprovante
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Garantias -->
                            <div class="guarantees text-center mt-4 fade-in" style="animation-delay: 0.8s;">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <i class="fas fa-shield-alt fa-2x text-primary"></i>
                                        <p class="mb-0">Pagamento Seguro</p>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-undo fa-2x text-primary"></i>
                                        <p class="mb-0">7 Dias de Garantia</p>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-headphones fa-2x text-primary"></i>
                                        <p class="mb-0">Suporte WhatsApp</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Scripts -->
<script>
function copyPixCode(button) {
    const code = document.getElementById('pixCode');
    navigator.clipboard.writeText(code.value).then(() => {
        button.innerHTML = '<i class="fas fa-check"></i> Copiado!';
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-copy"></i> Copiar';
        }, 2000);
    });
}

// Verificação de pagamento
const paymentId = '<?= $result['id'] ?? '' ?>';
if (paymentId) {
    const interval = setInterval(() => {
        fetch(`webhook.php?payment_id=${paymentId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'approved') {
                    document.getElementById('paymentStatus').className = 'alert alert-success';
                    document.getElementById('paymentStatus').innerHTML = '<i class="fas fa-check-circle"></i> Pagamento confirmado!';
                    document.getElementById('downloadButton').style.display = 'block';
                    clearInterval(interval);
                }
            })
            .catch(console.error);
    }, 5000);
}
</script>
</body>
</html>