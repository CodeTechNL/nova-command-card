<?php

namespace Codetech\CommandCard;

use Laravel\Nova\Http\Requests\NovaRequest;

/**
 *
 */
class ExecuteCommandRequest extends NovaRequest
{
    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->input('resource');
    }

    /**
     * @return int
     */
    public function getShopId(): int
    {
        return $this->route()->parameter('webshop')->id;
    }
}