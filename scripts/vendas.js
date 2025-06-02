// Elementos DOM
const generatePixBtn = document.getElementById("generatePixBtn");
const pixLoading = document.getElementById("pixLoading");
const pixContent = document.getElementById("pixContent");
const pixResult = document.getElementById("pixResult");
const qrCodeImg = document.getElementById("qrCodeImg");
const pixCode = document.getElementById("pixCode");
const step1 = document.getElementById("step1");
const step2 = document.getElementById("step2");

// Evento do botão gerar PIX
generatePixBtn.addEventListener("click", async () => {
  try {
    showLoading();

    const response = await fetch("http://localhost:3000/pagamento", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        valor: 19.9,
        descricao: "Playlist dos Coroas",
      }),
    });

    const data = await response.json();

    if (data.qr_code && data.qr_code_base64) {
      showPixResult(data);
      startPaymentCheck(data.id); // Supondo que a API retorne um ID da transação
    } else {
      throw new Error("Erro ao gerar PIX");
    }
  } catch (error) {
    showError("Erro ao gerar o PIX. Tente novamente.");
  }
});

// Funções auxiliares
function showLoading() {
  pixContent.classList.add("d-none");
  pixLoading.classList.remove("d-none");
  pixResult.classList.add("d-none");
}

function showPixResult(data) {
  pixLoading.classList.add("d-none");
  pixResult.classList.remove("d-none");
  qrCodeImg.src = data.qr_code_base64;
  pixCode.value = data.qr_code;
}

function copyPixCode() {
  pixCode.select();
  document.execCommand("copy");

  // Feedback visual
  const button = pixCode.nextElementSibling;
  button.innerHTML = '<i class="fas fa-check"></i>';
  setTimeout(() => {
    button.innerHTML = '<i class="fas fa-copy"></i>';
  }, 2000);
}

// Verificação do pagamento
async function startPaymentCheck(transactionId) {
  const checkInterval = setInterval(async () => {
    try {
      const response = await fetch(
        `http://localhost:3000/status/${transactionId}`
      );
      const data = await response.json();

      if (data.status === "approved") {
        clearInterval(checkInterval);
        showDownloadStep();
      }
    } catch (error) {
      console.error("Erro ao verificar status:", error);
    }
  }, 5000); // Verifica a cada 5 segundos
}

function showDownloadStep() {
  step1.classList.remove("active");
  step1.classList.add("d-none");
  step2.classList.remove("d-none");
  step2.classList.add("active");
}

function showError(message) {
  pixLoading.classList.add("d-none");
  pixContent.classList.remove("d-none");
  alert(message);
}
