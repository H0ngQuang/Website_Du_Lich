<?php

return [
    'tmn_code' => env('VNPAY_TMN_CODE', 'CGXZLS0Z'),
    'hash_secret' => env('VNPAY_HASH_SECRET', 'RAOEXHYVSDDIIENYWSLDIIZTANXUXZFJ'),
    'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'return_url' => env('VNPAY_RETURN_URL', 'http://localhost:8000/vnpay-return'),
];
