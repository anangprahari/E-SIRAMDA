<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 8px;
            width: 920px;
            height: 360px;
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
        }

        .outer {
            width: 100%;
            height: 100%;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            min-height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            border: 2px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 4px 6px;
            font-size: 14px;
        }

        /* COLUMN WIDTH */
        col.logo-left {
            width: 22%;
        }

        col.mid-left {
            width: 28%;
        }

        col.mid-right {
            width: 28%;
        }

        col.logo-right {
            width: 22%;
        }

        /* ROW HEIGHT */
        tr.hdr td {
            height: 55px;
        }

        tr.sub td {
            height: 45px;
        }

        tr.main td {
            height: 55px;
        }

        tr.foot td {
            height: 35px;
        }

        .logo {
            max-width: 95px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.3;
        }

        .bold {
            font-weight: bold;
        }

        .mono {
            font-family: "Courier New", monospace;
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    <div class="outer">
        <table>

            <colgroup>
                <col class="logo-left">
                <col class="mid-left">
                <col class="mid-right">
                <col class="logo-right">
            </colgroup>

            <!-- HEADER -->
            <tr class="hdr">
                <td rowspan="3">
                    <img src="{{ $logo_pemprov_path }}" class="logo">
                </td>
                <td colspan="2" class="title">
                    BARANG MILIK PEMERINTAH<br>
                    PROVINSI JAMBI
                </td>
                <td rowspan="3">
                    <img src="{{ $logo_kominfo_path }}" class="logo">
                </td>
            </tr>

            <!-- DISKOMINFO -->
            <tr class="sub">
                <td class="bold">DISKOMINFO</td>
                <td class="mono">2,16,2,20,2,21,01,0000</td>
            </tr>

            <!-- NAMA BARANG + FULL KODE BARANG (dengan register) -->
            <tr class="main">
                <td class="bold">{{ $nama_jenis_barang }}</td>
                <td class="mono">{{ $full_kode_barang }}</td>
            </tr>

            <!-- FOOTER -->
            <tr class="foot">
                <td colspan="4" class="bold">
                    {{ $ruangan }} {{ $tahun_perolehan }}.{{ $lokasi_barang }}
                </td>
            </tr>
        </table>
    </div>

</body>

</html>