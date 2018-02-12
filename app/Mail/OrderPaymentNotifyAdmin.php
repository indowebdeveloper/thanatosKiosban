<?php

namespace Thanatos\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaymentNotifyAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // from who ?
        $from = $this->data['website']['email'];
        return $this->markdown('point-of-sales::emails.payments.notifyadmin')
        ->subject('Payment Receive Order #'.$this->data['order']['order_number'])
        ->with(['data'=>$this->data])
        ->from($from);
    }
}
