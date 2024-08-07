<div class="modal fade" tabindex="-1" role="dialog" id="sitemap-upload-modal" data-backdrop="static"
     data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title w-100 text-center">{{translate('Upload_File')}}</h2>
                <div type="button" class="btn-close xml_file_upload_close" data-dismiss="modal" aria-label="Close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.47 2 2 6.47 2 12C2 17.53 6.47 22 12 22C17.53 22 22 17.53 22 12C22 6.47 17.53 2 12 2ZM16.3 16.3C16.2075 16.3927 16.0976 16.4662 15.9766 16.5164C15.8557 16.5666 15.726 16.5924 15.595 16.5924C15.464 16.5924 15.3343 16.5666 15.2134 16.5164C15.0924 16.4662 14.9825 16.3927 14.89 16.3L12 13.41L9.11 16.3C8.92302 16.487 8.66943 16.592 8.405 16.592C8.14057 16.592 7.88698 16.487 7.7 16.3C7.51302 16.113 7.40798 15.8594 7.40798 15.595C7.40798 15.4641 7.43377 15.3344 7.48387 15.2135C7.53398 15.0925 7.60742 14.9826 7.7 14.89L10.59 12L7.7 9.11C7.51302 8.92302 7.40798 8.66943 7.40798 8.405C7.40798 8.14057 7.51302 7.88698 7.7 7.7C7.88698 7.51302 8.14057 7.40798 8.405 7.40798C8.66943 7.40798 8.92302 7.51302 9.11 7.7L12 10.59L14.89 7.7C14.9826 7.60742 15.0925 7.53398 15.2135 7.48387C15.3344 7.43377 15.4641 7.40798 15.595 7.40798C15.7259 7.40798 15.8556 7.43377 15.9765 7.48387C16.0975 7.53398 16.2074 7.60742 16.3 7.7C16.3926 7.79258 16.466 7.90249 16.5161 8.02346C16.5662 8.14442 16.592 8.27407 16.592 8.405C16.592 8.53593 16.5662 8.66558 16.5161 8.78654C16.466 8.90751 16.3926 9.01742 16.3 9.11L13.41 12L16.3 14.89C16.68 15.27 16.68 15.91 16.3 16.3Z"
                            fill="#BFBFBF"/>
                    </svg>
                </div>
            </div>
            <div class="modal-body">
                <form action="{{ env('APP_MODE') == 'demo' ? 'javascript:' :  route('admin.seo-settings.sitemap-manual-upload') }}" method="POST"
                      id="xml_file_upload_form" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <div class="mb-3">
                            <div class="d-flex flex-column align-items-center gap-3">
                                <div class="mx-auto text-center max-w-300px w-100">

                                    <div id="xml_file_upload_container">
                                        <div id="xml_file_upload_placeholder">
                                            <label
                                                class="custom_upload_input d-flex mx-2 cursor-pointer align-items-center justify-content-center border-dashed-2">
                                                <input type="file" name="xml_file" id="xml_file_input"
                                                       class="custom-file-input image-preview-before-upload d-none"
                                                       accept=".xml"
                                                >
                                                <div class="placeholder-image py-3">
                                                    <div
                                                        class="d-flex flex-column justify-content-center align-items-center aspect-1">
                                                        <img alt="" width="33"
                                                             src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/product-upload-icon.svg') }}">
                                                        <h3 class="text-muted fz-12">{{ translate('upload_file') }}</h3>
                                                    </div>
                                                </div>
                                            </label>
                                            <p class="text-muted mt-2 fz-12 m-0">
                                                {{ translate('upload_your_sitemap_file_here') }}
                                            </p>
                                        </div>

                                        <div id="xml_file_upload_progress" class="d-none">
                                            <div class="p-3 border rounded">
                                                <div class="d-flex justify-content-between">
                                                <span class="progress-text"
                                                      data-progress="{{ translate('Uploading') }}"
                                                      data-complete="{{ translate('Uploaded') }}"
                                                >
                                                    0% {{ translate('Uploading') }}...
                                                </span>
                                                    <span
                                                        class="text-danger font-weight-bold cursor-pointer xml_file_upload_cancel_icon">x</span>
                                                </div>
                                                <div class="progress mt-2">
                                                    <div class="progress-bar" role="progressbar" style="width: 0%"
                                                         aria-valuenow="100" aria-valuemin="0"
                                                         aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <p class="mb-0 py-2 text-muted">
                                                {{ translate('if_you_submit_this_file_the_previous_file_will_be_automatically_replaced_by_this_file_in_the_server.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 btn--container justify-content-center">
                        <button type="reset" id="xml_file_upload_cancel" class="btn btn-soft-danger font-weight-bold"
                                data-dismiss="modal" aria-label="Close">
                            {{ translate('cancel') }}
                        </button>

                        <button type="{{env('APP_MODE')!='demo'? 'submit' : 'button' }}" id="xml_file_upload_submit" class="btn btn--primary font-weight-bold {{env('APP_MODE')!='demo'? '' : 'call-demo' }}"
                                disabled>
                            {{ translate('submit') }}
                        </button>
                    </div>
                </form>
            </div>
            <div class="btn--container"></div>
        </div>
    </div>
</div>
