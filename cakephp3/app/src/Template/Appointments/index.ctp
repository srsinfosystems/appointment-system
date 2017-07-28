<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Appointment[]|\Cake\Collection\CollectionInterface $appointments
  */
$role_id = $_SESSION['Auth']['User']['role'];
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <?php if($role_id == '2') :?>
        <li><?= $this->Html->link(__('New Appointment'), ['action' => 'add']) ?></li>
        <?php endif; ?>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Doctors'), ['controller' => 'Doctors', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>

    </ul>
</nav>
<div class="appointments index large-9 medium-8 columns content">
    <h3><?= __('Appointments') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('app_id','ID') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id','Patient') ?></th>
                <th scope="col"><?= $this->Paginator->sort('doc_id','Doctor') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('app_status','Status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('app_time','Time') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): 
            ?>
            <tr>

                <td><?= $this->Number->format($appointment->app_id) ?></td>
                <td><?= $patient_name = $appointment->user->first_name." ".$appointment->user->last_name;
                 $appointment->has('user') ? $this->Html->link($patient_name, ['controller' => 'Users', 'action' => 'view', $appointment->user->id]) : '' ?></td>
                <td><?= $appointment->has('doctor') ? $this->Html->link($appointment->doctor->name, ['controller' => 'Doctors', 'action' => 'view', $appointment->doctor->doc_id]) : '' ?></td>
                <td><?= h($appointment->title) ?></td>
                <td><?= h($appointment->app_status) ?></td>
                <td><?= h($appointment->app_time) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $appointment->app_id]) ?>
                    <?php 
                    $app_status = $appointment->app_status; 
                    if($role_id == '1' && $app_status =="pending") { echo $this->Html->link(__('Edit'), ['action' => 'edit_doc_appoint', $appointment->app_id]);}
                    if($role_id == '2' && $app_status == "pending"){ echo $this->Html->link(__('Edit'), ['action' => 'edit', $appointment->app_id]); }
                    ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $appointment->app_id], ['confirm' => __('Are you sure you want to delete # {0}?', $appointment->app_id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
