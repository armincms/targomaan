<?php

namespace Armincms\Targomaan\Tests\Translators;

use Armincms\Targomaan\Tests\Aggregate;
use Armincms\Targomaan\Tests\Fixtures\Page;
use Illuminate\Support\Str;

class SequentialTest extends Aggregate
{
    public function test_translation()
    {
        $page = factory(Page::class)->create();

        $page->setTranslation('name', 'حسن', 'fa');

        $page->save();

        $this->assertEquals($page->getTranslation('name', null, 'fa'), 'حسن');
    }

    public function test_set_mutation()
    {
        $page = factory(Page::class)->create();

        $page->setTranslation('title', 'spanish', 'es');
        $page->setTranslation('name', 'spanish', 'es');

        $page->save();

        $page->load('translations');

        $this->assertEquals(
            $page->translations->where('locale', 'es')->first()->getOriginal('title'), Str::upper('spanish')
        );
    }

    public function test_get_mutation()
    {
        $page = factory(Page::class)->create();

        $page->setTranslation('title', 'spanish', 'es');
        $page->setTranslation('name', 'spanish', 'es');

        $page->save();

        $page->load('translations');

        $this->assertEquals($page->getTranslation('title', null, 'es'), Str::title('spanish'));
    }
}
