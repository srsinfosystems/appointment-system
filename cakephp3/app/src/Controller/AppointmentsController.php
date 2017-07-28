<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
/**
 * Appointments Controller
 *
 * @property \App\Model\Table\AppointmentsTable $Appointments
 *
 * @method \App\Model\Entity\Appointment[] paginate($object = null, array $settings = [])
 */
class AppointmentsController extends AppController
{

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        // The add and index actions are always allowed.
        if (in_array($action, ['index', 'add', 'edit','delete','view','editDocAppoint'])) {
            return true;
        }
        // All other actions require an id.
        if (!$this->request->getParam('pass.0')) {
            return false;
        }

        // Check that the bookmark belongs to the current user.
        // $id = $this->request->getParam('pass.0');
        // $bookmark = $this->Bookmarks->get($id);
        // if ($bookmark->user_id == $user['id']) {
        //     return true;
        // }
        return parent::isAuthorized($user);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $uid = $_SESSION['Auth']['User']['id'];
        $doctors = TableRegistry::get('Doctors');
        $doc_id = $doctors->find('all',['conditions' => ['Doctors.user_id' => $uid]]);
        $results = $doc_id->all();
        $data = $results->toArray();
        if(!empty($data)) {$doc_id = $data[0]->doc_id;} else{$doc_id = 0;}
        $this->paginate = [
            'conditions' => ["OR"=>['Appointments.user_id' => $uid,'Appointments.doc_id' => $doc_id]],
            'contain' => ['Users', 'Doctors']
        ];
        $appointments = $this->paginate($this->Appointments);

        $this->set(compact('appointments'));
        $this->set('_serialize', ['appointments']);
    }

    /**
     * View method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $appointment = $this->Appointments->get($id, [
            'contain' => ['Users', 'Doctors']
        ]);

        $this->set('appointment', $appointment);
        $this->set('_serialize', ['appointment']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $appointment = $this->Appointments->newEntity();
        if ($this->request->is('post')) {
            $appointment = $this->Appointments->patchEntity($appointment, $this->request->getData());
            if ($result = $this->Appointments->save($appointment)) {
                $this->__sendNewAppointmentEmail($result->app_id);
                $this->Flash->success(__('The appointment has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment could not be saved. Please, try again.'));
        }
        $apps = $this->Appointments->find('list', ['limit' => 200]);
        $users = $this->Appointments->Users->find('list', ['limit' => 200]);
        $doctors = $this->Appointments->Doctors->find('list', ['limit' => 200]);
        $this->set(compact('appointment', 'appointments', 'users', 'doctors'));
        $this->set('_serialize', ['appointment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $role_id = $_SESSION['Auth']['User']['role'];
        $appointment = $this->Appointments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $appointment = $this->Appointments->patchEntity($appointment, $this->request->getData());
            if ($this->Appointments->save($appointment)) {
                $this->__sendEditAppointmentEmailDoc($id);
                $this->Flash->success(__('The appointment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appointment could not be saved. Please, try again.'));
        }
        $apps = $this->Appointments->find('list', ['limit' => 200]);
        $users = $this->Appointments->Users->find('list', ['limit' => 200]);
        $doctors = $this->Appointments->Doctors->find('list', ['limit' => 200]);
        $this->set(compact('appointment', 'appointments', 'users', 'doctors'));
        $this->set('_serialize', ['appointment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Appointment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appointment = $this->Appointments->get($id);
        if ($this->Appointments->delete($appointment)) {
            $this->Flash->success(__('The appointment has been deleted.'));
        } else {
            $this->Flash->error(__('The appointment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


        public function editDocAppoint($id = null)
        {
            $appointment = $this->Appointments->get($id, [
                'contain' => []
            ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $appointment = $this->Appointments->patchEntity($appointment, $this->request->getData());
                if ($this->Appointments->save($appointment)) {
                    $this->__sendEditAppointmentEmail($id);
                    $this->Flash->success(__('The appointment has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The appointment could not be saved. Please, try again.'));
            }
            $apps = $this->Appointments->find('list', ['limit' => 200]);
            $users = $this->Appointments->Users->find('list', ['limit' => 200]);
            $doctors = $this->Appointments->Doctors->find('list', ['limit' => 200]);
            $this->set(compact('appointment', 'appointments', 'users', 'doctors'));
            $this->set('_serialize', ['appointment']);
        }

        /* Email to doctor*/

        function __sendNewAppointmentEmail($id = null) {
                if (!empty($id)) {
                    $this->Appointments->id = $id;
                    $appointment = $this->Appointments->get($id, [
                        'contain' => ['Users','Doctors']
                    ]);
                    
                    $docusers = TableRegistry::get('Users');
                    $doc_id = $docusers->find('all',['conditions' => ['Users.id' => $appointment->doctor->user_id]]);
                    $results = $doc_id->all();
                    $data = $results->toArray();
                    $doc_email = $data[0]->email;
                    $Email = new Email('default');
                    $Email->viewVars($appointment);
                    $statsu = $Email->template('new_appointment')
                                    ->emailFormat('both')
                                    ->to($doc_email)
                                    ->from(array('do-not-reply@srs-infosystems.com' => 'SRS'))
                                    ->subject('New Appointment - DO NOT REPLY')
                                    ->send();
                    return true;
                }
                return false;
            }

        /* Email to patient*/
        
        function __sendEditAppointmentEmail($id = null) {
                if (!empty($id)) {
                    $this->Appointments->id = $id;
                    $appointment = $this->Appointments->get($id, [
                        'contain' => ['Users','Doctors']
                    ]);
                    
                    $docusers = TableRegistry::get('Users');
                    $doc_id = $docusers->find('all',['conditions' => ['Users.id' => $appointment->doctor->user_id]]);
                    $results = $doc_id->all();
                    $data = $results->toArray();
                    $patient_email = $appointment->user->email;
                    $Email = new Email('default');
                    $Email->viewVars($appointment);
                    $statsu = $Email->template('edit_appointment')
                                    ->emailFormat('both')
                                    ->to($patient_email)
                                    ->from(array('do-not-reply@srs-infosystems.com' => 'SRS'))
                                    ->subject('Appointment Status change- DO NOT REPLY')
                                    ->send();
                    return true;
                }
                return false;
            }

            function __sendEditAppointmentEmailDoc($id = null) {
                if (!empty($id)) {
                    $this->Appointments->id = $id;
                    $appointment = $this->Appointments->get($id, [
                        'contain' => ['Users','Doctors']
                    ]);
                    $docusers = TableRegistry::get('Users');
                    $doc_id = $docusers->find('all',['conditions' => ['Users.id' => $appointment->doctor->user_id]]);
                    $results = $doc_id->all();
                    $data = $results->toArray();
                    $doc_email = $data[0]->email;
                    $Email = new Email('default');
                    $Email->viewVars($appointment);
                    $statsu = $Email->template('docedit_appointment')
                                    ->emailFormat('both')
                                    ->to($doc_email)
                                    ->from(array('do-not-reply@srs-infosystems.com' => 'SRS'))
                                    ->subject('Patient Edited Appointment - DO NOT REPLY')
                                    ->send();
                    return true;
                }
                return false;
            }
}

