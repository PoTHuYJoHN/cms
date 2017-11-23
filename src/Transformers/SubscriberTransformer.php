<?php

namespace Webkid\Cms\Transformers;

use Webkid\Cms\Subscriber;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class SubscriberTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var  array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var  array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var  object
     */
    public function transform(Subscriber $subcriber)
    {
        $transformed = [
            'id' => $subcriber->id,
            'email' => $subcriber->email,
            'created_at' => $subcriber->created_at,
            'total' => $subcriber->count(),
        ];

        return $transformed;

    }

}
