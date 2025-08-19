<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <!--<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">-->
        <!--<link rel="stylesheet" type="text/css" href="{{ asset('css.css') }}">-->
        <!-- Bootstrap Core CSS -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap.min.css') }}">
        
    </head>
    <body>
      <div class="container-fluid">
            <div >
                <div class="card-body text-center" style="-webkit-box-flex: 1;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">
                    <h5 class="card-title">LAPORAN MINGGUAN / BULANAN</h5>
          <?php
        $ans = "";
        if(count($form) > 0)
        {
            foreach ($form as $value)
                $induk = $value->induk;
                $month = $value->month;
                $q1_1 = $value->q1_1;
                $q1_2 = $value->q1_2;
                $q1_3 = $value->q1_3;
                $q1_4 = $value->q1_4;
                $q1_5 = $value->q1_5;
                $q1_6 = $value->q1_6;
                $q1_7 = $value->q1_7;
                $q1_8 = $value->q1_8;
                
                $q2_1 = $value->q2_1;
                $q2_2 = $value->q2_2;
                $q2_3 = $value->q2_3;
                $q2_4 = $value->q2_4;
                $q2_5 = $value->q2_5;
                $q2_6 = $value->q2_6;
                $q2_7 = $value->q2_7;
                
                $q3_1 = $value->q3_1;
                $q3_2 = $value->q3_2;
                $q3_3 = $value->q3_3;
                $q3_4 = $value->q3_4;
                $q3_5 = $value->q3_5;
                $q3_6 = $value->q3_6;
                
                $q4_1 = $value->q4_1;
                $q4_2 = $value->q4_2;
                $q4_3 = $value->q4_3;
                $q4_4 = $value->q4_4;
                $q4_5 = $value->q4_5;
                $q4_6 = $value->q4_6;
                $q4_7 = $value->q4_7;
                $q4_8 = $value->q4_8;
                $q4_9 = $value->q4_9;
                $q4_10 = $value->q4_10;
                
                $q5_1 = $value->q5_1;
                $q5_2 = $value->q5_2;
                $q5_3 = $value->q5_3;
                $q5_4 = $value->q5_4;
                $q5_5 = $value->q5_5;
                $q5_6 = $value->q5_6;
                $q5_7 = $value->q5_7;
                $q5_8 = $value->q5_8;
                $q5_9 = $value->q5_9;
                
                $q6_1 = $value->q6_1;
                $q6_2 = $value->q6_2;
                $q6_3 = $value->q6_3;
                $q6_4 = $value->q6_4;
                $q6_5 = $value->q6_5;
                $q6_6 = $value->q6_6;
                $q6_7 = $value->q6_7;
                $q6_8 = $value->q6_8;
                $q6_9 = $value->q6_9;
                
                $q7_1 = $value->q7_1;
                $q7_2 = $value->q7_2;
                $q7_3 = $value->q7_3;
                $q7_4 = $value->q7_4;
                $q7_5 = $value->q7_5;
                $q7_6 = $value->q7_6;
                $q7_7 = $value->q7_7;
                $q7_8 = $value->q7_8;
                $q7_9 = $value->q7_9;
                
                $q8_1 = $value->q8_1;
                $q8_2 = $value->q8_2;
                $q8_3 = $value->q8_3;
                $q8_4 = $value->q8_4;
                $q8_5 = $value->q8_5;
                $q8_6 = $value->q8_6;
                $q8_7 = $value->q8_7;
                $q8_8 = $value->q8_8;
                $q8_9 = $value->q8_9;
                
                $q9_1 = $value->q9_1;
                $q9_2 = $value->q9_2;
                $q9_3 = $value->q9_3;
                $q9_4 = $value->q9_4;
                $q9_5 = $value->q9_5;
                $q9_6 = $value->q9_6;
                $q9_7 = $value->q9_7;
                $q9_8 = $value->q9_8;
                $q9_9 = $value->q9_9;
                $q9_10 = $value->q9_10;
                $q9_11 = $value->q9_11;
                $q9_12 = $value->q9_12;
                $q9_13 = $value->q9_13;
                $q9_14 = $value->q9_14;
                $q9_15 = $value->q9_15;
                $q9_16 = $value->q9_16;
                $q9_17 = $value->q9_17;
                $q9_18 = $value->q9_18;
                $q9_19 = $value->q9_19;
                
                $q10_1 = $value->q10_1;
                $q10_2 = $value->q10_2;
                $q10_3 = $value->q10_3;
                $q10_4 = $value->q10_4;
                $q10_5 = $value->q10_5;
                $q10_6 = $value->q10_6;
                $q10_7 = $value->q10_7;
                $q10_8 = $value->q10_8;
                $q10_9 = $value->q10_9;
                
                $q11_1 = $value->q11_1;
                $q11_2 = $value->q11_2;
                $q11_3 = $value->q11_3;
                $q11_4 = $value->q11_4;
                $q11_5 = $value->q11_5;
                $q11_6 = $value->q11_6;
                $q11_7 = $value->q11_7;
                $q11_8 = $value->q11_8;
                $q11_9 = $value->q11_9;
                $q11_10 = $value->q11_10;
                $q11_11 = $value->q11_11;
                $q11_12 = $value->q11_12;
                $q11_13 = $value->q11_13;
                $q11_14 = $value->q11_14;
                $q11_15 = $value->q11_15;
                $q11_16 = $value->q11_16;
                $q11_17 = $value->q11_17;
                $q11_18 = $value->q11_18;
                $q11_19 = $value->q11_19;
                
                $q12_1 = $value->q12_1;
                $q12_2 = $value->q12_2;
                $q12_3 = $value->q12_3;
                $q12_4 = $value->q12_4;
                $q12_5 = $value->q12_5;
                $q12_6 = $value->q12_6;
                $q12_7 = $value->q12_7;
                $q12_8 = $value->q12_8;
                $q12_9 = $value->q12_9;
                $q12_10 = $value->q12_10;
                $q12_11 = $value->q12_11;
                $q12_12 = $value->q12_12;
                $q12_13 = $value->q12_13;
                $q12_14 = $value->q12_14;
                $q12_15 = $value->q12_15;
                $q12_16 = $value->q12_16;
                $q12_17 = $value->q12_17;
                $q12_18 = $value->q12_18;
                $q12_19 = $value->q12_19;
                
                $q13_1 = $value->q13_1;
                $q13_2 = $value->q13_2;
                $q13_3 = $value->q13_3;
                $q13_4 = $value->q13_4;
                $q13_5 = $value->q13_5;
                $q13_6 = $value->q13_6;
                $q13_7 = $value->q13_7;
                $q13_8 = $value->q13_8;
                $q13_9 = $value->q13_9;
                $q13_10 = $value->q13_10;
                $q13_11 = $value->q13_11;
                $q13_12 = $value->q13_12;
                $q13_13 = $value->q13_13;
                $q13_14 = $value->q13_14;
                $q13_15 = $value->q13_15;
                $q13_16 = $value->q13_16;
                $q13_17 = $value->q13_17;
                $q13_18 = $value->q13_18;
                $q13_19 = $value->q13_19;
                
                $q14_1 = $value->q14_1;
                $q14_2 = $value->q14_2;
                $q14_3 = $value->q14_3;
                $q14_4 = $value->q14_4;
                $q14_5 = $value->q14_5;
                $q14_6 = $value->q14_6;
                $q14_7 = $value->q14_7;
                $q14_8 = $value->q14_8;
                $q14_9 = $value->q14_9;
                $q14_10 = $value->q14_10;
                $q14_11 = $value->q14_11;
                $q14_12 = $value->q14_12;
                $q14_13 = $value->q14_13;
                $q14_14 = $value->q14_14;
                $q14_15 = $value->q14_15;
                $q14_16 = $value->q14_16;
                $q14_17 = $value->q14_17;
                $q14_18 = $value->q14_18;
                $q14_19 = $value->q14_19;
                
                $q15_1 = $value->q15_1;
                $q15_2 = $value->q15_2;
                $q15_3 = $value->q15_3;
                $q15_4 = $value->q15_4;
                $q15_5 = $value->q15_5;
                $q15_6 = $value->q15_6;
                $q15_7 = $value->q15_7;
                $q15_8 = $value->q15_8;
                $q15_9 = $value->q15_9;
                $q15_10 = $value->q15_10;
                $q15_11 = $value->q15_11;
                $q15_12 = $value->q15_12;
                $q15_13 = $value->q15_13;
                $q15_14 = $value->q15_14;
                $q15_15 = $value->q15_15;
                $q15_16 = $value->q15_16;
                $q15_17 = $value->q15_17;
                $q15_18 = $value->q15_18;
                $q15_19 = $value->q15_19;
                $q15_20 = $value->q15_20;
                $q15_21 = $value->q15_21;
                $q15_22 = $value->q15_22;
                $q15_23 = $value->q15_23;
                $q15_24 = $value->q15_24;
                $q15_25 = $value->q15_25;
                $q15_26 = $value->q15_26;
                $q15_27 = $value->q15_27;
                
                $q16_1 = $value->q16_1;
                $q16_2 = $value->q16_2;
                $q16_3 = $value->q16_3;
                $q16_4 = $value->q16_4;
                $q16_5 = $value->q16_5;
                $q16_6 = $value->q16_6;
                $q16_7 = $value->q16_7;
                $q16_8 = $value->q16_8;
                $q16_9 = $value->q16_9;
                $q16_10 = $value->q16_10;
                $q16_11 = $value->q16_11;
                $q16_12 = $value->q16_12;
                $q16_13 = $value->q16_13;
                $q16_14 = $value->q16_14;
                $q16_15 = $value->q16_15;
                $q16_16 = $value->q16_16;
                $q16_17 = $value->q16_17;
                $q16_18 = $value->q16_18;
                $q16_19 = $value->q16_19;
                
                // $q16_1 = $value->q16_1;
                // $q16_2 = $value->q16_2;
                // $q16_3 = $value->q16_3;
                // $q16_4 = $value->q16_4;
                // $q16_5 = $value->q16_5;
                // $q16_6 = $value->q16_6;
                // $q16_7 = $value->q16_7;
                // $q16_8 = $value->q16_8;
                // $q16_9 = $value->q16_9;
                
                $q17_1 = $value->q17_1;
                $q17_2 = $value->q17_2;
                $q17_3 = $value->q17_3;
                $q17_4 = $value->q17_4;
                $q17_5 = $value->q17_5;
                $q17_6 = $value->q17_6;
                $q17_7 = $value->q17_7;
                $q17_8 = $value->q17_8;
                $q17_9 = $value->q17_9;
                
                $q18_1 = $value->q18_1;
                $q18_2 = $value->q18_2;
                $q18_3 = $value->q18_3;
                $q18_4 = $value->q18_4;
                $q18_5 = $value->q18_5;
                $q18_6 = $value->q18_6;
                $q18_7 = $value->q18_7;
                $q18_8 = $value->q18_8;
                $q18_9 = $value->q18_9;
                
                $q19_1 = $value->q19_1;
                $q19_2 = $value->q19_2;
                $q19_3 = $value->q19_3;
                $q19_4 = $value->q19_4;
                $q19_5 = $value->q19_5;
                $q19_6 = $value->q19_6;
                $q19_7 = $value->q19_7;
                $q19_8 = $value->q19_8;
                $q19_9 = $value->q19_9;
                
                $q20_1 = $value->q20_1;
                $q20_2 = $value->q20_2;
                $q20_3 = $value->q20_3;
                $q20_4 = $value->q20_4;
                $q20_5 = $value->q20_5;
                $q20_6 = $value->q20_6;
                $q20_7 = $value->q20_7;
                $q20_8 = $value->q20_8;
                $q20_9 = $value->q20_9;
                
                $q21_1 = $value->q21_1;
                $q21_2 = $value->q21_2;
                $q21_3 = $value->q21_3;
                $q21_4 = $value->q21_4;
                $q21_5 = $value->q21_5;
                $q21_6 = $value->q21_6;
                $q21_7 = $value->q21_7;
                $q21_8 = $value->q21_8;
                $q21_9 = $value->q21_9;
        }
        else
        {
                $induk = "";
                
                $q1_1 = "";
                $q1_2 = "";
                $q1_3 = "";
                $q1_4 = "";
                $q1_5 = "";
                $q1_6 = "";
                $q1_7 = "";
                $q1_8 = "";
                
                $q2_1 = "";
                $q2_2 = "";
                $q2_3 = "";
                $q2_4 = "";
                $q2_5 = "";
                $q2_6 = "";
                $q2_7 = "";
                
                $q3_1 = "";
                $q3_2 = "";
                $q3_3 = "";
                $q3_4 = "";
                $q3_5 = "";
                $q3_6 = "";
                
                $q4_1 = "";
                $q4_2 = "";
                $q4_3 = "";
                $q4_4 = "";
                $q4_5 = "";
                $q4_6 = "";
                $q4_7 = "";
                $q4_8 = "";
                $q4_9 = "";
                $q4_10 = "";
                
                $q5_1 = "";
                $q5_2 = "";
                $q5_3 = "";
                $q5_4 = "";
                $q5_5 = "";
                $q5_6 = "";
                $q5_7 = "";
                $q5_8 = "";
                $q5_9 = "";
                
                $q6_1 = "";
                $q6_2 = "";
                $q6_3 = "";
                $q6_4 = "";
                $q6_5 = "";
                $q6_6 = "";
                $q6_7 = "";
                $q6_8 = "";
                $q6_9 = "";
                
                $q7_1 = "";
                $q7_2 = "";
                $q7_3 = "";
                $q7_4 = "";
                $q7_5 = "";
                $q7_6 = "";
                $q7_7 = "";
                $q7_8 = "";
                $q7_9 = "";
                
                $q8_1 = "";
                $q8_2 = "";
                $q8_3 = "";
                $q8_4 = "";
                $q8_5 = "";
                $q8_6 = "";
                $q8_7 = "";
                $q8_8 = "";
                $q8_9 = "";
                
                $q9_1 = "";
                $q9_2 = "";
                $q9_3 = "";
                $q9_4 = "";
                $q9_5 = "";
                $q9_6 = "";
                $q9_7 = "";
                $q9_8 = "";
                $q9_9 = "";
                $q9_10 = "";
                $q9_11 = "";
                $q9_12 = "";
                $q9_13 = "";
                $q9_14 = "";
                $q9_15 = "";
                $q9_16 = "";
                $q9_17 = "";
                $q9_18 = "";
                $q9_19 = "";
                
                $q10_1 = "";
                $q10_2 = "";
                $q10_3 = "";
                $q10_4 = "";
                $q10_5 = "";
                $q10_6 = "";
                $q10_7 = "";
                $q10_8 = "";
                $q10_9 = "";
                
                $q11_1 = "";
                $q11_2 = "";
                $q11_3 = "";
                $q11_4 = "";
                $q11_5 = "";
                $q11_6 = "";
                $q11_7 = "";
                $q11_8 = "";
                $q11_9 = "";
                $q11_10 = "";
                $q11_11 = "";
                $q11_12 = "";
                $q11_13 = "";
                $q11_14 = "";
                $q11_15 = "";
                $q11_16 = "";
                $q11_17 = "";
                $q11_18 = "";
                $q11_19 = "";
                
                $q12_1 = "";
                $q12_2 = "";
                $q12_3 = "";
                $q12_4 = "";
                $q12_5 = "";
                $q12_6 = "";
                $q12_7 = "";
                $q12_8 = "";
                $q12_9 = "";
                $q12_10 = "";
                $q12_11 = "";
                $q12_12 = "";
                $q12_13 = "";
                $q12_14 = "";
                $q12_15 = "";
                $q12_16 = "";
                $q12_17 = "";
                $q12_18 = "";
                $q12_19 = "";
                
                $q13_1 = "";
                $q13_2 = "";
                $q13_3 = "";
                $q13_4 = "";
                $q13_5 = "";
                $q13_6 = "";
                $q13_7 = "";
                $q13_8 = "";
                $q13_9 = "";
                $q13_10 = "";
                $q13_11 = "";
                $q13_12 = "";
                $q13_13 = "";
                $q13_14 = "";
                $q13_15 = "";
                $q13_16 = "";
                $q13_17 = "";
                $q13_18 = "";
                $q13_19 = "";
                
                $q14_1 = "";
                $q14_2 = "";
                $q14_3 = "";
                $q14_4 = "";
                $q14_5 = "";
                $q14_6 = "";
                $q14_7 = "";
                $q14_8 = "";
                $q14_9 = "";
                $q14_10 = "";
                $q14_11 = "";
                $q14_12 = "";
                $q14_13 = "";
                $q14_14 = "";
                $q14_15 = "";
                $q14_16 = "";
                $q14_17 = "";
                $q14_18 = "";
                $q14_19 = "";
                
                $q15_1 = "";
                $q15_2 = "";
                $q15_3 = "";
                $q15_4 = "";
                $q15_5 = "";
                $q15_6 = "";
                $q15_7 = "";
                $q15_8 = "";
                $q15_9 = "";
                $q15_10 = "";
                $q15_11 = "";
                $q15_12 = "";
                $q15_13 = "";
                $q15_14 = "";
                $q15_15 = "";
                $q15_16 = "";
                $q15_17 = "";
                $q15_18 = "";
                $q15_19 = "";
                $q15_20 = "";
                $q15_21 = "";
                $q15_22 = "";
                $q15_23 = "";
                $q15_24 = "";
                $q15_25 = "";
                $q15_26 = "";
                $q15_27 = "";
                
                $q16_1 = "";
                $q16_2 = "";
                $q16_3 = "";
                $q16_4 = "";
                $q16_5 = "";
                $q16_6 = "";
                $q16_7 = "";
                $q16_8 = "";
                $q16_9 = "";
                $q16_10 = "";
                $q16_11 = "";
                $q16_12 = "";
                $q16_13 = "";
                $q16_14 = "";
                $q16_15 = "";
                $q16_16 = "";
                $q16_17 = "";
                $q16_18 = "";
                $q16_19 = "";
                
                // $q16_1 = $value->q16_1;
                // $q16_2 = $value->q16_2;
                // $q16_3 = $value->q16_3;
                // $q16_4 = $value->q16_4;
                // $q16_5 = $value->q16_5;
                // $q16_6 = $value->q16_6;
                // $q16_7 = $value->q16_7;
                // $q16_8 = $value->q16_8;
                // $q16_9 = $value->q16_9;
                
                $q17_1 = "";
                $q17_2 = "";
                $q17_3 = "";
                $q17_4 = "";
                $q17_5 = "";
                $q17_6 = "";
                $q17_7 = "";
                $q17_8 = "";
                $q17_9 = "";
                
                $q18_1 = "";
                $q18_2 = "";
                $q18_3 = "";
                $q18_4 = "";
                $q18_5 = "";
                $q18_6 = "";
                $q18_7 = "";
                $q18_8 = "";
                $q18_9 = "";
                
                $q19_1 = "";
                $q19_2 = "";
                $q19_3 = "";
                $q19_4 = "";
                $q19_5 = "";
                $q19_6 = "";
                $q19_7 = "";
                $q19_8 = "";
                $q19_9 = "";
                
                $q20_1 = "";
                $q20_2 = "";
                $q20_3 = "";
                $q20_4 = "";
                $q20_5 = "";
                $q20_6 = "";
                $q20_7 = "";
                $q20_8 = "";
                $q20_9 = "";
                
                $q21_1 = "";
                $q21_2 = "";
                $q21_3 = "";
                $q21_4 = "";
                $q21_5 = "";
                $q21_6 = "";
                $q21_7 = "";
                $q21_8 = "";
                $q21_9 = "";
        }
            
            ?>
                    
                </div>
                

                

