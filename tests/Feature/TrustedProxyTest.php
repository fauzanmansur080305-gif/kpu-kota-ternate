<?php

namespace Tests\Feature;

use Tests\TestCase;

class TrustedProxyTest extends TestCase
{
    public function test_forwarded_https_is_used_for_route_and_asset_urls(): void
    {
        $this->withServerVariables([
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('href="https://localhost/kpu-logo.png?v=20261005"', false)
            ->assertSee('href="https://localhost/login"', false);

        $this->get('/tamu')
            ->assertOk()
            ->assertSee('action="https://localhost/tamu"', false);

        $this->get('/login')
            ->assertOk()
            ->assertSee('action="https://localhost/login"', false);
    }
}
