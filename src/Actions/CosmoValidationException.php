<?php
declare(strict_types=1);
namespace DirectorMoav\CosmoBushG\Actions;

/**
 * Strictly to be used when a Validation fails
 * Any other exception or error thrown is an unhandled
 * exception that should be cought in the system using CosmoBushG
 */
class CosmoValidationException extends \Exception{}