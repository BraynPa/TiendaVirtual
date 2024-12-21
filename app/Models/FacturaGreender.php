<?php 

namespace App\Models;
use Illuminate\Support\Facades\Storage;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Dompdf\Dompdf;
use Dompdf\Options;
class FacturaGreender{
    private $certificatePath = 'certificate/certificate.pem';
    public $rutaXml; 
    public $rutaCdr; 
    public $rutaPdf; 
    public $see;

    public function generarSee(){
        $this->see = new See();
        $certificateContent = Storage::disk('private')->get($this->certificatePath);
        $this->see->setCertificate($certificateContent);
        $this->see->setService(SunatEndpoints::FE_BETA);
        $this->see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');
        
    }
    public function createInvoice($invoice)
    {
        
        $this->rutaXml = 'xml/'.$invoice->getName().'.xml';
        $this->rutaCdr = 'zips/R-'.$invoice->getName().'.zip';
        $result = $this->see->send($invoice);
        Storage::put($this->rutaXml, $this->see->getFactory()->getLastXml());
        Storage::put($this->rutaCdr, $this->see->getFactory()->getLastXml());
        $this->generarPdf($invoice);
        return $result;
    }
    public function defineStatus($result){
        $cdr = $result->getCdrResponse();

        $code = (int)$cdr->getCode();

        if ($code === 0) {
            return 'Aceptada';
        } else if ($code >= 2000 && $code <= 3999) {
            return 'Rechazada';
        } 
        return 'Other Error';
    }
    private function generarPdf($invoice){
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Crear el contenido HTML para el PDF.
        $html = "
            <h1>Factura: {$invoice->getSerie()}-{$invoice->getCorrelativo()}</h1>
            <p>Cliente: {$invoice->getClient()->getRznSocial()}</p>
            <p>Fecha de Emisión: {$invoice->getFechaEmision()->format('d/m/Y')}</p>
            <p>Moneda: {$invoice->getTipoMoneda()}</p>
            <table border='1' cellpadding='5' cellspacing='0' style='width: 100%;'>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
        ";

        // Agregar los detalles del producto.
        foreach ($invoice->getDetails() as $detail) {
            $html .= "
                <tr>
                    <td>{$detail->getDescripcion()}</td>
                    <td>{$detail->getCantidad()}</td>
                    <td>{$detail->getMtoPrecioUnitario()}</td>
                    <td>{$detail->getMtoValorVenta()}</td>
                </tr>
            ";
        }

        $html .= "
                </tbody>
            </table>
            <p>Total IGV: {$invoice->getMtoIGV()}</p>
            <p>Total a Pagar: {$invoice->getMtoImpVenta()}</p>
        ";

        // Cargar el HTML en Dompdf.
        $dompdf->loadHtml($html);

        // Configurar el tamaño y la orientación del papel.
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF.
        $dompdf->render();

        // Obtener el contenido del PDF.
        $pdfOutput = $dompdf->output();

        // Guardar el PDF en storage/app/private.
        $this->rutaPdf = "invoices/{$invoice->getSerie()}-{$invoice->getCorrelativo()}.pdf";
        Storage::disk('public')->put($this->rutaPdf, $pdfOutput);
    }

    public function downloadInvoice($fileName)
    { 
        return storage_path('app/' . $fileName);
    }

    private function getClient()
    {
        //return Http::withBasicAuth(env('FACTURAMA_USER'), env('FACTURAMA_PASSWORD'));
    }
}