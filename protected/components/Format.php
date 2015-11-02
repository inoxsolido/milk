<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Format
 *
 * @author Ball
 */
class Format extends CApplicationComponent
{

    public function NumToDec($Number)
    {
        $strval = strval(($Number));
        $strval = str_replace(",", "", $strval);
        $strval = strval(doubleval($strval));
        $len = strlen($strval);
        if (doubleval($strval) || doubleval($Number) == intval(0))
        {
            $flagfloat = false;
            $dp = 0;
            if ($strval != NULL)
            {
                //get resource to know it has dot then if has how many precision
                for ($i = 0; $i < $len; $i++)
                {
                    if ($strval[$i] == '.')
                    {
                        $flagfloat = true;
                    }
                    else if ($flagfloat)
                    {
                        $dp++;
                    }
                }
                //natural formating
                for ($i = 0, $ri = ($len - 1) - ($dp) - ($flagfloat ? 1 : intval(0)); $ri >= 0; $i++, $ri--)
                {
                    if ($i != 0 && $i % 3 == 0)
                    {
                        $strval = substr($strval, 0, ($ri + 1)) . ',' . substr($strval, ($ri + 1), $len+1);
                    }
                }

                //add float
                if ($flagfloat)
                {
                    if ($dp > 2)
                    {
                        //del float
                        $strval = substr($strval, 0, $len - ($dp - 2));
                    }
                    else if ($dp < 2)
                    {
                        for ($i = 0; $i < (2 - $dp); $i++)
                        {
                            $strval .= '0';
                        }
                    }
                }
                else
                {
                    $strval .= '.00';
                }
            }
            else
            {
                $strval = '0.00';
            }
            return $strval;
        }
        else
            return false;
    }

}
