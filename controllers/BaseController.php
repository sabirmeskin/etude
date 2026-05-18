<?php

require_once __DIR__ . '/../helpers/upload.php';

class BaseController
{
    public function render(string $view, array $data = [])
    {
        require_once __DIR__ . '/../helpers/AuthHelper.php';
        $data['currentUser'] = AuthHelper::user();
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (in_array($view, ['auth/login', 'auth/register', 'auth/forgot_password', 'auth/reset_password'], true)) {
            require __DIR__ . '/../views/layout-auth.php';
            return;
        }
        require __DIR__ . '/../views/layout.php';
    }
}
