<?php

namespace Armincms\Targomaan\Contracts;

interface Translatable
{
    /**
     * Driver name of the targomaan.
     *
     * @return [type] [description]
     */
    public function translator(): string;
}
