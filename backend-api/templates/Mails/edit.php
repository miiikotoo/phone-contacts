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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $mail->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $mail->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Mails'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="mails form content">
            <?= $this->Form->create($mail) ?>
            <fieldset>
                <legend><?= __('Edit Mail') ?></legend>
                <?php
                    echo $this->Form->control('contact_id');
                    echo $this->Form->control('mail');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