<form style="display: inline" method="POST" action="{{route('form.save')}}">
<table class="table table-bordered">
    <tr>
        <td colspan="7">MARKAZ / INDUK: </td>
        <td  colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="induk" value=<?php echo $induk ?>>
</div></td>
        <td name="month" id = "a" colspan="6">BULAN: {{$month}}</td>
        <div class="input-group ">
  <input type="hidden" class="form-control" name="month" value=<?php echo $month ?>>
</div>
</div></td>
    </tr>
  <tr >
    <td rowspan="5">1</td>
    <td colspan="3" rowspan="5" > ULAMA'</td>
    <td colspan="3">BIL. PENGALAMAN 1 TAHUN</td>
    <td colspan="2" ><div class="input-group ">
  <input type="text" class="form-control" name="q1_1" value=<?php echo $q1_1 ?>>
</div></td>
    <td colspan="5">TUNAI NISAB TAHUNAN						
</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q1_2" value=<?php echo $q1_2 ?> >
</div></td>
  </tr>
  <tr>
    <td colspan="3">BIL. PENGALAMAN 4B	
</td>
    <td colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q1_3" value=<?php echo $q1_3 ?>>
</div></td>
    <td colspan="5">TUNAI NISAB TAHUNAN						
</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q1_4" value=<?php echo $q1_4 ?>>
</div></td>
  </tr>
  <tr>
    <td colspan="3">BIL. PENGALAMAN 40H	</td>
    <td colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q1_5" value=<?php echo $q1_5 ?> >
