<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commodity;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CommodityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $commodities = Commodity::paginate(10);
        return view('commodities.index', compact('commodities'));
    }

    public function fsindex()
    {
        $commodities = Commodity::all();
        $suppliers = Commodity::select('supplier')->distinct()->pluck('supplier');
        $types = Commodity::select('type')->distinct()->pluck('type');
        return view('commodities.fsindex', compact('commodities', 'suppliers', 'types'));
    }

    public function create()
    {
        return view('commodities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'metric'   => 'nullable|string',
            'supplier' => 'required|string|max:255',
        ]);

        // Check for existing commodity with same name, metric, and supplier
        $existing = Commodity::where('name', $request->name)
            ->where('metric', $request->metric)
            ->where('supplier', $request->supplier)
            ->first();

        if ($existing) {
            $existing->quantity += $request->quantity;
            $existing->save();
            return redirect()->route('commodities.fsindex')->with('success', 'Stock updated successfully!');
        } else {
        Commodity::create([
            'name'     => $request->name,
            'type'     => $request->type,
            'quantity' => $request->quantity,
            'metric'   => $request->metric,
            'supplier' => $request->supplier,
        ]);
            return redirect()->route('commodities.fsindex')->with('success', 'Commodity added successfully!');
        }
    }

    public function show($id)
    {
        $commodity = Commodity::findOrFail($id);
        return view('commodities.show', compact('commodity'));
    }

    public function edit($id)
    {
        $commodity = Commodity::findOrFail($id);
        return view('commodities.edit', compact('commodity'));
    }

    public function update(Request $request, $id)
    {
        $commodity = Commodity::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'metric'   => 'nullable|string',
            'supplier' => 'required|string|max:255',
        ]);

        $commodity->update([
            'name'     => $request->name,
            'type'     => $request->type,
            'quantity' => $request->quantity,
            'metric'   => $request->metric,
            'supplier' => $request->supplier,
        ]);

        return back()->with('success', 'Commodity updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $commodity = Commodity::findOrFail($id);
        $commodity->delete();

        return back()->with('success', 'Commodity deleted successfully!');
    }

    /**
     * Display the order stock form
     */
    public function orderStock()
    {
        $commodities = Commodity::orderBy('name')->get();
        return view('commodities.orderstock', ['commodities' => $commodities]);
    }

    /**
     * Generate and download PDF for the order
     */
    public function generateOrderPdf(Request $request)
    {
        $request->validate([
            'order_date' => 'required|date',
            'requested_by' => 'required|string',
            'urgency' => 'required|string',
            'expected_delivery' => 'nullable|date',
            'special_instructions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string',
            'items.*.description' => 'nullable|string',
        ]);

        $orderData = $this->prepareOrderData($request);

        // Generate PDF content directly
        $pdfContent = $this->generatePdfContent($orderData);

        // Generate PDF
        $pdf = PDF::loadHTML($pdfContent);
        
        // Set PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Sanitize the order number to remove invalid filename characters
        $orderNumberForFilename = str_replace(['/', '\\'], '_', $orderData['order_number']);

        // Generate filename
        $filename = 'Stock_Order_' . $orderNumberForFilename . '_' . date('Y-m-d') . '.pdf';

        // Return PDF for download
        return $pdf->download($filename);
    }

    /**
     * Prepare order data for PDF
     */
    private function prepareOrderData(Request $request)
    {
        return [
            'order_number' => $request->input('order_number', 'N/A'),
            'order_date' => $request->input('order_date'),
            'requested_by' => $request->input('requested_by'),
            'supplier_name' => $request->input('supplier_name', 'N/A'),
            'supplier_contact' => $request->input('supplier_contact'),
            'supplier_address' => $request->input('supplier_address'),
            'urgency' => $request->input('urgency'),
            'expected_delivery' => $request->input('expected_delivery'),
            'special_instructions' => $request->input('special_instructions'),
            'items' => $request->input('items', []),
        ];
    }

    /**
     * Generate PDF HTML content directly
     */
    private function generatePdfContent($orderData)
    {
        $itemsHtml = '';
        foreach ($orderData['items'] as $item) {
            $itemsHtml .= '\n    <tr>\n        <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($item['name']) . '</td>\n        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">' . ($item['quantity'] ?? 'N/A') . '</td>\n        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">' . ($item['unit'] ?? 'N/A') . '</td>\n        <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($item['description'] ?? '-') . '</td>\n    </tr>';
        }

        $urgencyColor = '#28a745'; // Green for Low
        if ($orderData['urgency'] === 'Medium') $urgencyColor = '#ffc107';
        elseif ($orderData['urgency'] === 'High') $urgencyColor = '#dc3545';
        elseif ($orderData['urgency'] === 'Critical') $urgencyColor = '#721c24';

        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Stock Order Form - ' . $orderData['order_number'] . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; color: #000; line-height: 1.6; }
                .header { text-align: center; border-bottom: 3px solidrgb(0, 0, 0); padding-bottom: 20px; margin-bottom: 30px; }
                .company-name { font-size: 24px; font-weight: bold; color:rgb(0, 0, 0); margin-bottom: 5px; }
                .document-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; color: #000; }
                .order-number { font-size: 14px; color: #000; }
                .section { margin-bottom: 25px; }
                .section-title { font-size: 16px; font-weight: bold; color:rgb(0, 0, 0); border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 15px; }
                .info-grid { display: table; width: 100%; }
                .info-row { display: table-row; }
                .info-cell { display: table-cell; width: 50%; padding: 5px 10px; }
                .info-label { font-weight: bold; color: #555; font-size: 12px; text-transform: uppercase; }
                .info-value { font-size: 14px; margin-top: 2px; color: #000; }
                .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                .items-table th { background-color: #f8f9fa; border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; font-weight: bold; color: #000; }
                .total-section { display: none; }
                .footer { margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px; }
                .signature-section-wrapper { position: fixed; bottom: 40px; left: 0; width: 100%; }
                .signature-section { display: table; width: 100%; margin-top: 30px; }
                .signature-box { display: table-cell; text-align: center; width: 33%; }
                .signature-line { border-bottom: 1px solid #333; height: 40px; margin-bottom: 5px; }
                .signature-label { font-size: 12px; color: #666; }
                .urgency-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; color: white; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="company-name">PalmNTrack Order Form</div>
            </div>

            <div class="section">
                <div class="section-title">Order Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-cell">
                            <div class="info-label">Order Date</div>
                            <div class="info-value">' . date('F j, Y', strtotime($orderData['order_date'])) . '</div>
                        </div>
                        <div class="info-cell">
                            <div class="info-label">Requested By</div>
                            <div class="info-value">' . htmlspecialchars($orderData['requested_by']) . '</div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell">
                            <div class="info-label">Urgency Level</div>
                            <div class="info-value">
                                <span class="urgency-badge" style="background-color: ' . $urgencyColor . '; color: #fff;">' . $orderData['urgency'] . '</span>
                            </div>
                        </div>
                        <div class="info-cell">
                            <div class="info-label">Expected Delivery</div>
                            <div class="info-value">' . ($orderData['expected_delivery'] ? date('F j, Y', strtotime($orderData['expected_delivery'])) : 'Not specified') . '</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Items to Order</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd; padding: 10px;">Item Name</th>
                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Quantity</th>
                            <th style="border: 1px solid #ddd; padding: 10px; text-align: center;">Unit</th>
                            <th style="border: 1px solid #ddd; padding: 10px;">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $itemsHtml . '
                    </tbody>
                </table>
            </div>

            <div class="total-section" style="display: none;">
                <div class="total-row">
                    <span class="total-label">Subtotal:</span>
                    <span class="total-value">$0.00</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Tax (10%):</span>
                    <span class="total-value">$0.00</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">Grand Total:</span>
                    <span class="total-value">$0.00</span>
                </div>
            </div>

            <div class="signature-section-wrapper">
            <div class="section">
                    <div class="section-title">Approvals</div>
                <div class="signature-section">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">Requested By</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                            <div class="signature-label">Procurement Officer</div>
                        </div>
                    </div>
                    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #000;">
                        <p><strong>Generated on:</strong> ' . now()->format('F j, Y \\a\\t g:i A') . '</p>
                        <p>This document is computer generated and does not require a physical signature.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>';

        return $pdfContent;
    }

    public function stockIn(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'type' => 'nullable|string',
            'metric' => 'nullable|string',
            'supplier' => 'nullable|string',
        ]);

        $commodity = Commodity::where('name', $request->name)->first();
        if ($commodity) {
            $commodity->quantity += $request->quantity;
            $commodity->save();
            return back()->with('success', 'Stock updated successfully!');
        } else {
            Commodity::create([
                'name' => $request->name,
                'quantity' => $request->quantity,
                'type' => $request->type,
                'metric' => $request->metric,
                'supplier' => $request->supplier,
            ]);
            return back()->with('success', 'New commodity added!');
        }
    }
}
