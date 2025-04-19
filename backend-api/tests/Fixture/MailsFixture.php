<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MailsFixture
 */
class MailsFixture extends TestFixture
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
                'contact_id' => 1,
                'mail' => 'Lorem ipsum dolor sit amet',
                'modified' => '2025-04-19 02:05:37',
                'created' => '2025-04-19 02:05:37',
            ],
        ];
        parent::init();
    }
}