</div></td>
    <td colspan="5">TUNAI NISAB TAHUNAN</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q1_6" value=<?php echo $q1_6 ?>>
</div></td>
  </tr>
  <tr>
    <td colspan="10">BILANGAN ULAMA' BERI 2B TERTIB</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q1_7" value=<?php echo $q1_7 ?>>
</div></td>
  </tr>
    <tr>
    <td colspan="10">BILANGAN ULAMA' KELUAR 40H IKUT MESYUARAT SP</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q1_8" value=<?php echo $q1_8 ?>>
</div></td>
  </tr>
  <tr >
    <td  rowspan="4">2</td>
    <td  colspan="3" rowspan="3" >AWAM</td>
    <td  colspan="3">BIL. ORANG PENGALAMAN 4B</td>
    <td  colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q2_1" value=<?php echo $q2_1 ?>>
</div></td>
    <td colspan="3">BERI 4B TAHUNAN</td>
    <td><div class="input-group ">
  <input type="text" class="form-control" name="q2_2" value=<?php echo $q2_2 ?>>
</div></td>
<td >TUNAI NISAB TAHUNAN</td>
    <td style="width:10%"><div class="input-group ">
  <input type="text" class="form-control" name="q2_3" value=<?php echo $q2_3 ?>>
</div></td>
  </tr>
  <tr>
    <td colspan="3">BIL. ORANG PENGALAMAN 40H</td>
    <td colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q2_4" value=<?php echo $q2_4 ?>>
