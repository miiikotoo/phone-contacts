<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PhonesFixture
 */
class PhonesFixture extends TestFixture
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
                'number' => 'Lorem ipsum dolor ',
                'modified' => '2025-04-19 02:16:06',
                'created' => '2025-04-19 02:16:06',
            ],
        ];
        parent::init();
    }
}
