<!doctype html>
<html lang="en">

  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">

    <style>
      <?php if(isset($pageCssFile)) {require_once __DIR__ . '/../css/' . $pageCssFile;} ?>
    </style>

    <title>Reflect <?php if(isset($pageTitle)) { echo ' - ' . $pageTitle; } ?></title>

  </head>

  <body>

    <?php require_once __DIR__ . '/partials/header.php'; ?>
    <?php require_once __DIR__ . '/' . $mainContentFile; ?>
    <?php require_once __DIR__ . '/partials/footer.php'; ?>

    <!-- Bootstrap -->
    <script src="/js/bootstrap/jquery-3.5.1.slim.min.js"></script>
    <script src="/js/bootstrap/popper.min.js"></script>
    <script src="/js/bootstrap/bootstrap.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="/js/sweetalert2/sweetalert2.all.min.js"></script>

    <?php if($config['useTranspiledJs'] === 'true'): ?>

      <script>
      <?php require_once __DIR__ . '/../js/transpiled/' . 'main.js'; ?>
      <?php if(isset($pageJsFile)) { require_once __DIR__ . '/../js/transpiled/' . $pageJsFile; } ?>
      </script>

      <!-- Polyfills (required for JS files transpiled by Parcel) -->
      <script src="/js/polyfills/promise-polyfill/polyfill.min.js"></script>
      <script src="/js/polyfills/unfetch/polyfill/index.js"></script>

    <?php else: ?>

      <script>
      <?php require_once __DIR__ . '/../js/' . 'main.js'; ?>
      <?php if(isset($pageJsFile)) { require_once __DIR__ . '/../js/' . $pageJsFile; } ?>
      </script>

    <?php endif; ?>

  </body>
</html>
