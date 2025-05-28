<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Відображення форми входу.
     *
     * @return void
     */
    public function login()
    {
        View::render(
            'auth/login',
            [],
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Обробка форми входу (POST).
     *
     * @return void
     */
    public function loginPost()
    {
        $userModel = new User();
        $user = $userModel->findByEmail($_POST['email'] ?? '');

        if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
            Auth::login($user);
            header('Location: /');
            exit;
        }

        $error = 'Неправильна пошта або пароль';
        View::render(
            'auth/login',
            compact('error'),
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Відображення форми реєстрації.
     *
     * @return void
     */
    public function register()
    {
        View::render(
            'auth/register',
            [],
            'layouts/header',
            'layouts/footer'
        );
    }

    /**
     * Обробка форми реєстрації (POST).
     *
     * @return void
     */
    public function registerPost()
    {
        if (($_POST['password'] ?? '') !== ($_POST['password_confirmation'] ?? '')) {
            $error = 'Паролі не співпадають';
            View::render(
                'auth/register',
                compact('error'),
                'layouts/header',
                'layouts/footer'
            );
            return;
        }

        $userModel = new User();
        $data = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
        ];
        $userModel->create($data);

        header('Location: /login');
        exit;
    }

    /**
     * Вихід користувача.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();
        header('Location: /login');
        exit;
    }
}
