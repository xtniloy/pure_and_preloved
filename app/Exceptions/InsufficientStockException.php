<?php

namespace App\Exceptions;

use Exception;

/**
 * Thrown during checkout when a requested quantity exceeds available stock.
 * Used to roll back the order transaction without swallowing real DB errors.
 */
class InsufficientStockException extends Exception
{
}
