<?php $this->layout('base', ['activeItem' => 'home', 'pageTitle' => $this->t('Home')]); ?>
<?php $this->start('content'); ?>
<?php if ($motdMessage): ?>
    <p class="plain"><?=$this->batch($motdMessage['message'], 'escape|nl2br'); ?></p>
<?php endif; ?>
<p>
    <?=$this->t('Welcome to this VPN service!'); ?>
</p>
<?php $this->stop('content'); ?>
