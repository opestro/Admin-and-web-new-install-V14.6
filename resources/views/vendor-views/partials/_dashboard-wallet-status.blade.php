    <div class="col-lg-4">
        <!-- Card -->
        <div class="card h-100 d-flex justify-content-center align-items-center">
            <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
                <img width="48" class="mb-2" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw.png')}}" alt="">
                <h3 class="for-card-count mb-0 fz-24">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['totalEarning']), currencyCode: getCurrencyCode(type: 'default'))}}</h3>
                <div class="font-weight-bold text-capitalize mb-30">
                    {{translate('withdrawable_balance')}}
                </div>
                <a href="javascript:"
                    class="btn btn--primary px-4"
                    data-toggle="modal" data-target="#balance-modal">
                    {{translate('withdraw')}}
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row g-2">
            <div class="col-md-6">
                <div class="card card-body h-100 justify-content-center">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-start">
                            <h3 class="mb-1 fz-24">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['pendingWithdraw']), currencyCode: getCurrencyCode(type: 'default'))}}</h3>
                            <div class="text-capitalize mb-0">{{translate('pending_Withdraw')}}</div>
                        </div>
                        <div>
                            <img width="40" class="mb-2" src="{{dynamicAsset(path: 'public/assets/back-end/img/pw.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 justify-content-center">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-start">
                            <h3 class="mb-1 fz-24">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['adminCommission']), currencyCode: getCurrencyCode(type: 'default'))}}</h3>
                            <div class="text-capitalize mb-0">{{translate('total_Commission_given')}}</div>
                        </div>
                        <div>
                            <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/tcg.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 justify-content-center">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-start">
                            <h3 class="mb-1 fz-24">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['withdrawn']), currencyCode: getCurrencyCode(type: 'default'))}}</h3>
                            <div class="text-capitalize mb-0">{{translate('already_Withdrawn')}}</div>
                        </div>
                        <div>
                            <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/aw.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 justify-content-center">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-start">
                            <h3 class="mb-1 fz-24">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['deliveryManChargeEarned']), currencyCode: getCurrencyCode(type: 'default'))}}</h3>
                            <div class="text-capitalize mb-0">{{translate('total_delivery_charge_earned')}}</div>
                        </div>
                        <div>
                            <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/tdce.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 justify-content-center">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-start">
                            <h3 class="mb-1 fz-24">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['collectedTotalTax']), currencyCode: getCurrencyCode(type: 'default'))}}</h3>
                            <div class="text-capitalize mb-0">{{translate('total_tax_given')}}</div>
                        </div>
                        <div>
                            <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/ttg.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body h-100 justify-content-center">
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <div class="d-flex flex-column align-items-start">
                            <h3 class="mb-1 fz-24">{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['collectedCash']), currencyCode: getCurrencyCode(type: 'default'))}}</h3>
                            <div class="text-capitalize mb-0">{{translate('collected_cash')}}</div>
                        </div>
                        <div>
                            <img width="40" src="{{dynamicAsset(path: 'public/assets/back-end/img/cc.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

