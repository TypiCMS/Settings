@extends('core::admin.master')

@section('title', __('Settings'))

@section('content')

<h1>@lang('Settings')</h1>

<div class="row">

    <div class="col-sm-6">

    {!! BootForm::open()->multipart() !!}
    {!! BootForm::bind($data) !!}

        @include('settings::admin._form')

    {!! BootForm::close() !!}

    </div>

    <div class="col-sm-6">

        @if (config('typicms.cache'))
        <div>
            <a href="{{ route('admin::clear-cache') }}" class="btn btn-default"><span class="fa fa-trash-o"></span> {{ __('settings::global.Clear cache') }}</a>
        </div>
        @endif

        <table class="table table-condensed">
            <thead>
                <tr><th colspan="2">@lang('settings::global.System info')</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-6">@lang('settings::global.Environment')</td>
                    <td class="col-sm-6"><b>{{ App::environment() }}</b></td>
                </tr>
                <tr>
                    <td>@lang('settings::global.System locales')</td>
                    <td>
                        <div class="container-system-locales">
                            <b><?php try { system('locale -a'); } catch (Exception $e) { echo $e->getMessage(); } ?></b>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>@lang('settings::global.Active locale')</td>
                    <td><b>{{ config('app.locale') }}</b></td>
                </tr>
                <tr>
                    <td>@lang('settings::global.Cache')</td>
                    <td><b><?php echo config('typicms.cache') ? __('settings::global.Yes') : __('settings::global.No') ; ?></b></td>
                </tr>
            </tbody>
        </table>

    </div>

</div>

@endsection
