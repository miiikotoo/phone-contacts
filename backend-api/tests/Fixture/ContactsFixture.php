<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContactsFixture
 */
class ContactsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'kana' => 'Lorem ipsum dolor sit amet',
                'birthday' => '2025-04-19',
                'modified' => '2025-04-19 01:45:23',
                'created' => '2025-04-19 01:45:23',
            ],
        ];
        parent::init();
    }
}
