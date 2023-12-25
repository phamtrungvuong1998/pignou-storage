<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Models\File;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $search = request()->input('search', '');
        $trash = filter_var(request()->input('trash', 'false'), FILTER_VALIDATE_BOOLEAN);
        if (empty($trash)) {
            $data = Auth::user()->files()->where('name', 'like', "%{$search}%")->get();
        } else {
            $data = Auth::user()->trashedFiles()->where('name', 'like', "%{$search}%")->get();
        }

        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFileRequest  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($file): JsonResponse
    {
        $forceDelete = filter_var(request()->input('forceDelete', 'true'), FILTER_VALIDATE_BOOLEAN);

        try {
            $file = File::withTrashed()->findOrFail($file);
            $this->authorize('delete', $file);
            if (empty($forceDelete)) {
                $file->delete();
            } else {
                $file->forceDelete();
            }

            return response()->json([
                'message' => 'succees',
            ]);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Không tồn tại file hoặc folder',
            ], 500);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Bạn không có quyền',
            ], 403);
        } catch (QueryException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
