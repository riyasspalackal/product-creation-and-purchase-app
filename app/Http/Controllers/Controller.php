<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function paginate($request, $query)
    {
        $page = 1;
        if ($request->has('page')) {
            $page = $request->input('page');
        }
        $perPage = 10;
        if ($request->has('per_page')) {
            if ($request->input('per_page') != null) {
                $perPage = $request->input('per_page');
            }
        }

        $offSet = ($page - 1) * $perPage;
        $total = $query->get()->count();
        $totalPages = ceil($total / $perPage);


        $data = $query->offset($offSet)->limit($perPage);


        $data = $data->get();

        return response()->json(['status' => true, 'total' => $total, 'total_pages' => $totalPages, 'current_page' => $page, 'data' => $data, 'message' => 'Success'], Response::HTTP_OK);
    }
}
