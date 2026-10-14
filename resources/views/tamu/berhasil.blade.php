<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('kpu-logo.png') }}?v=20261005">

    <title>Kunjungan Berhasil | KPU Kota Ternate</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Inter, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, #e8eef7, transparent 35%),
                #f4f7fb;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #172b4d;
        }

        .container {
            width: 100%;
            max-width: 550px;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 45px 40px;
            text-align: center;

            border: 1px solid #e6ebf2;

            box-shadow:
                0 25px 70px rgba(20, 40, 70, .10);
        }

        .logo {
            width: 65px;
            height: 65px;

            margin: 0 auto 25px;

            border-radius: 18px;

            background: #071f3d;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;
            font-weight: 900;

            box-shadow:
                0 12px 25px rgba(7, 31, 61, .20);
        }

        .success {
            width: 78px;
            height: 78px;

            margin: 0 auto 25px;

            border-radius: 50%;

            background: #ecfdf3;
            border: 8px solid #f0fdf4;

            color: #16834b;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 35px;
            font-weight: 900;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 12px;
        }

        .subtitle {
            color: #718096;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .message {
            background: #f6f9fc;

            border: 1px solid #e7edf4;

            border-radius: 14px;

            padding: 16px;

            font-size: 13px;

            color: #52647c;

            margin-bottom: 25px;
        }

        .message strong {
            color: #263b57;
        }

        .btn {
            display: block;

            width: 100%;

            padding: 14px;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #b00821,
                    #8d071a
                );

            color: white;

            text-decoration: none;

            font-size: 14px;

            font-weight: 700;

            box-shadow:
                0 10px 25px rgba(176, 8, 33, .20);

            transition: .2s;
        }

        .btn:hover {
            transform: translateY(-2px);

            box-shadow:
                0 14px 28px rgba(176, 8, 33, .28);
        }

        .footer {
            margin-top: 25px;

            font-size: 11px;

            color: #9aa7b7;

            line-height: 1.6;
        }

        @media(max-width: 500px) {

            .card {
                padding: 35px 22px;
            }

            h1 {
                font-size: 24px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <div class="logo">
            KPU
        </div>

        <div class="success">
            <svg aria-hidden="true" width="34" height="34" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 12 4 4L19 6"/></svg>
        </div>

        <h1>
            Kunjungan Berhasil
        </h1>

        <p class="subtitle">

            Terima kasih telah melakukan kunjungan
            ke Komisi Pemilihan Umum Kota Ternate.

        </p>

        <div class="message">

            <strong>
                Data kunjungan Anda telah berhasil dicatat.
            </strong>

            <br><br>

            Silakan kembali ke halaman Buku Tamu
            apabila ingin melakukan pencatatan kunjungan lainnya.

        </div>

        <a
            href="{{ route('tamu.index') }}"
            class="btn"
        >
            ← Kembali ke Buku Tamu
        </a>

        <div class="footer">

            Komisi Pemilihan Umum Kota Ternate<br>

            Sistem Informasi Buku Tamu Digital

        </div>

    </div>

</div>

@include('layouts.loading')
</body>

</html>