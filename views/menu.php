<?php
$menuItems = [];
$menuItems['home'] = $this->t('Home');
$menuItems['configurations'] = $this->t('Configurations');
if ($enableWg) {
    $menuItems['wg'] = $this->t('WireGuard');
}
$menuItems['account'] = $this->t('Account');
$menuItems['documentation'] = $this->t('Documentation');
if ($isAdmin) {
    $menuItems['connections'] = $this->t('Connections');
    $menuItems['users'] = $this->t('Users');
    $menuItems['info'] = $this->t('Info');
    $menuItems['stats'] = $this->t('Stats');
    $menuItems['messages'] = $this->t('Messages');
    $menuItems['log'] = $this->t('Log');
}
?>
<ul>
<?php foreach ($menuItems as $menuKey => $menuText): ?>
<?php if ($menuKey === $activeItem): ?>
    <li class="active">
<?php else: ?>
    <li>
<?php endif; ?>
        <a href="<?=$this->e($menuKey); ?>"><?=$menuText; ?></a>
    </li>
<?php endforeach; ?>
</ul>
