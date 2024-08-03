<div class="p-3 px-xl-4 py-sm-5">
    <div class="text-center">
        <img width="100" class="mb-4" id="view-mail-icon"
             src="{{$template['image'] ? dynamicStorage('storage/app/public/email-template/'.$template['image']) : dynamicAsset(path: 'public/assets/back-end/img/email-template/congratulations.png')}}"
             alt="">
        <h3 class="mb-3 view-mail-title text-capitalize">
            {{$title}}
        </h3>
    </div>
    <div class="view-mail-body text-center">
        {!! $body !!}
    </div>
    <div class="main-table-inner mb-4">
        <div class="d-flex justify-content-center pt-3">
            <img width="76" class="mb-4 mx-auto" id="view-mail-logo" src="{{$template['logo'] ? dynamicStorage('storage/app/public/email-template/'.$template['logo']) : getValidImage(path: "storage/app/public/company/".$companyLogo, type:'backend-logo')}}" alt="">
        </div>
        <div class="text-center">
            <h3 class="mb-3 ">{{translate('order_Info')}}</h3>
            <p>
                {{translate('to_verify_when_download_your_product').','.translate('_use_your_order_info').'.'}}
            </p>
        </div>

        <div class="main-table-inner bg-white" id="order-information">
            <div class="{{$template['order_information_status'] == 1 ? '' : 'd-none'}} table-responsive" >
                <table class="email-table">
                    <tr>
                        <td >{{translate('Order').'#'.($data['order']->id ?? '312312')}}</td>
                        <td >{{($data['order']?->customer['phone'] ?? '+091231231')}}</td>
                    </tr>
                    <tr>
                        <td>
                            <h4 class="mb-2">{{translate('products')}}</h4>
                            @if(isset($data['order']))
                                @foreach($data['order']->details as $key=>$detail)
                                    @php($product = json_decode($detail['product_details']))
                                    @if($product->product_type == 'digital')
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <img class="product-image"
                                                src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product->thumbnail, type: 'backend-product') }}"
                                                 alt="">
                                            <div class="product-title">{{substr($product->name, 0, 50)}}</div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="d-flex align-items-center gap-3 min-w-200">
                                    <img class="product-image"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/email-template/digital-product.png')}}"
                                         alt="">
                                    <div class="product-title">{{translate('The Great Gatsby PDF')}}</div>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="text-center gap-3 p-5">
            <div class="text-capitalize mb-2 mt-2">{{translate('click_below')}}</div>
            <a href="{{isset($data['order']) ? route('digital-product-download-pos.index',['order_id'=>$data['order']->id,'email'=>$data['order']->customer['email']]) : 'javascript:' }}" class="{{isset($data['order']) ? '' : 'cursor-default'}}">{{url('digital-product-download-pos')}}</a>
        </div>
    </div>
    <div>
        <p>
            {{translate('don’t_share_your_order_information').', '.translate('_it’s_confidential').', '.translate('_if_you_share_this_info').', '.translate('_then_anyone_can_download').'.'}}
        </p>
        <p>
            {{translate('meanwhile_click_here_to_visit_').$companyName.translate('_website')}}
            <br>
            <a href="{{route('home')}}" target="_blank">{{url('/')}}</a>
        </p>
    </div>
    <hr>
    @include('admin-views.business-settings.email-template.partials-design.footer-design-without-logo-2')
</div>
