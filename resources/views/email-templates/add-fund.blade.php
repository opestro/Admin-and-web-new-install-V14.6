<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ translate('Add_Fund_Transaction_Status') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

        @import url('https://fonts.googleapis.com/css?family=Helvetica:700,400');

        body {
            font-family: 'Helvetica', sans-serif;
            font-style: normal;
            background-color: #ececec;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        .congrats-box { margin-top: 10px; margin-bottom: 38px; }
        .col { padding: 11px 0 11px 0; }
        .d-flex { display: flex}
        .d-block { display: block}
        .align-items-center { align-items: center}
        .justify-content-center { justify-content: center}
        .gap-1 { gap: .5rem}
        .gap-2 { gap: 1rem}
        .m-auto {margin:auto}
        .width-auto {width:auto}
        .bg-white {background-color:white}
        .pb-40px {padding-bottom: 40px}
        .pt-40px {padding-top: 40px}
        .text-center {text-align: center}
        .fs-14px {font-size: 14px}
        .fs-16px {font-size: 16px}
    </style>

</head>
<body>
    <?php

    $companyPhone = getWebConfig(name: 'company_phone');
    $companyEmail = getWebConfig(name: 'company_email');
    $companyName = getWebConfig(name: 'company_name');
    $companyLogo = getWebConfig(name: 'company_web_logo');
    ?>


<div style="height: 100px;background-color: #ececec; width:100%"></div>
<div class="m-auto bg-white pt-40px pb-40px text-center" style="width:595px;border-radius: 3px;">
    <div class="d-block">
        @if(is_file('storage/app/public/company/'.$companyLogo))
            <div class="d-flex justify-content-center align-items-center gap-1">
                <img src="{{ dynamicStorage(path: 'storage/app/public/company/'.$companyLogo) }}" alt="{{ $companyName }}"
                     style="height: 50px;" class="width-auto">
                {{ $companyName }}
            </div>
        @else
            {{ $companyName }}
        @endif
    </div>

    <img src="{{ dynamicAsset(path: 'public/assets/front-end/img/icons/add_fund_vector.png') }}" alt="" style="height: 50px; width:50px; margin-top:40px;">

    <div class="congrats-box">
        <span class="d-block" style="font-weight: 700;font-size: 22px;line-height: 135.5%;margin-bottom:10px;">
            {{ translate('Transaction_Successful') }}
        </span>
        <span class="fs-16px" style="font-weight: 400;line-height: 135.5%; color:#727272; margin-bottom:7px; display:block;">
            {{ translate('amount_successfully_credited to_your_wallet') }}
        </span>
        <span class="fs-14px" style="font-weight: 400;line-height: 135.5%;color: #182E4B;display:block; margin-bottom:10px;">
            <span class="fs-14px" style="font-weight: 700;line-height: 19px;color: #EF7822;">
                {{ translate('note') }}:
            </span>
            {{$data->transaction_type=='add_fund_by_admin' ? translate('Reward_by_company_admin') : translate('loyalty_point_to_wallet') }}
        </span>
        <span class="fs-14px" style="font-weight: 700;line-height: 135.5%;color: #182E4B; display:block; margin-bottom: 5px;">{{ translate('dear') }} {{$data->user->f_name.' '.$data->user->l_name}}</span>
        <span style="font-weight: 400;font-size: 12px;line-height: 135.5%;text-align: center;color: #182E4B;display:block; margin-bottom:34px;">
            {{ translate('Thank you for joining with') }}
            <span style="color: #EF7822;">
                {{$companyName}}!
            </span>
        </span>
    </div>

    <div style="background-color: #F5F5F5; width: 90%;margin: 30px auto auto;padding: 10px 20px 20px 5px;">
        <table style="width: 100%; text-transform: capitalize; font-size: 11px;line-height: 13px;text-align: center;color: #242A30;">
            <tbody>
            <tr style="font-weight: 700;">
                <th class="col" style="width:10%;">{{ translate('sl') }}</th>
                <th class="col"
                    style="width:35%;">{{ translate('transaction') }} {{ translate('id') }}</th>
                <th class="col"
                    style="width:20%">{{ translate('transaction') }} {{ translate('date') }}</th>
                <th class="col" style="width:15%">{{ translate('credit') }}</th>
                <th class="col" style="width:15%">{{ translate('debit') }}</th>
                <th class="col" style="width:15%;">{{ translate('balance') }}</th>
            </tr>

            <tr style="font-weight:400;">
                <td class="col">1</td>
                <td class="col">{{$data->transaction_id}}</td>
                <td class="col">{{$data->created_at}}</td>
                <td class="col">{{\App\Utils\Helpers::currency_converter($data->credit) }}</td>
                <td class="col">{{\App\Utils\Helpers::currency_converter($data->debit) }}</td>
                <td class="col">{{\App\Utils\Helpers::currency_converter($data->balance) }}</td>
            </tr>
            </tbody>
        </table>
    </div>


    <span style="font-weight: 400;font-size: 12px;line-height: 135.5%;color: #5D6774;display:block;margin-top:43px;">{{ translate('If you require any assistance or have feedback or suggestions about our site, you can email us at') }}
          <a href="{{ 'mailto:'.$companyEmail }}" class="email">{{ $companyEmail }}</a>
      </span>
</div>

<div style="padding:5px;width:650px;margin: 5px auto 50px;">
    <table style="margin:auto;width:90%; color:#777777;">
        <tbody style="text-align: center;">

        <tr>
            @php($social_media = \App\Models\SocialMedia::where('active_status', 1)->get())

            @if(isset($social_media))
                <th>
                    @foreach ($social_media as $item)
                        <div style="display: inline-block;">
                            <a href="{{$item->link}}" target=”_blank”>
                                <img src="{{dynamicAsset(path: 'public/assets/admin/img/'.$item->name.'.png') }}" alt=""
                                     style="height: 14px; width:14px; padding: 0 3px 0 5px;">
                            </a>
                        </div>
                    @endforeach
                </th>
            @endif
        </tr>
        <tr>
            <th>
                <div style="font-weight: 400;font-size: 11px;line-height: 22px;color: #242A30;">
                    <span style="margin-right:5px;">
                        <a href="{{ 'tel:'.$companyPhone }}"
                                                          style="text-decoration: none; color: inherit;">{{ translate('phone') }}: {{$companyPhone}}</a></span>
                    <span><a href="{{ 'mailto:'.$companyEmail }}" style="text-decoration: none; color: inherit;">{{ translate('email') }}: {{$companyEmail}}</a></span>
                </div>

                <span style="font-weight: 400;font-size: 10px;line-height: 22px;color: #242A30;">
                    {{ translate('All_copy_right_reserved').','.date('Y').' '.$companyName }}
                </span>
            </th>
        </tr>

        </tbody>
    </table>
</div>

</body>
</html>
