<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentChannel\BulkDestroyPaymentChannel;
use App\Http\Requests\Admin\PaymentChannel\DestroyPaymentChannel;
use App\Http\Requests\Admin\PaymentChannel\IndexPaymentChannel;
use App\Http\Requests\Admin\PaymentChannel\StorePaymentChannel;
use App\Http\Requests\Admin\PaymentChannel\UpdatePaymentChannel;
use App\Models\PaymentChannel;
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

class PaymentChannelsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexPaymentChannel $request
     * @return array|Factory|View
     */
    public function index(IndexPaymentChannel $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PaymentChannel::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name'],

            // set columns to searchIn
            ['id', 'name', 'description']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.payment-channel.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.payment-channel.create');

        return view('admin.payment-channel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePaymentChannel $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePaymentChannel $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PaymentChannel
        $paymentChannel = PaymentChannel::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/payment-channels'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/payment-channels');
    }

    /**
     * Display the specified resource.
     *
     * @param PaymentChannel $paymentChannel
     * @throws AuthorizationException
     * @return void
     */
    public function show(PaymentChannel $paymentChannel)
    {
        $this->authorize('admin.payment-channel.show', $paymentChannel);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PaymentChannel $paymentChannel
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PaymentChannel $paymentChannel)
    {
        $this->authorize('admin.payment-channel.edit', $paymentChannel);


        return view('admin.payment-channel.edit', [
            'paymentChannel' => $paymentChannel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePaymentChannel $request
     * @param PaymentChannel $paymentChannel
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePaymentChannel $request, PaymentChannel $paymentChannel)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PaymentChannel
        $paymentChannel->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/payment-channels'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/payment-channels');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPaymentChannel $request
     * @param PaymentChannel $paymentChannel
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPaymentChannel $request, PaymentChannel $paymentChannel)
    {
        $paymentChannel->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPaymentChannel $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPaymentChannel $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    DB::table('paymentChannels')->whereIn('id', $bulkChunk)
                        ->update([
                            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
