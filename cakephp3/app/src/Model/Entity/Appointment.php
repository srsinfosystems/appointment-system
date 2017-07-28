<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Appointment Entity
 *
 * @property int $app_id
 * @property int $user_id
 * @property int $doc_id
 * @property string $title
 * @property string $app_status
 * @property \Cake\I18n\FrozenTime $app_time
 *
 * @property \App\Model\Entity\App $app
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Doctor $doctor
 */
class Appointment extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        //'app_id' => false,
        // 'user_id' => false,
        // 'doc_id' => false
    ];
}
