<?php
require_once 'model/Config.php';

class ConfigController {
    public function index() {
        global $pageTitle, $activePage, $breadcrumbs;
        $pageTitle = 'Configuración';
        $activePage = 'config';
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Configuración']
        ];

        $config = Config::get();

        require_once 'view/layout/header.php';
        require_once 'view/config/index.php';
        require_once 'view/layout/footer.php';
    }

    public function guardar() {
        if ($_POST) {
            $data = [
                'owner_name' => $_POST['owner_name'],
                'owner_phone' => $_POST['owner_phone'],
                'owner_email' => $_POST['owner_email'],
                'theme_color' => $_POST['theme_color'],
                'mora_percentage' => floatval($_POST['mora_percentage'])
            ];
            Config::save($data);
            header('Location: ?c=config&msg=success');
        }
    }
}
