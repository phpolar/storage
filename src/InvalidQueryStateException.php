<?php

namespace Phpolar\Phpolar\Storage;

use RuntimeException;

/**
 * Represents the situation where queried
 * data is not found and an alternative
 * action has not been configured.
 */
final class InvalidQueryStateException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            <<<EOM
        The queried data does not exist and an
        alternative action has not been configured.
        Have you forgotten to add an alternative action
        using the orElse method?
        EOM
        );
    }
}
