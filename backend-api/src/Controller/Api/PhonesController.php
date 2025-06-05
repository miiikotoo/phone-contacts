<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Utility\ResponseHelper;
use Cake\Datasource\ConnectionManager;
use Exception;
use App\Controller\Component\ResponseHelperComponent; // Correct namespace for ResponseHelperComponent

/**
 * Phones Controller
 *
 * @property \App\Model\Table\PhonesTable $Phones
 */
class PhonesController extends AppController
{
    // index, viewはContactsに任せるのでpost, put, deleteだけ実装

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ResponseHelper'); // Correctly loads ResponseHelperComponent
    }

    /**
     * @param int $id
     * @return \Cake\Http\Response|null
     */
    public function create()
    {
        $this->request->allowMethod(['options', 'post']);

        // リクエストデータを確認して、必要な情報があるかチェック
        $data = $this->request->getData();
        if (empty($data['contact_id']) || empty($data['number'])) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Missing required fields'], 400);
        }

        $phone = $this->Phones->newEmptyEntity();
        $phone = $this->Phones->patchEntity($phone, $this->request->getData());
        if ($this->Phones->save($phone)) {
            return $this->ResponseHelper->jsonResponse($phone, 201);
        } else {
            return $this->ResponseHelper->jsonResponse($phone->getErrors(), 400);
        }
    }

    /**
     * @param int $id
     * @return \Cake\Http\Response|null
     */
    public function update($id)
    {
        $this->request->allowMethod(['put']);

        // リクエストデータを確認して、必要な情報があるかチェック
        $data = $this->request->getData();

        $this->log(print_r($data, true), 'debug');

        if (empty($data['number'])) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Missing required fields'], 400);
        }

        $phone = $this->Phones->get($id);
        if (!$phone) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Phone not found'], 404);
        }

        $phone = $this->Phones->patchEntity($phone, $data);
        if ($this->Phones->save($phone)) {
            return $this->ResponseHelper->jsonResponse($phone, 200);
        }

        return $this->ResponseHelper->jsonResponse($phone->getErrors(), 400);
    }

    /**
     * @param int $id
     * @return \Cake\Http\Response|null
     */
    public function delete($id)
    {
        $this->request->allowMethod(['delete']);

        $phone = $this->Phones->get($id);
        if (!$phone) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Phone not found'], 404);
        }

        if ($this->Phones->delete($phone)) {
            return $this->ResponseHelper->jsonResponse(['message' => 'Phone deleted successfully'], 200);
        }

        return $this->ResponseHelper->jsonResponse(['error' => 'Failed to delete phone'], 400);
    }

}