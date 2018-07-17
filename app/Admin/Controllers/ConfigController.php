<?php

namespace App\Admin\Controllers;

use Encore\Admin\Config\ConfigController as BaseConfigController;
use Encore\Admin\Config\ConfigModel;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ConfigController extends BaseConfigController
{
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('配置');
            $content->body($this->grid());
        });
    }

    public function grid()
    {
        return Admin::grid(ConfigModel::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->name('配置名称')->display(function ($name) {
                return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
            });
            $grid->value('值');
            $grid->description('描述');

            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name');
                $filter->like('value');
            });
        });
    }

    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('添加配置');
            $content->body($this->form());
        });
    }

    public function form()
    {
        return Admin::form(ConfigModel::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name', '配置名称')->rules('required');
            $form->textarea('value', '配置值')->rules('required');
            $form->textarea('description', '配置描述');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }

    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('编辑配置');
            $content->body($this->form()->edit($id));
        });
    }
}
