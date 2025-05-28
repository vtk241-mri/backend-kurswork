<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Auth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Показує профіль користувача.
     * Відображає сторінку з інформацією про поточного користувача.
     *
     * @return void
     */
    public function profile()
    {
        $user = Auth::user();
        View::render('profile/index', compact('user'), 'layouts/header', 'layouts/footer');
    }

    /**
     * Показує форму для редагування профілю користувача.
     * Відображає форму для редагування інформації про користувача.
     *
     * @return void
     */
    public function editProfile()
    {
        $user = Auth::user();
        View::render('profile/edit', compact('user'));
    }

    /**
     * Обробляє оновлення профілю користувача.
     * Оновлює інформацію користувача, включаючи ім’я користувача, email, а також аватар.
     * Якщо аватар було завантажено, він зберігається на сервері.
     * Також перезавантажується сесія з оновленими даними користувача.
     *
     * @return void
     */
    public function updateProfile()
    {
        $user = Auth::user();
        $userModel = new User();

        $data = [
            'username' => trim($_POST['username'] ?? $user['username']),
            'email' => trim($_POST['email'] ?? $user['email']),
        ];

        // 1) якщо завантажили новий аватар
        if (
            isset($_FILES['avatar'])
            && $_FILES['avatar']['error'] === UPLOAD_ERR_OK
        ) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $name = uniqid('avatar_') . '.' . $ext;
            $dest = $_SERVER['DOCUMENT_ROOT'] . '/uploads/avatars/' . $name;
            if (!is_dir(dirname($dest))) {
                mkdir(dirname($dest), 0755, true);
            }
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                $data['avatar'] = '/uploads/avatars/' . $name;
            } else {
                $error = 'Не вдалося зберегти аватарку на сервері.';
                return View::render('profile/edit', compact('user', 'error'));
            }
        }

        // 2) обробка паролю
        if (!empty($_POST['password']) && $_POST['password'] === $_POST['password_confirmation']) {
            $data['password'] = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
        } elseif (!empty($_POST['password']) && $_POST['password'] !== $_POST['password_confirmation']) {
            $error = 'Паролі не співпадають.';
            return View::render('profile/edit', compact('user', 'error'));
        }

        // 3) оновлення
        $ok = $userModel->update($user['id'], $data);
        if (!$ok) {
            $error = 'Не вдалося оновити профіль. Спробуйте ще раз.';
            return View::render('profile/edit', compact('user', 'error'));
        }

        // 4) перезавантажити сесію новими даними
        $updated = $userModel->findById($user['id']);
        Auth::login($updated);

        header('Location: /profile');
        exit;
    }
}
