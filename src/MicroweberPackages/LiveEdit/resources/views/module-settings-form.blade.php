<div>

    @if(isset($this->moduleTitle) and $this->moduleTitle)
        <h1 class="font-weight-bold mb-4"><?php print $this->moduleTitle; ?></h1>
    @endif



    @php

        /**
         * @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
         */
        $formBuilder = app()->make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);

    @endphp


    @if($this->settingsForm)

        @foreach($this->settingsForm as $formItemKey => $formItem)

            <div class="form-group">

                @if(isset($formItem['label']) and $formItem['label'])
                    <label class="form-label"><?php print $formItem['label']; ?></label>
                @endif


                @php

                    $type = $formItem['type'] ?? 'text';
                    $placeholder = $formItem['placeholder'] ?? '';
                    $label = $formItem['label'] ?? false;
                    $help = $formItem['help'] ?? '';
                    $required = $formItem['required'] ?? false;
                    $autocomplete = $formItem['autocomplete'] ?? false;
                    $options = $formItem['options'] ?? false;
                    $select = $formItem['select'] ?? false;
                    $settingsKey = 'settings.' . $formItemKey;


                    $element = $formBuilder->make($type, $settingsKey);
                    $element->setAttribute('wire:model.debounce.100ms', $settingsKey);

                    if ($label and method_exists($element, 'label')) {
                        $element->label($label);
                    }
                    if ($placeholder and method_exists($element, 'placeholder')) {
                        $element->placeholder($placeholder);
                    }
                    if ($autocomplete and method_exists($element, 'autocomplete')) {
                        $element->autocomplete($autocomplete);
                    }
                    if ($required and method_exists($element, 'required')) {
                        $element->required($required);
                    }
                    if ($options and method_exists($element, 'options')) {
                        $element->options($options);
                    }
                    if ($select and method_exists($element, 'select')) {
                        $element->select($select);
                    }

                    print $element->render();

                @endphp



                @if(isset($formItem['help']) and $formItem['help'])
                    <small class="form-hint">{{ $formItem['help'] }}</small>
                @endif

            </div>

        @endforeach

    @endif

</div>
