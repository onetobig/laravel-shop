<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/16
 * Time: 17:50
 */

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field\Text as BaseText;

class Text extends BaseText
{
    public function render()
    {
        $this->initPlainInput();
        $name = $this->elementName ?: $this->formatName($this->column);
        $this->prepend('<i class="fa fa-pencil fa-fw"></i>')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $name)
            ->defaultAttribute('value', old($name, $this->value()))
            ->defaultAttribute('class', 'form-control '.$this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());
        $this->addVariables([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);
        return parent::render();
    }
}