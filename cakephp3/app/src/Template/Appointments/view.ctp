<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Appointment $appointment
  */
$role_id = $_SESSION['Auth']['User']['role'];
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $role_id == '1' ? $this->Html->link(__('Edit'), ['action' => 'edit_doc_appoint', $appointment->app_id]): $this->Html->link(__('Edit'), ['action' => 'edit', $appointment->app_id]); 
                    ?></li>
        <li><?= $this->Form->postLink(__('Delete Appointment'), ['action' => 'delete', $appointment->app_id], ['confirm' => __('Are you sure you want to delete # {0}?', $appointment->app_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Appointments'), ['action' => 'index']) ?> </li>
        <?php if($role_id == '2') :?>
        <li><?= $this->Html->link(__('New Appointment'), ['action' => 'add']) ?></li>
        <?php endif; ?>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('List Doctors'), ['controller' => 'Doctors', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?> </li>
    </ul>
</nav>
<div class="appointments view large-9 medium-8 columns content">
    <h3><?= h($appointment->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Patient') ?></th>
            <td><?= $patient_name = $appointment->user->first_name." ".$appointment->user->last_name;
                 $appointment->has('user') ? $this->Html->link($patient_name, ['controller' => 'Users', 'action' => 'view', $appointment->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Doctor') ?></th>
            <td><?= $appointment->has('doctor') ? $this->Html->link($appointment->doctor->name, ['controller' => 'Doctors', 'action' => 'view', $appointment->doctor->doc_id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($appointment->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($appointment->app_status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('ID') ?></th>
            <td><?= $this->Number->format($appointment->app_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Time') ?></th>
            <td><?= h($appointment->app_time) ?></td>
        </tr>
    </table>
</div>
