<?php 

namespace Armincms\Targomaan\Tests\Translators;  

use Armincms\Targomaan\Tests\Aggregate;  
use Armincms\Targomaan\Tests\Fixtures\Post;
use Illuminate\Support\Str;

class JsonTest extends Aggregate
{  
	public function test_translation($value='')
	{ 
	    $post = factory(Post::class)->create();

	    $post->setTranslation('name', 'Hasan', 'en');
	    $post->setTranslation('name', 'حسن', 'fa');  

	    $post->save();   
		
	    $post = $post->fresh();  
	    
	    $this->assertEquals($post->getTranslation('name', null, 'en'), 'Hasan');
	    $this->assertEquals($post->getTranslation('name', null, 'fa'), 'حسن'); 
	}

	public function test_mutation()
	{ 
	    $post = factory(Post::class)->create();  

	    $post->setTranslation('title', 'ali', 'en');
	    $post->setTranslation('title', 'محمد', 'fa');


	    $post->save();     
		
	    $post = $post->fresh();

	    $this->assertEquals($post->getTranslation('title', null, 'en'), Str::title('ali'));
	    $this->assertEquals($post->getTranslation('title', null, 'fa'), Str::title('محمد'));  
	}
}