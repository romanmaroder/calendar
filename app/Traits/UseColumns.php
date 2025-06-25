<?php

namespace App\Traits;

trait UseColumns
{
    /**
     * @param array $makeHidden
     * @param array $makeVisible
     * @return array
     */
    public static function columns( array $makeHidden=[], array $makeVisible=[]): array
    {
        $model = new static();
        $model->makeHidden($makeHidden);
        $model->makeVisible($makeVisible);
        $columns = array_diff($model->getFillable(), $model->getHidden());

        return array_map(function ($item) use ($model) {
            return [$model->field => $item, $model->header => ucfirst($item)];
        }, $columns);
    }

}