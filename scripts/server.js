const express = require("express");
const axios = require("axios");
const cors = require("cors");

const app = express();
const PORT = 3000;

app.use(cors());
app.use(express.json());

app.post("/pagamento", async (req, res) => {
  try {
    const resposta = await axios.post(
      "https://api.mercadopago.com/v1/payments",
      {
        transaction_amount: 19.9,
        description: "CD Playlist dos Coroas",
        payment_method_id: "pix",
        payer: {
          email: "comprador@email.com",
          first_name: "Comprador",
          last_name: "Teste",
        },
      },
      {
        headers: {
          Authorization:
            "Bearer APP_USR-7165375125176906-042903-1614b2f24c618b1f0cb1b36a055d25c4-460406015",
          "Content-Type": "application/json",
        },
      }
    );

    const qrCode =
      resposta.data.point_of_interaction.transaction_data.qr_code_base64;
    const copiaCola =
      resposta.data.point_of_interaction.transaction_data.qr_code;

    res.json({ qrCode, copiaCola });
  } catch (erro) {
    console.error(
      "Erro ao gerar pagamento:",
      erro.response?.data || erro.message
    );
    res.status(500).json({ erro: "Erro ao gerar pagamento" });
  }
});

app.listen(PORT, () => {
  console.log(`Servidor rodando em http://localhost:${PORT}`);
});
