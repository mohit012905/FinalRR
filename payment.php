<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Secure Payment</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: url('pay.jpg') no-repeat center center/cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .payment-container {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      width: 400px;
      border-radius: 15px;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    .payment-options {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .tab-button {
      flex-grow: 1;
      background: #e0e0e0;
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 5px;
      transition: 0.3s;
    }

    .tab-button.active {
      background: #007bff;
      color: white;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .pay-button {
      background: #28a745;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      cursor: pointer;
      border-radius: 5px;
      transition: 0.3s;
    }

    .pay-button:hover {
      background: #218838;
    }
  </style>
</head>
<body>

<div class="payment-container">
  <h2>Complete Your Payment</h2>

  <div class="payment-options">
    <button class="tab-button active" onclick="openTab(event, 'card')">Card</button>
    <button class="tab-button" onclick="openTab(event, 'upi')">UPI</button>
    <button class="tab-button" onclick="openTab(event, 'netbanking')">Net Banking</button>
    <button class="tab-button" onclick="openTab(event, 'wallet')">Wallet</button>
  </div>

  <!-- CARD -->
  <form id="card" class="tab-content active" action="process_payment.php" method="POST">
    <h3>Card Payment</h3>
    <input type="hidden" name="payment_method" value="card">
    <input type="text" name="card_number" placeholder="Card Number" required>
    <input type="text" name="cardholder_name" placeholder="Cardholder Name" required>
    <input type="month" name="expiry_date" required>
    <input type="password" name="cvv" placeholder="CVV" required>
    <input type="number" name="amount" placeholder="Amount" required>
    <button type="submit" class="pay-button">Pay Now</button>
  </form>

  <!-- UPI -->
  <form id="upi" class="tab-content" action="process_payment.php" method="POST">
    <h3>UPI Payment</h3>
    <input type="hidden" name="payment_method" value="upi">
    <input type="text" name="upi_id" placeholder="Enter UPI ID" required>
    <input type="number" name="amount" placeholder="Amount" required>
    <button type="submit" class="pay-button">Pay via UPI</button>
  </form>

  <!-- Netbanking -->
  <form id="netbanking" class="tab-content" action="process_payment.php" method="POST">
    <h3>Net Banking</h3>
    <input type="hidden" name="payment_method" value="netbanking">
    <select name="bank_name" required>
      <option value="">Select Your Bank</option>
      <option value="HDFC">HDFC Bank</option>
      <option value="ICICI">ICICI Bank</option>
      <option value="SBI">SBI</option>
      <option value="Axis">Axis Bank</option>
    </select>
    <input type="number" name="amount" placeholder="Amount" required>
    <button type="submit" class="pay-button">Proceed to Pay</button>
  </form>

  <!-- Wallet -->
  <form id="wallet" class="tab-content" onsubmit="redirectWallet(event)">
    <h3>Wallet Payment</h3>
    <input type="hidden" name="payment_method" value="wallet">
    <select name="wallet_name" id="wallet_name" required>
      <option value="">Select Wallet</option>
      <option value="Paytm">Paytm</option>
      <option value="PhonePe">PhonePe</option>
      <option value="GooglePay">Google Pay</option>
    </select>
    <input type="number" name="amount" placeholder="Amount" required>
    <button type="submit" class="pay-button">Pay with Wallet</button>
  </form>
</div>

<script>
  function openTab(event, tabName) {
    const tabContent = document.getElementsByClassName("tab-content");
    const tabButtons = document.getElementsByClassName("tab-button");

    for (let i = 0; i < tabContent.length; i++) {
      tabContent[i].classList.remove("active");
    }

    for (let i = 0; i < tabButtons.length; i++) {
      tabButtons[i].classList.remove("active");
    }

    document.getElementById(tabName).classList.add("active");
    event.currentTarget.classList.add("active");
  }

  function redirectWallet(event) {
    event.preventDefault();
    const wallet = document.getElementById("wallet_name").value;
    
    if (wallet === "Paytm") {
      window.location.href = "https://paytm.com";
    } else if (wallet === "PhonePe") {
      window.location.href = "https://www.phonepe.com";
    } else if (wallet === "GooglePay") {
      window.location.href = "https://pay.google.com";
    } else {
      alert("Please select a wallet.");
    }
  }
</script>

</body>
</html>
