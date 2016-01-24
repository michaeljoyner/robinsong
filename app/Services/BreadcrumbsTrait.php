<?php
/**
 * Created by PhpStorm.
 * User: mooz
 * Date: 1/23/16
 * Time: 4:00 PM
 */

namespace App\Services;


trait BreadcrumbsTrait
{
    public function getBreadcrumbName()
    {
        return mb_strtolower($this->{$this->breadcrumblings['build_name_from']});
    }

    public function getBreadcrumbUrl()
    {
        return '/' . $this->breadcrumblings['url_base'] . '/' . $this->{$this->breadcrumblings['url_unique']};
    }

    public function getBreadcrumbParent()
    {
        return $this->breadcrumblings['parent'] ? $this->{$this->breadcrumblings['parent']} : null;
    }
}