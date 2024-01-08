<?php

namespace App\Interfaces;

use App\Models\Examination;

interface TicketInterface
{
  public function store(int $amount, Examination $examination);

  public function generateTicket(int $length);
}
