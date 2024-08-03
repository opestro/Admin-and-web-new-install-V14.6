@if(count($generateCombination) > 0)
    <div class="card-header">
        <div class="d-flex gap-2">
            <i class="tio-user-big"></i>
            <h4 class="mb-0">{{ translate('file_upload') }}</h4>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead class="thead-light thead-50 text-capitalize">
                <tr>
                    <th class="text-center">{{ translate('SL') }}</th>
                    <th class="text-center">{{ translate('Product_Variation') }}</th>
                    <th class="text-center">{{ translate('Price') }}
                        ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})
                    </th>
                    <th class="text-center">{{ translate('SKU') }}</th>
                    @if($digitalProductType == 'ready_product')
                        <th>
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <span>{{ translate('Upload_File') }}</span>
                                <span class="input-label-secondary cursor-pointer mb-1" data-toggle="tooltip"
                                      title="{{ translate('it_can_be_possible_to_upload_all_types_of_audio,_video_and_documentation_and_software_files.') }}">
                            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg') }}"
                                 alt="">
                        </span>
                            </div>
                        </th>
                    @endif
                </tr>
                </thead>
                <tbody>

                @php
                    $serial = 1;
                @endphp

                @foreach ($generateCombination as $combinationKey => $combination)
                    <tr>
                        <td class="text-center">
                            {{ $serial++ }}
                        </td>
                        <td class="text-center">
                            <label for="" class="control-label">{{ $combination['variant_key'] }}</label>
                            <input type="hidden" name="digital_product_variant_key[{{ $combination['unique_key'] }}]"
                                   value="{{ $combination['variant_key'] }}">
                        </td>
                        <td>
                            <input type="number" name="digital_product_price[{{ $combination['unique_key'] }}]"
                                   value="{{ usdToDefaultCurrency(amount: $combination['price']) }}" min="0" step="0.01"
                                   class="form-control variation-price-input remove-symbol" required
                                   placeholder="{{ translate('ex').': 100' }}">
                        </td>
                        <td>
                            <input type="text" name="digital_product_sku[{{ $combination['unique_key'] }}]"
                                   value="{{ strtoupper($combination['sku']) }}" class="form-control store-keeping-unit"
                                   required>
                        </td>

                        @if($digitalProductType == 'ready_product')
                            <td>
                                <div class="variation-upload-item">
                                    <label class="variation-upload-file {{ $combination['file'] ? 'collapse' : '' }}">
                                        <input type="file" class="d-none" accept=""
                                               name="digital_files[{{ $combination['unique_key'] }}]">
                                        <img
                                            src="{{ dynamicAsset(path: '/public/assets/back-end/img/upload-icon.png') }}"
                                            alt="">
                                        <span>{{ translate('Upload_File') }}</span>
                                    </label>

                                    <div class="variation-upload-file uploading-item collapse">
                                        <img
                                            src="{{ dynamicAsset(path: '/public/assets/back-end/img/upload-icon.png') }}"
                                            alt="">
                                        <span class="mr-auto text--title">{{ translate('Uploading') }}</span>
                                        <button class="no-gutter cancel-upload" type="button">
                                            <img
                                                src="{{ dynamicAsset(path: '/public/assets/back-end/img/cancel-icon.png') }}"
                                                alt="">
                                        </button>
                                    </div>

                                    <div
                                        class="variation-upload-file uploaded-item {{ $combination['file'] ? '' : 'collapse' }}">
                                        <span class="mr-auto text--title file-name">
                                            {{ $combination['file'] }}
                                        </span>

                                        @if($combination['file'])
                                            <button class="no-gutter cancel-upload digital-variation-file-delete-button"
                                                    type="button"
                                                    data-product="{{ $combination['product_id'] }}"
                                                    data-variant="{{ $combination['variant_key'] }}">
                                                <img
                                                    src="{{ dynamicAsset(path: '/public/assets/back-end/img/delete-icon.png') }}"
                                                    alt="">
                                            </button>
                                        @else
                                            <button class="no-gutter cancel-upload" type="button">
                                                <img
                                                    src="{{ dynamicAsset(path: '/public/assets/back-end/img/delete-icon.png') }}"
                                                    alt="">
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@if(count($generateCombination) <= 0)
    @if($digitalProductType == 'ready_product' && (!isset($request['extensions_type']) || count($request['extensions_type']) <= 0))
        <div class="card-header">
            <div class="d-flex gap-2">
                <i class="tio-user-big"></i>
                <h4 class="mb-0">
                    {{ translate('file_upload') }}
                </h4>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="digital_file_ready"
                               id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="inputGroupFile01">
                            {{ translate('choose_file') }}
                        </label>
                    </div>
                </div>
                <div class="mt-2">{{ translate('file_type') }}: {{ "jpg, jpeg, png, gif, zip, pdf" }}</div>
            </div>
        </div>
    @endif
@endif
