<ul class="list-group list-group-flush">
    @foreach($products as $product)
        <li class="list-group-item bg--light">
            <a href="{{route('product',$product->slug)}}" >
                {{ $product['name'] }}
            </a>
        </li>
    @endforeach
</ul>
