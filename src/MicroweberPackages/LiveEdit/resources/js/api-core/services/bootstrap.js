import './index.js'

import {MWUniversalContainer} from "./containers/container.js";


import {EditorComponent} from "./components/editor/editor.js";
import {LiveEditCanvas} from "./components/live-edit-canvas/live-edit-canvas.js";
import {liveEditComponent} from "./components/live-edit/live-edit.js";
import {Commands} from "./services/commands.js";
import {Modules} from "./services/modules.js";
import {Layouts} from "./services/layouts.js";
import {KeyboardEvents} from  "./services/keyboard-events.js";
import {ModuleSettings} from "./services/module-settings";
import {IconPicker} from "./services/icon-picker";


mw.app = new MWUniversalContainer();


//setTimeout(function() {

    mw.app.register('commands', Commands);
    mw.app.register('modules', Modules);

    mw.app.register('layouts', Layouts);
    mw.app.register('keyboard', KeyboardEvents);
    mw.app.register('iconPicker', IconPicker);

//mw.app.register('commands', Commands);


//}, 300);