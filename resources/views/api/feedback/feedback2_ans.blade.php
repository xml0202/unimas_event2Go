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
                    
                <?php
        $ans = "";
            foreach ($feedback_ans as $value)
                $q1ans1 = $value->q1ans1;
                $q1ans2 = $value->q1ans2;
                $q1ans3 = $value->q1ans3;
                $q1ans4 = $value->q1ans4;
                $q1ans5 = $value->q1ans5;
                $q1ans6 = $value->q1ans6;
                $q2ans1 = $value->q2ans1;
                $q2ans2 = $value->q2ans2;
                $q2ans3 = $value->q2ans3;
                $q3ans1 = $value->q3ans1;
                $q4ans1 = $value->q4ans1;
            ?>
                </div>
                
                
                <form id="form" method="POST" action="{{route('feedback2.save')}}">
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
                                        <td width = "30%" >Memenuhi objektif yang disasarkan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-1" name="Memenuhi" class="custom-control-input" value="1" <?php echo is_checked($q1ans1, "1") ?> disabled>
                                                <label class="custom-control-label" for="Memenuhi-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-2" name="Memenuhi" class="custom-control-input" value="2" <?php echo is_checked($q1ans1, "2") ?> disabled>
                                                <label class="custom-control-label" for="Memenuhi-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-3" name="Memenuhi" class="custom-control-input" value="3" <?php echo is_checked($q1ans1, "3") ?> disabled>
                                                <label class="custom-control-label" for="Memenuhi-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-4" name="Memenuhi" class="custom-control-input" value="4" <?php echo is_checked($q1ans1, "4") ?> disabled>
                                                <label class="custom-control-label" for="Memenuhi-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Memenuhi-5" name="Memenuhi" class="custom-control-input" value="5" <?php echo is_checked($q1ans1, "5") ?> disabled>
                                                <label class="custom-control-label" for="Memenuhi-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Memenuhi kepuasan pengguna</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="kepuasan-1" name="kepuasan" class="custom-control-input" value="1" <?php echo is_checked($q1ans2, "1") ?> disabled>
                                                <label class="custom-control-label" for="kepuasan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="kepuasan-2" name="kepuasan" class="custom-control-input" value="2" <?php echo is_checked($q1ans2, "2") ?> disabled>
                                                <label class="custom-control-label" for="kepuasan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="kepuasan-3" name="kepuasan" class="custom-control-input" value="3" <?php echo is_checked($q1ans2, "3") ?> disabled>
                                                <label class="custom-control-label" for="kepuasan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="kepuasan-4" name="kepuasan" class="custom-control-input" value="4" <?php echo is_checked($q1ans2, "4") ?> disabled>
                                                <label class="custom-control-label" for="kepuasan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="kepuasan-5" name="kepuasan" class="custom-control-input" value="5" <?php echo is_checked($q1ans2, "5") ?> disabled>
                                                <label class="custom-control-label" for="kepuasan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Bersifat mesra pengguna dan mudah difahami</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mesra-1" name="mesra" class="custom-control-input" value="1" <?php echo is_checked($q1ans3, "1") ?> disabled>
                                                <label class="custom-control-label" for="mesra-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mesra-2" name="mesra" class="custom-control-input" value="2" <?php echo is_checked($q1ans3, "2") ?> disabled>
                                                <label class="custom-control-label" for="mesra-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mesra-3" name="mesra" class="custom-control-input" value="3" <?php echo is_checked($q1ans3, "3") ?> disabled>
                                                <label class="custom-control-label" for="mesra-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mesra-4" name="mesra" class="custom-control-input" value="4" <?php echo is_checked($q1ans3, "4") ?> disabled>
                                                <label class="custom-control-label" for="mesra-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="mesra-5" name="mesra" class="custom-control-input" value="5" <?php echo is_checked($q1ans3, "5") ?> disabled>
                                                <label class="custom-control-label" for="mesra-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Membantu urusan menjadi pantas dan efisyen</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="urusan-1" name="urusan" class="custom-control-input" value="1" <?php echo is_checked($q1ans4, "1") ?> disabled>
                                                <label class="custom-control-label" for="urusan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="urusan-2" name="urusan" class="custom-control-input" value="2" <?php echo is_checked($q1ans4, "2") ?> disabled>
                                                <label class="custom-control-label" for="urusan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="urusan-3" name="urusan" class="custom-control-input" value="3" <?php echo is_checked($q1ans4, "3") ?> disabled>
                                                <label class="custom-control-label" for="urusan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="urusan-4" name="urusan" class="custom-control-input" value="4" <?php echo is_checked($q1ans4, "4") ?> disabled>
                                                <label class="custom-control-label" for="urusan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="urusan-5" name="urusan" class="custom-control-input" value="5" <?php echo is_checked($q1ans4, "5") ?> disabled>
                                                <label class="custom-control-label" for="urusan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Masih boleh dibaiki untuk masa hadapan</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="dibaiki-1" name="dibaiki" class="custom-control-input" value="1" <?php echo is_checked($q1ans5, "1") ?> disabled>
                                                <label class="custom-control-label" for="dibaiki-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="dibaiki-2" name="dibaiki" class="custom-control-input" value="2" <?php echo is_checked($q1ans5, "2") ?> disabled>
                                                <label class="custom-control-label" for="dibaiki-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="dibaiki-3" name="dibaiki" class="custom-control-input" value="3" <?php echo is_checked($q1ans5, "3") ?> disabled>
                                                <label class="custom-control-label" for="dibaiki-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="dibaiki-4" name="dibaiki" class="custom-control-input" value="4" <?php echo is_checked($q1ans5, "4") ?> disabled>
                                                <label class="custom-control-label" for="dibaiki-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="dibaiki-5" name="dibaiki" class="custom-control-input" value="5" <?php echo is_checked($q1ans5, "5") ?> disabled>
                                                <label class="custom-control-label" for="dibaiki-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Memberi manfaat kepada komuniti</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-1" name="manfaat" class="custom-control-input" value="1" <?php echo is_checked($q1ans5, "1") ?> disabled>
                                                <label class="custom-control-label" for="manfaat-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-2" name="manfaat" class="custom-control-input" value="2" <?php echo is_checked($q1ans5, "2") ?> disabled>
                                                <label class="custom-control-label" for="manfaat-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-3" name="manfaat" class="custom-control-input" value="3" <?php echo is_checked($q1ans5, "3") ?> disabled>
                                                <label class="custom-control-label" for="manfaat-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-4" name="manfaat" class="custom-control-input" value="4" <?php echo is_checked($q1ans5, "4") ?> disabled>
                                                <label class="custom-control-label" for="manfaat-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="manfaat-5" name="manfaat" class="custom-control-input" value="5" <?php echo is_checked($q1ans5, "5") ?> disabled>
                                                <label class="custom-control-label" for="manfaat-5"></label>
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
                                                <input type="radio" id="Pengetahuan-1" name="Pengetahuan" class="custom-control-input" value="1" <?php echo is_checked($q2ans1, "1") ?> disabled>
                                                <label class="custom-control-label" for="Pengetahuan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-2" name="Pengetahuan" class="custom-control-input" value="2" <?php echo is_checked($q2ans1, "2") ?> disabled>
                                                <label class="custom-control-label" for="Pengetahuan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-3" name="Pengetahuan" class="custom-control-input" value="3" <?php echo is_checked($q2ans1, "3") ?> disabled>
                                                <label class="custom-control-label" for="Pengetahuan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-4" name="Pengetahuan" class="custom-control-input" value="4" <?php echo is_checked($q2ans1, "4") ?> disabled>
                                                <label class="custom-control-label" for="Pengetahuan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pengetahuan-5" name="Pengetahuan" class="custom-control-input" value="5" <?php echo is_checked($q2ans1, "5") ?> disabled>
                                                <label class="custom-control-label" for="Pengetahuan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pembentangan hasil produk</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-1" name="Pembentangan" class="custom-control-input" value="1" <?php echo is_checked($q2ans2, "1") ?> disabled>
                                                <label class="custom-control-label" for="Pembentangan-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-2" name="Pembentangan" class="custom-control-input" value="2" <?php echo is_checked($q2ans2, "2") ?> disabled>
                                                <label class="custom-control-label" for="Pembentangan-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-3" name="Pembentangan" class="custom-control-input" value="3" <?php echo is_checked($q2ans2, "3") ?> disabled>
                                                <label class="custom-control-label" for="Pembentangan-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-4" name="Pembentangan" class="custom-control-input" value="4" <?php echo is_checked($q2ans2, "4") ?> disabled>
                                                <label class="custom-control-label" for="Pembentangan-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="Pembentangan-5" name="Pembentangan" class="custom-control-input" value="5" <?php echo is_checked($q2ans2, "5") ?> disabled>
                                                <label class="custom-control-label" for="Pembentangan-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pengurusan masa</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-1" name="masa" class="custom-control-input" value="1" <?php echo is_checked($q2ans3, "1") ?> disabled>
                                                <label class="custom-control-label" for="masa-1"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-2" name="masa" class="custom-control-input" value="2" <?php echo is_checked($q2ans3, "2") ?> disabled>
                                                <label class="custom-control-label" for="masa-2"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-3" name="masa" class="custom-control-input" value="3" <?php echo is_checked($q2ans3, "3") ?> disabled>
                                                <label class="custom-control-label" for="masa-3"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-4" name="masa" class="custom-control-input" value="4" <?php echo is_checked($q2ans3, "4") ?> disabled>
                                                <label class="custom-control-label" for="masa-4"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="masa-5" name="masa" class="custom-control-input" value="5" <?php echo is_checked($q2ans3, "5") ?> disabled>
                                                <label class="custom-control-label" for="masa-5"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                    
                    <!-- QUESTION 3 -->
                    <tr>
                        <div class="table-responsive">
                            <p> Sila nyatakan manfaat/kebaikan produk ini. </p>
                            <label for="fname">Bagaimana latihan ini dapat menyumbang ke arah peningkatan kerja-kerja anda pada masa hadapan?</label><br>
                             <textarea name="Text1" cols="150" rows="5" disabled> <?php echo $q3ans1 ?> </textarea><br><br>
                        </div>
                    </tr>
                    
                    <!-- QUESTION 4 -->
                    <tr>
                        <div class="table-responsive">
                            <label for="fname">Cadangan lain, komen dan sebagainya.</label><br>
                             <textarea name="Text2" cols="150" rows="5" disabled> <?php echo $q4ans1 ?> </textarea><br><br>
                        </div>
                    </tr>    
                </ul>
                <div><button class="btn btn-primary" type="submit" disabled>Submit</button></div>
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