<?php

namespace MicroweberPackages\Editor\TemplateSettingsV2\Http\Controllers;

use Illuminate\Routing\Controller;
use MicroweberPackages\Option\Models\Option;

class EditorTemplateSettingsV2Controller extends Controller
{
    public function getSettings()
    {
        $getTemplateConfig = mw()->template->get_config();

        $optionGroup = 'mw-template-' . $getTemplateConfig['dir_name'] . '-settings';
        $optionGroupLess = 'mw-template-' . $getTemplateConfig['dir_name'];

        $settingGroups = [];

        if (isset($getTemplateConfig['stylesheet_compiler']['settings'])) {

            $mainGroup = 'Styles';
            $valuesGroup = 'Other';
            foreach ($getTemplateConfig['stylesheet_compiler']['settings'] as $key => $value) {

                if ($value['type'] == 'delimiter') {
                    continue;
                }
                if ($value['type'] == 'group') {
                    $mainGroup = $value['label'];
                    continue;
                }
                if ($value['type'] == 'title') {
                    $valuesGroup = $value['label'];
                    continue;
                }

                $value['optionGroup'] = $optionGroupLess;
                $value['value'] = get_option($key, $optionGroupLess);

                $settingGroups[$mainGroup]['type'] = 'stylesheet';
                $settingGroups[$mainGroup]['values'][$valuesGroup][$key] = $value;
            }
        }

        if (isset($getTemplateConfig['template_settings'])) {
            $valuesGroup = 'Other';
            foreach ($getTemplateConfig['template_settings'] as $key => $value) {
                if ($value['type'] == 'delimiter') {
                    continue;
                }
                if ($value['type'] == 'title') {
                    $valuesGroup = $value['label'];
                    continue;
                }

                $value['optionGroup'] = $optionGroup;
                $value['value'] = get_option($key, $optionGroup);

                $settingGroups['Template Settings']['type'] = 'template';
                $settingGroups['Template Settings']['values'][$valuesGroup][$key] = $value;
            }
        }

        return response()->json([
            'settingsGroups' => $settingGroups,
            'optionGroup'=> $optionGroup,
            'optionGroupLess'=> $optionGroupLess,
        ]);
    }
}
