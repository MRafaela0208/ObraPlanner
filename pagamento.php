<?php
$plano = isset($_GET['plano']) ? $_GET['plano'] : 'Plano não especificado'; 
$preco = isset($preco) ? $preco : 0.00; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <title>ObraPlanner</title>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Lexend:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

      html, body {
          width: 100vw;
          overflow-x: hidden;
          margin: 0;
          scroll-behavior: smooth;
      }

      header {
          background-color: #00244b;
          padding: 5px 0;
          text-align: center;
      }

      header img {
          max-height: 50px;
      }

      h1 {
          text-align: center;
          color: #074470;
          margin: 20px 0;
          font-size: 2rem;
      }
.container {
          display: flex;
          justify-content: space-between; 
          padding: 30px;
      }

      .informacoes-plano {
          padding: 30px;
          max-width: 500px; 
      }

      .informacoes-plano h2 {
          color: #074470;
      }

      .informacoes-plano p {
          color: #555;
          font-size: 1.2rem;
      }

      .metodos-pagamento {
          padding: 30px;
          background-color: #ffffff;
          margin: 20px 30px;
          border-radius: 8px;
          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
          max-width: 500px; 
      }

      .metodos-pagamento h2 {
          color: #074470;
          text-align: center;
      }

      #payment-form {
          display: flex;
          flex-direction: column;
          gap: 15px;
      }

      label {
          font-size: 1rem;
          cursor: pointer;
      }

      input[type="radio"] {
          margin-right: 10px;
      }

      button {
          background-color: #074470;
          color: white;
          border: none;
          padding: 10px 15px;
          border-radius: 4px;
          cursor: pointer;
          transition: background-color 0.3s;
          font-size: 1rem;
      }

      button:hover {
          background-color: #005f85;
      }

      #payment-details {
          margin-top: 20px;
      }

      .payment-info {
          display: none; 
          padding: 15px;
          border: 1px solid #ddd;
          border-radius: 8px;
          background-color: #f9f9f9;
          margin-bottom: 15px;
      }

      .payment-info h3 {
          margin: 0;
          color: #074470;
      }

      .payment-info input[type="text"] {
          width: calc(100% - 20px);
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
          margin-top: 10px;
      }

      #finalizar-pagamento {
          background-color: #2f9e44;
      }

      #finalizar-pagamento:hover {
          background-color: #228b3e;
      }
    </style>
</head>
<body>
<header>
    <img src="../imgs/obraplanner7.png" alt="Logo do Sistema" onclick="window.location.href='../index.php'">
</header>

<h1>Página de Pagamento</h1>

<div class="container">
    <div class="informacoes-plano">
        <h2>Você escolheu: <?php echo ucfirst($plano); ?></h2>
        <p>Preço: R$ <?php echo number_format($preco, 2, ',', '.'); ?>/mês</p>
        </div>

    <div class="metodos-pagamento">
        <h2>Formas de Pagamento</h2>
        <form id="payment-form">
            <label>
                <input type="radio" name="pagamento" value="cartao_credito" required>
                Cartão de Crédito
            </label>
            <label>
                <input type="radio" name="pagamento" value="boleto_bancario" required>
                Boleto Bancário
            </label>
            <label>
                <input type="radio" name="pagamento" value="pix" required>
                Pix
            </label>
            <label>
                <input type="radio" name="pagamento" value="cartao_debito" required>
                Cartão de Débito
            </label>
            <button type="button" onclick="showPaymentForm()">Continuar</button>
        </form>
        
        <div id="payment-details" style="display:none;">
            <div id="cartao_credito" class="payment-info">
                <h3>Informações do Cartão de Crédito</h3>
                <input type="text" placeholder="Número do Cartão" required>
                <input type="text" placeholder="Nome do Titular" required>
                <input type="text" placeholder="Data de Validade (MM/AA)" required>
                <input type="text" placeholder="CVV" required>
            </div>
            <div id="boleto_bancario" class="payment-info" style="display:none;">
                <h3>Boleto Bancário</h3>
                <p>Um boleto será enviado para o seu e-mail após a confirmação.</p>
            </div>
            <div id="pix" class="payment-info" style="display:none;">
                <h3>Pix</h3>
                <p>Use a chave abaixo para realizar o pagamento:</p>
                <p><strong>chave@pix.com</strong></p>
            </div>
            <div id="cartao_debito" class="payment-info" style="display:none;">
                <h3>Informações do Cartão de Débito</h3>
                <input type="text" placeholder="Número do Cartão" required>
                <input type="text" placeholder="Nome do Titular" required>
                <input type="text" placeholder="Data de Validade (MM/AA)" required>
                <input type="text" placeholder="CVV" required>
            </div>

            <button id="finalizar-pagamento" onclick="finalizarCompra()">Finalizar Pagamento</button>
        </div>
    </div>
</div>
<script>
    function showPaymentForm() {
        document.getElementById('payment-details').style.display = 'block';
        const selectedPayment = document.querySelector('input[name="pagamento"]:checked').value;

        document.querySelectorAll('.payment-info').forEach(function (el) {
            el.style.display = 'none';
        });

        document.getElementById(selectedPayment).style.display = 'block';
    }

    function finalizarCompra() {
        alert("Pagamento realizado com sucesso! Você será redirecionado para a sua Dashboard.");
        window.location.href = '../empresa/dash.php';
    }
</script>
</body>
</html>