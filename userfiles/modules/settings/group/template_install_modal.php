<?php must_have_access(); ?>

<script>
    function mw_change_template() {

        var selectedTemplate = $('.mw-site-theme-selector').find("[name='current_template']").first().val();
        var importType = $('input[name="import_type"]:checked').val();

        $('.js-button-change-template').attr('disabled','disabled');
        $('.js-button-change-template').html('Loading..');

        setTimeout(function () {
            $('.js-button-change-template').html('This can take some time..');
        }, 5000);

        setTimeout(function () {
            $('.js-button-change-template').html('Importing template files..');
        }, 10000);

        $.ajax({
            url: mw.settings.site_url + 'api/template/change?template=' + selectedTemplate + "&import_type=" + importType,
            type: "GET",
            success: function (json) {

                $('.js-button-change-template').html('Change Template');
                $('.js-button-change-template').removeAttr('disabled');

                if (json.data['done']) {
                    mw.notification.success('Template has been changed.');
                    changeTemplateDialog.remove();
                }
                console.log(json.data['done']);
            }
        });
    }
</script>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup/css/style.css?v=' .time(); ?>" type="text/css"/>

<div class="mw-backup-restore">

    <div class="mw-backup-restore-options mt-4">

        <h2 style="font-weight: bold"><?php _e("How to apply this template?") ?></h2>
        <br/>

        <div class="card bg-light mb-4">
            <div class="card-body">
                <label class="form-check py-2" id="js-template-import-type-default">
                    <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="default" checked="checked" />

                    <label class="form-label"><?php _e("Use template with current content") ?></label>
                    <span class="fs-5"><?php _e("Change only website template without any content changes") ?></span>
                </label>
            </div>
        </div>

        <div class="card bg-light mb-4">
           <div class="card-body">
               <label class="form-check py-2 active" id="js-template-import-type-full">
                   <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="full" />

                   <span class="form-label"><?php _e("Import default content, media and css files") ?></span>
                   <span class="fs-5"><?php _e("Import the default content, media and css files from template") ?></span>
               </label>
           </div>
        </div>


        <div class="card bg-light mb-4">
            <div class="card-body">
                <label class="form-check py-2" id="js-template-import-type-only-media">
                    <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="only_media" />

                    <span class="form-label"><?php _e("Import only media and css") ?></span>
                    <span class="fs-5"><?php _e("This option will import only the media and css files") ?></span>
                </label>
            </div>
        </div>

        <div class="card bg-light mb-4">
            <div class="card-body">
                <label class="form-check py-2" id="js-template-import-type-delete">
                    <input class="form-check-input mt-3 me-3" type="radio" name="import_type" value="delete" />

                    <span class="form-label"><?php _e("Delete all website data") ?></span>
                    <span class="fs-5"><?php _e("This option will delete all website data and will import fresh content") ?>.</span>
                </label>
            </div>
        </div>

    </div>

    <div style="margin-bottom:20px;" class="js-backup-restore-installation-language-wrapper"></div>
    <div class="backup-restore-modal-log-progress"></div>

    <div class="mw-backup-restore-buttons">
        <button class="btn btn-primary js-button-change-template me-2" onclick="mw_change_template()" type="submit">
            <?php _e("Change Template") ?>
        </button>
        <a class="btn btn-link" style="font-weight: normal;" onclick="mw.dialog.get().remove()"><?php _e("Cancel") ?></a>
    </div>

</div>
