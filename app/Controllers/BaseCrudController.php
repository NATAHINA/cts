<?php

namespace App\Controllers;

use CodeIgniter\Controller;

abstract class BaseCrudController extends BaseController
{
    protected string $viewPath = '';
    protected string $modelClass = '';

    protected string $moduleTitle = '';

    protected $model;

    public function __construct()
    {
        if ($this->modelClass !== '') {
            $this->model = model($this->modelClass);
        }
    }

    protected function getTenantId(): int
    {
        return (int) session('tenant_id');
    }

    protected function indexData(): array
    {
        return [
            'title' => $this->moduleTitle,
        ];
    }
}