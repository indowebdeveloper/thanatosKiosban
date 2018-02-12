@component('mail::message')

@slot('logo')
{{$data['website']['logo']}}
@endslot

# Konfirmasi Update Pesanan

Halo **{{$data['customerData']['name']}}**

Anda baru saja melakukan update pemesanan pada invoice dengan nomor **#{{$data['order']['order_number']}}**

Dengan total transaksi sebesar **{{toCurrency($data['order']['total'])}}**

Rincian pemesanan anda dapat dilihat dengan mengunduh file pdf yang terlampir pada email ini, 
atau dengan melihat secara langsung dengan menekan tombol dibawah ini

@component('mail::button', ['url' => config('app.url').'view-invoice/'.$data['order']['order_number']])
Lihat Pesanan
@endcomponent

# Cara Pembayaran

{!!nl2br(getSettings('general-settings','payment_instruction'))!!}

@endcomponent
