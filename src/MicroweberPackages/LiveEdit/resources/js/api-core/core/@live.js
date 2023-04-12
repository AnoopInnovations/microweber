import {Handle} from "./handle.js";
import {GetPointerTargets} from "./pointer.js";
import {ModeAuto} from "./mode-auto.js";
import {Handles} from "./handles.js";
import {ObjectService} from "./classes/object.service.js";
import {DroppableElementAnalyzerService} from "./analizer.js";
import {DropIndicator} from "./interact.js";
import {ElementHandleContent} from "./handles-content/element.js";
import {ModuleHandleContent} from "./handles-content/module.js";
import {LayoutHandleContent} from "./handles-content/layout.js";
import {ElementManager} from "./classes/element.js";
import {lang} from "./i18n.js";
import {Dialog} from "./classes/dialog.js";
import {Resizable} from "./classes/resizable.js";
import {HandleMenu} from "./handle-menu.js";

import {Tooltip} from "./tooltip.js";
import { InteractionHandleContent } from "./handles-content/interaction.js";
import { DomService } from "./classes/dom.js";


export class LiveEdit {


    constructor(options) {

        const scope = this;

        const _e = {};
        this.on = (e, f) => { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = (e, f) => { _e[e] ? _e[e].forEach( (c) => { c.call(this, f); }) : ''; };

        this.paused = false;

        var defaults = {
            elementClass: 'element',
            backgroundImageHolder: 'background-image-holder',
            cloneableClass: 'cloneable',
            editClass: 'edit',
            stateManager: null,
            moduleClass: 'module',
/*            rowClass: 'mw-row',
            colClass: 'mw-col',
            safeElementClass: 'safe-element',
            plainElementClass: 'plain-text',
            emptyElementClass: 'empty-element',*/
            nodrop: 'nodrop',
            allowDrop: 'allow-drop',
            unEditableModules: [
                '[type="template_settings"]'
            ],
            frameworksClasses: {
                col: ['col', 'mw-col']
            },
            document: document,
            mode: 'manual', // 'auto' | 'manual'
            lang: 'en',
            strict: true, // element and modules should be dropped only in layouts
            strictLayouts: false, // layouts can only exist as edit-field children
            viewWindow: window,
  
        };

        this.settings = ObjectService.extend({}, defaults, options);
        this.document = this.settings.document;

        this.stateManager = this.settings.stateManager;

        this.lang = function (key) {
            return lang(key, this.settings.lang);
        }

        if(!this.settings.root) {
            this.settings.root = this.settings.document.body
        }

        this.root = this.settings.root;

        this.elementAnalyzer = new DroppableElementAnalyzerService(this.settings);

        this.dropIndicator = new DropIndicator(this.settings);

        const elementHandleContent = new ElementHandleContent(this);
        const moduleHandleContent = new ModuleHandleContent(this);
        const layoutHandleContent = new LayoutHandleContent(this);

        this.elementHandleContent = elementHandleContent;
        this.moduleHandleContent = moduleHandleContent;
        this.layoutHandleContent = layoutHandleContent;

        this.layoutHandleContent.on('insertLayoutRequest', () => {
            this.dispatch('insertLayoutRequest')
        })

        this.dialog = function (options) {
            if(!options){
                options = {};
            }

            var defaults = {
                // document: scope.document,
                document: window.top.document,
                position: moduleHandleContent.menu.getTarget(),
                mode: 'absolute'
            };

            scope.pause();
            const _dlg = new Dialog(ObjectService.extend({}, defaults, options));

            _dlg.on('close', function () {
                scope.play();
            });

            return _dlg;
        };



        var elementHandle = this.elementHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: elementHandleContent.root,
            handle: elementHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            resizable: true
        });
        this.isResizing = false;

        elementHandle.resizer.on('resizeStart', e => this.isResizing = true)
        elementHandle.resizer.on('resizeStop', e => this.isResizing = false)

        elementHandle.on('targetChange', function (target){
            elementHandleContent.menu.setTarget(target);


            if(target.className.includes('col-')) {
                elementHandle.resizer.disable()
            } else {
                elementHandle.resizer.enable()
            }

        });

