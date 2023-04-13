import {HandleMenu} from "../handle-menu.js";
import {ElementManager} from "../classes/element.js";
import { Confirm } from "../classes/dialog.js";

export const ElementHandleContent = function (proto) {
    this.root = ElementManager({
        props: {
            id: 'mw-handle-item-element-root'
        }
    });

    const cloneAbleMenu = [
        {
            title: 'Duplicate' ,
            text: '',
            icon: '<svg fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z"></path></svg>',
            className: 'mw-handle-clone-button',
            onTarget: function (target, selfNode) {
                selfNode.style.display = target.classList.contains('cloneable') ? '' : 'none';
            },
            action: function (el) {

                ElementManager(el).after(el.outerHTML)
            }
        },
        {
            title: 'Move backward' ,
            text: '',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" ><path d="M20 13.5C20 17.09 17.09 20 13.5 20H6V18H13.5C16 18 18 16 18 13.5S16 9 13.5 9H7.83L10.91 12.09L9.5 13.5L4 8L9.5 2.5L10.92 3.91L7.83 7H13.5C17.09 7 20 9.91 20 13.5Z" /></svg>',
            className: 'mw-handle-move-back-button',
            onTarget: function (target, selfNode) {
                const isCloneable = target.classList.contains('cloneable');
                const prev = target.previousElementSibling;
                selfNode.style.display = isCloneable && prev ? '' : 'none';
            },
            action: function (el) {
                const prev = el.previousElementSibling;
                if(prev) {
                    prev.before(el);
                    proto.elementHandle.set(el)
                }
            }
        },
        {
            title: 'Move forward' ,
            text: '',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M10.5 18H18V20H10.5C6.91 20 4 17.09 4 13.5S6.91 7 10.5 7H16.17L13.08 3.91L14.5 2.5L20 8L14.5 13.5L13.09 12.09L16.17 9H10.5C8 9 6 11 6 13.5S8 18 10.5 18Z" /></svg>',
            className: 'mw-handle-move-back-button',
            onTarget: function (target, selfNode) {
                const isCloneable = target.classList.contains('cloneable');
                const next = target.nextElementSibling;
                selfNode.style.display = isCloneable && next  ? '' : 'none';
            },
            action: function (el) {
                const next = el.nextElementSibling;
 
                if(next) {
                    next.after(el);
                    proto.elementHandle.set(el)
                }
                
            }
        },
    ];
    
    this.menu = new HandleMenu({
        id: 'mw-handle-item-element-menu',
        title: 'Element',
        buttons: [
            {
                title: 'Drag to rearange' ,
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="currentColor" width="24" height="24" ><path d="M7,19V17H9V19H7M11,19V17H13V19H11M15,19V17H17V19H15M7,15V13H9V15H7M11,15V13H13V15H11M15,15V13H17V15H15M7,11V9H9V11H7M11,11V9H13V11H11M15,11V9H17V11H15M7,7V5H9V7H7M11,7V5H13V7H11M15,7V5H17V7H15Z" /></svg>',
                className: 'mw-handle-drag-button',
                onTarget: function (target, selfNode) {
                    // console.log(target)
                },
                action: function (el) {

                    proto.dialog({

                    })
                }
            },
            {
                title: 'Settings' ,
                text: '',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="24" height="24" viewBox="0 0 24 24"><path d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z" /></svg>',
                className: 'mw-handle-insert-button',
                onTarget: function (target, selfNode) {
                    // console.log(target)
                },
                action: function (el) {

                    proto.dialog({

                    })
                }
            },
            ...cloneAbleMenu,
            {
                title: proto.lang('Delete'),
                text: '',
                icon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6M9,8V17H11V8H9M13,8V17H15V8H13Z" /></svg>',
                className: 'mw-handle-insert-button',
                action: function (el) {
                    
                    Confirm(ElementManager('<span>Are you sure</span>'), () => {
                        
                        el.remove()
                        proto.elementHandle.hide()
                    })
                }
            }
        ],
    });

    this.menu.show()

    this.root.append(this.menu.root)


}

