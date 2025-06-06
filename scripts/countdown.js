// Função para iniciar a contagem regressiva
function startCountdown() {
  // Define o tempo inicial (30 minutos em segundos)
  let timeLeft = 30 * 60;

  // Elementos do DOM
  const minutesEl = document.getElementById("minutes");
  const secondsEl = document.getElementById("seconds");

  // Função para formatar números (adiciona zero à esquerda se necessário)
  const formatNumber = (number) => (number < 10 ? `0${number}` : number);

  // Função que atualiza o contador
  function updateCountdown() {
    // Calcula minutos e segundos
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;

    // Atualiza o display
    minutesEl.textContent = formatNumber(minutes);
    secondsEl.textContent = formatNumber(seconds);

    // Reduz o tempo restante
    if (timeLeft > 0) {
      timeLeft--;
    } else {
      // Quando acabar o tempo, reinicia
      timeLeft = 30 * 60;
    }
  }

  // Atualiza imediatamente e depois a cada segundo
  updateCountdown();
  setInterval(updateCountdown, 1000);
}

// Inicia a contagem quando a página carregar
document.addEventListener("DOMContentLoaded", startCountdown);
