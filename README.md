# phone-contacts

## 概要
* このプロジェクトでは「電話帳アプリケーション」を実装する

## 要求定義
* 電話帳として連絡先（電話番号・メールアドレス）の登録と登録した連絡先の確認ができるようにする

## 機能・要件定義
* 本電話帳アプリケーションでは以下の機能を実装する
  * 連絡先の登録
  * 連絡先の一覧表示
    * 一覧では検索・ソートができるようにする
  * 連絡先の詳細表示
  * 連絡先の変更（手動で登録できる項目全て）
  * 連絡先の削除

## 技術スタック
* このプロジェクトでは以下の技術を使用する
  * バックエンド：~~Django (Python)~~ CakePHP
  * フロントエンド：Next.js (React), JavaScript, HTML, SCSS (Sass), Flutter

### 登録項目
* 連絡先に登録する項目は以下のとおり
  * 氏名
  * フリガナ
  * 電話番号
  * メールアドレス
  * 生年月日（西暦）

* 氏名・フリガナは文字列であれば英数字など制限はしない
  * ある程度ユーザーに入力の自由度を持たせる

* 電話番号・メールアドレスは重複不可とする

* 1つの連絡先に対して電話番号・メールアドレスは複数登録できるようにする
  * 登録可能件数は無制限にする


## 画面設計

### 連絡先一覧
* 連絡先の一覧を表示
  * 表示するのは氏名, 電話番号, メールアドレスまで
  * 電話番号, メールアドレスが複数登録されている場合は最初の1件のみ

* 氏名での検索機能を実装
* 表示している項目でのソート機能を実装

* 連絡先登録と詳細画面への動線を配置

### 連絡先登録
* 各項目のinputを配置し登録する機能を実装

* 複数登録できる電話番号とメールアドレスの項目は追加削除できるようにする

* 連絡先一覧への動線を配置

### 連絡先詳細
* 一覧で選択した連絡先のすべての項目を表示

* 電話番号の選択で架電を行う ※スマホの場合
* メールアドレスの選択でメーラーの起動を行う

* 編集への動線を配置
* 削除ボタンの配置
  * 削除ボタンが選択されたら確認を挟んで削除を行う

### 連絡先編集
* 登録同様にinputの配置と更新機能を実装
  * 登録済みの項目は登録内容が入力された状態にする

* 連絡先詳細への動線を配置

* 他は登録と同じ

## DB設計
* 主となるユーザーのデータをcontactsテーブルとする
* 電話番号とメールアドレスは1対多の関係になるので別テーブルで保存する
* 電話番号とメールアドレスはユニークにする
* 電話番号は先頭の0を保持する必要があるのでvarcharにする
* 電話番号はハイフンなどを含めることを考慮して20文字にする

### contacts
| Column Name | Data Type | Constraints | Notes |
|-------------|------------|-------------|-------|
| id          | int        | NOT NULL, PRIMARY KEY | Auto-increment |
| name        | varchar(255) | NOT NULL    | |
| kana        | varchar(255) | NULL        | |
| birthday    | date         | NULL        | |
| modified    | datetime     | NOT NULL    | |
| created     | datetime     | NOT NULL    | |


### phones
| Column Name | Data Type | Constraints | Notes |
|-------------|------------|-------------|-------|
| id          | int        | NOT NULL, PRIMARY KEY | Auto-increment |
| contact_id  | int        | NOT NULL, FOREIGN KEY ON DELETE CASCADE | References contacts(id) |
| number      | varcahr(20)  | NOT NULL, UNIQUE | |
| modified    | datetime     | NOT NULL    | |
| created     | datetime     | NOT NULL    | |

### mails
| Column Name | Data Type | Constraints | Notes |
|-------------|------------|-------------|-------|
| id          | int        | NOT NULL, PRIMARY KEY | Auto-increment |
| contact_id  | int        | NOT NULL, FOREIGN KEY ON DELETE CASCADE | References contacts(id) |
| mail        | varchar(255) | NOT NULL, UNIQUE | |
| modified    | datetime     | NOT NULL    | |
| created     | datetime     | NOT NULL    | |

## api設計
### endpoint url
* 取得系：`/api/controller/`
* 作成系：`/api/controller/create`
* 更新系：`/api/controller/id/update`
* 削除系：`/api/controller/id/delete`

* 各apiのendpointはswaggerに定義する