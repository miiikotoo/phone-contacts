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
        $this->request->allowMethod(['options', 'get']);

        // limitとoffsetの取得
        $limit = (int)($this->request->getQuery('limit') ?? 10);
        $offset = (int)($this->request->getQuery('offset') ?? 0);
        // sort,orderの取得
        $sort = $this->request->getQuery('sort') ?? 'created';
        $order = $this->request->getQuery('order') ?? 'desc';

        // todo. 検索条件を適用してデータを取得
        // name, kana, email, numberの検索条件の取得
        // $name = $this->request->getQuery('name') ?? null;
        // $kana = $this->request->getQuery('kana') ?? null;
        // $email = $this->request->getQuery('email') ?? null;
        // $number = $this->request->getQuery('number') ?? null;
        // 検索条件の配列を作成
        // $conditions = [];
        // if ($name) {
        //     $conditions['Contacts.name LIKE'] = '%' . $name . '%';
        // }
        // if ($kana) {
        //     $conditions['Contacts.kana LIKE'] = '%' . $kana . '%';
        // }
        // if ($email) {
        //     $conditions['Mails.email LIKE'] = '%' . $email . '%';
        // }
        // if ($number) {
        //     $conditions['Phones.number LIKE'] = '%' . $number . '%';
        // }

        $contacts = $this->Contacts->find('all')
            ->contain(['Phones', 'Mails'])
            ->order([$sort => $order])
            ->limit($limit)
            ->offset($offset)
            ;

        $contactsCount = $contacts->count();

        // レスポンスの生成
        $response = $this->response;
        $response = $response->withType('application/json');
        $response = $response->withStringBody(json_encode(
            [
                'contacts' =>$contacts,
                'count' => $contactsCount,
            ]
        ));

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
        $this->request->allowMethod(['options', 'post']);
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
                if (isset($data['mail'])) {
                    $mail = $this->Contacts->Mails->newEntity([
                        'mail' => $data['mail'],
                        'contact_id' => $contact->id,
                    ]);
                    if (!$this->Contacts->Mails->save($mail)) {
                        throw new Exception('Failed to save mail');
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

    /**
     * Edit method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function update($id = null)
    {
        $this->request->allowMethod(['options', 'put']);

        // responseの設定
        $response = $this->response;
        $response = $response->withType('application/json');

        // IDが指定されていない場合はエラー
        if (empty($id)) {
            return $this->response->withStatus(400)->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Contact ID is required'
            ]));
        }

        // 連絡先を取得
        $contact = $this->Contacts->find('all')
            ->where(['id' => $id])
            ->contain(['Phones', 'Mails'])
            ->first();

        // 連絡先が存在しない場合はエラー
        if (!$contact) {
            return $this->response->withStatus(404)->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Contact not found'
            ]));
        }

        $data = $this->request->getData();

        // データの整形
        if (isset($data['phones'])) {
            $phonesData = [];
            $existingPhoneIds = [];

            // 既存のphone IDを収集
            foreach ($contact->phones as $phone) {
                $existingPhoneIds[] = $phone->id;
            }

            // 送信されたデータの処理
            foreach ($data['phones'] as $phoneData) {
                if (isset($phoneData['id'])) {
                    // 既存のphoneを更新
                    $phonesData[] = [
                        'id' => $phoneData['id'],
                        'number' => $phoneData['number'],
                        'contact_id' => $contact->id
                    ];
                    // 処理済みのIDを配列から削除
                    $key = array_search($phoneData['id'], $existingPhoneIds);
                    if ($key !== false) {
                        unset($existingPhoneIds[$key]);
                    }
                } else {
                    // 新しいphoneを追加
                    $phonesData[] = [
                        'number' => $phoneData['number'],
                        'contact_id' => $contact->id
                    ];
                }
            }

            // 更新リクエストに含まれていない既存のphoneを削除
            if (!empty($existingPhoneIds)) {
                $this->Contacts->Phones->deleteAll(['id IN' => $existingPhoneIds]);
            }

            // 連絡先の更新（phones）
            $contact = $this->Contacts->patchEntity($contact, [
                'phones' => $phonesData
            ], ['associated' => ['Phones']]);
        }

        // メールアドレスの更新処理
        if (isset($data['mails'])) {
            $mailsData = [];
            $existingMailIds = [];

            // 既存のmail IDを収集
            foreach ($contact->mails as $mail) {
                $existingMailIds[] = $mail->id;
            }

            // 送信されたデータの処理
            foreach ($data['mails'] as $mailData) {
                if (isset($mailData['id'])) {
                    // 既存のmailを更新
                    $mailsData[] = [
                        'id' => $mailData['id'],
                        'mail' => $mailData['mail'],
                        'contact_id' => $contact->id
                    ];
                    // 処理済みのIDを配列から削除
                    $key = array_search($mailData['id'], $existingMailIds);
                    if ($key !== false) {
                        unset($existingMailIds[$key]);
                    }
                } else {
                    // 新しいmailを追加
                    $mailsData[] = [
                        'mail' => $mailData['mail'],
                        'contact_id' => $contact->id
                    ];
                }
            }

            // 更新リクエストに含まれていない既存のmailを削除
            if (!empty($existingMailIds)) {
                $this->Contacts->Mails->deleteAll(['id IN' => $existingMailIds]);
            }

            // 連絡先の更新（mails）
            $contact = $this->Contacts->patchEntity($contact, [
                'mails' => $mailsData
            ], ['associated' => ['Mails']]);
        }

        // データベース接続の取得
        $connection = ConnectionManager::get('default');

        // トランザクションの開始
        $connection->begin();

        try {
            // 連絡先を更新
            if ($this->Contacts->save($contact, ['associated' => ['Phones', 'Mails']])) {
                // トランザクションのコミット
                $connection->commit();
                // レスポンスの生成
                $response = $response->withStringBody(json_encode([
                    'status' => 'success',
                    'message' => 'Contact updated successfully',
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
                'message' => 'Failed to update contact',
                'errors' => $contact->getErrors()
            ]));
        } finally {
            // レスポンスの返却
            return $response;
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['options', 'delete']);

        // responseの設定
        $response = $this->response;
        $response = $response->withType('application/json');

        $contact = $this->Contacts->find('all')
            ->where(['id' => $id])
            ->contain(['Phones', 'Mails'])
            ->first();

        // 連絡先が存在しない場合はエラー
        if (!$contact) {
            return $response->withStatus(404)
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Contact not found'
                ]));
        }

        // データベース接続の取得
        $connection = ConnectionManager::get('default');

        try {
            // トランザクションの開始
            $connection->begin();

            // 連絡先を削除
            if ($this->Contacts->delete($contact)) {
                // トランザクションのコミット
                $connection->commit();
                // レスポンスの生成
                return $response->withStatus(200)
                    ->withStringBody(json_encode([
                        'status' => 'success',
                        'message' => 'Contact deleted successfully'
                    ]));
            } else {
                throw new Exception('Failed to delete contact');
            }
        } catch (Exception $e) {
            // トランザクションのロールバック
            $connection->rollback();
            $this->log('Failed to delete contact: ' . $contact->id . ', Error: ' . $e->getMessage(), 'error');

            // エラーレスポンスの生成
            return $response->withStatus(400)
                ->withStringBody(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete contact'
                ]));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['options', 'get']);

        // responseの設定
        $response = $this->response;
        $response = $response->withType('application/json');

        // IDが指定されていない場合はエラー
        if (empty($id)) {
            return $this->response->withStatus(400)->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Contact ID is required'
            ]));
        }

        // 連絡先を取得
        $contact = $this->Contacts->find('all')
            ->where(['id' => $id])
            ->contain(['Phones', 'Mails'])
            ->first();
        // 連絡先が存在しない場合はエラー
        if (!$contact) {
            return $this->response->withStatus(404)->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Contact not found'
            ]));
        }

        // レスポンスの生成
        $response = $response->withStringBody(json_encode($contact));

        // レスポンスを返す
        return $response;
    }

}