<?php
    switch($type){
        case 'logo':
            $q = DB::table('games')->select('logo1','ext')->distinct()
                    ->join('images','images.id','=','games.logo1')
                    ->where('team1','LIKE','%'.$param.'%')
                    ->get();

            if($q)
                foreach ($q as $i) echo '<img src="'.URL::to('images').'/i'.$i->logo1.$i->ext.'" class="clickable1" data-id="'.$i->logo1.'" />';   
            else
            {
                $q2 = DB::table('games')->select('logo2','ext')->distinct()
                    ->join('images','images.id','=','games.logo2')
                    ->where('team2','LIKE','%'.$param.'%')
                    ->get();

                if($q2)
                    foreach ($q2 as $i) echo '<img src="'.URL::to('images').'/i'.$i->logo2.$i->ext.'" class="clickable2" data-id="'.$i->logo2.'" />';
                else
                    echo '<img src="'.URL::to('images').'/nologo.png" class="clickable2" data-id="0" />';
            }

            echo  "<script>$(function() {
                $('.clickable1').click(function() {
                    $('.logo1 img').removeClass('clicked');
                    $(this).addClass('clicked');
                    $('input[name=\"hlogo1\"]').val($(this).data('id'));
                });

                $('.clickable2').click(function() {
                    $('.logo2 img').removeClass('clicked');
                    $(this).addClass('clicked');
                    $('input[name=\"hlogo2\"]').val($(this).data('id'));
                });
                });</script>";
        break;

        default:
        break;
    }