<div class="modal fade" id="reviewModal{{$id}}" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header px-sm-5">
                <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('submit_a_review')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('review.store')}}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="modal-body px-sm-5">
                <div class="form-group mb-4">
                    <label for="rating">{{translate('rating')}}</label>
                    @if ($order_details->reviewData)
                        <select name="rating" id="rating" class="form-select">
                            <option value="1" {{ $order_details?->reviewData?->rating == 1 ? 'selected' : '' }}>
                                {{ translate('1') }}
                            </option>
                            <option value="2" {{ $order_details?->reviewData?->rating == 2 ? 'selected' : '' }}>
                                {{ translate('2') }}
                            </option>
                            <option value="3" {{ $order_details?->reviewData?->rating == 3 ? 'selected' : '' }}>
                                {{ translate('3') }}
                            </option>
                            <option value="4" {{ $order_details?->reviewData?->rating == 4 ? 'selected' : '' }}>
                                {{ translate('4') }}
                            </option>
                            <option value="5" {{ $order_details?->reviewData?->rating == 5 ? 'selected' : '' }}>
                                {{ translate('5') }}
                            </option>
                        </select>
                    @else
                        <select name="rating" id="rating" class="form-select">
                            <option value="1">{{translate('1')}}</option>
                            <option value="2">{{translate('2')}}</option>
                            <option value="3">{{translate('3')}}</option>
                            <option value="4">{{translate('4')}}</option>
                            <option value="5">{{translate('5')}}</option>
                        </select>
                    @endif
                </div>
                <div class="form-group mb-4">
                    <label for="comment">{{translate('comment')}}</label>
                    <input name="product_id" value="{{$order_details->product_id}}" hidden>
                    <input name="order_id" value="{{$order_details->order_id}}" hidden>
                    <input name="review_id" value="{{ $order_details->reviewData?->id ?? '' }}" hidden>
                    <textarea name="comment" id="comment" class="form-control" rows="4"
                              placeholder="{{ translate('Leave_a_comment') }}">{{ $order_details->reviewData?->comment ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label>{{translate('attachment')}}</label>
                    <div class="d-flex flex-column gap-3">
                        @if ($order_details?->reviewData && isset($order_details?->reviewData?->attachment_full_url))
                            <div class="d-flex flex-wrap gap-3">
                                @foreach ($order_details?->reviewData?->attachment_full_url as $key => $attachmentItem)
                                    <div class="review-exist-images img-container-{{$key}}">
                                        <span class="img-remove-icon-2 remove-img-row-by-key cursor-pointer"
                                              data-key="{{$key}}"
                                              data-review-id="{{ $order_details?->reviewData?->id }}"
                                              data-photo="{{ $attachmentItem['key'] }}"
                                              data-route="{{ route('delete-review-image') }}">
                                              <i class="bi bi-x"></i>
                                          </span>
                                        <img src="{{ getStorageImages(path: $attachmentItem, type: 'product') }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="row coba"></div>
                        <div class="text-muted">{{translate('file_type').':'.'.jpg,.jpeg,.png'.translate('maximum_size').':'.'2MB'}}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer gap-3 pb-4 px-sm-5">
                <a href="{{ URL::previous() }}" class="btn btn-secondary m-0" data-bs-dismiss="modal">{{translate('back')}}</a>
                <button type="submit" class="btn btn-primary m-0">{{translate('submit')}}</button>
            </div>
            </form>
        </div>
    </div>
</div>
