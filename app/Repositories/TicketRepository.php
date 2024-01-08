<?php

namespace App\Repositories;

use App\Interfaces\TicketInterface;
use App\Models\Examination;
use App\Models\Ticket;

class TicketRepository implements TicketInterface
{

  public function store($amount = 1, Examination $examination)
  {

    for ($index = 1; $index <= $amount; $index++) {
      $ticket = new Ticket;
      $ticket->examination_id = $examination->id;
      $ticket->price = $examination->price;
      $ticket->number = $index;
      $ticket->code = $this->generateTicket();
      $ticket->save();
    }
  }

  public function generateTicket($length = 15)
  {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $ticket = '';

    for ($i = 0; $i < $length; $i++) {
      $ticket .= $characters[rand(0, strlen($characters) - 1)];
    }

    $ticketExist = Ticket::where('code', $ticket)->count();

    if ($ticketExist > 0) {
      return $this->generateTicket();
    }
    return $ticket;
  }

}
