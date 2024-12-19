<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('application_configuration')->insert([
            [
            'name' => 'invoice_email_body',
            'value' => <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - FastDev Labs</title>
    <style>
        :root {
            --primary-color: #00c7ce;
            --primary-dark: #00a5a9;
            --secondary-color: #2d3436;
            --accent-color: #6c5ce7;
            --background-color: #f5f6fa;
            --border-color: #dfe6e9;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --gradient-start: #00c7ce;
            --gradient-end: #00a5a9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: var(--secondary-color);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .invoice-container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .invoice-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
        }

        .invoice-header {
            background: linear-gradient(135deg, #ffffff 0%, #f5f6fa 100%);
            padding: 40px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .company-logo {
            max-width: 200px;
            height: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .invoice-details {
            text-align: right;
            position: relative;
        }

        .invoice-details h1 {
            color: var(--primary-color);
            font-size: 3rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .invoice-details p {
            margin: 8px 0;
            font-size: 1.1rem;
            color: var(--secondary-color);
            position: relative;
            padding-right: 20px;
        }

        .invoice-content {
            padding: 40px;
        }

        .client-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-box {
            background: linear-gradient(135deg, #ffffff 0%, #f5f6fa 100%);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .info-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--gradient-start), var(--gradient-end));
        }

        .info-box h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box h3::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: var(--primary-color);
            border-radius: 50%;
        }

        .responsive-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 30px 0;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .responsive-table th {
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 20px;
            text-align: left;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .responsive-table td {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            font-size: 1.1rem;
            background-color: white;
        }

        .responsive-table tbody tr:last-child td {
            border-bottom: none;
        }

        .responsive-table tbody tr:hover td {
            background-color: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .totals {
            margin: 40px 0;
            display: flex;
            justify-content: flex-end;
        }

        .totals table {
            width: 400px;
            background: linear-gradient(135deg, #ffffff 0%, #f5f6fa 100%);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .total-row {
            font-size: 1.4rem;
            color: var(--primary-color);
            font-weight: 700;
        }

        .total-row td {
            padding: 15px 10px;
            border-top: 2px solid var(--primary-color);
        }

        .notes {
            background: linear-gradient(135deg, #ffffff 0%, #f5f6fa 100%);
            padding: 30px;
            border-radius: 15px;
            margin-top: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .notes::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--gradient-start), var(--gradient-end));
        }

        .notes h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .notes p {
            font-size: 0.75rem;
            line-height: 1.8;
            color: #636e72;
        }

        .footer {
            text-align: center;
            background: linear-gradient(135deg, #ffffff 0%, #f5f6fa 100%);
            padding: 30px;
            margin-top: 40px;
            border-top: 1px solid var(--border-color);
        }

        .footer p {
            color: #636e72;
            font-size: 0.75rem;
            margin: 5px 0;
        }



        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
            }
        }

        @media screen and (max-width: 768px) {
            .invoice-container {
                margin: 20px;
            }

            .invoice-header {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .invoice-details {
                text-align: center;
                margin-top: 20px;
            }

            .client-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .responsive-table {
                display: block;
                overflow-x: auto;
            }

            .totals table {
                width: 100%;
            }

            .invoice-details h1 {
                font-size: 2.5rem;
            }
        }

        @media screen and (max-width: 480px) {
            .invoice-content {
                padding: 20px;
            }

            .invoice-details h1 {
                font-size: 2rem;
            }

            .company-logo {
                max-width: 150px;
            }

            .info-box {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <div class="invoice-header">
        <img src="https://fastdevlabs.com/img/demos/it-services/logo.png" alt="FastDev Labs Logo" class="company-logo">
        <div class="invoice-details">
            <h1>INVOICE</h1>
            <p><strong>Invoice #:</strong> %INVOICE_NO%</p>
            <p><strong>Date:</strong> %CURRENT_DATE%</p>
        </div>
    </div>

    <div class="invoice-content">
        <div class="client-info">
            <div class="info-box bill-to">
                <h3>Bill To</h3>
                <p><strong>Name:</strong> %NAME%</p>
                <p><strong>Email:</strong> %EMAIL%</p>
            </div>
            <div class="info-box invoice-info">
                <h3>Payment Details</h3>
                <p><strong>Payment Method:</strong> Bank Transfer/Card</p>
                <p><strong>Status</strong>PAID</p>
            </div>
        </div>

        <table class="responsive-table">
            <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Tax</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>%DESCRIPTION%</td>
                <td>$%PRICE%</td>
                <td>$0.00</td>
            </tr>
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr class="total-row">
                    <td><strong>Total Amount:</strong></td>
                    <td><strong>$%PRICE%</strong></td>
                </tr>
            </table>
        </div>

        <div class="notes">
            <h3>Terms & Conditions</h3>
            <p>
                Our services are provided without contracts, activation fees, or cancellation fees, offering maximum flexibility
                to our clients. Please note that all payments are non-refundable. Clients have 3 working days from the payment
                date to submit any return or dispute requests. After this period, no refund or return requests will be considered.
            </p>
        </div>

        <div class="footer">
            <p>&copy; 2024 FastDev Labs | All Rights Reserved</p>
            <p>1942 Broadway Street 314C, Boulder, CO 80302, USA</p>
            <p>Tel: +1 (719) 403-7392 | Email: support@fastdevlabs.com</p>
        </div>
    </div>
</div>
</body>
</html>
HTML,
            'created_at' => now(),
            'updated_at' => now()
            ], [
                'name' => 'invoice_email_subject',
                'value' => 'Invoice <#%INVOICE_NO%> from FastDev Labs llc',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('application_configuration')
            ->where('name', 'invoice_email_subject')
            ->delete();

        DB::table('application_configuration')
            ->where('name', 'invoice_email_body')
            ->delete();
    }
};
