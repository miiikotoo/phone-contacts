<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Phone> $phones
 */
?>
<div class="phones index content">
    <?= $this->Html->link(__('New Phone'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Phones') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('contact_id') ?></th>
                    <th><?= $this->Paginator->sort('number') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($phones as $phone): ?>
                <tr>
                    <td><?= $this->Number->format($phone->id) ?></td>
                    <td><?= $phone->hasValue('contact') ? $this->Html->link($phone->contact->name, ['controller' => 'Contacts', 'action' => 'view', $phone->contact->id]) : '' ?></td>
                    <td><?= h($phone->number) ?></td>
                    <td><?= h($phone->modified) ?></td>
                    <td><?= h($phone->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $phone->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $phone->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $phone->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $phone->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>