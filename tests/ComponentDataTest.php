<?php

declare(strict_types=1);

namespace Inertia\Tests;

use Inertia\ComponentData;

class ComponentDataTest extends TestCase
{
    public function test_component_name_normalizes_dots_to_slashes(): void
    {
        $data = new ComponentData;
        $data->setComponent('users.index');

        $this->assertSame('users/index', $data->getComponent());

        $data = new ComponentData;
        $data->setComponent('/users/index/');

        $this->assertSame('users/index', $data->getComponent());
    }

    public function test_url_defaults_to_root_path(): void
    {
        unset($_SERVER['REQUEST_URI']);

        $data = new ComponentData;
        $data->setComponent('Users/Index');

        $this->assertSame('/', $data->toArray()['url']);
    }

    public function test_url_preserves_query_string(): void
    {
        $_SERVER['REQUEST_URI'] = '/users?page=2&tab=active';

        $data = new ComponentData;
        $data->setComponent('Users/Index');

        $this->assertSame('/users?page=2&tab=active', $data->toArray()['url']);
    }

    public function test_version_is_null_by_default_and_stable_across_calls(): void
    {
        $data = new ComponentData;
        $data->setComponent('Users/Index');

        $first  = $data->toArray();
        $second = $data->toArray();

        $this->assertNull(ComponentData::getVersion());
        $this->assertNull($first['version']);
        $this->assertSame($first['version'], $second['version']);
    }

    public function test_configured_version_is_returned_verbatim(): void
    {
        ComponentData::setVersion('2026-10-20');

        $data = new ComponentData;
        $data->setComponent('Users/Index');

        $this->assertSame('2026-10-20', $data->toArray()['version']);
        $this->assertSame('2026-10-20', ComponentData::getVersion());
    }

    public function test_set_props_merges_and_keeps_first_values(): void
    {
        $data = new ComponentData;
        $data->setProps(['a' => 1, 'b' => 2]);
        $data->setProps(['b' => 20, 'c' => 3]);

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $data->getProps());
    }
}
