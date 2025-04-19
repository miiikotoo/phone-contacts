<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Contact $contact
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Contact'), ['action' => 'edit', $contact->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Contact'), ['action' => 'delete', $contact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contact->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Contacts'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Contact'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="contacts view content">
            <h3><?= h($contact->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($contact->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Kana') ?></th>
                    <td><?= h($contact->kana) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($contact->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Birthday') ?></th>
                    <td><?= h($contact->birthday) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($contact->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($contact->created) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Mails') ?></h4>
                <?php if (!empty($contact->mails)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Contact Id') ?></th>
                            <th><?= __('Mail') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Created') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($contact->mails as $mail) : ?>
                        <tr>
                            <td><?= h($mail->id) ?></td>
                            <td><?= h($mail->contact_id) ?></td>
                            <td><?= h($mail->mail) ?></td>
                            <td><?= h($mail->modified) ?></td>
                            <td><?= h($mail->created) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Mails', 'action' => 'view', $mail->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Mails', 'action' => 'edit', $mail->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Mails', 'action' => 'delete', $mail->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $mail->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Phones') ?></h4>
                <?php if (!empty($contact->phones)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Contact Id') ?></th>
                            <th><?= __('Number') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Created') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($contact->phones as $phone) : ?>
                        <tr>
                            <td><?= h($phone->id) ?></td>
                            <td><?= h($phone->contact_id) ?></td>
                            <td><?= h($phone->number) ?></td>
                            <td><?= h($phone->modified) ?></td>
                            <td><?= h($phone->created) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Phones', 'action' => 'view', $phone->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Phones', 'action' => 'edit', $phone->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Phones', 'action' => 'delete', $phone->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $phone->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>