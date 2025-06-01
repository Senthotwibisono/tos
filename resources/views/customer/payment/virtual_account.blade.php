<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Virtual Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('logo/icon.png')}}" type="image/png">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full text-center">
        <div class="relative w-1/2 mx-auto mb-4">
            <!-- Gambar utama -->
            <img src="{{ asset('logo/icon.png') }}" alt="icon" class="w-full">
            <!-- Gambar cancel di pojok kanan atas -->
            @if($va->status == 'C')
              <img src="{{ asset('images/cancel.png') }}" alt="cancel" class="absolute top-20 left-20  rounded-full">
            @endif
            @if($va->status == 'Y')
              <img src="{{ asset('images/paid.png') }}" alt="cancel" class="absolute top-20 left-20  rounded-full">
            @endif
            @if($va->status == 'E')
              <img src="{{ asset('images/expired.png') }}" alt="cancel" class="absolute top-20 left-20  rounded-full">
            @endif
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-4">Pembayaran Virtual Account</h1>   

        <p class="text-gray-500 mb-2">Nomor Virtual Account</p>
        <div class="text-2xl font-mono text-blue-600 mb-4">
            {{ $va->virtual_account }}
        </div>  

        <p class="text-gray-500 mb-2">Waktu tersisa untuk membayar</p>
        @if($va->status == 'N')
          <div id="countdown" class="text-xl font-semibold text-red-500"></div>   
        @endif   
        <div class="mt-6">
          @if($va->status == 'N')
            <button onclick="copyVA()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl transition">
                Salin Nomor VA
            </button>
          @else
           <h1 class="text-2xl font-bold text-gray-800 mb-4">Transaksi telah ditutup</h1>   
          @endif
        </div>
        <br>
        <b>Rincian dan Total</b>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
              <tr>
                <th scope="col" class="px-4 py-3">Proforma</th>
                <th scope="col" class="px-4 py-3">Service</th>
                <th scope="col" class="px-4 py-3">Customer</th>
                <th scope="col" class="px-4 py-3">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($details as $detil)
                <tr style="white-space: nowrap;">
                  <td class="px-4 py-2">{{$detil->proforma_no}}</td>
                  <td class="px-4 py-2">{{$detil->invoice->os_name ?? '-'}}</td>
                  <td class="px-4 py-2">{{$detil->invoice->cust_name}}</td>
                  <td class="px-4 py-2">Rp. {{number_format(ceil($detil->invoice->grand_total),0)}}</td>
                </tr>
                @endforeach
              <!-- Baris lainnya -->
            </tbody>
          </table>
        </div>

        <p class="text-gray-500 mb-2">Total Tagihan</p>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Rp. {{number_format(ceil($va->billing_amount))}}</h1>
    </div>

    <script>
        const expiredAt = new Date("{{ \Carbon\Carbon::parse($va->expired_va)->format('Y-m-d H:i:s') }}").getTime();

        const countdown = () => {
            const now = new Date().getTime();
            const distance = expiredAt - now;   

            if (distance < 0) {
                document.getElementById("countdown").innerHTML = "Waktu habis";
                return;
            }   

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);    

            document.getElementById("countdown").innerHTML =
                `${hours}j ${minutes}m ${seconds}s`;
        };  

        setInterval(countdown, 1000);
        countdown(); // tampilkan langsung tanpa tunggu 1 detik 

        function copyVA() {
    const vaNumber = "{{ $va->virtual_account }}";

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(vaNumber).then(() => {
            alert("Nomor VA disalin!");
        }).catch(err => {
            alert("Gagal menyalin teks: " + err);
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement("textarea");
        textArea.value = vaNumber;
        // Avoid scrolling to bottom
        textArea.style.position = "fixed";
        textArea.style.top = 0;
        textArea.style.left = 0;
        textArea.style.width = "2em";
        textArea.style.height = "2em";
        textArea.style.padding = 0;
        textArea.style.border = "none";
        textArea.style.outline = "none";
        textArea.style.boxShadow = "none";
        textArea.style.background = "transparent";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if(successful) {
                alert("Nomor VA disalin!");
            } else {
                alert("Gagal menyalin teks.");
            }
        } catch (err) {
            alert("Gagal menyalin teks: " + err);
        }

        document.body.removeChild(textArea);
    }
}

    </script>

</body>
</html>
