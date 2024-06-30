<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        table{
            font-size: x-small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
        .gray {
            background-color: lightgray
        }
        .font{
        font-size: 15px;
        }
        .authority {
            /*text-align: center;*/
            float: right
        }
        .authority h5 {
            margin-top: -10px;
            color: green;
            /*text-align: center;*/
            margin-left: 35px;
        }
        .thanks p {
            color: green;;
            font-size: 16px;
            font-weight: normal;
            font-family: serif;
            margin-top: 20px;
        }
    </style>

</head>
<body>

    <table width="100%" style="background: #F7F7F7; padding:0 20px 0 20px;">
        <tr>
            <td valign="top">
                <!-- <img src="" alt="" width="150"/> -->
                <h2 style="color: green; font-size: 26px;"><strong>EasyShop</strong></h2>
            </td>
            <td align="right">
                <pre class="font" >
                    EasyShop Head Office
                    Email:support@easylearningbd.com <br>
                    Mob: 1245454545 <br>
                    Dhaka 1207,Dhanmondi:#4 <br>
                </pre>
            </td>
        </tr>

    </table>


    <table width="100%" style="background:white; padding:2px;"></table>

    <table width="100%" style="background: #F7F7F7; padding:0 5 0 5px;" class="font">
        <tr>
            <td>
                <p class="font" style="margin-left: 20px;">
                    <strong>Name:</strong> {{ $payment->name }} <br>
                    <strong>Email:</strong> {{ $payment->email }} <br>
                    <strong>Phone:</strong> {{ $payment->phone }} <br>
                    <strong>Address:</strong> {{ $payment->address }} <br>
                </p>
            </td>
            <td>
                <p class="font">
                    <h3><span style="color: green;">Invoice:</span> #{{ $payment->invoice_number }}</h3>
                    Order Date: {{ \Carbon\Carbon::parse($payment->created_at)->format('j F Y') }} <br>
                    Payment Type : {{ $payment->payment_type }} </span>
                </p>
            </td>
        </tr>
    </table>
    <br/>
    <h3>Products</h3>


    <table width="100%">
        <thead style="background-color: green; color:#FFFFFF;">
            <tr class="font">
                <th>Image</th>
                <th>Course Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Instructor</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payment->orders as $order)
                <tr class="font">
                    <td align="center">
                        <img src="{{ public_path('course/images/' . $order->course->image) }}" height="60px;" width="60px;" alt="">
                    </td>
                    <td align="center">{{ $order->course->name }}</td>
                    <td align="center">{{ $order->course->category->category_name }}</td>
                    <td align="center">{{ $order->course->subCategory->subCategory_name }}</td>
                    <td align="center">{{ $order->course->instructor->name }}</td>
                    <td align="center">
                        @if ($order->course->discount_price > 0)
                            ${{ $order->course->discount_price }}
                        @else
                            ${{ $order->course->selling_price }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table width="100%" style=" padding:0 10px 0 10px;">
        <tr>
            <td align="right" >
                <h2><span style="color: green;">Subtotal:</span> ${{ $payment->total_amount }}</h2>
                <h2><span style="color: green;">Total:</span> ${{ $payment->total_amount }}</h2>
                {{-- <h2><span style="color: green;">Full Payment PAID</h2> --}}
            </td>
        </tr>
    </table>
    <div class="thanks mt-3">
        <p>Thanks For Buying Course..!!</p>
    </div>
    <div class="authority float-right mt-5">
        <p>-----------------------------------</p>
        <h5>Authority Signature:</h5>
    </div>
</body>
</html>