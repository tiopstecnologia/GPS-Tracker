<?php declare(strict_types=1);

namespace App\Domains\Alarm\Test\Feature;

class Create extends FeatureAbstract
{
    /**
     * @var string
     */
    protected string $route = 'alarm.create';

    /**
     * @var string
     */
    protected string $action = 'create';

    /**
     * @return void
     */
    public function testGetGuestUnauthorizedFail(): void
    {
        $this->get($this->routeToController())
            ->assertStatus(302)
            ->assertRedirect(route('user.auth.credentials'));
    }

    /**
     * @return void
     */
    public function testGetGuestFail(): void
    {
        $this->post($this->routeToController())
            ->assertStatus(302)
            ->assertRedirect(route('user.auth.credentials'));
    }

    /**
     * @return void
     */
    public function testGetAuthEmptySuccess(): void
    {
        $this->authUser();

        $this->get($this->routeToController())
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testGetAuthSuccess(): void
    {
        $this->authUser();
        $this->factoryCreate();

        $this->get($this->routeToController())
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testPostAuthEmptySuccess(): void
    {
        $this->authUser();

        $data = $this->factoryMake()->toArray();

        $this->post($this->routeToController(), $data + $this->action())
            ->assertStatus(302)
            ->assertRedirect(route('alarm.update', $this->rowLast()->id));

        $row = $this->rowLast();

        foreach ($data as $key => $value) {
            $this->assertEquals($row->$key, $value);
        }
    }

    /**
     * @return void
     */
    public function testPostAuthSuccess(): void
    {
        $this->authUser();
        $this->factoryCreate();

        $data = $this->factoryMake()->toArray();

        $this->post($this->routeToController(), $data + $this->action())
            ->assertStatus(302)
            ->assertRedirect(route('alarm.update', $this->rowLast()->id));

        $row = $this->rowLast();

        foreach ($data as $key => $value) {
            $this->assertEquals($row->$key, $value);
        }
    }

    /**
     * @return string
     */
    protected function routeToController(): string
    {
        return $this->route();
    }
}
