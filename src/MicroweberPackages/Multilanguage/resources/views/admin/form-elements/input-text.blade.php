<div x-data="{
defaultLanguageInputField: '{{$fieldValue}}',
currentLanguageData: @js($currentLanguageData)
}"
x-init="function() {
mw.on('mlChangedLanguage', function (e, mlCurrentLanguage) {
    currentLanguageData = mlCurrentLanguage;
});
}"
>

    <input type="hidden" x-model="defaultLanguageInputField" name="{{$fieldName}}" />

    <div class="input-group">

        @foreach($supportedLanguages as $language)
            <input name="multilanguage[{{$fieldName}}][{{$language['locale']}}]"
                   value="{{$translations[$language['locale']]}}"
                   x-show="currentLanguageData.locale == '{{$language['locale']}}'"

                   @if($language['locale'] == $defaultLanguage)
                   x-model="defaultLanguageInputField"
                   @else
                       style="display:none"
                   @endif

                   type="text" class="form-control">
        @endforeach

        <button data-bs-toggle="dropdown" type="button" class="btn dropdown-toggle dropdown-toggle-split" aria-expanded="false">
            <i :class="function () {
                    return 'flag-icon flag-icon-'+currentLanguageData.icon+' mr-4';
            }"></i>
        </button>

        <div class="dropdown-menu dropdown-menu-end">
            @foreach($supportedLanguages as $language)
            <a class="dropdown-item" href="#" x-on:click="function() {
        currentLanguageData = @js($language);
        mw.trigger('mlChangedLanguage', currentLanguageData);
}">
                <i class="flag-icon flag-icon-{{$language['icon']}} mr-4"></i>
                <span> {{strtoupper($language['locale'])}}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
