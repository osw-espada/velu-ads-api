<?php namespace App\Laravel\Services;

use League\Fractal\Serializer\ArraySerializer;

class DataArraySerializer extends ArraySerializer
{
    /**
     * {@inheritDoc}
     */
    public function collection(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function item(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function null(): ?array
    {
        return [];
    }
}
