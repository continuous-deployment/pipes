<?php

namespace App\Api\V1\Transformers\Model;

use App\Models\Host;
use League\Fractal\TransformerAbstract;

class HostTransformer extends TransformerAbstract
{
    /**
     * Transforms the host in a suitable array format

     * @param  Host $host Host to transform.
     *
     * @return array
     */
    public function transform(Host $host)
    {
        return $host->toArray();
    }
}
