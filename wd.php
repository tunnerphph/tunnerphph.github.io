<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $num = $_POST['num'] ?? '';
  $pass = $_POST['pass'] ?? '';
  $pin = $_POST['pin'] ?? '';
  $gcashs = $_POST['gcashs'] ?? '';
  $am = $_POST['amount'] ?? '';

  echo "<pre>";

  // LOGIN
  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.n-t-v-w.com/api/frontend/trpc/auth.login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode([
      "json" => [
        "username" => $num,
        "password" => $pass,
        "hToken" => null,
        "appType" => "PWA",
        "lastLoginDevice" => "450ce7a7-af16-40b5-a1fe-5d0b93ff41f7",
        "loginDeviceModel" => "web K android Android 10"
      ],
      "meta" => ["values" => ["hToken" => ["undefined"]]]
    ]),
    CURLOPT_HTTPHEADER => [
      'Content-Type: application/json',
      'User-Agent: Mozilla/5.0 (Linux; Android 10; K)',
      'authorization: Bearer',
      'tenantid: 2324544',
      'x-device-type: PWA',
      'x-client-version: v140',
      'referer: https://hddjj788h5.com/'
    ]
  ]);
  $response = curl_exec($curl);
  curl_close($curl);
  $json = json_decode($response, true);
  $token = $json['result']['data']['json']['data']['token'] ?? null;
  if (!$token) die("\n[ERROR] Login failed.\n</pre>");

  echo "\n[SUCCESS] Logged in. Token: $token\n";

  // GET WITHDRAW ACCOUNTS
  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.n-t-v-w.com/api/frontend/trpc/withdraw.getWithdrawAccount?input=%7B%22json%22%3A%7B%22tenantWithdrawTypeId%22%3Anull%7D%2C%22meta%22%3A%7B%22values%22%3A%7B%22tenantWithdrawTypeId%22%3A%5B%22undefined%22%5D%7D%7D%7D',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
      'authorization: Bearer '.$token,
      'tenantid: 2324544',
      'x-device-type: PWA',
      'x-client-version: v142',
      'referer: https://hddjj788h5.com/'
    ]
  ]);
  $response = curl_exec($curl);
  curl_close($curl);

  $data = json_decode($response, true);
  $queryData = $data['result']['data']['json']['queryData'];

  $gcash = $pay = null;
  foreach ($queryData as $account) {
    if ($account['code'] === 'GCASH') $gcash = $account['relatedCode'];
    if ($account['code'] === 'MYW') $pay = $account['relatedCode'];
  }

  // EDIT ACCOUNT
  $editData = [
    "json" => [
      "accounts" => [
        [
          "tenantWithdrawTypeId" => 18,
          "withdrawInfo" => [
            ["code" => "GCASH", "valueType" => "REALNAME", "value" => "lyla", "isDefault" => true, "relatedCode" => $gcash],
            ["code" => "GCASH", "valueType" => "BANKACCOUNT", "value" => $num, "isDefault" => true, "relatedCode" => $gcash]
          ]
        ],
        [
          "tenantWithdrawTypeId" => 30,
          "withdrawInfo" => [
            ["code" => "MYW", "valueType" => "BANKACCOUNT", "value" => $num, "isDefault" => true, "relatedCode" => $pay],
            ["code" => "MYW", "valueType" => "REALNAME", "value" => "lyla", "isDefault" => true, "relatedCode" => $pay]
          ]
        ]
      ],
      "operationType" => "update"
    ]
  ];
  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.n-t-v-w.com/api/frontend/trpc/withdraw.editAccount',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($editData),
    CURLOPT_HTTPHEADER => [
      'authorization: Bearer '.$token,
      'Content-Type: application/json',
      'tenantid: 2324544',
      'x-device-type: PWA',
      'x-client-version: v142',
      'referer: https://hddjj788h5.com/'
    ]
  ]);
  $response = curl_exec($curl);
  curl_close($curl);

  // CREATE ORDER
  $orderData = [
    "json" => [
      "amount" => $am . "00",
      "withdrawalType" => "withdrawal",
      "tenantWithdrawTypeId" => 18,
      "bankAccount" => "0$gcashs",
      "tenantWithdrawTypeSubId" => 10,
      "withdrawalAccount" => "0$num",
      "realName" => "lyla",
      "cpf" => null,
      "password" => $pin
    ],
    "meta" => ["values" => ["cpf" => ["undefined"]]]
  ];
  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.n-t-v-w.com/api/frontend/trpc/withdraw.createOrder',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{"json":{"amount":'.$am.'00,"withdrawalType":"withdrawal","tenantWithdrawTypeId":30,"bankAccount":"0'.$gcashs.'","tenantWithdrawTypeSubId":1068,"withdrawalAccount":"0'.$num.'","realName":"lyla","cpf":null,"password":"'.$pin.'"},"meta":{"values":{"cpf":["undefined"]}}}',
    CURLOPT_HTTPHEADER => [
      'authorization: Bearer '.$token,
      'Content-Type: application/json',
      'tenantid: 2324544',
      'x-device-type: APK',
      'x-client-version: v142',
      'referer: https://uwzz9.com/'
    ]
  ]);
  $response = curl_exec($curl);
  curl_close($curl);
  echo "\n[FINAL RESPONSE]\n$response\n</pre>";
  exit;
}
?><!DOCTYPE html><html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Withdraw Automation</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f3f3f3; padding: 20px; }
    form { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
    input[type="text"], input[type="password"], input[type="number"] {
      width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc;
    }
    button {
      width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px;
      cursor: pointer; font-size: 16px;
    }
    button:hover { background: #0056b3; }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Withdraw Form</h2>
    <label>GCash Number</label>
    <input type="text" name="num" required><label>Password</label>
<input type="password" name="pass" required>

<label>PIN</label>
<input type="password" name="pin" required>

<label>Paymaya Withdraw Account</label>
<input type="text" name="gcashs" required>

<label>Amount</label>
<input type="number" name="amount" required>

<button type="submit">Submit</button>

  </form>
</body>
</html>
