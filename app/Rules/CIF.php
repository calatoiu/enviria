<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CIF implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->checkCIF($value);
    }


    function checkCIF($cif)
    {
        if ( !is_numeric($cif) )
            return false;
        if ( strlen($cif) == 13 )
            return $this->validCNP($cif);
        if ( strlen($cif)>10 )
            return false;

        $cifra_control = substr($cif, -1);

        $cif = substr($cif, 0, -1);
        while ( strlen($cif) != 9 )
        {
            $cif = '0' . $cif;
        }
//        $coef = [7, 5, 3, 2, 1, 7, 5, 3, 2];
        $suma = $cif[0] * 7 + $cif[1] * 5 + $cif[2] * 3 + $cif[3] * 2 + $cif[4] * 1 + $cif[5] * 7 + $cif[6] * 5 + $cif[7] * 3 + $cif[8] * 2;
        $suma = $suma * 10;
        $rest = fmod($suma, 11);
        if ( $rest == 10 )
            $rest = 0;

        return $rest == $cifra_control;
    }


    function validCNP($p_cnp) {
        // CNP must have 13 characters
        // if(strlen($p_cnp) != 13) {
        //     return false;
        // }
        $cnp = str_split($p_cnp);
        unset($p_cnp);
        $hashTable = [2, 7, 9, 1, 4, 6, 3, 5, 8, 2, 7, 9];
        $hashResult = 0;
        // All characters must be numeric
        for($i = 0; $i < 13; $i++ ) {
            // if(!is_numeric($cnp[$i])) {
            //     return false;
            // }
   //         $cnp[$i] = (int)$cnp[$i];
            if($i < 12) {
                $hashResult += (int)$cnp[$i] * (int)$hashTable[$i];
            }
        }
        unset($hashTable, $i);
        $hashResult = $hashResult % 11;
        if($hashResult == 10) {
            $hashResult = 1;
        }
        if ($cnp[12] != $hashResult){
            return false;
        }
        // Check Year
        $year = ($cnp[1] * 10) + $cnp[2];


        switch( $cnp[0] ) {
            case 1  : case 2 : { $year += 1900; } break; // cetateni romani nascuti intre 1 ian 1900 si 31 dec 1999
            case 3  : case 4 : { $year += 1800; } break; // cetateni romani nascuti intre 1 ian 1800 si 31 dec 1899
            case 5  : case 6 : { $year += 2000; } break; // cetateni romani nascuti intre 1 ian 2000 si 31 dec 2099
            case 7  : case 8 : case 9 : {                // rezidenti si Cetateni Straini
            $year += 2000;
            if($year > (int)date('Y')-14) {
                $year -= 100;
            }
        } break;
            default : {
                return false;
            } break;
        }
        return ($year > 1800 && $year < 2099);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
