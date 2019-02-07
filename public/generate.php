<?php
    
    $link = mysqli_connect("localhost", "root", "", "test");    
    $sql = "";
            
    $index = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $base = strlen($index);
    $len = 4;

    for ($i = 0, $l = pow(strlen($index), $len); $i < $l; $i++) {
        $sql = "INSERT INTO links (short, url) VALUES ('";
        $sql .= str_pad(dec2any($i, $base, $index), $len, "0", STR_PAD_LEFT);
        $sql .= "', '')";
        mysqli_query($link, $sql);
    } 

    mysqli_close($link);

    function dec2any( $num, $base=62, $index=false ) {
        if (! $base ) {
            $base = strlen( $index );
        } else if (! $index ) {
            $index = substr( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,0 ,$base );
        }
        $out = "";
        for ( $t = floor( log10( $num ) / log10( $base ) ); $t >= 0; $t-- ) {
            $a = floor( $num / pow( $base, $t ) );
            $out = $out . substr( $index, $a, 1 );
            $num = $num - ( $a * pow( $base, $t ) );
        }
        return $out;
    }