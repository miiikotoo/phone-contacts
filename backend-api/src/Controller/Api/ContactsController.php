<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Utility\ResponseHelper;
use Cake\Datasource\ConnectionManager;
use Exception;

/**
 * Contacts Controller
 *
 * @property \App\Model\Table\ContactsTable $Contacts
 */
class ContactsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $contacts = $this->Contacts->find('all', [
            'order' => ['Contacts.created' => 'DESC'],
            'contain' => ['Phones', 'Mails'],
        ]);
        
        // レスポンスの生成
        $response = $this->response;
        $response = $response->withType('application/json');
        $response = $response->withStringBody(json_encode($contacts));

        // レスポンスを返す
        return $response;
    }
}