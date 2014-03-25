<div class="toolbar-wrapper">
  <div class="toolbar-content">    
    <ul>
      <?= $this->Section->adminNav() ?>
      <li><a href="#groups">Grupos de usuarios</a></li>
      <? foreach( $menu as $nav): ?>
          <li><a href="<?= $nav ['url'] ?>"><?= $nav ['label'] ?></a></li>
      <? endforeach ?>
    </ul>
    

  </div>
</div>
<div class="cofree-modal" ng-view click-anywhere-but-here="clickedSomewhereElse"></div>