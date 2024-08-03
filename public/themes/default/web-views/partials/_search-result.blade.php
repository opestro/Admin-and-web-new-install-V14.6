<ul class="list-group list-group-flush">
    @foreach($products as $product)
        <li class="list-group-item px-0 overflow-hidden">
            <button type="submit" class="search-result-product btn p-0 m-0 search-result-product-button align-items-baseline text-start" data-product-name="{{ $product['name'] }}">
                <span><i class="czi-search"></i></span>
                <div class="text-truncate">{{$product['name']}}</div>
                <span class="px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1H3.707l10.147 10.146a.5.5 0 0 1-.708.708L3 3.707V8.5a.5.5 0 0 1-1 0z"/>
                    </svg>
                </span>
            </button>
        </li>
    @endforeach
</ul>
