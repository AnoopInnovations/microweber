<?php

if (isset($params['is_shop']) && $params['is_shop'] == 1){
    $createRoute = route('admin.shop.category.create')."?parent=shop";
} else {
    $createRoute = route('admin.category.create')."?parent=blog";
}
?>

<div>

<div class="col-xl-9 mx-auto mw-module-category-manager admin-side-content">
    <div class="card-body mb-3">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between mb-5">
            <div class="d-flex align-items-center">
                <h1 class="main-pages-title mb-0">
                    <?php _e("Categories"); ?>
                </h1>

                <h3 class="ms-1 mb-0">
                    <?php if (isset($params['is_shop']) && $params['is_shop'] == 1): ?>
                    \<?php _e("Shop"); ?>
                    <?php else : ?>
                    \<?php _e("Website"); ?>
                    <?php endif; ?>
                </h3>
            </div>


            <div class="input-icon col-xl-5 col-sm-5 col-12  text-lg-center text-start my-sm-0 mt-5 mb-3">
                <input type="text" value="" class="form-control" placeholder="Search" id="category-tree-search">
                <span class="input-icon-addon">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                </span>
            </div>

            <div class="js-hide-when-no-items">
                <div class="d-flex align-items-center">
                    <?php if (user_can_access('module.categories.edit')): ?>
                    <?php if (isset($params['is_shop']) && $params['is_shop'] == 1): ?>
                    <a href="<?php echo $createRoute; ?>" class="btn btn-dark">
                        <svg fill="currentColor" class="me-1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>

                        <?php _e("New Category"); ?></a>
                    <?php else: ?>
                    <a href="<?php echo $createRoute; ?>" class="btn btn-dark">
                        <svg fill="currentColor" class="me-1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>

                        <?php _e("New Category"); ?></a>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div wire:ignore>
            <div class=" mb-5" id="bulk-actions-block" style="display: none;" >
                <label for="" class="form-label"><?php _e("Select action from the field") ?></label>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php _e('Bulk Actions') ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item js-multiple-move-to-category" type="button">Move To Category</button></li>
                        <li><button class="dropdown-item js-multiple-hidden" type="button">Make Hidden</button></li>
                        <li><button class="dropdown-item js-multiple-visible" type="button">Make Visible</button></li>
                        <li><button class="dropdown-item js-multiple-delete" type="button">Delete</button></li>
                    </ul>
                </div>
            </div>

            <div>
                <div id="mw-admin-categories-tree-manager"></div>
                <div id="mw-admin-categories-tree-manager-no-results-message" style="display: none;">
                    <div class="empty">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="12" cy="12" r="9" />
                                <line x1="9" y1="10" x2="9.01" y2="10" />
                                <line x1="15" y1="10" x2="15.01" y2="10" />
                                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" />
                            </svg>
                        </div>
                        <p class="empty-title"> <?php _e("No results found"); ?></a></p>
                        <p class="empty-subtitle text-muted">
                            <?php _e("Try adjusting your search or filter to find what you're looking for"); ?></a>.
                        </p>

                    </div>

                </div>


                <script>

                    selectedPages = [];
                    selectedCategories = [];

                    $('.js-multiple-move-to-category').click(function() {
                        window.livewire.emit('openModal', 'admin-category-bulk-move-modal', {ids: selectedCategories});
                    });

                    $('.js-multiple-visible').click(function() {
                        mw.tools.confirm('<?php echo _ejs('Are you sure you want to make visible the selected categories?'); ?>', function() {
                            $.ajax({
                                url: route('api.category.visible-bulk'),
                                type: 'POST',
                                data: {ids: selectedCategories},
                                success: function (data) {
                                    mw.reload_module('categories/manage');
                                    mw.notification.success('<?php _ejs("Categories are visible."); ?>.');
                                    mw.parent().trigger('pagesTreeRefresh');
                                }
                            });
                        });
                    });

                    $('.js-multiple-hidden').click(function() {
                        mw.tools.confirm('<?php echo _ejs('Are you sure you want to make hidden the selected categories?'); ?>', function() {
                            $.ajax({
                                url: route('api.category.hidden-bulk'),
                                type: 'POST',
                                data: {ids: selectedCategories},
                                success: function (data) {
                                    mw.reload_module('categories/manage');
                                    mw.notification.success('<?php _ejs("Categories are hidden."); ?>.');
                                    mw.parent().trigger('pagesTreeRefresh');
                                }
                            });
                        });

                    });

                    $('.js-multiple-delete').click(function() {
                        mw.tools.confirm('<?php echo _ejs('Are you sure you want to delete the selected categories?'); ?>', function() {
                            $.ajax({
                                url: route('api.category.delete-bulk'),
                                type: 'DELETE',
                                data: {ids: selectedCategories},
                                success: function (data) {
                                    mw.reload_module('categories/manage');
                                    mw.notification.success('<?php _ejs("Categories are deleted."); ?>.');
                                    mw.parent().trigger('pagesTreeRefresh');
                                }
                            });
                        });
                    });

                    <?php if(isset($params['show_add_post_to_category_button'])): ?>
                    // this is for the post manage categories
                    treeDataOpts = {

                        sortable: '>.type-category',
                        sortableHandle: '.mw-tree-item-content',
                        selectable: false,
                        singleSelect: true,
                        saveState: false,
                        searchInput: document.getElementById('category-tree-search'),
                        skin: 'category-manager',
                        contextMenu: [

                            {
                                title: mw.lang('Select'),
                                icon: 'mdi mdi-check',
                                action: function (element, data, menuitem) {
                                    mw.top().trigger("mwSelectToAddCategoryToContent", data.id);
                                },
                                filter: function (obj, node) {
                                    return obj.type === 'category';
                                },

                                className: 'btn btn-outline-success btn-sm  '
                            }


                        ]
                    };
                    <?php else: ?>

                    // this is for the main  manage categories page

                    treeDataOpts = {
                        cantSelectTypes: ['page'],
                        sortable: '>.type-category',
                        sortableHandle: '.mw-tree-item-sortable-handle',
                        createSortableHandle: function (list){
                            setTimeout(() => {
                                mw.$('.mw-tree-item-content', list).each(function (){
                                    $(this)
                                        .not('.mw-tree-item-sortable-handle-ready')
                                        .addClass('mw-tree-item-sortable-handle-ready')
                                        .prepend(`

                                        <div class="cursor-move-holder me-2 mw-tree-item-sortable-handle" onmousedown="mw.manage_content_sort()" style="max-width: 80px;">
                                            <span href="javascript:;" class="btn btn-link text-blue-lt">
                                                <svg class="mdi-cursor-move" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path></svg>
                                            </span>
                                        </div>
                                    `)
                                })
                            });

                        },

                        selectable: true,
                        rowSelect : false,
                        singleSelect: false,
                        multiPageSelect: false,
                        allowPageSelect: false,
                        saveState: false,
                        searchInput: document.getElementById('category-tree-search'),
                        skin: 'category-manager',
                        contextMenu: [

                            {
                                title: '<svg class="me-1 ms-0 tblr-body-color" fill="currentColor" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"></path></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"></path></g></g></g></svg>',

                                icon: 'd-none',
                                action: function (element, data, menuitem) {
                                    self.location.href = data.admin_edit_url;
                                },
                                filter: function (obj, node) {
                                    return obj.type === 'category';
                                },
                                className: ''
                            },
                            {
                                title: '<svg class=" me-1 ms-0 text-danger" fill="currentColor" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>',
                                icon: 'd-none',
                                action: function (element, data, menuitem) {
                                    if (data.type === 'category') {
                                        mw.content.deleteCategory(data.id, function () {
                                            $(element).fadeOut(function () {
                                                $(element).remove()
                                            })
                                        }, false);
                                    }
                                },
                                filter: function (obj, node) {
                                    return obj.type === 'category';
                                },
                                className: ''
                            }


                        ]
                    };
                    <?php endif; ?>

                    function renderCategoryTree() {

                        categoryTree = mw.admin.tree(document.getElementById('mw-admin-categories-tree-manager'), {
                            options: treeDataOpts,
                            params: {
                                only_categories: 1,
                                no_limit: true,
                                <?php if(isset($params['is_shop']) && $params['is_shop'] == 1): ?>
                                is_shop: 1,
                                <?php else: ?>
                                is_blog: 1,
                                <?php endif; ?>
                            }

                        }, 'tree').then(function (res) {
                            res.tree.openAll();

                            res.tree.on('searchNoResults', function(){
                                document.getElementById('mw-admin-categories-tree-manager-no-results-message').style.display = '';
                            });
                            res.tree.on('searchResults', function(){
                                document.getElementById('mw-admin-categories-tree-manager-no-results-message').style.display = 'none';
                            });


                            $(res.tree).on('orderChange', function (e, obj) {
                                var items = res.tree.getSameLevelObjects(obj).filter(function (obj) {
                                    return obj.type === 'category';
                                }).map(function (obj) {
                                    return obj.id;
                                });
                                $.post("<?php print api_link('category/reorder'); ?>", {ids: items}, function () {
                                    mw.notification.success('<?php _ejs("All changes are saved"); ?>.');
                                    mw.parent().trigger('pagesTreeRefresh');
                                });
                            });
                            $(res.tree).on("selectionChange", function () {

                                var bulk = document.getElementById('bulk-actions-block');

                                if(res.tree.getSelected().length === 0) {
                                    $('.js-hide-when-no-items-selected').hide()
                                    $(bulk).hide()
                                } else {
                                    $('.js-hide-when-no-items-selected').show();
                                    $(bulk).show();
                                }

                                if (res.tree.getSelected().length == 1) {
                                    $('.js-count-selected-categories').html(res.tree.getSelected().length + ' <?php _ejs('category'); ?>');
                                }
                                if (res.tree.getSelected().length > 1) {
                                    $('.js-count-selected-categories').html(res.tree.getSelected().length + ' <?php _ejs('categories'); ?>');
                                }

                                selectedCategories = [];
                                $.each(res.tree.getSelected(), function (key, item) {
                                    if (item.type == 'category') {
                                        selectedCategories.push(item.id);
                                    }
                                    if (item.type == 'page') {
                                        selectedPages.push(item.id);
                                    }
                                });

                              //  window.livewire.emit('setSelectedIds',selectedCategories);

                            });
                        });
                    }

                    renderCategoryTree();

                </script>
            </div>
        </div>


    </div>
</div>
</div>
