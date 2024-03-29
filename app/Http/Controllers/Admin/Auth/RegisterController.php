<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\Admin\RegisterUserRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Admin\Auth
 */
class RegisterController extends Controller
{
    protected $viewPath = 'admin.auth';

    /**
     * @var User
     */
    protected $user;

    /**
     * RegisterController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Admin register
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function register(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->guard('admin')->check()) {
                // redirect to dashboard if logged
                return redirect()->route('admin.home');
            } else {
                // redirect login form
                return view("{$path}.register")->with([
                    'path' => $path
                ]);
            }
        } else {
            // POST Method
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'max:191'],
                'last_name' => ['required', 'max:191'],
                'username' => ['required', 'min:6', 'max:191', 'unique:users,username'],
                'email' => ['required', 'max:191', 'email', 'unique:users,email'],
                'password' => ['required', 'min:6', 'max:32'],
            ], [
                'first_name.required' => 'Tên là trường bắt buộc.',
                'first_name.max' => 'Tên có tối đa 191 ký tự.',
                'last_name.required' => 'Họ, đệm là trường bắt buộc.',
                'last_name.max' => 'Tên có tối đa 191 ký tự.',
                'username.required' => 'Tên tài khoản là trường bắt buộc.',
                'username.min' => 'Tên tài khoản có tối thiểu 6 ký tự.',
                'username.max' => 'Tên tài khoản có tối đa 191 ký tự.',
                'username.unique' => 'Tên tài khoản đã tồn tại.',
                'email.required' => 'Email là trường bắt buộc.',
                'email.max' => 'Email có tối đa 191 ký tự.',
                'email.email' => 'Email không đúng định dạng.',
                'email.exists' => 'Email đã tồn tại.',
                'password.required' => 'Mật khẩu là trường bắt buộc.',
                'password.min' => 'Mật khẩu có tối thiểu 6 ký tự.',
                'password.max' => 'Mật khẩu có tối đa 32 ký tự.',
            ]);

            // Validate
            if ($validator->fails()) {
                // check validate and return error message.
                hwa_notify_error($validator->getMessageBag()->first(), ['top' => true, 'title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get user data from request
                $userData = [
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'username' => $request['username'],
                    'email' => $request['email'],
                    'password' => bcrypt($request['password']), // Hash
                ];

                if (!hwa_demo_env()) {
                    // Create new user
                    if ($user = $this->user->create($userData)) {
                        $dataSend = [
                            'subject' => hwa_app_name() . " | Đăng ký thành công tài khoản mới",
                            'first_name' => $user->first_name,
                            'email' => $user->email,
                            'password' => trim($request['password']),
                        ];

                        try {
                            $user->notify(new RegisterUserRequest($dataSend));
                        } catch (\Exception $exception) {
                            Log::error($exception->getMessage());
                        }

                        // Notify and redirect to login page
                        hwa_notify_success("Đăng ký thành công.", ['top' => true]);
                        return redirect()->route("{$path}.login");
                    } else {
                        // Notify and redirect back
                        hwa_notify_error("Lỗi đăng ký.", ['top' => true]);
                        return redirect()->back()->withInput();
                    }
                } else {
                    // Notify and redirect to login page
                    hwa_notify_success("Đăng ký thành công.", ['top' => true]);
                    return redirect()->route("{$path}.login");
                }
            }
        }
    }
}
