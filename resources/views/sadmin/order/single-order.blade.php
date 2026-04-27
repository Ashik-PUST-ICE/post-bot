<div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Transaction Details') }}</h4>
    <button type="button"
            class="border-0 p-0 bg-transparent text-para-text"
            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<div class="transaction-table-part">
    <div class="table-responsive">
        <table class="table zTable zTable-last-item-right zTable-last-item-padding">
            <thead>
            <tr>
                <th class="invoice-heading-color">
                    <div class="text-nowrap">{{ __('Date') }}</div>
                </th>
                <th class="invoice-heading-color">
                    <div class="text-nowrap">{{ __('Gateway') }}</div>
                </th>
                <th class="invoice-heading-color">
                    <div class="text-nowrap">{{ __('Transaction ID') }}</div>
                </th>
                <th class="invoice-tbl-last-field invoice-heading-color">
                    <div class="text-nowrap">{{ __('Amount') }}</div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="orderDate">{{$order->created_at}}</td>
                <td class="orderPaymentTitle">{{$order->gateway->title}}</td>
                <td class="orderPaymentId">{{$order->transaction_id}}</td>
                <td class="orderTotal invoice-tbl-last-field">{{showPrice($order->amount)}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
