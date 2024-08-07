<div class="horizontal-scroll d-flex gap-2 gap-sm-3 gap-lg-4 mt-4 {{count($vendorRegistrationReasons) <4 ? 'justify-content-center' : ''}}">
    @foreach($vendorRegistrationReasons as $key=>$reason)
        <div class="d-flex flex-column gap-3 ">
            <h5>{{$reason->title}}</h5>
            <p>{{$reason->description}}</p>
        </div>
    @endforeach
</div>
