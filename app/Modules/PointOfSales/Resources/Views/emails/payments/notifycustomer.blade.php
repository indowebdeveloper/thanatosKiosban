@component('mail::message')

@slot('logo')
{{$data['website']['logo']}}
@endslot

# Penerimaan Pembayaran

Kami baru saja menerima pembayaran dari anda dengan nomor invoice **#{{$data['order']['order_number']}}**.

Dengan rincian sebagai berikut :

@component('mail::table')
| ID        | Date           | Method  | Amount |
| ------------- | ------------- | ----- | ---- |
| {{$data['trans']['transaction_id']}} | {{$data['trans']['transaction_date']}} | {{$data['trans']['method']}} | {{toCurrency($data['trans']['amount'])}} |
@endcomponent

Terimakasih telah melakukan pembayaran, tim sales kami akan segera menghubungi anda.

@endcomponent
