<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap'); */

        * {
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .print-m-0 {
                margin: 0 !important;
            }
        }

        .btn {
            padding: 10px 17px;
            border-radius: 3px;
            background: #f4b71a;
            border: none;
            font-size: 12px;
            margin: 10px 5px;
        }

        .toolbar {
            background: #333;
            width: 100vw;
            position: fixed;
            left: 0;
            top: 0;
            text-align: center;
        }

        .cert-container {
            margin: 65px 0 10px 0;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .cert {
            width: 850px;
            height: 600px;
            padding: 15px 20px;
            text-align: center;
            position: relative;
            z-index: -1;
        }

        .cert-bg {
            position: absolute;
            left: 0px;
            top: 0;
            z-index: -1;
            width: 100%;
        }

        .cert-content {
            width: 700px;
            height: 450px;
            padding: 70px 60px 0px 160px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 44px;
        }

        p {
            font-size: 25px;
        }

        small {
            font-size: 14px;
            line-height: 12px;
        }

        .bottom-txt {
            padding: 0px 15px 0px 18px;
            display: flex;
            justify-content: space-between;
            font-size: 16px;
        }

        .bottom-txt * {
            white-space: nowrap !important;
        }

        /* .other-font {
            font-family: Cambria, Georgia, serif;
            font-style: italic;
        } */

        .ml-215 {
            margin-left: 215px;
        }

        .header {
            margin-top: 0px;
            margin-bottom: 7px;
        }

        .done {
            margin-top: 10px;
        }
    </style>

    <title>Document</title>
</head>

<body>
    <div class="cert-container print-m-0">
        <div id="content2" class="cert">
            <img src="https://printabletemplates.com/wp-content/uploads/2018/01/certificate-border-34.jpg" class="cert-bg" alt="" />

            <div class="cert-content">
                <img src="https://i.ibb.co/HGK24sH/brand-logo.png" alt="" width="70px" />
                <h1 class="header">Sertifikat Kelulusan</h1>
                <!-- <span style="font-size: 40px;">{{$data[0]->name}}</span> -->
                <span style="font-size: 35px;">Muhamad Khafidh Fuadi</span>
                <br />
                <div class="done"><i>telah menyelasaikan</i></div>
                <span style="font-size: 27px;"><b>Kelas {{$data[0]->pelajaran}}</b></span>

                <br /><br />
                <small>Berhasil menyelesaikan kelas kursus {{$data[0]->pelajaran}} di Al-Qolam Arabic Course dengan predikat : </small>
                <br /><br />
                <span style="font-size: 25px;">{{$predikat['arab']}}</span>
                <br />
                <span style="font-size: 13px;"><i>({{$predikat['latin']}})</i></span>
                <br />
                <br />

                <div class="bottom-txt">
                    <span>G-1 DAPE-ARR-SF</span>
                    <span>Telah Selesai Pada: {{$created_at}}</span>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>

</html>
