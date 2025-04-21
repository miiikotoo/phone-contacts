<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Http\Response;

class ResponseHelper
{
    /**
     * Generates a JSON response.
     *
     * @param mixed $data The data to include in the response body.
     * @param int $status The HTTP status code.
     * @return \Cake\Http\Response
     */
    public function jsonResponse($data, int $status = 200): Response
    {
        $response = new Response();
        return $response->withType('application/json')
                        ->withStatus($status)
                        ->withStringBody(json_encode($data));
    }
}
