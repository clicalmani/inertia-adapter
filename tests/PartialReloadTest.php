<?php

declare(strict_types=1);

namespace Inertia\Tests;

use Inertia\ComponentData;
use Inertia\Support\Header;

class PartialReloadTest extends TestCase
{
    private const PROPS = [
        'users'   => ['Ada', 'Grace'],
        'filters' => ['search' => ''],
        'flash'   => 'Bonjour',
    ];

    public function test_full_props_without_current_request(): void
    {
        $this->assertSame(self::PROPS, $this->component()->toArray()['props']);
    }

    public function test_full_props_when_request_has_no_partial_headers(): void
    {
        $this->inertiaRequest();

        $this->assertSame(self::PROPS, $this->component()->toArray()['props']);
    }

    public function test_full_props_for_another_component(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'Other/Component',
            Header::PARTIAL_ONLY->value      => 'users',
        ]);

        $this->assertSame(self::PROPS, $this->component()->toArray()['props']);
    }

    public function test_full_props_when_component_matches_but_only_header_missing(): void
    {
        $this->inertiaRequest([Header::PARTIAL_COMPONENT->value => 'Users/Index']);

        $this->assertSame(self::PROPS, $this->component()->toArray()['props']);
    }

    public function test_only_keeps_requested_props(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'Users/Index',
            Header::PARTIAL_ONLY->value      => 'users,filters',
        ]);

        $props = $this->component()->toArray()['props'];

        $this->assertSame(['users', 'filters'], array_keys($props));
        $this->assertSame(['Ada', 'Grace'], $props['users']);
    }

    public function test_only_keys_are_trimmed(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'Users/Index',
            Header::PARTIAL_ONLY->value      => ' users , filters ',
        ]);

        $this->assertSame(
            ['users', 'filters'],
            array_keys($this->component()->toArray()['props'])
        );
    }

    public function test_except_removes_props_from_the_only_selection(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'Users/Index',
            Header::PARTIAL_ONLY->value      => 'users,filters',
            Header::PARTIAL_EXCEPT->value    => 'filters',
        ]);

        $this->assertSame(
            ['users'],
            array_keys($this->component()->toArray()['props'])
        );
    }

    public function test_unknown_only_keys_fall_back_to_full_props(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'Users/Index',
            Header::PARTIAL_ONLY->value      => 'nonexistent',
        ]);

        $this->assertSame(self::PROPS, $this->component()->toArray()['props']);
    }

    public function test_mixed_only_and_unknown_keys_keeps_known_intersection(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'Users/Index',
            Header::PARTIAL_ONLY->value      => 'users,nonexistent',
        ]);

        $this->assertSame(
            ['users'],
            array_keys($this->component()->toArray()['props'])
        );
    }

    public function test_except_without_only_keeps_full_props(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'Users/Index',
            Header::PARTIAL_EXCEPT->value    => 'users',
        ]);

        $this->assertSame(self::PROPS, $this->component()->toArray()['props']);
    }

    public function test_dotted_component_name_matches_normalized_header_value(): void
    {
        $this->inertiaRequest([
            Header::PARTIAL_COMPONENT->value => 'users/index',
            Header::PARTIAL_ONLY->value      => 'flash',
        ]);

        $data = new ComponentData;
        $data->setComponent('users.index');
        $data->setProps(self::PROPS);

        $this->assertSame(
            ['flash'],
            array_keys($data->toArray()['props'])
        );
    }

    private function component(): ComponentData
    {
        $data = new ComponentData;
        $data->setComponent('Users/Index');
        $data->setProps(self::PROPS);

        return $data;
    }
}
