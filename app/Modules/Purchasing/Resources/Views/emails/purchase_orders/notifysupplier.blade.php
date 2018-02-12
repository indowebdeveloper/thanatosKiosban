@component('mail::message')

@slot('logo')
{{$data['website']['logo']}}
@endslot

# Pesanan baru

Mohon untuk memproses pesanan # **{{$data['order']['order_number']}}#{{$data['po']['id']}}**.

Dengan rincian sebagai berikut :

@component('mail::table')
[pimage]: data:image/png;base64,{{base64_encode(file_get_contents(public_path($data['po']['image'])))}}
| | | 
|-----|----|
| **Gambar Produk** | ![][pimage] |
| **Nama Produk** | {{$data['po']['product_name']}} |
| **Kode Produk** | {{$data['po']['product_code']}} |
| **Nama Pengirim** | {{$data['po']['sender_name']}} |
| **Telepon Pengirim** | {{$data['po']['sender_phone']}} |
| **Nama Penerima** | {{$data['po']['receiver_name']}} |
| **Telepon Penerima** | {{$data['po']['receiver_phone']}} |
| **Ucapan** | {{$data['po']['greetings']}} |
| **Notes** | {{$data['po']['notes']}} |
| **Alamat** | {{$data['po']['shipping_address']}} |
| **Kota** | {{$data['pr']['geo_city']['name']}} |
| **Provinsi** | {{$data['pr']['geo_province']['name']}} |
| **Negara** | {{$data['pr']['geo_country']['name']}} |
| **Tgl Pengiriman** | {{$data['po']['date_time']}} |
| **Qty** | {{$data['po']['qty']}} |
| **Harga @** | {{toCurrency($data['po']['real_price'])}} |
| **Total** | {{toCurrency($data['po']['total'])}} |
@endcomponent

@endcomponent
