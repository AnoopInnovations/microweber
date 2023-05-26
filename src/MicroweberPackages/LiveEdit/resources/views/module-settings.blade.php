@extends('admin::layouts.iframe')



@section('content')

    <div id="settings-container">


        <?php



        $moduleTypeForComponent = str_replace('/', '-', $moduleType);
        $moduleTypeForLegacyModule = module_name_decode($moduleType);
       // $moduleTypeForLegacyModule = $moduleTypeForLegacyModule.'/admin';

        $moduleTypeForComponent = str_replace('_', '-', $moduleTypeForComponent);
        $hasError = false;
        $output = false;

//        try {
//            $output = \Livewire\Livewire::mount('microweber-live-edit::' . $moduleTypeForComponent, [
//                //'id' => $moduleId,
//                'moduleId' => $moduleId,
//                'moduleType' => $moduleType,
//            ])->html();
//
//        } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        } catch (InvalidArgumentException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        } catch (\Exception $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        }
//
//        if ($hasError) {
//            print '<div class="alert alert-danger" role="alert">';
//            print $output;
//            print '</div>';
//        } else {
//            print $output;
//        }


        ?>

        @if(livewire_component_exists('microweber-module-'.$moduleTypeForComponent.'::live-edit'))
                @livewire('microweber-module-'.$moduleTypeForComponent.'::live-edit', [
                    'moduleId' => $moduleId,
                    'moduleType' => $moduleType,
                ])
        @else


            @if(is_module($moduleTypeForLegacyModule))


<script>
    // saving module settings for legacy modules
    var settingsAction = function () {
        var settings_container_mod_el = $('#settings-container');
        mw.options.form(settings_container_mod_el, function () {
            if (mw.notification) {
                mw.notification.success('<?php _ejs('Settings are saved') ?>');
            }
             <?php if (isset($params['id'])) : ?>

                if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {
                    mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '<?php print $params['id']  ?>'} || {}))
                }

            <?php endif; ?>

        });

        createAutoHeight()
    };
    $(document).ready(function () {
        settingsAction();
    });

</script>


            <div>
                @if(isset($params['live_edit'])):
                <module type="{{ $moduleTypeForLegacyModule}}" id="{{ $moduleId }}" live_edit="true"/>
                @else
                <module type="{{ $moduleTypeForLegacyModule}}" id="{{ $moduleId }}"/>
                @endif
            </div>
            @endif


        @endif
    </div>







    <script>

        Livewire.on('settingsChanged', $data => {
            if (typeof mw !== 'undefined' && mw.top().app && mw.top().app.editor) {
                mw.top().app.editor.dispatch('onModuleSettingsChanged', ($data || {}))
            }
        })
    </script>



    <script>

        var createAutoHeight = function () {
            if (window.thismodal && thismodal.iframe) {
                mw.tools.iframeAutoHeight(thismodal.iframe, 'now');
            }
            else if (mw.top().win.frameElement && mw.top().win.frameElement.contentWindow === window) {
                mw.tools.iframeAutoHeight(mw.top().win.frameElement, 'now');
            } else if (window.top !== window) {
                mw.top().$('iframe').each(function () {
                    try {
                        if (this.contentWindow === window) {
                            mw.tools.iframeAutoHeight(this, 'now');
                        }
                    } catch (e) {
                    }
                })
            }
        };


        if (self !== top) {
            $(window).on('load', function () {

                mw.interval('_settingsAutoHeight', function () {
                    if (document.querySelector('.mw-iframe-auto-height-detector') === null) {
                        createAutoHeight();
                    }
                });

            });



        }
    </script>

@endsection
