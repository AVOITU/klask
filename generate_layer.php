#!/usr/bin/env php
<?php

// === 1. Nom demandé ===
$nomChoisi = readline("Nom de la ressource (ex: Ticket) : ");
$nomChoisi = trim($nomChoisi);

if ($nomChoisi === '') {
    echo "Nom vide, arrêt.\n";
    exit(1);
}

$classe = ucfirst($nomChoisi);
$baseDir = __DIR__;

// === 2. Dossiers ===
$templateDir        = $baseDir . '/templates';
$controllerDir      = $baseDir . '/src/Controller';
$controllerImplDir  = $baseDir . '/src/Controller/Impl';
$serviceDir         = $baseDir . '/src/Service';
$serviceImplDir     = $baseDir . '/src/Service/Impl';
$jsDir              = $baseDir . '/public/js/pages';
$cssDir             = $baseDir . '/public/css/pages';

// Création auto
foreach ([$templateDir, $controllerDir, $controllerImplDir, $serviceDir, $serviceImplDir, $jsDir, $cssDir] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
}

// === 2.1 Template ===
$templateFile = $templateDir . '/' . $classe . '.php';
if (!file_exists($templateFile)) {
    $templateContent = <<<PHP
<?php
// Template {$classe}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{$classe}</title>
    <link rel="stylesheet" href="/css/pages/{$classe}.css">
</head>
<body>

<h1>Page {$classe}</h1>

<script src="/js/pages/{$classe}.js"></script>
</body>
</html>

PHP;
    file_put_contents($templateFile, $templateContent);
    echo "Créé : {$templateFile}\n";
}

// === 2.2 Interface Controller ===
$controllerInterfaceFile = $controllerDir . '/' . $classe . 'Controller.php';
if (!file_exists($controllerInterfaceFile)) {
    $controllerInterfaceContent = <<<PHP
<?php

interface {$classe}Controller
{
    public function index(): void;
}

PHP;
    file_put_contents($controllerInterfaceFile, $controllerInterfaceContent);
    echo "Créé : {$controllerInterfaceFile}\n";
}

// === 2.3 Impl Controller ===
$controllerImplFile = $controllerImplDir . '/' . $classe . 'ControllerImpl.php';
if (!file_exists($controllerImplFile)) {
    $controllerImplContent = <<<PHP
<?php

require_once __DIR__ . '/../{$classe}Controller.php';
require_once __DIR__ . '/../../Service/{$classe}Service.php';

class {$classe}ControllerImpl implements {$classe}Controller
{
    private {$classe}Service \$service;

    public function __construct({$classe}Service \$service)
    {
        \$this->service = \$service;
    }

    public function index(): void
    {
        \$data = \$this->service->getAll();
        include __DIR__ . '/../../../templates/{$classe}.php';
    }
}

PHP;
    file_put_contents($controllerImplFile, $controllerImplContent);
    echo "Créé : {$controllerImplFile}\n";
}

// === 2.4 Interface Service ===
$serviceInterfaceFile = $serviceDir . '/' . $classe . 'Service.php';
if (!file_exists($serviceInterfaceFile)) {
    $serviceInterfaceContent = <<<PHP
<?php

interface {$classe}Service
{
    public function getAll(): array;
}

PHP;
    file_put_contents($serviceInterfaceFile, $serviceInterfaceContent);
    echo "Créé : {$serviceInterfaceFile}\n";
}

// === 2.5 Impl Service ===
$serviceImplFile = $serviceImplDir . '/' . $classe . 'ServiceImpl.php';
if (!file_exists($serviceImplFile)) {
    $serviceImplContent = <<<PHP
<?php

require_once __DIR__ . '/../{$classe}Service.php';

class {$classe}ServiceImpl implements {$classe}Service
{
    public function getAll(): array
    {
        return [];
    }
}

PHP;
    file_put_contents($serviceImplFile, $serviceImplContent);
    echo "Créé : {$serviceImplFile}\n";
}

// === 2.6 JS file ===
$jsFile = $jsDir . '/' . $classe . '.js';
if (!file_exists($jsFile)) {
    $jsContent = <<<JS
console.log("JS chargé pour {$classe}");
JS;
    file_put_contents($jsFile, $jsContent);
    echo "Créé : {$jsFile}\n";
}

// === 2.7 CSS file ===
$cssFile = $cssDir . '/' . $classe . '.css';
if (!file_exists($cssFile)) {
    $cssContent = <<<CSS
/* CSS pour {$classe} */

body {
    background: #f8f8f8;
}
CSS;
    file_put_contents($cssFile, $cssContent);
    echo "Créé : {$cssFile}\n";
}

echo "\nGénération complète terminée pour '{$classe}'.\n";
