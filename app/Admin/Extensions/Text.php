<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/18
 * Time: 13:49
 */

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field\Text as BaseText;

class Text extends BaseText
{
    public function render()
    {
        $this->initPlainInput();
        $field_name = $this->elementName ?: $this->formatName($this->column);
        $this->prepend('<i class="fa fa-pencil fa-fw"></i>')
            ->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $field_name)
            ->defaultAttribute('value', old($field_name, $this->value()))
            ->defaultAttribute('class', 'form-control '.$this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder());
        $this->addVariables([
            'prepend' => $this->prepend,
            'append'  => $this->append,
        ]);
        return parent::render();
    }
}