<?php
require('fpdf.php');

class PDF_Dash extends FPDF
{
    function SetDash($black=null, $white=null)
    {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }

    //Cell with horizontal scaling if text is too wide
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    //Cell with horizontal scaling only if necessary
    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    //Cell with horizontal scaling always
    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    //Cell with character spacing only if necessary
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    //Cell with character spacing always
    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        //Same as calling CellFit directly
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }
    function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){
        $wide = $baseline;
        $narrow = $baseline / 3 ; 
        $gap = $narrow;
    
        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn'; 
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';
    
        //$this->SetFont('Arial','',10);
        //$this->Text($xpos, $ypos + $height + 4, $code);
        $this->SetFillColor(0);
    
        $code = '*'.strtoupper($code).'*';
        for($i=0; $i<strlen($code); $i++){
            $char = $code[$i];
            if(!isset($barChar[$char])){
                $this->Error('Invalid character in barcode: '.$char);
            }
            $seq = $barChar[$char];
            for($bar=0; $bar<9; $bar++){
                if($seq[$bar] == 'n'){
                    $lineWidth = $narrow;
                }else{
                    $lineWidth = $wide;
                }
                if($bar % 2 == 0){
                    $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
                }
                $xpos += $lineWidth;
            }
            $xpos += $gap;
        }
    }
}
?>