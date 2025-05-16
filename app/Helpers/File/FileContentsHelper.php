<?php

namespace App\Helpers\File;

use Illuminate\Support\Facades\Http;

/**
 * Class FileContentsHelper.
 */
class FileContentsHelper
{
    /**
     * The input string
     *
     * @var \File
     */
    protected $_file;

    /**
     * HtmlHelper constructor.
     *
     * @param String|null $string
     */
    public function __construct($file = null)
    {
        $this->_file = $file;
    }


    /**
     * Extract the string content from a file
     * This will only work for PDF and Word files
     * @param $file
     *
     * @return string
     */
    public static function extractContent($file)
    {
        $content = '';
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename());
        // is it a PDF or Word?
        switch ($type) {
            // word
            case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
            case "application/msword":
                // extract the string
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getElements')) {
                            foreach ($element->getElements() as $childElement) {
                                if (method_exists($childElement, 'getText')) {
                                    $content .= $childElement->getText() . ' ';
                                } else if (method_exists($childElement, 'getContent')) {
                                    $content .= $childElement->getContent() . ' ';
                                }
                            }
                        } else if (method_exists($element, 'getText')) {
                            $content .= $element->getText() . ' ';
                        }
                    }
                }
                break;
            case "application/pdf":
                // read PDF
                $parser = new \Smalot\PdfParser\Parser();
                $content = $parser->parseFile($file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename())->getText();
                break;
            default:
                // throw error here
                throw new \Exception('File type not supported');
                break;
        }
        return $content;
    }
}
