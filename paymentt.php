<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Page</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .payment-container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 20px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      max-width: 400px;
      text-align: center;
    }

    .payment-container h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .payment-methods {
      text-align: left;
      margin: 20px 0;
    }

    .payment-methods label {
      display: block;
      margin: 10px 0;
      cursor: pointer;
    }

    .payment-methods input {
      margin-right: 10px;
    }

    .pay-btn {
      background: #007bff;
      color: #fff;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .pay-btn:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="payment-container">
    <h2>💳 Choose Payment Method</h2>
    <form onsubmit="processPayment(event)">
      <div class="payment-methods">
        <label><input type="radio" name="payment_method" value="Card" required /> Credit/Debit Card</label>
        <label><input type="radio" name="payment_method" value="GPay" /> Google Pay (GPay)</label>
        <label><input type="radio" name="payment_method" value="PhonePe" /> PhonePe</label>
        <label><input type="radio" name="payment_method" value="Paytm" /> Paytm</label>
      </div>
      <button type="submit" class="pay-btn">Pay Now</button>
    </form>
  </div>

  <script>
    function processPayment(event) {
      event.preventDefault();
      const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

      switch (selectedMethod) {
        case "Paytm":
          window.location.href = "https://paytm.com";
          break;
        case "GPay":
            window.location.href = "https://pay.google.com";
          break;
        case "PhonePe":
            window.location.href = "https://phonepe.com";
          break;
        case "Card":
          alert("Proceeding to card payment gateway...");
          break;
        default:
          alert("Please select a payment method.");
      }
    }
  </script>
</body>
</html>
