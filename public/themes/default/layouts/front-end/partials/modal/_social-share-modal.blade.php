<div class="modal fade" id="social-share-modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="social-share-popup">
                <header>
                    <span>{{ translate('Share_Modal') }}</span>
                    <div class="close" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></div>
                </header>
                <div class="content">
                    <p class="text-center">{{ translate('Share_this_link_via') }}</p>
                    <ul class="icons p-0">
                        <li>
                            <a href="javascript:"
                               class="share-on-social-media"
                               data-action="{{ $link }}"
                               data-social-media-name="facebook.com/sharer/sharer.php?u=">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:" class="share-on-social-media"
                               data-action="{{ $link }}"
                               data-social-media-name="twitter.com/intent/tweet?text=">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:" class="share-on-social-media"
                               data-action="{{ $link }}"
                               data-social-media-name="linkedin.com/shareArticle?mini=true&url=">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:" class="share-on-social-media"
                               data-action="{{ $link }}"
                               data-social-media-name="api.whatsapp.com/send?text=">
                                <i class="fa fa-whatsapp"></i>
                            </a>
                        </li>
                    </ul>
                    @if(isset($link))
                        <p class="text-center pt-3">{{ translate('Or_copy_link') }}</p>
                        <div class="field">
                            <i class="tio-link"></i>
                            <input type="text" readonly value="{{ $link }}">
                            <button class="click-to-copy-data-value btn--primary" data-value="{{ $link }}">
                                {{ translate('copy') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
