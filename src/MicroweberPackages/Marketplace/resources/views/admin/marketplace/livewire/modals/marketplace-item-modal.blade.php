<div>
    @if (!isset($package['description']))
<div class="alert alert-danger">{{'Please select a package'}}</div>
    @else
    <script>mw.require('admin_package_manager.js');</script>

    <div class="modal-header">
        <h5 class="modal-title">
            {{$package['description']}}
        </h5>
        <button type="button" class="btn-close" wire:click="$emit('closeModal')" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row row-cols-2">

            <div class="marketplace-template-img-wrapper col-xl-7 me-3 px-0" style="max-height:400px;overflow:hidden">
                <div class="marketplace-template-img-wrapper-overlay">

                    <a href="" class="btn btn-dark marketplace-template-img-btn">
                        {{'Preview'}}
                    </a>
                </div>

                @if (isset($package['extra']['_meta']['screenshot_large']))
                    <img src="{{$package['extra']['_meta']['screenshot_large']}}" />
                @elseif(isset($package['extra']['_meta']['screenshot']))
                    <img src="{{$package['extra']['_meta']['screenshot']}}" />
                @else
                <div class="card-img-top text-center">
                    <i class="mdi mdi-view-grid-plus text-muted" style="opacity:0.5;font-size:126px;margin-left: 15px;"></i>
                </div>
                @endif
            </div>
            <div class="col-xl-4">
                <h1>{{$package['description']}}</h1>
                <div class="tblr-body-color">
                    {{'Latest Version'}}: {{$package['version']}}
                </div>
                <br />

                       {{'Install version'}}:
                <div class="d-flex align-items-center ">
                   <div class="col-xl-5 me-2">
                       <select class="form-select form-select-sm" wire:model="installVersion" class="form-control">
                           @foreach($package['versions'] as $version)
                               <option value="{{$version}}">{{$version}}</option>
                           @endforeach
                       </select>
                   </div>
                    <div class=" text-end">

                        @if($package['has_update'] && $installVersion == $package['version'])
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-warning btn-sm js-package-install-btn">
                                <i class="mdi mdi-rocket"></i> {{_e('Update to')}} {{$installVersion}}
                            </a>
                        @elseif($package['current_install'] && $installVersion < $package['version'])
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-danger btn-sm js-package-install-btn">
                                <i class="mdi mdi-arrow-down"></i> {{_e('Downgrade to')}} {{$installVersion}}
                            </a>
                        @elseif($package['current_install'])
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-danger btn-sm js-package-install-btn">
                                {{_e('Reinstall')}}
                            </a>
                        @else
                            <a vkey="{{$installVersion}}" href="javascript:;"
                               id="js-install-package-action"
                               onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('{{$package['name']}}',$(this).attr('vkey'), this)"
                               class="btn btn-outline-success btn-sm js-package-install-btn">
                                <i class="mdi mdi-download"></i>
                                {{_('Install')}}
                            </a>
                        @endif


                    </div>
                </div>

                <br />
                <div>
                    <table cellspacing="0" cellpadding="0" class="table table-striped fs-5  m-0" width="100%">
                                <tbody>

                                <tr>
                                    <td><?php _e('License'); ?></td>
                                    <td>
                                        <?php _e('N/A'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?php _e('Website'); ?></td>
                                    <td>
                                        <?php if (isset($package['homepage'])): ?>
                                        <a href="<?php print $package['homepage']; ?>" target="_blank"
                                           class="mw-blue package-ext-link"><?php print $package['homepage']; ?></a>
                                                    <?php else: ?>
                                            <?php _e('N/A'); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                @if(isset($package['authors']) && !empty($package['authors']))
                                    <tr>
                                        <td><?php _e('Author'); ?> <br> <?php _e('Support'); ?></td>
                                        <td>
                                            @foreach($package['authors'] as $author)
                                                {{$author['name']}}  <a href="mailto:{{$author['email']}}">{{$author['email']}}</a>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td><?php _e('Released'); ?></td>
                                    <td><?php print date('d M Y', strtotime($package['time'])) ?></td>
                                </tr>

                                <tr>
                                    <td><?php _e('Keywords'); ?></td>
                                    <td>
                                        <?php if (isset($package['keywords'])): ?>
                                            <?php print implode(", ", $package['keywords']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
