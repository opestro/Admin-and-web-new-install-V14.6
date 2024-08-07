<section class="pt-4 pt-lg-5">
    <div class="container">
        <div class="d-flex flex-column gap-2 align-items-center text-center">
            <h2 class="section-title mb-2">{{translate('Frequently Asked Questions')}}</h2>
            <p class="max-w-500 mb-4">{{translate('got_questions_about_becoming_a_vendor').' ? '.translate('explore_our_vendor_FAQ_section_for_answers_to_any_queries_you_may_have_about_joining_our_platform_as_a_vendor')}}</p>
        </div>

        <div class="accordion__custom" id="accordion">
            @foreach($helpTopics as $key=>$topic)
                <div class="card">
                    <div class="card-header" id="heading-{{$key}}">
                        <h6 class="faq-title mb-0 py-2 collapsed" data-toggle="collapse" data-target="#collapse-{{$key}}" aria-expanded="true" aria-controls="collapse-{{$key}}">
                            {{$topic->question}}
                        </h6>
                    </div>

                    <div id="collapse-{{$key}}" class="collapse" aria-labelledby="heading-{{$key}}" data-parent="#accordion">
                        <div class="card-body">
                            {{$topic->answer}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