</div></td>
    <td colspan="5">TUNAI NISAB TAHUNAN</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q2_5" value=<?php echo $q2_5 ?>>
</div></td>
  </tr>
  <tr>
    <td colspan="3">BIL. ORANG PENGALAMAN 3H</td>
    <td colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q2_6" value=<?php echo $q2_6 ?>>
</div></td>
    <td colspan="5"></td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="6">JUMLAH RAKAN SEUSAHA</td>
    <td colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q2_7" value=<?php echo $q2_7 ?>>
</div></td>
    <td colspan="5"></td>
    <td ></td>
  </tr>
  <tr >
    <td  rowspan="3">3</td>
    <td  colspan="2" rowspan="3" >USAHA MASTURAT</td>
    <td  colspan="3">BIL. PENGALAMAN 2B/40H</td>
    <td  colspan="1"><div class="input-group ">
  <input type="text" class="form-control" name="q3_1" value=<?php echo $q3_1 ?>>
</div></td>
    <td colspan="5">BIL. PENGALAMAN 15H/10H</td>
    <td><div class="input-group ">
  <input type="text" class="form-control" name="q3_2" value=<?php echo $q3_2 ?>>
</div></td>
<td >BIL. PENGALAMAN  3H</td>
    <td style="width:10%"><div class="input-group ">
  <input type="text" class="form-control" name="q3_3" value=<?php echo $q3_3 ?>>
