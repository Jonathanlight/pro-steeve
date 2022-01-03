<?php

namespace MailingBundle\Service;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class InlineStyles{
    public function insertStyles($html, $additionnalCss = array()){
        $cssToInlineStyles = new CssToInlineStyles();
        $htmlCssInline = $cssToInlineStyles->convert($html, file_get_contents(__DIR__ . '/../../../web/css/styles.css'));
        $htmlCssInline = $cssToInlineStyles->convert($htmlCssInline, file_get_contents(__DIR__ . '/../../../web/libs/bootstrap-3.3.6/css/bootstrap.min.css'));
        $htmlCssInline = $cssToInlineStyles->convert($htmlCssInline, file_get_contents(__DIR__ . '/../../../web/libs/bootstrap-3.3.6/css/bootstrap-theme.min.css'));

        foreach ($additionnalCss as $css){
            $htmlCssInline = $cssToInlineStyles->convert($htmlCssInline, file_get_contents($css));
        }
        return $htmlCssInline;
    }
}