<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/23/16
 * Time: 3:55 PM
 */

namespace App\Services;


interface BreadcrumbableInterface
{
    public function getBreadcrumbName();

    public function getBreadcrumbUrl();

    public function getBreadcrumbParent();
}