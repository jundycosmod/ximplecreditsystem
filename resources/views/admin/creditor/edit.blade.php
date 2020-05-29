@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.creditor.actions.edit', ['name' => $creditor->first_name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <creditor-form
                :action="'{{ $creditor->resource_url }}'"
                :data="{{ $creditor->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.creditor.actions.edit', ['name' => $creditor->first_name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.creditor.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </creditor-form>

        </div>
    
</div>

@endsection