<?php

namespace Thanatos\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderUpdatedNotifyCustomer extends Mailable
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
        return $this->markdown('point-of-sales::emails.orders.notifycustomerUpdate')
        ->subject('Order Update Confirmation #'.$this->data['order']['order_number'])
        ->with(['data'=>$this->data])
        ->from($from)
        ->attach($this->data['pdf']);
    }
}
