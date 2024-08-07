<?php

namespace App\Enums\ViewPaths\Web;

enum Review
{
    const ADD = [
        URI => 'review',
        VIEW => ''
    ];

    const ADD_DELIVERYMAN_REVIEW = [
        URI => 'submit-deliveryman-review',
        VIEW => ''
    ];

    const DELETE_REVIEW_IMAGE = [
        URI => 'review-delete-image',
        VIEW => ''
    ];

}
