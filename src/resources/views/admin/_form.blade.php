@section('js')
    <script src="{{ asset('js/admin/form.js') }}"></script>
@stop

<div class="row">
    <div class="form-group col-sm-12">
        <button class="btn-primary btn" type="submit">@lang('validation.attributes.save')</button>
    </div>
</div>

<label>@lang('validation.attributes.website_title')</label>
@foreach ($locales as $lang)
    <div class="row">
        <div class="col-sm-9 form-group">
            <div class="input-group">
                <span class="input-group-addon">{{ strtoupper($lang) }}</span>
                <input class="form-control" type="text" name="{{ $lang }}[website_title]" value="@if(isset($data->$lang)){{ $data->$lang->website_title }}@endif">
            </div>
        </div>
        <div class="col-sm-3 checkbox">
            <label>
                <input type="checkbox" name="{{ $lang }}[status]" value="1" @if(isset($data->$lang) and $data->$lang->status)checked @endif> @lang('validation.attributes.online')
            </label>
        </div>
    </div>
@endforeach

{!! BootForm::email(trans('validation.attributes.webmaster_email'), 'webmaster_email') !!}
{!! BootForm::textarea(trans('validation.attributes.welcome_message'), 'welcome_message') !!}
{!! BootForm::select(trans('validation.attributes.admin_locale'), 'admin_locale', array_combine($locales, $locales)) !!}
{!! BootForm::text(trans('validation.attributes.typekit_code'), 'typekit_code') !!}
{!! BootForm::text(trans('validation.attributes.google_analytics_code'), 'google_analytics_code') !!}
{!! BootForm::checkbox(trans('validation.attributes.lang_chooser'), 'lang_chooser') !!}
{!! BootForm::checkbox(trans('validation.attributes.auth_public'), 'auth_public') !!}
{!! BootForm::checkbox(trans('validation.attributes.registration allowed'), 'register') !!}
