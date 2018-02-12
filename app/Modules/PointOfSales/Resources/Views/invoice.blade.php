<html>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <link rel="stylesheet" href="https://unpkg.com/material-icons-base64"/>
        <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans:500" rel="stylesheet">
        <style>
            .invHeading h3{
                font-family: 'Alegreya Sans', sans-serif !important;
                font-weight: 500;
                color: #515151;
                font-size: 30px;
                margin-top: 60px;
            }
            .invHeading h4{
                font-size: 16px;
                color: #737373;
                margin-top: 0px;
                margin-bottom: 5px;
            }
            .invHeading{
                background: #f1f1f1;
                padding: 20px;
                min-height: 250px;
                padding-left: 60px;
                padding-right: 60px;
            }
            .theIcon{
                text-align:center;
            }
            .theIcon .border{
                width: 85px;
                margin: 0 auto;
                border-radius: 100%;
                border: 2px solid #aaa;
                margin-top: 80px;
            }
            .theIcon i.material-icons{
                font-size: 80px;
                color:#aaa;
            }
            .withSeparator{
                padding-top: 30px;
                margin-top: 30px;
                border-top: 1px solid #e5e5e5;
            }
            .totalPrice{
                margin: 0px;
                font-weight: 600;
                color: #337ab7;
            }
            .unpaid{
                text-align: center;
                padding: 14px;
                background: #d9534f;
                color: #fff;
                border-radius: 10px;
                font-size: 24px;
            }
            .paid{
                text-align: center;
                padding: 14px;
                background: #2e6da4;
                color: #fff;
                border-radius: 10px;
                font-size: 24px;
            }
            .canceled{
                text-align: center;
                padding: 14px;
                background: #909395;
                color: #fff;
                border-radius: 10px;
                font-size: 24px;
            }
            .cartList label{
                font-size: 12px;
                font-weight: 600;
                color: #123d7a;
                display: block;
                border-bottom: 1px solid #e1e1e1;
                padding-bottom: 10px;
            }
            .cartList .lists{
                padding: 45px;
                border-top: 1px solid #da4453;
            }
            .invFooter{
                padding: 20px 42px;
                background: #2e6da4;
                color: #fff;
                border-bottom: 11px solid #0d4371;
            }
            .invFooter h5{
                font-weight: 600;
                font-size: 17px;
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 2px solid #59a1e1;
            }
            div{
                page-break-inside: avoid!important;
            }
        </style>
    </head>
   <body>
        <div class="container" style="border: 1px solid #dbdbdb;">
            <div class="row invHeading">
                <!-- website Logo Area -->
                <div class="row">
                        <div class="col-xs-9">
                                <img src="{{url($data['websiteData']['logo'])}}">
                        </div>
                        <div class="col-xs-3">
                            <h1 class="{{$data['payment_status']}}">{{strtoupper($data['payment_status'])}}</h1>
                        </div>            
                </div>
                <div class="row">
                        <div class="col-xs-5">
                                <h3>{{$data['websiteData']['name']}}</h3>
                                <h4>{{$data['websiteData']['website']}}</h4>
                                <h4>{{$data['websiteData']['email']}}</h4>
                                <h4>{{$data['websiteData']['phone']}}</h4>
                                <h4>{{$data['websiteData']['address']}}</h4>
                            </div>
                            <div class="col-xs-2 theIcon">
                                <div class="border">
                                        <i class="material-icons">&#xE315;</i>
                                </div>
                            </div>
                            <div class="col-xs-5" style="text-align:right">
                                    <h3>{{$data['customerData']['name']}}</h3>
                                    <h4>{{$data['customerData']['phone']}}</h4>
                                    <h4>{{$data['customerData']['email']}}</h4>
                                    @if($data['customerData']['type']==1)
                                    <h3>{{$data['customerData']['company_name']}}</h3>
                                    <h4>{{$data['customerData']['company_phone']}}</h4>
                                    <h4>{{$data['customerData']['company_email']}}</h4>
                                    @endif
                            </div>
                </div>
                <div class="row withSeparator">
                    <!-- Invoice info block -->
                    <div class="col-xs-6">
                            <h4>Invoice</h4>
                            <h3 style="margin-top: 0px;font-size: 45px;margin-bottom: 0px;">{{$data['order_number']}}</h3>
                            <strong style="font-size: 13px;color: #848484;">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',  $data['created_at'])->format('F j, Y') }}</strong>
                    </div>
                    <div class="col-xs-6" style="text-align:right;">
                            <h4>Total</h4>
                            <h1 class="totalPrice">{{toCurrency($data['total'])}}</h1>
                    </div>
                </div>
            </div>
            <div class="row cartList">
            <!-- items -->
            @foreach($data['cart'] as $cart)
               <div class="col-xs-12 lists">
                        
                                <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <strong class="pname">{{$cart['name']}}</strong>
                                            <span class="pcode">[ {{$cart['product_code']}} ]</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                            <div class="form-group">
                                                <label>Qty</label>
                                                <strong>{{$cart['qty']}}</strong>
                                            </div>
                                    </div>
                                    <div class="col-xs-3">
                                            <div class="form-group">
                                                <label>@Price</label>
                                                <strong>{{toCurrency($cart['price'])}}</strong>
                                            </div>
                                    </div>
                                    <div class="col-xs-3">
                                            <div class="form-group">
                                                <label>Subtotal</label>
                                                <strong>{{toCurrency($cart['subtotal'])}}</strong>
                                            </div>
                                    </div>
                                    <div class="col-xs-4">
                                            <div class="form-group">
                                                <label>Sender Name</label>
                                                <strong>{{$cart['sender_name']}}</strong>
                                            </div>
                                            <div class="form-group">
                                                    <label>Receiver Name</label>
                                                    <strong>{{$cart['receiver_name']}}</strong>
                                                </div>
                                            <div class="form-group">
                                                <label>Shipping Expedition</label>
                                                <strong>{{$cart['shipping_expedition']}}</strong>
                                            </div>
                                            <div class="form-group">
                                                <label>Shipping Cost</label>
                                                <strong>{{toCurrency($cart['shipping_cost'])}}</strong>
                                            </div>
                                    </div>
                                    <div class="col-xs-4">
                                            <div class="form-group">
                                                <label>Greetings</label>
                                                <p>{{$cart['greetings']}}</p>
                                            </div>
                                            <div class="form-group">
                                                    <label>Receiver Phone</label>
                                                    <strong>{{$cart['receiver_phone']}}</strong>
                                                </div>
                                            <div class="form-group">
                                                <label>Shipping Country</label>
                                                <strong>{{$cart['geo_country']['name']}}</strong>
                                            </div>
                                            <div class="form-group">
                                                    <label>Shipping Province</label>
                                                    <strong>{{$cart['geo_province']['name']}}</strong>
                                            </div>
                                    </div>
                                    <div class="col-xs-4">
                                            <div class="form-group">
                                                    <label>Notes</label>
                                                    <p>{{$cart['notes']}}</p>
                                                </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <p>{{$cart['shipping_address']}}</p>
                                            </div>
                                            <div class="form-group">
                                                    <label>Shipping City</label>
                                                    <strong>{{$cart['geo_city']['name']}}</strong>
                                            </div>
                                            <div class="form-group">
                                                    <label>Delivery Date & Time</label>
                                                    <strong>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',  $cart['date_time'])->format('F j, Y') }}</strong>
                                            </div>
                                    </div>
                                    <div class="clearfix"></div>
                      
                        <div class="clearfix"></div>
                </div>
            @endforeach
            </div>
            <div class="row invFooter">
                <div class="col-xs-12">
                    <h5>Payment Instruction</h5>
                    <p>{!!nl2br(getSettings('general-settings','payment_instruction'))!!}</p>
                </div>
            </div>
        </div>
    </body>
</html>