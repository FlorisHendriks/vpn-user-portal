<?php $this->layout('base', ['activeItem' => 'account', 'pageTitle' => $this->t('Account')]); ?>
<?php $this->start('content'); ?>
    <h1><?=$this->t('Account'); ?></h1>
    <h2><?=$this->t('Events'); ?></h2>
    <p>
        <?=$this->t('This is a list of events that occurred related to your account.'); ?>
    </p>
    <?php if (0 === count($userMessages)): ?>
        <p class="plain"><?=$this->t('No events yet.'); ?></p>
    <?php else: ?>
        <table class="tbl">
            <thead>
                <tr><th><?=$this->t('Date/Time'); ?> (<?=$this->e(date('T')); ?>)</th><th><?=$this->t('Message'); ?></th><th><?=$this->t('Type'); ?></tr>
            </thead>
            <tbody>
                <?php foreach ($userMessages as $message): ?>
                    <tr>
                        <td><?=$this->d($message['date_time']); ?></td>
                        <td><?=$this->e($message['message']); ?></td>
                        <td><span class="plain"><?=$this->e($message['type']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php $this->stop('content'); ?>
