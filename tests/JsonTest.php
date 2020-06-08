<?php 

namespace Armincms\Targomaan\Tests; 

use Armincms\Targomaan\Tests\Fixtures\Post;

class JsonTest extends AggregateTest
{
	/**
	 * Setup the test environment.
	 */
	protected function setUp(): void
	{
	    parent::setUp(); 

	    $post = factory(Post::class)->create();

	    $post->{"name::en"} =  'Hasan';
	    $post->{"name::fa"} =  'حسن'; 
	    
	    $post->save();
	    
	    $post = $post->fresh();
	    
	    $this->assertEquals($post->{"name::en"}, 'Hasan');
	    $this->assertEquals($post->{"name::fa"}, 'حسن');
	} 
}