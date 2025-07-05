<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<body>
    <h2>Terima kasih atas pesanan Anda!</h2>
    <p>Nomor Pesanan: {{ $order->id }}</p>
    <p>Total: Rp {{ number_format($order->total_amount) }}</p>
</body>
</html>
