<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddressType\BulkDestroyAddressType;
use App\Http\Requests\Admin\AddressType\DestroyAddressType;
use App\Http\Requests\Admin\AddressType\IndexAddressType;
use App\Http\Requests\Admin\AddressType\StoreAddressType;
use App\Http\Requests\Admin\AddressType\UpdateAddressType;
use App\Models\AddressType;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressTypesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexAddressType $request
     * @return array|Factory|View
     */
    public function index(IndexAddressType $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(AddressType::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name'],

            // set columns to searchIn
            ['id', 'name']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.address-type.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.address-type.create');

        return view('admin.address-type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAddressType $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAddressType $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the AddressType
        $addressType = AddressType::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/address-types'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/address-types');
    }

    /**
     * Display the specified resource.
     *
     * @param AddressType $addressType
     * @throws AuthorizationException
     * @return void
     */
    public function show(AddressType $addressType)
    {
        $this->authorize('admin.address-type.show', $addressType);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AddressType $addressType
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(AddressType $addressType)
    {
        $this->authorize('admin.address-type.edit', $addressType);


        return view('admin.address-type.edit', [
            'addressType' => $addressType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAddressType $request
     * @param AddressType $addressType
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAddressType $request, AddressType $addressType)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values AddressType
        $addressType->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/address-types'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/address-types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAddressType $request
     * @param AddressType $addressType
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAddressType $request, AddressType $addressType)
    {
        $addressType->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyAddressType $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyAddressType $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    AddressType::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
