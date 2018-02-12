@component('mail::message')

@slot('logo')
{{$data['website']['logo']}}
@endslot

# Purchase Order Baru

Tim Purchasing baru saja membuat Purchase Order Baru dengan ID # **{{$data['order']['order_number']}}#{{$data['po']['id']}}**.

PO ini merupakan PO Dari : 
Invoice : #**{{$data['order']['order_number']}}**.
PR ID   : #**{{$data['pr']['id']}}**.

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
| **Harga @ ( Sistem )** | {{toCurrency($data['po']['capital_price'])}} |
| **Harga @ ( Supplier )** | {{toCurrency($data['po']['real_price'])}} |
| **Total** | {{toCurrency($data['po']['total'])}} |
@endcomponent

@endcomponent
