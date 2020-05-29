<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Creditor\BulkDestroyCreditor;
use App\Http\Requests\Admin\Creditor\DestroyCreditor;
use App\Http\Requests\Admin\Creditor\IndexCreditor;
use App\Http\Requests\Admin\Creditor\StoreCreditor;
use App\Http\Requests\Admin\Creditor\UpdateCreditor;
use App\Models\Creditor;
use Brackets\AdminListing\Facades\AdminListing;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CreditorsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexCreditor $request
     * @return array|Factory|View
     */
    public function index(IndexCreditor $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Creditor::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'first_name', 'middle_name', 'last_name', 'is_active'],

            // set columns to searchIn
            ['id', 'first_name', 'middle_name', 'last_name']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.creditor.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.creditor.create');

        return view('admin.creditor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCreditor $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCreditor $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Creditor
        $creditor = Creditor::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/creditors'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/creditors');
    }

    /**
     * Display the specified resource.
     *
     * @param Creditor $creditor
     * @throws AuthorizationException
     * @return void
     */
    public function show(Creditor $creditor)
    {
        $this->authorize('admin.creditor.show', $creditor);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Creditor $creditor
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Creditor $creditor)
    {
        $this->authorize('admin.creditor.edit', $creditor);


        return view('admin.creditor.edit', [
            'creditor' => $creditor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCreditor $request
     * @param Creditor $creditor
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCreditor $request, Creditor $creditor)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Creditor
        $creditor->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/creditors'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/creditors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCreditor $request
     * @param Creditor $creditor
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyCreditor $request, Creditor $creditor)
    {
        $creditor->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCreditor $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyCreditor $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    DB::table('creditors')->whereIn('id', $bulkChunk)
                        ->update([
                            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
