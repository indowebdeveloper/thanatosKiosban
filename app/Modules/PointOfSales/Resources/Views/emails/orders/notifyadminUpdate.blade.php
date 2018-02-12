@component('mail::message')

@slot('logo')
{{$data['website']['logo']}}
@endslot

# Update Pesanan

Sales baru saja melakukan update pesanan dengan nomor invoice **#{{$data['order']['order_number']}}**.

Dengan total transaksi sebesar **{{toCurrency($data['order']['total'])}}**

Rincian pemesanan dapat dilihat dengan mengunduh file pdf yang terlampir pada email ini, 
atau dengan melihat secara langsung didalam sistem dengan mencari nomor invoice diatas

Atau anda juga dapat melihat pesanan dengan menekan tombol dibawah ini

@component('mail::button', ['url' => config('app.url').'view-invoice/'.$data['order']['order_number']])
Lihat Pesanan
@endcomponent

@endcomponent
