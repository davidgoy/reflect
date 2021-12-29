<nav id="primaryMenu" class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">

  <div class="container-fluid">

    <a class="navbar-brand" href="//<?php echo $_SERVER['SERVER_NAME']; ?>/"><img src="<?php if(isset($themeConfig['logoUrl']) && !empty($themeConfig['logoUrl'])) { echo $themeConfig['logoUrl']; } ?>"></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

      <?php foreach($primaryMenu as $menuItem): ?>

        <?php if(count($menuItem['children']) > 0): ?>

        <li class="nav-item dropdown">

          <a class="nav-link dropdown-toggle" href="<?php echo $menuItem['url']; ?>" id="navbarDropdownMenuLink<?php echo $menuItem['ID']; ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $menuItem['title']; ?>
          </a>

          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink<?php echo $menuItem['ID']; ?>">

            <?php foreach($menuItem['children'] as $subMenuItem): ?>

            <li><a class="dropdown-item <?php if(parse_url($subMenuItem['url'], PHP_URL_PATH) === parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) { echo 'active'; } ?>" href="<?php echo $subMenuItem['url']; ?>"><?php echo $subMenuItem['title']; ?></a></li>

            <?php endforeach; unset($subMenuItem); ?>

          </ul>

        </li>

        <?php else: ?>

          <li class="nav-item <?php if(parse_url($menuItem['url'], PHP_URL_PATH) === parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) { echo 'active'; } ?>"><a class="nav-link" href="<?php echo $menuItem['url']; ?>"><?php echo $menuItem['title']; ?></a></li>

        <?php endif; ?>

      <?php endforeach; unset($menuItem); ?>
      </ul>

      <form action="//<?php echo $_SERVER['SERVER_NAME']; ?>/" method="get" id="siteSearchForm" name="siteSearchForm" class="d-flex">
        <input id="searchTerms" name="searchTerms" class="form-control me-2" type="search" aria-label="Search" placeholder="">
        <button class="btn btn-outline-secondary" type="submit"><small>SEARCH</small></button>
      </form>

    </div>

  </div>

</nav>
