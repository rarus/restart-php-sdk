<?php
declare(strict_types = 1);

namespace Rarus\Restart\Exceptions;
/**
 * Class RestartException
 *
 * \Exception
 *      RestartException — base class
 *          IoRestartException — I/O network errors
 *          ApiRestartException — API-level exceptions
 *              ItemNotFoundRestartException — Item not found exception
 *
 * @package Rarus\Restart
 */
class RestartException extends \Exception
{
}