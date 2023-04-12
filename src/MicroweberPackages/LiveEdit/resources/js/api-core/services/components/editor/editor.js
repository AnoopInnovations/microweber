

mw.require('editor.js');
mw.require('css_parser.js');
export const EditorComponent = function () {
    var holder = document.querySelector('#mw-live-edit-editor');

    var _fontFamilyProvider = function () {
        var _e = {};
        this.on = function (e, f) {
            _e[e] ? _e[e].push(f) : (_e[e] = [f])
        };
        this.dispatch = function (e, f) {
            _e[e] ? _e[e].forEach(function (c) {
                c.call(this, f);
            }) : '';
        };

        this.provide = function (fontsArray) {
            this.dispatch('change', fontsArray.map(function (font) {
                return {
                    label: font,
                    value: font,
                }
            }))
        }

    };



    var fontFamilyProvider = new _fontFamilyProvider();
    window.fontFamilyProvider = fontFamilyProvider;
    const frame = mw.app.get('canvas').getFrame();
    frame.contentWindow.fontFamilyProvider = fontFamilyProvider;


    const liveEditor = mw.Editor({
        document: frame.contentWindow.document,
        executionDocument: frame.contentWindow.document,
        actionWindow: frame.contentWindow,
        element: holder,
        mode: 'document',
        notEditableClasses: ['module'],
        regions: '.edit',
        skin: 'le2',
        editMode: 'liveedit',
        scopeColor: 'white',
        controls: [
            [

                {
                    group: {
                        icon: 'mdi mdi-format-title',
                        controls: ['format', 'lineHeight']
                    }
                },

                {
                    group: {
                        controller: 'bold',
                        controls: ['italic', 'underline', 'strikeThrough', 'removeFormat']
                    }
                },
                'fontSelector',

                'fontSize',


                {
                    group: {
                        controller: 'alignLeft',
                        controls: ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify']
                    }
                },

                {
                    group: {
                        controller: 'ul',
                        controls: ['ol']
                    }
                },


                'image',
                {
                    group: {
                        controller: 'link',
                        controls: ['unlink']
                    }
                },
                {
                    group: {
                        controller: 'textColor',
                        controls: ['textBackgroundColor']
                    }
                },


            ]
        ],
        smallEditorPositionX: 'center',
        smallEditorSkin: 'lite',

        interactionControls: [],

        id: 'live-edit-wysiwyg-editor',

        minHeight: 250,
        maxHeight: '70vh',
        state: mw.liveEditState,

        fontFamilyProvider: fontFamilyProvider
    });



    var btnUndo = document.getElementById('toolbar-undo')
    var btnRedo = document.getElementById('toolbar-redo')

    liveEditor.state.on('record', function () {

        btnRedo.disabled = !liveEditor.state.hasPrev;
        btnUndo.disabled = !liveEditor.state.hasNext;
    })
    liveEditor.state.on('change', function () {

        btnRedo.disabled = !liveEditor.state.hasPrev;
        btnUndo.disabled = !liveEditor.state.hasNext;
    })

    if (btnUndo) {
        btnUndo.addEventListener('click', function () {
            liveEditor.state.undo()
        });
    }
    if (btnRedo) {
        btnRedo.addEventListener('click', function () {
            liveEditor.state.redo()
        });
    }

    /*                liveEditor.on('action', function (){
                        mw.wysiwyg.change(liveEditor.api.elementNode(liveEditor.api.getSelection().focusNode))
                    })
                    liveEditor.on('smallEditorReady', function (){
                        fontFamilyProvider.provide(mw.top().wysiwyg.fontFamiliesExtended);
                    })
                    $(liveEditor).on('selectionchange', function (){
                        var sel = liveEditor.getSelection();
                        if(sel.rangeCount) {
                            liveEditor.lastRange =  sel.getRangeAt(0) ;
                        } else {
                            liveEditor.lastRange = undefined;
                        }

                    })*/

    holder.innerHTML = '';
    holder.appendChild(liveEditor.wrapper);


    var memPin = liveEditor.storage.get(liveEditor.settings.id + '-small-editor-pinned');
    if (typeof memPin === 'undefined' && typeof liveEditor.smallEditorApi !== 'undefined') {
        liveEditor.smallEditorApi.pin()
    }
    mw.app.register('richTextEditor', liveEditor);

    mw.app.register('richTextEditorAPI', liveEditor.api);
};


