<ul class="list-group">
    @foreach($items as $i)
        <li class="list-group-item">
            <a href="{{url('/').'/'.$i['url']}}">
                {{$i['key']}}
            </a>
        </li>
    @endforeach
</ul>
