<?php

namespace Armincms\Targomaan\Tests\Translators;

use Armincms\Targomaan\Tests\Aggregate;
use Armincms\Targomaan\Tests\Fixtures\Product;

class LayericTranslatorTest extends Aggregate
{
    public function test_translation()
    {
        $page = factory(Product::class)->create();

        $page->setTranslation('name', 'gholi', 'en');

        $page->save();

        $this->assertEquals($page->getTranslation('name', null, 'en'), 'gholi');
    }
}
