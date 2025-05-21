<?php
session_start();

class Wallet {
    public $balance;

    public function __construct() {
        if (!isset($_SESSION['balance'])) {
            $_SESSION['balance'] = 0;
        }
        $this->balance = $_SESSION['balance'];
    }

    public function deposit($amount) {
        if ($amount <= 0) {
            return "Please enter an amount greater than zero.";
        }

        if ($this->balance == 0) {
            $this->balance += $amount;
            $_SESSION['balance'] = $this->balance;
            return "You have added $amount to your wallet.";
        } else {
            return "Deposit is only allowed when your wallet is empty.";
        }
    }

    public function withdraw($amount) {
        if ($amount <= 0) {
            return "Please enter an amount greater than zero.";
        }

        if ($amount <= $this->balance) {
            $this->balance -= $amount;
            $_SESSION['balance'] = $this->balance;
            return "You have taken out $amount.";
        } else {
            return "Not enough money in your wallet.";
        }
    }
}

$wallet = new Wallet();
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $amount = (float) $_POST['amount'];

    if ($action === 'deposit') {
        $notice = $wallet->deposit($amount);
    } elseif ($action === 'withdraw') {
        $notice = $wallet->withdraw($amount);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wallet</title>
    <style type="text/css"> 
    body {
        height:100vh;
        background-color:skyblue;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        color:blue;
    }
    
    form {
        color:blue;
        font-size:20px;     
        padding: 40px;
        text-align:center;
    }
    button {
        border:none;
        border-radius:10px;
        padding:10px 40px;
    }
    button[type="submit"] {
        background: blue;
        color: white;
        cursor: pointer;
    }
    </style>
</head>
<body>
    <h2>My Wallet</h2>
    <p>Your Current Amount: <?php echo number_format($wallet->balance, 2); ?></p>

    <?php if ($notice) echo "<p style='color:red;'><strong>$notice</strong></p>"; ?>

    <form method="post">
        Enter Amount: <input type="number" name="amount" step="1" required><br><br>
        <button type="submit" name="action" value="deposit">Deposit</button>
        <button type="submit" name="action" value="withdraw">Withdraw</button>
    </form>
</body>
</html>
