<section class="pt-4 pt-lg-5">
    <div class="container">
        <div class="d-flex flex-column gap-2 align-items-center text-center">
            <h2 class="section-title mb-2">{{translate('frequently_asked_questions')}}</h2>
            <p class="max-w-500 mb-4">{{translate('got_questions_about_becoming_a_vendor').' ? '.translate('explore_our_vendor_FAQ_section_for_answers_to_any_queries_you_may_have_about_joining_our_platform_as_a_vendor')}}</p>
        </div>

        <div class="accordion accordion__custom accordion-flush" id="accordionFlushExample">
            @foreach($helpTopics as $key=>$topic)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-heading-{{$key}}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{$key}}" aria-expanded="false" aria-controls="flush-collapse-{{$key}}">
                            {{$topic->question}}
                        </button>
                    </h2>
                    <div id="flush-collapse-{{$key}}" class="accordion-collapse collapse" aria-labelledby="flush-heading-{{$key}}" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">{{$topic->answer}}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
