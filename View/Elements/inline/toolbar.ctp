<div class="inline-modal inline-modal-sections" ui-view="sections"></div>

<div class="toolbar-wrapper">
  <div class="toolbar-content">    
    <ul>
      <?= $this->Section->adminNav() ?>
      <? foreach( $menu as $nav): ?>
          <li><a href="<?= $nav ['url'] ?>"><?= $nav ['label'] ?></a></li>
      <? endforeach ?>
    </ul>
  </div>
</div>
<div class="inline-modal inline-modal-main" ng-view close-window></div>