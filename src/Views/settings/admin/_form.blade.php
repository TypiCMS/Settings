@section('js')
    <script src="{{ asset('js/admin/form.js') }}"></script>
@stop

<div class="row">
    <div class="form-group col-sm-12">
        <button class="btn-primary btn" type="submit">@lang('validation.attributes.save')</button>
    </div>
</div>

<label>@lang('validation.attributes.websiteTitle')</label>
@foreach ($locales as $lang)
    <div class="row">
        <div class="col-sm-9 form-group">
            <div class="input-group">
                <span class="input-group-addon">{{ strtoupper($lang) }}</span>
                <input type="text" name="{{ $lang }}[websiteTitle]" class="form-control">
            </div>
        </div>
        <div class="col-sm-3 checkbox">
            <label>
                <input type="checkbox" name="{{ $lang }}[status]" value="1"> @lang('validation.attributes.online')
            </label>
        </div>
    </div>
@endforeach

{!! BootForm::text(trans('validation.attributes.webmasterEmail'), 'webmasterEmail') !!}
{!! BootForm::text(trans('validation.attributes.welcomeMessageURL'), 'welcomeMessageURL') !!}
{!! BootForm::textarea(trans('validation.attributes.welcomeMessage'), 'welcomeMessage') !!}
{!! BootForm::select(trans('validation.attributes.welcomeMessage'), 'adminLocale', array_combine($locales, $locales)) !!}
{!! BootForm::text(trans('validation.attributes.typekitCode'), 'typekitCode') !!}
{!! BootForm::text(trans('validation.attributes.googleAnalyticsCode'), 'googleAnalyticsCode') !!}
{!! BootForm::checkbox(trans('validation.attributes.langChooser'), 'langChooser') !!}
{!! BootForm::checkbox(trans('validation.attributes.authPublic'), 'authPublic') !!}
{!! BootForm::checkbox(trans('validation.attributes.registration allowed'), 'register') !!}
