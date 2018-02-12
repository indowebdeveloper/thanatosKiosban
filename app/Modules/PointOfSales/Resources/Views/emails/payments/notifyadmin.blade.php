@component('mail::message')

@slot('logo')
{{$data['website']['logo']}}
@endslot

# Penerimaan Pembayaran

Anda baru saja mendapatkan pembayaran dari customer dengan nomor invoice **#{{$data['order']['order_number']}}**.

Dengan rincian sebagai berikut :

@component('mail::table')
| ID        | Date           | Method  | Amount |
| ------------- | ------------- | ----- | ---- |
| {{$data['trans']['transaction_id']}} | {{$data['trans']['transaction_date']}} | {{$data['trans']['method']}} | {{toCurrency($data['trans']['amount'])}} |
@endcomponent

@endcomponent
