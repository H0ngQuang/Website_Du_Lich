<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Tours;
use Illuminate\Http\Request;

class VNPayController extends Controller
{
    private $tour;

    public function __construct()
    {
        parent::__construct();
        $this->tour = new Tours();
    }

    /**
     * Tạo URL thanh toán VNPay và redirect người dùng
     */
    public function createPayment(Request $request)
    {
        // Lưu tourId vào session để callback có thể lấy lại
        session()->put('vnpay_tourId', $request->tourId);

        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = config('vnpay.return_url');

        $vnp_TxnRef = time() . '_' . rand(100, 999); // Mã giao dịch unique
        $vnp_OrderInfo = 'Thanh_toan_tour_du_lich_Travela';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->amount * 100; // VNPay yêu cầu số tiền * 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Sắp xếp theo key
        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            }
            else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        // Tạo chữ ký bảo mật
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        return response()->json(['payUrl' => $vnp_Url]);
    }

    /**
     * Xử lý callback khi VNPay redirect về
     */
    public function handleReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        // Lấy secure hash từ request
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        // Sắp xếp và tạo hash để verify
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            }
            else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $tourId = session()->get('vnpay_tourId');
        $tour = $this->tour->getTourDetail($tourId);
        session()->forget('vnpay_tourId');

        if ($secureHash === $vnp_SecureHash) {
            $responseCode = $request->input('vnp_ResponseCode');
            if ($responseCode == '00') {
                // Thanh toán thành công
                $transIdVnpay = $request->input('vnp_TransactionNo');
                $title = 'Đã thanh toán VNPay';
                return view('clients.booking', compact('title', 'tour', 'transIdVnpay'));
            }
            else {
                // Thanh toán thất bại
                $transIdVnpay = null;
                $title = 'Thanh toán VNPay thất bại';
                return view('clients.booking', compact('title', 'tour', 'transIdVnpay'));
            }
        }
        else {
            // Chữ ký không hợp lệ
            $transIdVnpay = null;
            $title = 'Thanh toán không hợp lệ';
            return view('clients.booking', compact('title', 'tour', 'transIdVnpay'));
        }
    }
}
