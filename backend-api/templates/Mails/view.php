<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $mail
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Mail'), ['action' => 'edit', $mail->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Mail'), ['action' => 'delete', $mail->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mail->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Mails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Mail'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="mails view content">
            <h3><?= h($mail->mail) ?></h3>
            <table>
                <tr>
                    <th><?= __('Mail') ?></th>
                    <td><?= h($mail->mail) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($mail->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Contact Id') ?></th>
                    <td><?= $this->Number->format($mail->contact_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($mail->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($mail->created) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>