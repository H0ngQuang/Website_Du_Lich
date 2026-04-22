<?php

namespace App\Http\Controllers\clients;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\clients\Login;
use App\Models\clients\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeEmail;

class LoginController extends Controller
{

    private $login;
    protected $user;

    public function __construct()
    {
        $this->login = new Login();
        $this->user = new User();
    }
    public function index()
    {
        $title = 'Đăng nhập';
        return view('clients.login', compact('title'));
    }


    public function register(Request $request)
    {
        try {
            $request->validate([
                'username_register' => [
                    'required',
                    'max:50',
                    'regex:/^[a-zA-Z0-9_]+$/',
                    'unique:tbl_users,username',
                ],
                'email_register' => [
                    'required',
                    'email:rfc',
                    'max:100',
                    'unique:tbl_users,email',
                ],
                'password_register' => [
                    'required',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                ],
                're_pass' => [
                    'required',
                    'same:password_register',
                ],
            ], [
                'username_register.required'  => 'Vui lòng nhập tên tài khoản.',
                'username_register.max'       => 'Tên tài khoản tối đa 50 ký tự.',
                'username_register.regex'     => 'Tên tài khoản chỉ được chứa chữ cái, số và dấu gạch dưới (_).',
                'username_register.unique'    => 'Tên tài khoản đã được sử dụng.',
                'email_register.required'     => 'Vui lòng nhập email.',
                'email_register.email'        => 'Email không đúng định dạng.',
                'email_register.max'          => 'Email tối đa 100 ký tự.',
                'email_register.unique'       => 'Email này đã được đăng ký.',
                'password_register.required'  => 'Vui lòng nhập mật khẩu.',
                'password_register.min'       => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'password_register.regex'     => 'Mật khẩu phải có chữ hoa, chữ thường, số và ký tự đặc biệt.',
                're_pass.required'            => 'Vui lòng nhập lại mật khẩu.',
                're_pass.same'               => 'Mật khẩu nhập lại không khớp.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            // Flatten all errors into one message or return field map
            return response()->json([
                'success' => false,
                'errors'  => $errors,
                'message' => collect($errors)->flatten()->first(),
            ], 422);
        }

        $username = strip_tags(trim($request->username_register));
        $email    = strtolower(trim($request->email_register));
        $password = $request->password_register;

        $activation_token = Str::random(60);

        $dataInsert = [
            'username'         => $username,
            'email'            => $email,
            'password'         => Hash::make($password),
            'activation_token' => $activation_token,
        ];

        $this->login->registerAcount($dataInsert);

        // Gửi email kích hoạt
        $this->sendActivationEmail($email, $activation_token);

        // Gửi email chào mừng
        try {
            Mail::to($email)->send(new WelcomeEmail($username));
        } catch (\Exception $e) {
            \Log::error('Lỗi gửi welcome email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.',
        ]);
    }

    public function sendActivationEmail($email, $token)
    {
        $activation_link = route('activate.account', ['token' => $token]);

        try {
            Mail::send('clients.mail.emails_activation', ['link' => $activation_link], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Kích hoạt tài khoản của bạn');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi gửi activation email: ' . $e->getMessage());
        }
    }

    public function activateAccount($token)
    {
        $user = $this->login->getUserByToken($token);
        if ($user) {
            $this->login->activateUserAccount($token);

            return redirect('/login')->with('message', 'Tài khoản của bạn đã được kích hoạt!');
        } else {
            return redirect('/login')->with('error', 'Mã kích hoạt không hợp lệ!');
        }
    }

    //Xử lý người dùng đăng nhập
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user_login = $this->login->login($username);

        if ($user_login && Hash::check($password, $user_login->password)) {
            $userId = $this->user->getUserId($username);
            $user = $this->user->getUser($userId);
            $request->session()->put('username', $username);
            $request->session()->put('avatar', $user->avatar);
            toastr()->success("Đăng nhập thành công!",'Thông báo');
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'redirectUrl' => route('home'),  // Optional: dynamic home route
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin tài khoản không chính xác!',
            ]);
        }
    }

    //Xử lý đăng xuất
    public function logout(Request $request)
    {
        // Xóa session lưu trữ thông tin người dùng đã đăng nhập
        $request->session()->forget('username');
        $request->session()->forget('avatar');
        $request->session()->forget('userId');
        toastr()->success("Đăng xuất thành công!",'Thông báo');
        return redirect()->route('home');
    }


}
