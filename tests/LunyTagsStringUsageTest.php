<?php


class LunyTagsStringUsageTest extends TestCase
{
    protected $page;

    public function setUp()
    {
        parent::setUp();

        foreach (['test', 'laravel', 'testing', 'redis'] as $tag) {
            $tagStub = \TagStub::create([
                'name' => $tag,
                'slug' => str_slug($tag),
            ]);
        }

        $this->page = \PageStub::create([
            'title' => 'adam',
            'body' => 'this is the body',
        ]);

    }

    public function test_can_tag_a_page()
    {
        $this->page->tag(['laravel', 'test']);

        $this->assertCount(2, $this->page->tags);

        foreach (['test', 'laravel'] as $tag) {
            $this->assertContains($tag, $this->page->tags->pluck('name'));
        }
    }

}
