<?php
namespace Jiny\Core\Views;

abstract class AbstractView
{
    public function setViewFile($file)
    {
        $this->view_file = $file;
    }

    public function setViewData($data)
    {
        $this->view_data = $data;
    }
}