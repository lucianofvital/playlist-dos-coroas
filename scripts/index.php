<?php include('config.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pagamento via Pix - Playlist dos Coroas</title>
  <style>
    body { font-family: Arial; text-align: center; margin-top: 50px; }
    button { padding: 10px 20px; font-size: 18px; }
    .hidden { display: none; }
  </style>
</head>
<body>

<h1>Comprar Playlist dos Coroas - R$ 20,00</h1>

<button onclick="gerarPix()">Gerar Pix</button>

<div id="qrcode" class="hidden">
  <h2>Escaneie o QR Code ou copie o código Pix</h2>
  <img id="imgQr" src="">
  <p id="copyCode"></p>
</div>

<div id="statusPagamento" class="hidden">
  <h3>Aguardando pagamento...</h3>
</div>

<div id="download" class="hidden">
  <h2>Pagamento aprovado!</h2>
  <a href="#" target="_blank"><button>👉 Baixar Playlist 👈</button></a>
</div>

<script>
  let paymentId = '';

  function gerarPix() {
    fetch('criar_pix.php')
    .then(response => response.json())
    .then(data => {
      paymentId = data.id;
      document.getElementById('imgQr').src = data.qr_code_base64;
      document.getElementById('copyCode').innerText = data.qr_code;
      document.getElementById('qrcode').classList.remove('hidden');
      document.getElementById('statusPagamento').classList.remove('hidden');
      verificarPagamento();
    });
  }

  function verificarPagamento() {
    const intervalo = setInterval(() => {
      fetch('verificar_pagamento.php?id=' + paymentId)
      .then(response => response.json())
      .then(data => {
        if(data.status === 'approved'){
          clearInterval(intervalo);
          document.getElementById('statusPagamento').classList.add('hidden');
          document.getElementById('download').classList.remove('hidden');
        }
      });
    }, 5000); // Verifica a cada 5 segundos
  }
</script>
<a href="LINK_DO_MEGA_AQUI" target="_blank"><button>👉 Baixar Playlist 👈</button></a>

</body>
</html>
