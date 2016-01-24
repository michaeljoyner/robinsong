<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/23/16
 * Time: 3:30 PM
 */

namespace App\Services;


class Breadcrumbs
{
    public static function makeFor($model)
    {
        $list = [];

        while($model) {
            array_unshift($list, [
                'name' => $model->getBreadcrumbName(),
                'url' => $model->getBreadcrumbUrl()
            ]);

            $model = $model->getBreadcrumbParent();
        }

        array_unshift($list, ['name' => 'shop', 'url' => '/collections']);

        return $list;
    }
}