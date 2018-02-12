@component('mail::message')

@slot('logo')
{{$data['website']['logo']}}
@endslot

# Konfirmasi Peluanasan Pembayaran

Halo **{{$data['customerData']['name']}}**

Sebelumnya, kami mengucapkan terimakasih karena telah melakukan pelunasan untuk pemesanan anda di **{{$data['website']['name']}}**. 
Oleh karena itu email ini merupakan bukti pelunasan anda, dengan nomor invoice **#{{$data['order']['order_number']}}**.

Dengan total transaksi sebesar **{{toCurrency($data['order']['total'])}}**

Rincian pemesanan anda dapat dilihat dengan mengunduh file pdf yang terlampir pada email ini, 
atau dengan melihat secara langsung dengan menekan tombol dibawah ini

@component('mail::button', ['url' => config('app.url').'view-invoice/'.$data['order']['order_number']])
Lihat Pesanan
@endcomponent

@endcomponent
