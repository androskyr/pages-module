@extends('backend.layouts.app')

@section('title')
{{ $module_action }} {{ $module_title }} | {{ app_name() }}
@stop

@section('breadcrumbs')
@backendBreadcrumbs
    @slot('level_1')
        <li class="breadcrumb-item"><a href='{!!route("backend.$module_name.index")!!}'><i class="{{ $module_icon }}"></i> {{ $module_title }}</a></li>
    @endslot
    @slot('level_2')
        <li class="breadcrumb-item active"> {{ $module_action }}</li>
    @endslot
@endbackendBreadcrumbs
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <h4 class="card-title mb-0">
                    <i class="{{ $module_icon }}"></i>  {{ $module_title }} <small class="text-muted">{{ $module_action }} - {{$type_of_page}}</small>
                </h4>
                <div class="small text-muted">
                    {{ ucwords($module_name) }} Management Dashboard
                </div>
            </div>
        </div>
        <!--/.row-->

        <hr>

        <div class="row mt-4">
            <div class="col">
                {{ html()->modelForm($$module_name_singular, 'PATCH', route("backend.$module_name.update", $$module_name_singular))->class('form')->open() }}
                <div class="row">
                    <div class="col-12">
                        <div class="float-right">
                            <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Back</a>
                            <button type="submit" name="action" class="btn btn-warning" value="draft"> Save As Draft</button>
                            <button type="submit" name="action" class="btn btn-success" value="published"> Save As Published</button>
                            <a href="{!!route("backend.$module_name.preview", $$module_name_singular)!!}" class="btn btn-secondary" data-toggle="tooltip" target="_blank" title="Preview Page"><i class="fas fa-view"></i> Preview</a>
                        </div>
                    </div>
                </div>
                @include ("pages::backend.form")

                <div class="row">
                    <div class="col-12">
                        <div class="float-right">
                            <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Back</a>
                            <button type="submit" name="action" class="btn btn-warning" value="draft"> Save As Draft</button>
                            <button type="submit" name="action" class="btn btn-success" value="published"> Save As Published</button>
                        </div>
                    </div>
                </div>

                {{ html()->form()->close() }}

            </div>
        </div>
    </div>
    

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">
                    Updated: {{$$module_name_singular->updated_at->diffForHumans()}},
                    Created at: {{$$module_name_singular->created_at->toCookieString()}}
                </small>
            </div>
        </div>
    </div>
</div>

@stop
