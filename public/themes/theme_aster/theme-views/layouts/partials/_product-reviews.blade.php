@foreach ($productReviews as $item)
<div class="card border-primary-light flex-grow-1">
    <div class="media flex-wrap align-items-center gap-3 p-3">
        <div class="avatar border rounded-circle size-3-437rem">
            <img src="{{ getValidImage(path: 'storage/app/public/profile/'.(isset($item->user)?$item->user->image : ''), type:'avatar') }}" alt="{{translate('image')}}"
            class="img-fit dark-support rounded-circle">
        </div>
        <div class="media-body d-flex flex-column gap-2">
            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                <div>
                    <h6 class="mb-1">{{isset($item->user)?$item->user->f_name:translate('User_Not_Exist')}}</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="star-rating text-gold fs-12">
                            @for ($inc=0; $inc < 5; $inc++)
                                @if ($inc < $item->rating)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span>({{$item->rating}}/5)</span>
                    </div>
                </div>
                <div>{{$item->created_at->format("d M Y h:i:s A")}}</div>
            </div>
            <p>{{$item->comment}}</p>
        </div>
    </div>
</div>
@endforeach
