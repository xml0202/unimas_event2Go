<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <!--<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">-->
        <link rel="stylesheet" type="text/css" href="{{ asset('css.css') }}">
        <!-- Bootstrap Core CSS -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap.min.css') }}">
    </head>
    <body>
       
            
        <div class="container mt-2">
            <div class="card">
                <div class="card-body" style="-webkit-box-flex: 1;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">
                    <h5 class="card-title">Feedback Form</h5>
          
                    <p class="card-text">Kami ingin tahu sejauh mana latihan ini telah memenuhi jangkaan anda.Penilaiaan ini akan membantu kami untuk meningkatkan format dan kandungan sesi latihan. Maklum balas anda amat dihargai.</p>
                    
               
                </div>
                

                <form id="form" method="POST" action="{{route('feedback.save')}}">
                    @csrf
                <ul class="list-group list-group-flush">
                    
                    <input type="hidden" id="event_id" name="event_id" value = {{ $event_id}} >
            
                    <input type="hidden" id="user_id" name="user_id" value = {{ $user_id}} >
                    
                    <!-- QUESTION 1 -->
                   <li class="list-group-item">
                        <div class="table-responsive">
                            <label class="mb-0">I.	PENYAMPAIAN LATIHAN (1 = Sangat Tidak Memuaskan, 5 = Sangat Memuaskan)</label>
                            
                            <br/><br/><table class="table table-hover table-border">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="30%">Memenuhi objektif yang disasarkan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-1" name="Memenuhi" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="Memenuhi-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-2" name="Memenuhi" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="Memenuhi-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-3" name="Memenuhi" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="Memenuhi-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-4" name="Memenuhi" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="Memenuhi-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-5" name="Memenuhi" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="Memenuhi-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kesesuaian topik/kandungan latihan dengan objektif latihan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Kesesuaian-1" name="Kesesuaian" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="Kesesuaian-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Kesesuaian-2" name="Kesesuaian" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="Kesesuaian-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Kesesuaian-3" name="Kesesuaian" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="Kesesuaian-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Kesesuaian-4" name="Kesesuaian" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="Kesesuaian-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Kesesuaian-5" name="Kesesuaian" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="Kesesuaian-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kesesuaian masa yang diperuntukkan untuk setiap topik/kandungan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="diperuntukkan-1" name="diperuntukkan" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="diperuntukkan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="diperuntukkan-2" name="diperuntukkan" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="diperuntukkan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="diperuntukkan-3" name="diperuntukkan" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="diperuntukkan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="diperuntukkan-4" name="diperuntukkan" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="diperuntukkan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="diperuntukkan-5" name="diperuntukkan" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="diperuntukkan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kualiti dokumentasi/modul/rujukan yang digunakan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="modul-1" name="modul" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="modul-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="modul-2" name="modul" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="modul-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="modul-3" name="modul" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="modul-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="modul-4" name="modul" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="modul-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="modul-5" name="modul" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="modul-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>

                    <!-- QUESTION 2 -->
                   <li class="list-group-item">
                        <div class="table-responsive">
                            <label class="mb-0">II.	SUMBER MANUSIA (1 = Sangat Tidak Memuaskan, 5 = Sangat Memuaskan)</label> <br />
                            <table class="table table-hover table-border">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="30%">Pengetahuan dan kepakaran</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-1" name="Pengetahuan" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="Pengetahuan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-2" name="Pengetahuan" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="Pengetahuan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-3" name="Pengetahuan" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="Pengetahuan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-4" name="Pengetahuan" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="Pengetahuan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-5" name="Pengetahuan" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="Pengetahuan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pembentangan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-1" name="Pembentangan" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="Pembentangan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-2" name="Pembentangan" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="Pembentangan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-3" name="Pembentangan" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="Pembentangan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-4" name="Pembentangan" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="Pembentangan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-5" name="Pembentangan" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="Pembentangan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pengurusan masa</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-1" name="masa" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="masa-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-2" name="masa" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="masa-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-3" name="masa" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="masa-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-4" name="masa" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="masa-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-5" name="masa" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="masa-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Interaksi dengan peserta</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Interaksi-1" name="Interaksi" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="Interaksi-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Interaksi-2" name="Interaksi" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="Interaksi-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Interaksi-3" name="Interaksi" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="Interaksi-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Interaksi-4" name="Interaksi" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="Interaksi-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Interaksi-5" name="Interaksi" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="Interaksi-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Persediaan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Persediaan-1" name="Persediaan" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="Persediaan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Persediaan-2" name="Persediaan" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="Persediaan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Persediaan-3" name="Persediaan" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="Persediaan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Persediaan-4" name="Persediaan" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="Persediaan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Persediaan-5" name="Persediaan" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="Persediaan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>

                    <!-- QUESTION 3 -->
                    <li class="list-group-item">
                        <div class="table-responsive">
                            <label class="mb-0">III.	LATIHAN (1 = Sangat Tidak Memuaskan, 5 = Sangat Memuaskan)</label> <br />
                            <table class="table table-hover table-border">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="30%">Pengurusan latihan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengurusan-1" name="Pengurusan" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="Pengurusan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengurusan-2" name="Pengurusan" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="Pengurusan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengurusan-3" name="Pengurusan" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="Pengurusan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengurusan-4" name="Pengurusan" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="Pengurusan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengurusan-5" name="Pengurusan" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="Pengurusan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>

                    <!-- QUESTION 4 -->
                    <li class="list-group-item">
                        <div class="table-responsive">
                            <label class="mb-0">IV.	LAIN-LAIN (1 = Sangat Tidak Memuaskan, 5 = Sangat Memuaskan)</label> <br />
                            <table class="table table-hover table-border">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="30%">Saya boleh mengaplikasikan apa yang telah saya pelajari di dalam latihan ini kepada komuniti/masyarakat</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masyarakat-1" name="masyarakat" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="masyarakat-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masyarakat-2" name="masyarakat" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="masyarakat-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masyarakat-3" name="masyarakat" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="masyarakat-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masyarakat-4" name="masyarakat" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="masyarakat-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masyarakat-5" name="masyarakat" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="masyarakat-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Saya telah mendapat manfaat daripada latihan ini</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-1" name="manfaat" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="manfaat-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-2" name="manfaat" class="custom-control-input" value="2" required>
                                                <label class="custom-control-label" for="manfaat-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-3" name="manfaat" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="manfaat-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-4" name="manfaat" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="manfaat-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-5" name="manfaat" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="manfaat-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Saya akan mencadangkan latihan seperti ini kepada rakan-rakan dan kenalan saya</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mencadangkan-1" name="mencadangkan" class="custom-control-input" value="1" required>
                                                <label class="custom-control-label" for="mencadangkan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mencadangkan-2" name="mencadangkan" class="custom-control-input" value="2" required> 
                                                <label class="custom-control-label" for="mencadangkan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mencadangkan-3" name="mencadangkan" class="custom-control-input" value="3" required>
                                                <label class="custom-control-label" for="mencadangkan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mencadangkan-4" name="mencadangkan" class="custom-control-input" value="4" required>
                                                <label class="custom-control-label" for="mencadangkan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mencadangkan-5" name="mencadangkan" class="custom-control-input" value="5" required>
                                                <label class="custom-control-label" for="mencadangkan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                    
                    <!-- QUESTION 5 -->
                    <tr>
                        <div class="table-responsive">
                            <p> Sila nyatakan manfaat/kebaikan latihan ini. </p>
                            <label for="fname">Bagaimana latihan ini dapat menyumbang ke arah peningkatan kerja-kerja anda pada masa hadapan?</label><br>
                             <textarea name="Text1" cols="150" rows="5"></textarea><br><br>
                        </div>
                    </tr>
                    
                    <!-- QUESTION 6 -->
                    <tr>
                        <div class="table-responsive">
                            <label for="fname">Cadangan lain, komen dan sebagainya.</label><br>
                             <textarea name="Text2" cols="150" rows="5"></textarea><br><br>
                        </div>
                    </tr>    
                </ul>
                <div><button class="btn btn-primary" type="submit">Submit</button></div>
            </form>
            </div>
        </div>
    </body>
    
    <?php
    function is_checked($db_value, $html_value){
  if($db_value == $html_value){
    return "checked";
  }
  else{
    return "";
  }
}
    ?>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type="text/javascript">
        console.log('Doc Ready');
        $(document).ready(function() {

            $("#customCheck7").change(function() {
                triggerJoinTxtArea();
            });

            function triggerJoinTxtArea() {
                if($('#customCheck7').prop('checked')) {
                    $('#joinTxtArea').removeClass('d-none');
                } else {
                    console.log('test2');
                    $('#joinTxtArea').addClass('d-none');
                }
            }
            
        });
    </script>
</html>