        this.moduleHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: moduleHandleContent.root,
            handle: moduleHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            resizable: true
        });
        var moduleHandle = this.moduleHandle;

        this.getModuleQuickSettings = type => {
            return new Promise(resolve => {
                resolve(mw.quickSettings[type]);
               this.dispatch('moduleQuickSettings', {module: type});
            });
        };


        moduleHandle.on('targetChange', function (node){


            scope.getModuleQuickSettings(node.dataset.type).then(function (settings) {

                moduleHandleContent.menu.root.remove();
                

                

                moduleHandleContent.menu = new HandleMenu({
                    id: 'mw-handle-item-element-menu',
                    title: node.dataset.type,
                    rootScope: scope,
                    buttons: settings ? settings.mainMenu || [] : [],
                    data: {target: node}
                });
                moduleHandleContent.menu.setTarget(node);


                moduleHandleContent.menu.show();

                moduleHandleContent.root.append(moduleHandleContent.menu.root);


            });

        });

        this.layoutHandle = new Handle({
            ...this.settings,
            dropIndicator: this.dropIndicator,
            content: layoutHandleContent.root,
            handle: layoutHandleContent.menu.title,
            document: this.settings.document,
            stateManager: this.settings.stateManager,
            type: 'layout'
        });

        var layoutHandle = this.layoutHandle;

        var title = scope.lang('Layout');
        layoutHandleContent.menu.setTitle(title)
        layoutHandle.on('targetChange', function (target){
            
            layoutHandleContent.menu.setTarget(target);
            layoutHandleContent.menu.setTitle(title);
            if( scope.elementAnalyzer.isEditOrInEdit(target)) {
                layoutHandleContent.plusTop.show()
                layoutHandleContent.plusBottom.show()
            } else {
                layoutHandleContent.plusTop.hide()
                layoutHandleContent.plusBottom.hide()
            }
        });

        layoutHandleContent.handle = layoutHandle;
        moduleHandleContent.handle = moduleHandle;
        elementHandleContent.handle = elementHandle;

        const interactionHandleContent = new InteractionHandleContent(this);

 

        this.interactionHandle = new Handle({
            ...this.settings,
   
            content: interactionHandleContent.root,
         
            document: this.settings.document,
            
            resizable: false,
            className: 'mw-handle-item-interaction-handle'
        });
        this.interactionHandle.menu = interactionHandleContent.menu;
         

        this.handles = new Handles({
            element: elementHandle,
            module: moduleHandle,
            layout: layoutHandle,
            interactionHandle: this.interactionHandle,
        });
        this.observe = new GetPointerTargets(this.settings);
        this.init();
    }

    play() {
        this.paused = false;
    }

    pause() {
        this.handles.hide();
        this.paused = true;
    }

    init() {
        if(this.settings.mode === 'auto') {
            setInterval(() =>  ModeAuto(this), 1000)
            
        }

        const _eventsHandle = (e) => {
             
            if(this.handles.targetIsOrInsideHandle(e)) {
                return
            }
            const elements = this.observe.fromEvent(e);
            let first = elements[0];

            if(first.nodeName !== 'IMG') {
                first = DomService.firstBlockLevel(elements[0]);
            }

             
            
              
            this.handles.get('element').set(null)
            this.handles.hide();
             
           
            if(first) {
               const type = this.elementAnalyzer.getType(first);
               if(type && type !== 'edit') {
                   this.handles.set(type, elements[0])
                   if(type === 'element') {
                       this.handles.hide('module');
                   } else if(type === 'module') {
                    this.handles.hide('element');
                    }  else if(type === 'layout') {
                        this.handles.set('layout', layout);
                    } else {
                        this.handles.hide();
                   }
               }
 
            } else {
                const layout =  DomService.firstParentOrCurrentWithAnyOfClasses(e.target, ['module-layouts']);
                if(layout) {
                    this.handles.set('layout', layout)
                } 
            }
 
        }

 

        let events;
  
            events = 'mousedown touchstart';
            ElementManager(this.root).on('mousemove', (e) => {
                if(this.paused ||  this.isResizing) {
                    this.interactionHandle.hide();
                    return
                }
                if(this.handles.targetIsOrInsideHandle(e)) {
                    this.interactionHandle.hide();
                    return
                }
                const elements = this.observe.fromEvent(e);
                
                const target =  DomService.firstParentOrCurrentWithAnyOfClasses(elements[0], ['element', 'module', 'cloneable']);
                const layout =  DomService.firstParentOrCurrentWithAnyOfClasses(e.target, ['module-layouts']);
                let layoutHasSelectedTarget = false;

                

                if(layout) {
                 
                    const elementTarget = this.handles.get('element').getTarget();
                    const moduleTarget = this.handles.get('module').getTarget();
                     
                    if(layout.contains(elementTarget)) {
                        layoutHasSelectedTarget = true;
                    }

                    if(layout.contains(moduleTarget)) {
                        layoutHasSelectedTarget = true;
                    }
                  
                    if(!layoutHasSelectedTarget) {
                        this.handles.set('layout', layout);
                    } else {
                        this.handles.hide('layout');
                    }
                    
                }

                
                if(target && !this.handles.targetIsSelected(target, this.interactionHandle) && !target.classList.contains('module-layouts')) {
                    var title = '';
                    if(target.dataset.mwTitle) {
                        title = target.dataset.mwTitle;
                    } else if(target.dataset.type) {
                        title = target.dataset.type;
                    }  else if(target.nodeName === 'P') {
                        title = this.lang('Paragraph');
                    } else if(/(H[1-6])/.test(target.nodeName)) {
                        title = this.lang('Title') + ' ' + target.nodeName.replace( /^\D+/g, '');
                    } else if(target.nodeName === 'IMG' || target.nodeName === 'IMAGE') {
                        title = this.lang('Image');
                    }  else if(['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(target.nodeName)) {
                        title = this.lang('Title ' + target.nodeName.replace('H', ''));
                    }  else if(['DIV', 'MAIN', 'SECTION'].includes(target.nodeName)) {
                        title = this.lang('Block');
                    }   else {
                        title = this.lang('Text');
                    }
            
                    this.interactionHandle.menu.setTitle(title);
                    this.interactionHandle.show();
                    this.interactionHandle.set(target);
                } else {
                    this.interactionHandle.hide();
                }
                 
            })
            ElementManager(this.root).on(events, (e) => {
                if ( !this.paused  ) {
                    _eventsHandle(e)
                }
            });
         
 
         
    };
}

globalThis.LiveEdit = LiveEdit;
