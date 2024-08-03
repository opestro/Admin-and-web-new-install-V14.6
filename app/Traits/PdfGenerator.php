<?php

namespace App\Traits;



trait  PdfGenerator
{
    public static function generatePdf($view, $filePrefix, $filePostfix, $pdfType=null, $requestFrom='admin'): string
    {
        $mpdf = new \Mpdf\Mpdf(['default_font' => 'FreeSerif', 'mode' => 'utf-8', 'format' => [190, 250], 'autoLangToFont' => true]);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        if($pdfType = 'invoice'){
            $footerHtml = self::footerHtml($requestFrom);
            $mpdf->SetHTMLFooter($footerHtml);
        }
        $mpdf_view = $view;
        $mpdf_view = $mpdf_view->render();
        $mpdf->WriteHTML($mpdf_view);
        $mpdf->Output($filePrefix . $filePostfix . '.pdf', 'D');

    }

    public static function footerHtml(string $requestFrom):string
    {
        $getCompanyPhone = getWebConfig(name: 'company_phone');
        $getCompanyEmail = getWebConfig(name: 'company_email');
        if($requestFrom == 'web' && theme_root_path() == 'theme_aster' || theme_root_path() == 'theme_fashion'){
            return  '<div style="width:560px;margin: 0 auto;background-color: #1455AC">
                <table class="fz-10">
                    <tr>
                        <td style="padding: 10px">
                            <span style="color:#ffffff;">'.url('/').'</span>
                        </td>
                        <td style="padding: 10px">
                            <span style="color:#ffffff;">'.$getCompanyPhone.'</span>
                        </td>
                        <td style="padding: 10px">
                            <span style="color:#ffffff;">'.$getCompanyEmail.'</span>
                        </td>
                    </tr>
                </table>
            </div>';
        }else{
            return  '<div style="width:520px;margin: 0 auto;background-color: #F2F4F7;padding: 11px 19px 10px 32px;">
            <table class="fz-10">
                <tr>
                    <td>
                        <span>'.url('/').'</span>
                    </td>
                    <td>
                        <span>'.$getCompanyPhone.'</span>
                    </td>
                    <td>
                        <span>'.$getCompanyEmail.'</span>
                    </td>
                </tr>
            </table>
        </div>';
        }

    }
}