</div></td>
  </tr>
  <tr>
    <td colspan="4" rowspan= "2">BIL. TAKLIM MINGGUAN MASTURAT</td>
    <td colspan="5">LEPAS</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q3_4" value=<?php echo $q3_4 ?>>
</div></td>
<td rowspan= "2">BIL. JOR MASTURAT 6 BULANAN</td>
    <td rowspan= "2"><div class="input-group ">
  <input type="text" class="form-control" name="q3_5" value=<?php echo $q3_5 ?>>
</div></td>
  </tr>
  <tr>
    <td colspan="5">TERKINI</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q3_6" value=<?php echo $q3_6 ?>>
</div></td>
  </tr>
  <tr >
    <td  rowspan="3">4</td>
    <td  colspan="5">BILANGAN PENEMPATAN</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_1" value=<?php echo $q4_1 ?>>
</div></td>
<td colspan="2">BIL. HALQAH</td>
    <td colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q4_2" value=<?php echo $q4_2 ?>>
</div></td>
<td>BIL. MASJID</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_3" value=<?php echo $q4_3 ?>>
</div></td>
<td >BIL. SURAU</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_4" value=<?php echo $q4_4 ?>>
</div></td>
  </tr>
  <tr >
    <td  colspan="5">JUMLAH MASJID & SURAU</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_5" value=<?php echo $q4_5 ?>>
