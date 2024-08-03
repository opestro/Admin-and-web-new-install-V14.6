<h6 class="mb-3">{{translate('Ratings')}}</h6>
<ul class="common-nav nav flex-column">
    <li>
        <div class="flex-between-gap-3 align-items-center">
            <label class="custom-checkbox filter-by-rating" data-rating="5">
                <span class="star-rating text-gold">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </span>
            </label>
            <span class="badge bg-badge rounded-pill text-dark">
                {{$ratings['rating_5']}}
            </span>
        </div>
    </li>
    <li>
        <div class="flex-between-gap-3 align-items-center">
            <label class="custom-checkbox filter-by-rating" data-rating="4">
                <span class="star-rating text-gold">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                </span>
            </label>
            <span class="badge bg-badge rounded-pill text-dark">
                {{$ratings['rating_4']}}
            </span>
        </div>
    </li>
    <li>
        <div class="flex-between-gap-3 align-items-center">
            <label class="custom-checkbox filter-by-rating" data-rating="3">
                <span class="star-rating text-gold">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                </span>
            </label>
            <span class="badge bg-badge rounded-pill text-dark">
                {{$ratings['rating_3']}}
            </span>
        </div>
    </li>
    <li>
        <div class="flex-between-gap-3 align-items-center">
            <label class="custom-checkbox filter-by-rating" data-rating="2">
                <span class="star-rating text-gold">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                </span>
            </label>
            <span class="badge bg-badge rounded-pill text-dark">
                {{$ratings['rating_2']}}
            </span>
        </div>
    </li>
    <li>
        <div class="flex-between-gap-3 align-items-center">
            <label class="custom-checkbox filter-by-rating" data-rating="1">
                <span class="star-rating text-gold">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                </span>
            </label>
            <span class="badge bg-badge rounded-pill text-dark">
                {{$ratings['rating_1']}}
            </span>
        </div>
    </li>
</ul>
