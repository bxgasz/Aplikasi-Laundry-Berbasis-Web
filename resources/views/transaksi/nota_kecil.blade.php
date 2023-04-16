<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>
    <?php 
        $style ='
        <style>
        *{
            font-family: "consolas", sans-serif;
        }
        p{
            display: block;
            margin: 3;
            font-size: 10pt;
        }

        .table td{
            font-size: 9pt;
        }

        .text-center{
            text-align: center;
        }

        .text-right{
            text-align: right;
        }

        @media print {
            @page{
                margin: 0;
                size: 75mm 
    
        ';
    ?>
    <?php 
        $style .= 
        ! empty($_COOKIE['innerHeight']) ? $_COOKIE['innerHeight'] . 'mm; }' : '}'; 
    ?>
    <?php
        $style .= '
        html, body{
            width:70mm;
        }

        .btn-print{
            display:none;
        }
    }
</style>
        '
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem" onclick="window.print()">Print</button>
    <div class="text-center">
        <h4 style="margin-bottom: 5px;">{{ strtoupper($setting->nama_perusahaan) }}</h4>
        <p>{{ strtoupper($setting->alamat) }}</p>
    </div>
    <br>
    <div class="">
        <p style="float: left;">{{  date('d-m-Y') }}</p>
        <p style="float: right;">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    <div class="clear-both" style="clear: both"></div>
    <p>No : {{ tambah_nol_didepan($transaksi->id_transaksi, 8) }}</p>
    <p class="text-center">===================================</p>
    <table width="100%" style="border: 0">
        @foreach ($detail as $item)
            <tr>
                <td colspan="2">{{ $item->paket->nama_paket }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty }} x {{ format_uang($item->subtotal) }}</td>
                <td></td>
                <td class="text-right">{{ format_uang($item->qty * $item->paket->harga) }}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">===================================</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Harga :</td>
            <td class="text-right">{{ format_uang($transaksi->total_bayar) }}</td>
        </tr>
        <tr>
            <td>Pajsk :</td>
            <td class="text-right">{{ format_uang($transaksi->pajak) }}</td>
        </tr>
        <tr>
            <td>Total Bayar :</td>
            <td class="text-right">{{ format_uang($transaksi->total_bayar) }}</td>
        </tr>
        <tr>
            <td>Diterima :</td>
            <td class="text-right">{{ format_uang($transaksi->pajak) }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
        document.cookie = "innerHeight=; expire=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight=" + ((height) * 0.264583);
    </script>
</body>
</html>