<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class InvoiceMailable extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public bool $isForAdmin;

    public function __construct(Order $order, bool $isForAdmin = false)
    {
        $this->order = $order->load('user');
        $this->isForAdmin = $isForAdmin;
    }

    public function envelope(): Envelope
    {
        $subject = $this->isForAdmin
            ? 'Laporan Pembelian Baru: Pesanan #' . $this->order->id
            : 'Konfirmasi Pembayaran & Faktur Pesanan #' . $this->order->id;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_body', // Ganti dengan view yang kamu pakai
            // Jika ingin pakai data tambahan, bisa pakai with: [ ... ]
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('invoices.purchase_report', ['order' => $this->order]);
        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice-'.$this->order->id.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}