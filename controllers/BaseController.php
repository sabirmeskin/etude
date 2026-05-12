<?php

require_once __DIR__ . '/../helpers/upload.php';

class BaseController
{
    public function render(string $view, array $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layout.php';
    }
}
