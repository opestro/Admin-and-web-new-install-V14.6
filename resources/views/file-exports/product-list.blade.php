<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate($data['type'].'_product_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('filter_Criteria').' - ' }}</th>
                <th></th>
                <th>
                    @if(isset($data['vendor']))
                        {{translate('store_Name')}} - {{$data['vendor']?->shop?->name}}
                        <br>
                    @endif
                    {{translate('category').' - '. ($data['category'] != 'all' ? $data['category']['defaultName'] : $data['category'])  }}
                    <br>
                    {{translate('sub_Category').' - '. ($data['sub_category'] != 'all' ? $data['sub_category']['defaultName'] : $data['sub_category'])  }}
                    <br>
                    {{translate('sub_Sub_Category').' - '. ($data['sub_sub_category'] != 'all' ? $data['sub_sub_category']['defaultName'] : $data['sub_sub_category'])  }}
                    <br>
                    {{translate('brand').' - '. ($data['brand'] != 'all' ? $data['brand']['defaultName'] : $data['brand'])  }}

                    @if($data['type']=='seller')
                        <br>
                        {{translate('store').' - '. ($data['seller']?->shop->name ?? translate('all'))}}
                        <br>
                        {{translate('status').' - '. ($data['status']==0 ? translate('pending') : ($data['status'] == 1 ? translate('approved') : translate('denied')) )}}
                    @endif
                    <br>
                    {{translate('search_Bar_Content').' - '. (!empty($data['searchValue']) ?  ucwords($data['searchValue']) : 'N/A') }}

                </th>
            </tr>
            <tr>
                <td> {{translate('SL')}}</td>
                <td> {{translate('product_Image')}}	</td>
                <td> {{translate('image_URL')}}	</td>
                <td> {{translate('product_Name')}}	</td>
                <td> {{translate('product_SKU')}}</td>
                <td> {{translate('description')}}</td>

                <td>
                    @if($data['type']=='seller')
                        {{translate('store_Name')}}
                    @endif
                </td>
                <td> {{translate('category_Name')}}</td>
                <td> {{translate('sub_Category_Name')}}</td>
                <td> {{translate('sub_Sub_Category_Name')}}</td>
                <td> {{translate('brand')}}</td>
                <td> {{translate('product_Type')}}</td>
                <td> {{translate('price')}}</td>
                <td> {{translate('tax')}}</td>
                <td> {{translate('discount')}}</td>
                <td> {{translate('discount_Type')}}</td>
                <td> {{translate('rating')}}</td>
                <td> {{translate('product_Tags')}}</td>
                <td> {{translate('status')}}</td>
            </tr>
            @foreach ($data['products'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height: 200px"></td>
                    <td>{{dynamicStorage(path: 'storage/app/public/product/thumbnail/'.$item->thumbnail)}}</td>
                    <td> {{$item->name}}</td>
                    <td>{{$item->code}}</td>
                    <td>{{strip_tags(str_replace('&nbsp;', ' ', $item->details))}}</td>
                    <td>
                        @if($data['type']=='seller')
                        {{ucwords($item?->seller?->shop->name ?? translate('not_found'))}}
                        @endif
                    </td>
                    <td>{{ $item?->category->name ?? 'N/A'}}</td>
                    <td>{{ $item?->subCategory->name ?? 'N/A'}}</td>
                    <td>{{ $item?->subSubCategory->name ?? 'N/A'}}</td>
                    <td>{{ $item?->brand->name ?? 'N/A'}}</td>
                    <td>{{ $item?->product_type}}</td>
                    <td>{{setCurrencySymbol(amount: usdToDefaultCurrency($item['unit_price'] ?? 0), currencyCode: getCurrencyCode())}}</td>
                    <td>{{setCurrencySymbol(amount: usdToDefaultCurrency($item['tax'] ?? 0), currencyCode: getCurrencyCode())}}</td>
                    <td>{{setCurrencySymbol(amount: usdToDefaultCurrency($item['discount'] ?? 0), currencyCode: getCurrencyCode())}}</td>
                    <td>{{$item->discount_type}}</td>
                    <td>{{$item?->rating && count($item->rating) > 0 ?  number_format($item->rating[0]->average,2) : 'N/A'}}</td>
                    <td>
                        @if($item->tags)
                            @foreach ($item->tags as $tag)
                                {{$tag->tag}},
                            @endforeach
                        @endif
                    </td>
                    <td> {{translate($item->status == 1 ? 'active' : 'inactive')}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