</div></td>
<td colspan="2">BIL. MAKTAB</td>
    <td colspan="2"><div class="input-group ">
  <input type="text" class="form-control" name="q4_6" value=<?php echo $q4_6 ?>>
</div></td>
<td>LEPAS</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_7" value=<?php echo $q4_7 ?>>
</div></td>
<td>TERKINI</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_8" value=<?php echo $q4_8 ?>>
</div></td>
  </tr>
  <tr >
    <td  colspan="5">JUMLAH MASJID & SURAU</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_9" value=<?php echo $q4_9 ?>>
</div></td>
<td colspan="7">BIL. BACA HAYATUS SAHABAH DALAM JOR BULANAN HALAQAH</td>
    <td ><div class="input-group ">
  <input type="text" class="form-control" name="q4_10" value=<?php echo $q4_10 ?>>
</div></td>
  </tr>
  <tr>
      <td  rowspan="2" colspan="6"></td>
      <td  rowspan="2" colspan="1">BULAN LEPAS</td>
      <td  colspan="5">MINGGUAN</td>
      <td colspan="1" >BULANAN</td>
      <td  rowspan="2" colspan="1">AZAM BULAN INI</td>
      <td  rowspan="2" colspan="1">AZAM 2021</td>
    </tr>
    <tr>
      <td style="position: sticky" >M1</td>
      <td >M2</td>
      <td >M3</td>
      <td >M4</td>
      <td >M5</td>
      <td>JUMLAH BULAN INI</td>
    </tr>
    <tr>
      <td>5</td>
      <td  colspan="5">BIL. MASJID ADA BEBERAPA AMAL</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_1" value=<?php echo $q5_1 ?>>
        </div></td>
<td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_2" value=<?php echo $q5_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_3" value=<?php echo $q5_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_4" value=<?php echo $q5_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_5" value=<?php echo $q5_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_6" value=<?php echo $q5_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_7" value=<?php echo $q5_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_8" value=<?php echo $q5_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q5_9" value=<?php echo $q5_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td >6</td>
      <td  colspan="5">BIL. MASJID HIDUP 5 AMAL</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_1" value=<?php echo $q6_1 ?>>
        </div></td>
