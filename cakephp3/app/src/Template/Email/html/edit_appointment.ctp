<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<p>Hello <?php echo $appointment->user->first_name. " " .$appointment->user->last_name; ?>,</p>
<p> Doctor has seen appointment has edited. <br/>
	Please find details below:<br>
	</p>
	<table>
		<tr>
			<th>Doctor Name</th>
			<td><?php echo $appointment->doctor->name; ?></td>
		</tr>
		<tr>
			<th>Appointment Detail</th>
			<td><?php echo $appointment->title; ?></td>
		</tr>
		<tr>
			<th>Appointment Time</th>
			<td><?php echo date('l jS \of F Y h:i:s A',strtotime($appointment->app_time)); ?></td>
		</tr>
		<tr>
			<th>Appointment Status</th>
			<td><?php echo $appointment->app_status; ?></td>
		</tr>
	</table>
	<p>Please  login to view the appointment</p>
<p>Regards,<br>
SRS
</p>
