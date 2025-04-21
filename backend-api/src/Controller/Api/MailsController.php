<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Utility\ResponseHelper;
use Cake\Datasource\ConnectionManager;
use Exception;
use App\Controller\Component\ResponseHelperComponent; // Correct namespace for ResponseHelperComponent

/**
 * Mails Controller
 *
 * @property \App\Model\Table\MailsTable $Mails
 */
class MailsController extends AppController
{
    // index, viewはContactsに任せるのでpost, put, deleteだけ実装

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ResponseHelper'); // Correctly loads ResponseHelperComponent
    }

    /**
     * @param int $id
     * @return \Cake\Http\Response|null
     */
    public function create()
    {
        $this->request->allowMethod(['post']);

        // リクエストデータを確認して、必要な情報があるかチェック
        $data = $this->request->getData();
        if (empty($data['contact_id']) || empty($data['mail'])) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Missing required fields'], 400);
        }

        $mail = $this->Mails->newEmptyEntity();
        $mail = $this->Mails->patchEntity($mail, $this->request->getData());
        if ($this->Mails->save($mail)) {
            return $this->ResponseHelper->jsonResponse($mail, 201);
        } else {
            return $this->ResponseHelper->jsonResponse($mail->getErrors(), 400);
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
        if (empty($data['mail'])) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Missing required fields'], 400);
        }

        $mail = $this->Mails->find()->where(['id' => $id])->first(); // getの場合存在しないと500エラーになるのでfindで取得（エラーハンドリングが目的）
        // 存在チェック
        if (!$mail) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Mail not found'], 404);
        }

        $mail = $this->Mails->patchEntity($mail, $this->request->getData());
        if ($this->Mails->save($mail)) {
            return $this->ResponseHelper->jsonResponse($mail, 200);
        } else {
            return $this->ResponseHelper->jsonResponse($mail->getErrors(), 400);
        }
    }

    /**
     * @param int $id
     * @return \Cake\Http\Response|null
     */
    public function delete($id)
    {
        $this->request->allowMethod(['delete']);

        $mail = $this->Mails->find()->where(['id' => $id])->first(); // getの場合存在しないと500エラーになるのでfindで取得（エラーハンドリングが目的）
        // 存在チェック
        if (!$mail) {
            return $this->ResponseHelper->jsonResponse(['error' => 'Mail not found'], 404);
        }

        if ($this->Mails->delete($mail)) {
            return $this->ResponseHelper->jsonResponse(['message' => 'Mail deleted successfully'], 200);
        } else {
            return $this->ResponseHelper->jsonResponse(['error' => 'Failed to delete mail'], 400);
        }
    }

}