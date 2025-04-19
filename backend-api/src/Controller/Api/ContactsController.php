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

    /**
     * Create method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function create()
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();

        // responseの設定
        $response = $this->response;
        $response = $response->withType('application/json');

        // データベース接続の取得
        $connection = ConnectionManager::get('default');

        // トランザクションの開始
        $connection->begin();

        try {
            // 新しい連絡先を作成
            $contact = $this->Contacts->newEntity($data);

            if ($this->Contacts->save($contact)) {

                // todo.phoneとmailの処理をContactsのaccociateでまとめられるはず？
                // numberが存在する場合patchentityでrelationのphonesを追加
                $phone = null;
                if (isset($data['number'])) {
                    $phone = $this->Contacts->Phones->newEntity([
                        'number' => $data['number'],
                        'contact_id' => $contact->id,
                    ]);
                    if (!$this->Contacts->Phones->save($phone)) {
                        throw new Exception('Failed to save phone');
                    }
                }
                // emailが存在する場合patchentityでrelationのmailsを追加
                $mail = null;
                if (isset($data['email'])) {
                    $mail = $this->Contacts->Mails->newEntity([
                        'email' => $data['email'],
                        'contact_id' => $contact->id,
                    ]);
                    if (!$this->Contacts->Mails->save($mail)) {
                        throw new Exception('Failed to save email');
                    }
                }
                $contact->set('phones', [$phone]);
                $contact->set('mails', [$mail]);

                // トランザクションのコミット
                $connection->commit();
                // レスポンスの生成
                $response = $response->withStringBody(json_encode([
                    'status' => 'success',
                    'message' => 'Contact created successfully',
                    'contact' => $contact,
                ]));
            } else {
                throw new Exception('Failed to save contact');
            }
        } catch (Exception $e) {
            // トランザクションのロールバック
            $connection->rollback();

            // エラーレスポンスの生成
            $response = $response->withStatus(400);
            $response = $response->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Failed to create contact',
                'errors' => $contact->getErrors()
            ]));
        } finally {
            // レスポンスの返却
            return $response;
        }
    }

}