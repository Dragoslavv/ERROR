<?php
error_reporting( E_ALL );
ini_set('display_errors',1);

function yesterday_file ( )
{
    $time = date("H:i");
    /* If at 7 a clock search file and show errors */
    if ($time == "07:00") {

        $data  = date('Y-m-d',strtotime("-1 days"));

        readFile1('debug_' . $data . '.log' );

    } else {
        echo "The script will be start in 7 a clock";
    }

}

function readFile1( $filename )
{
    $handle = fopen( $filename, "r" ) or die ( 'File opening failed' );

    $line = fgets( $handle );

    while ( !feof($handle) ) {

        if(preg_match("/(ERROR.*)/", $line, $res) == 1){

            $array = array();

            foreach ($res as $val) $array[] = $val;

            echo print_r(array_count_values($array)). '<br>';
        }

        $line = fgets( $handle );
    }

    fclose( $handle );
}

readFile1('debug_2019-01-23.log' );

yesterday_file ( );
