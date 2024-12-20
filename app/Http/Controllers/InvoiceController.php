<?php

namespace App\Http\Controllers;

use App\Models\FacturaGreender;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use DateTime;

class InvoiceController extends Controller
{
    public function index(){
        $invoices = auth()->user()->orders()->actives()->get();
        return view('invoices.index', [
            'invoices' => $invoices
        ]);
    }
    public function store(FacturaGreender $factura, Request $request){
        $order = Order::find($request->invoice_id);
        
        $factura->generarSee();
        // Cliente
        $client = new Client();
        $client->setTipoDoc('1')
            ->setNumDoc('20203030')
            ->setRznSocial('PERSON 1');
            // Emisor
        $address = (new Address())
        ->setUbigueo('150101')
        ->setDepartamento('LIMA')
        ->setProvincia('LIMA')
        ->setDistrito('LIMA')
        ->setUrbanizacion('-')
        ->setDireccion('Av. Villa Nueva 221')
        ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
        ->setRuc('20123456789')
        ->setRazonSocial('GREEN SAC')
        ->setNombreComercial('GREEN')
        ->setAddress($address);

        // Venta
        $invoice = new Invoice();
        $invoice
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc('03')
            ->setSerie('B001')
            ->setCorrelativo('2')
            ->setFechaEmision(new DateTime())
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(100)
            ->setMtoIGV(18)
            ->setTotalImpuestos(18)
            ->setValorVenta(100)
            ->setSubTotal(118)
            ->setMtoImpVenta(118)
            ;

        $item1 = new SaleDetail();
        $item1->setCodProducto('C023')
            ->setUnidad('NIU')
            ->setCantidad(2)
            ->setDescripcion('PROD 1')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18)
            ->setIgv(18)
            ->setTipAfeIgv('10')
            ->setTotalImpuestos(18)
            ->setMtoValorVenta(100)
            ->setMtoValorUnitario(50)
            ->setMtoPrecioUnitario(59);

        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue('SON CIENTO DIECIOCHO CON 00/100 SOLES');

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

        $invoice->setDetails([$item1])
                ->setLegends([$legend]);

        $response = $factura->createInvoice($invoice);
        $order->update(['factura_id' => $response->getCdrResponse()->getId(), 'pdf_file' => $factura->rutaPdf , 'xml_file' => $factura->rutaXml, 'cdr_file' => $factura->rutaCdr]);

        return redirect()->route('invoices.index');
    }
    public function download(Order $order)
    {
        // return response()->download($order->downloadInvoice());
        return response()->download($order->downloadInvoice());
    }
}
