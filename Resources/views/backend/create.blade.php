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
                    <i class="{{ $module_icon }}"></i> {{ $module_title }} <small class="text-muted">{{ $module_action }}</small>
                </h4>
                <div class="small text-muted">
                    {{ ucwords($module_name) }} Management Dashboard
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <hr>

        <div class="row mt-4">
            <div class="col">
                {{ html()->form('POST', route("backend.$module_name.store"))->class('form')->open() }}
                <div class="row">
                    <div class="col-12">
                        <div class="float-right">
                            <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Back</a>
                            <button type="submit" name="action" class="btn btn-warning" value="draft"> Save As Draft</button>
                            <button type="submit" name="action" class="btn btn-success" value="published"> Save As Published</button>
                        </div>
                    </div>
                </div>

                @include ("pages::backend.form")
                <div class="col-12">
                    <div class="float-right">
                        <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Back</a>
                        <button type="submit" name="action" class="btn btn-warning" value="draft"> Save As Draft</button>
                        <button type="submit" name="action" class="btn btn-success" value="published"> Save As Published</button>
                    </div>
                </div>

                {{ html()->form()->close() }}

            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">

            </div>
        </div>
    </div>
</div>

@stop
