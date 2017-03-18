<?php


class LunyTagsUsageTest extends TestCase
{
    protected $page;


    /**
     * Execute setup before each test
     */
    public function setUp()
    {
        parent::setUp();

        // we create several tag models
        foreach (['test', 'laravel', 'testing', 'redis'] as $tag) {
            $tagStub = \TagStub::create([
                'name' => $tag,
                'slug' => str_slug($tag),
            ]);
        }
        // we create a page stub
        $this->page = \PageStub::create([
            'title' => 'this is the title',
            'body' => 'this is the body of my page',
        ]);

    }

    /**
     * test page->tag(['tag1', 'tag2'])
     */
    public function testCanTagPage()
    {
        $this->page->tag(['laravel', 'test']);

        $this->assertCount(2, $this->page->tags);

        foreach (['test', 'laravel'] as $tag) {
            $this->assertContains($tag, $this->page->tags->pluck('name'));
        }
    }

    /**
     * test page->untag(['some_tag']);
     */
    public function testCanUntagFromPage()
    {
        $this->page->tag(['laravel', 'test']);

        $this->assertCount(2, $this->page->tags);

        $this->page->untag(['laravel']);

        $this->assertCount(1, $this->page->tags()->get());

        $this->page->untag(['test']);

        // reload the tags.
        $this->page->load('tags');

        $this->assertCount(0, $this->page->tags);

        foreach (['test', 'laravel'] as $tag) {
            $this->assertNotContains($tag, $this->page->tags->pluck('name'), "The tag was contained in the page");
        }
    }

    /**
     * test page->untag()
     */
    public function testCanUntagAllFromPage()
    {
        // we tag our page with two tags
        $this->page->tag(['laravel', 'test']);

        $this->assertCount(2, $this->page->tags);

        $this->page->untag();

        // reload the tags.
        $this->page->load('tags');

        $this->assertCount(0, $this->page->tags);

        foreach (['test', 'laravel'] as $tag) {
            $this->assertNotContains($tag, $this->page->tags->pluck('name'), "The tag was contained in the page");
        }
    }


    /**
     * test page->untag()
     */
    public function testCanTagSameTagTwice()
    {
        $this->page->tag(['laravel', 'test']);

        $this->assertCount(2, $this->page->tags);

        $this->page->tag(['laravel', 'test']);

        $this->page->load('tags');

        $this->assertCount(2, $this->page->tags);

        foreach (['test', 'laravel'] as $tag) {
            $this->assertContains($tag, $this->page->tags->pluck('name'), "The tag was Not contained in the page");
        }
    }

    public function testIfNonExistingTagsAreIgnored()
    {
        $this->page->tag(['nonExistingTag', 'anothernonExistingTag']);
        $this->assertCount(0, $this->page->tags);
    }


    /** @test */
    public function testCanTagWithATagModelAndNotString()
    {
        $this->page->tag(\TagStub::where('slug', 'laravel')->first());

        $this->page->load('tags');

        $this->assertCount(1, $this->page->tags);

        $this->assertContains('laravel', $this->page->tags->pluck('name'));
    }

}
