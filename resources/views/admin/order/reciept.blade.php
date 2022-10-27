<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Order Receipt</title>
    <style>
        * {
            font-family: monospace;
            color: rgb(106, 106, 106);
        }

        #main {
            padding-top: 10px;
            padding-right: 40px;
            padding-bottom: 10px;
            padding-left: 40px;
        }

        .restaurant-logo {
            display: block;
            margin-top: auto;
            margin-right: auto;
            margin-bottom: auto;
            margin-left: auto;
        }

        .block {
            display: block;
        }

        .text-center {
            text-align: center;
        }

        .heading-lg {
            font-size: 32px;
        }

        .mb-1 {
            margin-bottom: 1rem;
        }

        .mb-2 {
            margin-bottom: 2rem;
        }

        .fs-1 {
            font-size: 1.0rem;
        }

        .fs-2 {
            font-size: 1.5rem;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .table {
            width: 100%;
        }

        table {
            border-collapse: collapse;
            border-bottom: 2px dashed;
            border-color: rgb(red, green, blue);
            padding-bottom: 20px;
        }

        th {
            border-bottom: 2px dashed;
            border-color: rgb(red, green, blue);
            padding-bottom: 20px;
        }

        tbody td {
            padding-top: 5px;
        }


    </style>

</head>

<body>

<div id="main">
    <div>
        <div style="text-align: center;">
            <img class="restaurant-logo" src="{{$logo}}" width="120" style="filter: grayscale()"/>
        </div>
        <div class="mb-1">
            <span class="block heading-lg">Order</span>
            <span class="block heading-lg">Receipt #{{$order_number}}</span>
        </div>

        <div class="mb-1">
            <span class="block fs-1 ">{{$date}}</span>
            <span class="block fs-1 ">Address: {{$address}}</span>
        </div>

        <div class="mb-1">
            <span class="block fs-1"><span>Customer</span>&nbsp;: {{$customer}}</span>
            <span class="block fs-1 "><span>Phone</span> &nbsp; &nbsp;: {{$phone}}</span>
        </div>
        <table class="table fs-1">
            <tr>
                <th class="text-left">Item</th>
                <th class="text-left">Price</th>
                <th>Qty</th>
                <th class="text-right">Total</th>
            </tr>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{$item['product'] ?? ''}}</td>
                    <td>{{$currency.$item['price']}}</td>
                    <td class="text-right">{{$item['quantity']}}</td>
                    <td class="text-right">{{$currency.$item['total']}}</td>
                </tr>
            @endforeach
            @foreach($deals as $deal)
                <tr>
                    <td>{{$deal['product'] ?? ''}}</td>
                    <td>{{$currency.$deal['price']}}</td>
                    <td class="text-right">{{$deal['quantity']}}</td>
                    <td class="text-right">{{$currency. $deal['total']}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>

        <table  class="table fs-1" style="width: 80%; margin: auto; margin-top: 10px; border-bottom: 0px;">
            <tbody>
            <tr>
                <td class="text-right">Sub Total : </td>
                <td class="text-right">{{$currency.$subtotal}}</td>
            </tr>
            <tr>
                <td class="text-right">Tax : </td>
                <td class="text-right">{{$currency.$tax}}</td>
            </tr>

            @if($delivery_fee > 0)
                <tr>
                    <td class="text-right">Delivery Charges : </td>
                    <td class="text-right">{{$currency.$delivery_fee}}</td>
                </tr>
            @endif
            @if($discount > 0)
                <tr>
                    <td class="text-right">Discount : </td>
                    <td class="text-right">{{$currency.$discount}}</td>
                </tr>
            @endif

            <tr>
                <td class="text-right">Order Total : </td>
                <td class="text-right">{{$currency.$total_amount}}</td>
            </tr>
            </tbody>
        </table>

    </div>
</div>

    <div style="width: 50%; margin: auto; text-align: center; margin-top: 30px">
        <span class="block" style="padding-top: 40px">********************</span>
    </div>
    <h1></h1>
    <div>
        <span class="block fs-1 text-center">Thank You!</span>
    </div>
    <h1></h1>
</body>

</html>
