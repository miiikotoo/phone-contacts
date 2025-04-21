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
        $this->request->allowMethod(['get']);

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

    /**
     * Edit method
     *
     * @param string|null $id Contact id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function update($id = null)
    {
        $this->request->allowMethod(['put']);

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
        $contact = $this->Contacts->find('all')->where(['id' => $id])->first();
        // 連絡先が存在しない場合はエラー
        if (!$contact) {
            return $this->response->withStatus(404)->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Contact not found'
            ]));
        }

        // todo. postmanでのテストではputの$dataがnullになってしまっていたので要検証
        $data = $this->request->getData();

        // データベース接続の取得
        $connection = ConnectionManager::get('default');

        // トランザクションの開始
        $connection->begin();

        try {
            // 連絡先を更新
            $contact = $this->Contacts->patchEntity($contact, $data);

            if ($this->Contacts->save($contact)) {
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
        $this->request->allowMethod(['delete']);

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
        $contact = $this->Contacts->find('all')->where(['id' => $id])->first();
        // 連絡先が存在しない場合はエラー
        if (!$contact) {
            return $this->response->withStatus(404)->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Contact not found'
            ]));
        }

        // データベース接続の取得
        $connection = ConnectionManager::get('default');

        // トランザクションの開始
        $connection->begin();

        try {
            // 連絡先を削除
            if ($this->Contacts->delete($contact)) {
                // トランザクションのコミット
                $connection->commit();
                // レスポンスの生成
                $response = $response->withStringBody(json_encode([
                    'status' => 'success',
                    'message' => 'Contact deleted successfully'
                ]));
            } else {
                $this->log('Failed to delete contact: ' . $contact->id, 'error');
                throw new Exception('Failed to delete contact');
            }
        } catch (Exception $e) {
            // トランザクションのロールバック
            $connection->rollback();

            // エラーレスポンスの生成
            $response = $response->withStatus(400);
            $response = $response->withStringBody(json_encode([
                'status' => 'error',
                'message' => 'Failed to delete contact',
                'errors' => $contact->getErrors()
            ]));
        } finally {
            // レスポンスの返却
            return $response;
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
        $this->request->allowMethod(['get']);

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