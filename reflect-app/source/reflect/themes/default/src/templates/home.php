<main role="main" class="container-fluid">

  <?php if($themeConfig['homePageTitleDisplayOption'] === '3'): ?>

  <div class="pageOrPostTitle">

    <h1 class="text-center display-5"><?php echo $cmsContent['title']; ?></h1>

  </div>

  <?php endif; ?>

  <div id="mainContent" class="wp-embed-responsive">

    <?php if(isset($cmsContent['featuredMedia'])): ?>

      <div id="featuredMedia" style="background: url('<?php echo $cmsContent['featuredMedia']; ?>') no-repeat center/cover;">

        <?php if($themeConfig['homePageTitleDisplayOption'] === '2'): ?>
        <h1 class="text-center display-5 text-light"><?php echo $cmsContent['title']; ?></h1>
        <?php endif; ?>

      </div>

    <?php endif; ?>

    <?php if($themeConfig['homePageTitleDisplayOption'] === '1'): ?>

    <div class="pageOrPostTitle">

      <h1 class="text-center display-5"><?php echo $cmsContent['title']; ?></h1>

    </div>

    <?php endif; ?>

    <?php echo $cmsContent['body']; ?>

  </div>

</main>
