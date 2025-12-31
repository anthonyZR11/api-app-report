<?php

namespace App\Exceptions;

use Exception;

class ConflictException extends Exception
{
  public int $status = 409;
}
