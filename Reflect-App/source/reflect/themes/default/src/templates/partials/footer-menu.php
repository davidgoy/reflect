<div id="footerMenu" class="mt-2">
  <p class="text-center">
    <?php foreach($footerMenu as $menuItem): ?>
    <a class="mx-2" href="<?php echo $menuItem['url']; ?>"><?php echo $menuItem['title']; ?></a>
    <?php endforeach; unset($menuItem); ?>
  </p>
</div>
