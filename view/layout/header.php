<?php
/**
 * header.php — R&F Automotores
 * Incluir al inicio de cada vista: include "view/layout/header.php";
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="R&F Automotores — Sistema de Gestión de Cuotas">
  <meta name="theme-color" content="#0d0f14">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — R&F Automotores' : 'R&F Automotores' ?></title>

  <!-- ══════════════════════════════════════════════
       1. FONTS
  ══════════════════════════════════════════════ -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <!-- ══════════════════════════════════════════════
       2. FONT AWESOME 6
  ══════════════════════════════════════════════ -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

  <!-- ══════════════════════════════════════════════
       3. BOOTSTRAP ICONS 1.11
  ══════════════════════════════════════════════ -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- ══════════════════════════════════════════════
       4. BOOTSTRAP 5.3
  ══════════════════════════════════════════════ -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

  <!-- ══════════════════════════════════════════════
       5. DATATABLES + BOTONES DE EXPORTACIÓN
  ══════════════════════════════════════════════ -->
  <!-- DataTables core -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <!-- Buttons -->
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
  <!-- Responsive -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <!-- ══════════════════════════════════════════════
       6. SWEETALERT2
  ══════════════════════════════════════════════ -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- ══════════════════════════════════════════════
       7. CSS PROPIO
  ══════════════════════════════════════════════ -->
  <link rel="stylesheet" href="assets/styles/sidebar.css">
<link rel="stylesheet" href="assets/styles/style.css">

<?php
require_once "model/Config.php";
$appConfig = Config::get();
$themeColor = $appConfig['theme_color'] ?? '#6c7fff';
?>
<style>
  :root {
    --rf-accent: <?= $themeColor ?> !important;
    --rf-accent-dim: <?= $themeColor ?>26 !important; /* 15% opacity */
    --rf-accent-glow: <?= $themeColor ?>59 !important; /* 35% opacity */
  }
</style>

<!-- 1. jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

  <!-- CSS adicional por vista (opcional) -->
  <?php if (isset($extraCss)) echo $extraCss; ?>
</head>

<body>
<!-- ══════════════════════════════════════════════════════════
     WRAPPER PRINCIPAL (Flex container)
══════════════════════════════════════════════════════════════ -->
<div id="wrapper">

  <?php include "view/layout/sidebar.php"; ?>

  <!-- ── Contenido principal ─────────────────────────────── -->
  <div id="content" class="flex-grow-1 d-flex flex-column">

    <?php include "view/layout/navbar.php"; ?>

    <!-- Área del contenido dinámico -->
    <div id="main-content" class="flex-grow-1">