<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Utility\ResponseHelper; // Correct namespace for ResponseHelper

class ResponseHelperComponent extends Component
{
    protected ResponseHelper $helper;

    public function initialize(array $config): void
    {
        $this->helper = new ResponseHelper();
    }

    /**
     * Proxy method to call jsonResponse from ResponseHelper.
     *
     * @param mixed $data The data to include in the response body.
     * @param int $status The HTTP status code.
     * @return \Cake\Http\Response
     */
    public function jsonResponse($data, int $status = 200)
    {
        return $this->helper->jsonResponse($data, $status);
    }
}