<td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_2" value=<?php echo $q6_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_3" value=<?php echo $q6_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_4" value=<?php echo $q6_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_5" value=<?php echo $q6_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_6" value=<?php echo $q6_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_7" value=<?php echo $q6_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_8" value=<?php echo $q6_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q6_9" value=<?php echo $q6_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td >7</td>
      <td  colspan="5">BIL. JEMAAH 3 HARI SEBULAN</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_1" value=<?php echo $q7_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_2" value=<?php echo $q7_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_3" value=<?php echo $q7_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_4" value=<?php echo $q7_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_5" value=<?php echo $q7_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_6" value=<?php echo $q7_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_7" value=<?php echo $q7_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_8" value=<?php echo $q7_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q7_9" value=<?php echo $q7_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td >8</td>
      <td  colspan="5">BIL. ORG BERI 10 HARI BULANAN</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_1" value=<?php echo $q8_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_2" value=<?php echo $q8_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_3" value=<?php echo $q8_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_4" value=<?php echo $q8_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_5" value=<?php echo $q8_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_6" value=<?php echo $q8_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_7" value=<?php echo $q8_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_8" value=<?php echo $q8_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q8_9" value=<?php echo $q8_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td rowspan="2" >9</td>
      <td rowspan="2" colspan="4">BIL. ORG BERI MASA HARIAN</td>
      <td >8 JAM</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_1" value=<?php echo $q9_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_2" value=<?php echo $q9_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_3" value=<?php echo $q9_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_4" value=<?php echo $q9_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_5" value=<?php echo $q9_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_6" value=<?php echo $q9_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_7" value=<?php echo $q9_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_8" value=<?php echo $q9_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_9" value=<?php echo $q9_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td colspan="1">2 SETENGAH JAM</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_10" value=<?php echo $q9_10 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_11" value=<?php echo $q9_11 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_12" value=<?php echo $q9_12 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_13" value=<?php echo $q9_13 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_14" value=<?php echo $q9_14 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_15" value=<?php echo $q9_15 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_16" value=<?php echo $q9_16 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_17" value=<?php echo $q9_17 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q9_18" value=<?php echo $q9_18 ?>>
        </div></td>
    </tr>
    <tr>
      <td >10</td>
      <td  colspan="5">BIL. ORG BERI 10 HARI BULANAN</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_1" value=<?php echo $q10_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_2" value=<?php echo $q10_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_3" value=<?php echo $q10_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_4" value=<?php echo $q10_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_5" value=<?php echo $q10_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_6" value=<?php echo $q10_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_7" value=<?php echo $q10_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_8" value=<?php echo $q10_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q10_9" value=<?php echo $q10_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td rowspan="2" >11</td>
      <td rowspan="2" colspan="4">TAKLIM RUMAH</td>
      <td >BIL. TAKLIM RUMAH</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_1" value=<?php echo $q11_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_2" value=<?php echo $q11_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_3" value=<?php echo $q11_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_4" value=<?php echo $q11_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_5" value=<?php echo $q11_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_6" value=<?php echo $q11_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_7" value=<?php echo $q11_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_8" value=<?php echo $q11_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_9" value=<?php echo $q11_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td colspan="1">BIL. TAKLIM RUMAH + 5 AMAL</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_10" value=<?php echo $q11_10 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_11" value=<?php echo $q11_11 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_12" value=<?php echo $q11_12 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_13" value=<?php echo $q11_13 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_14" value=<?php echo $q11_14 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_15" value=<?php echo $q11_15 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_16" value=<?php echo $q11_16 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_17" value=<?php echo $q11_17 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q11_18" value=<?php echo $q11_18 ?>>
        </div></td>
    </tr>
    <tr>
      <td rowspan="2" >12</td>
      <td rowspan="2" colspan="4">BIL. JEMAAH DIHANTAR KE LUAR NEGARA</td>
      <td >4 BULAN</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_1" value=<?php echo $q12_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_2" value=<?php echo $q12_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_3" value=<?php echo $q12_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_4" value=<?php echo $q12_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_5" value=<?php echo $q12_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_6" value=<?php echo $q12_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_7" value=<?php echo $q12_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_8" value=<?php echo $q12_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_9" value=<?php echo $q12_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td colspan="1">40 HARI</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_10" value=<?php echo $q12_10 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_11" value=<?php echo $q12_11 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_12" value=<?php echo $q12_12 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_13" value=<?php echo $q12_13 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_14" value=<?php echo $q12_14 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_15" value=<?php echo $q12_15 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_16" value=<?php echo $q12_16 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_17" value=<?php echo $q12_17 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q12_18" value=<?php echo $q12_18 ?>>
        </div></td>
    </tr>
    <tr>
    <td rowspan="2" >13</td>
      <td  colspan="5">BIL. JEMAAH  KELUAR 4 BULAN (DALAM NEGERI)</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_1" value=<?php echo $q13_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_2" value=<?php echo $q13_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_3" value=<?php echo $q13_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_4" value=<?php echo $q13_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_5" value=<?php echo $q13_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_6" value=<?php echo $q13_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_7" value=<?php echo $q13_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_8" value=<?php echo $q13_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_9" value=<?php echo $q13_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="3">JALAN KAKI</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_10" value=<?php echo $q13_10 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_11" value=<?php echo $q13_11 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_12" value=<?php echo $q13_12 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_13" value=<?php echo $q13_13 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_14" value=<?php echo $q13_14 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_15" value=<?php echo $q13_15 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_16" value=<?php echo $q13_16 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_17" value=<?php echo $q13_17 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q13_18" value=<?php echo $q13_18 ?>>
        </div></td>
    </tr>
    <tr>
    <td rowspan="2" >14</td>
      <td  colspan="5">BIL. JEMAAH KELUAR 40 HARI (DALAM NEGERI)</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_1" value=<?php echo $q14_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_2" value=<?php echo $q14_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_3" value=<?php echo $q14_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_4" value=<?php echo $q14_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_5" value=<?php echo $q14_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_6" value=<?php echo $q14_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_7" value=<?php echo $q14_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_8" value=<?php echo $q14_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_9" value=<?php echo $q14_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td colspan="3">JALAN KAKI</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_10" value=<?php echo $q14_10 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_11" value=<?php echo $q14_11 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_12" value=<?php echo $q14_12 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_13" value=<?php echo $q14_13 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_14" value=<?php echo $q14_14 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_15" value=<?php echo $q14_15 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_16" value=<?php echo $q14_16 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_17" value=<?php echo $q14_17 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q14_18" value=<?php echo $q14_18 ?>>
        </div></td>
    </tr>
    <tr>
    <td rowspan="3" >15</td>
      <td  rowspan="3" colspan="2">JEMAAH KELUAR SECARA MASJID</td>
      <td  colspan="3">4 BULAN & 40H (LUAR NEGARA)</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_1" value=<?php echo $q15_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_2" value=<?php echo $q15_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_3" value=<?php echo $q15_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_4" value=<?php echo $q15_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_5" value=<?php echo $q15_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_6" value=<?php echo $q15_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_7" value=<?php echo $q15_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_8" value=<?php echo $q15_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_9" value=<?php echo $q15_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td  colspan="3">4 BULAN (DALAM NEGERI)</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_10" value=<?php echo $q15_10 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_11" value=<?php echo $q15_11 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_12" value=<?php echo $q15_12 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_13" value=<?php echo $q15_13 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_14" value=<?php echo $q15_14 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_15" value=<?php echo $q15_15 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_16" value=<?php echo $q15_16 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_17" value=<?php echo $q15_17 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_18" value=<?php echo $q15_18 ?>>
        </div></td>
    </tr>
    <tr>
      <td  colspan="3">40 HARI (DALAM NEGERI)</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_19" value=<?php echo $q15_19 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_20" value=<?php echo $q15_20 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_21" value=<?php echo $q15_21 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_22" value=<?php echo $q15_22 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_23" value=<?php echo $q15_23 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_24" value=<?php echo $q15_24 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_25" value=<?php echo $q15_25 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_26" value=<?php echo $q15_26 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q15_27" value=<?php echo $q15_27 ?>>
        </div></td>
    </tr>
    <tr>
      <td rowspan="2" >16</td>
      <td rowspan="2" colspan="4">BIL. JEMAAH DIHANTAR KE LUAR NEGARA</td>
      <td >2 BULAN</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_1" value=<?php echo $q16_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_2" value=<?php echo $q16_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_3" value=<?php echo $q16_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_4" value=<?php echo $q16_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_5" value=<?php echo $q16_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_6" value=<?php echo $q16_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_7" value=<?php echo $q16_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_8" value=<?php echo $q16_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_9" value=<?php echo $q16_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td colspan="1">40 HARI</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_10" value=<?php echo $q16_10 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_11" value=<?php echo $q16_11 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_12" value=<?php echo $q16_12 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_13" value=<?php echo $q16_13 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_14" value=<?php echo $q16_14 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_15" value=<?php echo $q16_15 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_16" value=<?php echo $q16_16 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_17" value=<?php echo $q16_17 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q16_18" value=<?php echo $q16_18 ?>>
        </div></td>
    </tr>
    <tr>
      <td  >17</td>
      <td rowspan="3" colspan="4">BIL. JEMAAH DIHANTAR KE LUAR NEGARA</td>
      <td >2 BULAN & 40 HARI</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_1" value=<?php echo $q17_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_2" value=<?php echo $q17_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_3" value=<?php echo $q17_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_4" value=<?php echo $q17_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_5" value=<?php echo $q17_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_6" value=<?php echo $q17_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_7" value=<?php echo $q17_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_8" value=<?php echo $q17_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q17_9" value=<?php echo $q17_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td  >18</td>
      <td colspan="1">10 HARI</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_1" value=<?php echo $q18_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_2" value=<?php echo $q18_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_3" value=<?php echo $q18_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_4" value=<?php echo $q18_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_5" value=<?php echo $q18_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_6" value=<?php echo $q18_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_7" value=<?php echo $q18_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_8" value=<?php echo $q18_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q18_9" value=<?php echo $q18_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td  >19</td>
      <td colspan="1">3 HARI</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_1" value=<?php echo $q19_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_2" value=<?php echo $q19_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_3" value=<?php echo $q19_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_4" value=<?php echo $q19_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_5" value=<?php echo $q19_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_6" value=<?php echo $q19_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_7" value=<?php echo $q19_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_8" value=<?php echo $q19_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q19_9" value=<?php echo $q19_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td >20</td>
      <td  colspan="5">BIL. ORG BERI 2 BULAN TERTIB DI NIZAMUDDIN</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_1" value=<?php echo $q20_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_2" value=<?php echo $q20_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_3" value=<?php echo $q20_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_4" value=<?php echo $q20_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_5" value=<?php echo $q20_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_6" value=<?php echo $q20_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_7" value=<?php echo $q20_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_8" value=<?php echo $q20_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q20_9" value=<?php echo $q20_9 ?>>
        </div></td>
    </tr>
    <tr>
      <td >21</td>
      <td  colspan="5">BIL. JEMAAH DIHANTAR KE TEMPAT YG DIMESYUARATKAN (BERKEMBAR)</td>
      <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_1" value=<?php echo $q21_1 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_2" value=<?php echo $q21_2 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_3" value=<?php echo $q21_3 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_4" value=<?php echo $q21_4 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_5" value=<?php echo $q21_5 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_6" value=<?php echo $q21_6 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_7" value=<?php echo $q21_7 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_8" value=<?php echo $q21_8 ?>>
        </div></td>
        <td ><div class="input-group ">
        <input type="text" class="form-control" name="q21_9" value=<?php echo $q21_9 ?>>
        </div></td>
    </tr>
  </table>
                <button class="btn btn-primary" type="submit">Submit</button>
                
                <!--<input type="submit" value="Submit">-->
                
            </form>
            <form style="display: inline" method="GET" action="{{route('form.export')}}">
    
    <input type="hidden" class="form-control" name="month" value=<?php echo $month ?>>
    
    <!--<input type="submit" value="Export">-->
    
    &nbsp;<button class="btn btn-primary" type="submit">Excel</button>
    <!--<input type="submit" id="submit-form" class="hidden" />-->
    </form>

    <!--<label class="label label-default" for="submit-form" tabindex="0">Submit</label>-->
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
        
        function getQueryVariable(variable) {
                var query = window.location.search.substring(1);
                var parms = query.split('&');
                for (var i = 0; i < parms.length; i++) {
                    var pos = parms[i].indexOf('=');
                    if (pos > 0 && variable == parms[i].substring(0, pos)) {
                        return parms[i].substring(pos + 1);;
                    }
                }
                return "";
}

    // var name = getQueryVariable("month");
    // document.getElementById("a").innerHTML = "Bulan: " + name;
        
    </script>
</html>