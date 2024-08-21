<?php
/**
 * There are no changes required in this file.
 * This endpoint represents the API you must consume.
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class ProductDataController extends Controller
{
    /**
     * @return array
     */
    public function index(): array
    {
        $data = file_get_contents(storage_path('app/public'). '/mock_data.json');

        return json_decode($data, true);
    }
}
