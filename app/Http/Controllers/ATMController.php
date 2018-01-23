<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NoteUnavailableException;

class ATMController extends Controller
{
    /**
     * 
     */
    public function validateUserInput($userInput) {

        if($userInput < 0) {
            throw new InvalidArgumentException;
        }

        if($userInput % 10 !== 0) {
            throw new NoteUnavailableException;
        }
    }
    
    /**
     * Process the request for withdraw and
     * returns an array where the higher notes 
     * have priority
     *
     * @return array
     */
    public function withdraw($withdrawalAmount)
    {

        $this->validateUserInput($withdrawalAmount);
        $moneyNotesValues = array(100, 50, 20, 10); //values available for withdraw
        $moneyNotesKeys = array(0, 0, 0, 0); //array which will store the amount of each note to withdraw
        $i = 0;
    
        while($withdrawalAmount > 0) {
            $moneyNotesKeys[$i] = floor($withdrawalAmount / $moneyNotesValues[$i]); // Withdrawal amount divided by each money value
            $withdrawalAmount = $withdrawalAmount % $moneyNotesValues[$i] !== 0 ? $withdrawalAmount % $moneyNotesValues[$i] : 0; // In case of remainder of the division, continues to deliver subsequent notes
    
            $i++;
        }
        
        $return = array();

        for($i=0; $i<=count($moneyNotesValues) - 1; $i++) {
            for($k=0; $k<=$moneyNotesKeys[$i] -1; $k++) {
                $return[] = $moneyNotesValues[$i];
            }
            
        }
        
        return $return;
    }

    /**
     * Returns an empty array when the withdraw route is not provided with an amount
     * @return array
     */
    public function returnEmptyArray() {
        return array();
    }
}
