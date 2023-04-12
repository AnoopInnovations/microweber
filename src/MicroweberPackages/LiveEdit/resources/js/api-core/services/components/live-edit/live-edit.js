

import { EditorHandles } from '../../../../ui/adapters/module-handle.js';
import {LiveEdit} from '../../../core/@live.js';



import liveeditCssDist from '../../../core/css/scss/liveedit.scss';
import {ModuleSettings} from "../../services/module-settings";
import {TemplateSettings} from "../../services/template-settings";


export const liveEditComponent = () => {
    const frame = mw.app.get('canvas').getFrame();
    const frameHolder = frame.parentElement;
    const doc = mw.app.get('canvas').getDocument();
    const link = doc.createElement('style');
    link.textContent = liveeditCssDist;

    doc.head.prepend(link);

    const liveEdit = new LiveEdit({
        root: doc.body,
        strict: false,
        mode: 'auto',
        document: doc
    });



    liveEdit.on('insertLayoutRequest', function(){
        mw.app.editor.dispatch('insertLayoutRequest', mw.app.get('liveEdit').handles.get('layout').getTarget());
    });


    mw.app.call('onLiveEditReady');

    mw.app.register('liveEdit', liveEdit);
    mw.app.register('state', mw.liveEditState);

    mw.app.register('editor', EditorHandles );

    mw.app.register('moduleSettings', ModuleSettings);

    mw.app.register('templateSettings', TemplateSettings);// don't remove this

}