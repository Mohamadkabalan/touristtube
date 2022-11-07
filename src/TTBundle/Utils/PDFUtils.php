<?php

namespace TTBundle\Utils;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


class PDFUtils
{
    private $orientation = "p";
    private $format = "A4";
    private $lang = "en";
    private $unicode = true;
    private $encoding = "UTF-8";
    private $margins = array(5, 5, 5, 8);
    private $pdfa = false;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Generate a PDF result from given HTML string
     * 
     * @param String $htmlString : HTLM content to be converted
     * @param String $outputFile : File name to be used, or full path
     * @param String $outputTarget : D -> download in browser , F -> Save file on server , S -> return the PDf content to be stored in a variable
     */
    public function htmlToPDF($htmlString, $outputFile=null, $outputTarget="D")
    {
        return $this->htmlToPDFWithOpts($htmlString, $outputFile, $outputTarget, $this->orientation, $this->format, $this->lang, $this->unicode, $this->encoding, $this->margins, $this->pdfa);
    }

    public function htmlToPDFWithOpts($htmlString, $outputFile=null, $outputTarget="D", $orientation=null, $format=null, $lang=null, $unicode=null, $encoding=null, $margins=null, $pdfa=null)
    {
        try {
            $html2pdf = new Html2Pdf($orientation, $format, $lang, $unicode, $encoding, $margins, $pdfa);
            //
    //      $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($htmlString);
            //
            if(!isset($outputFile) || $outputFile == "") $outputFile = "generated_pdf_" . time() . ".pdf";
            //
            return $html2pdf->output($outputFile, $outputTarget);
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }


}