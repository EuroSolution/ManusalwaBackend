<table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Customer Name</th>
            <th>Total</th>
            <th>Order Date</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($content as $order)
        <tr class="odd">
            <td>{{$order['order_number']}}</td>
            <td>{{$order['user']['name']}}</td>
            <td>{{$order['total_amount']}}</td>
            <td>{{date('d-M-Y H:i:s', strtotime($order['created_at']))}}</td>
            <td>{{$order['address']}}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>