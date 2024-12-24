<?php
use App\Models\Product;
use App\Models\User;
use Mockery\MockInterface;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;
use App\Mail\NoticeInvoiceCreated;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('is customer', function () {
    $user = user::factory()->create();

    $response = $this->actingAs($user)->get('/home');
    $response->assertStatus(200);
    $response->assertSee("Facturas");
});

it('is admin', function () {
    $user = user::factory()->create([
        'role' => User::ADMIN
    ]);

    $response = $this->actingAs($user)->get('/home');
    $response->assertStatus(200);
    $response->assertSee("+ Product");
});

it('is creats in user', function () {
    $this->expectsDatabaseQueryCount(7);
    $user = user::factory()->create([
        'email' => 'sally@example.com',
    ]);
    $this->assertEquals(1, User::count());
    $this->assertDatabaseCount(User::class,1);
    $this->assertDatabaseHas('users', [
        'email' => 'sally@example.com',
    ]);
    $this->assertDatabaseMissing('users', [
        'role' => '1',
    ]);
    $user->delete();

    $this->assertModelMissing($user);
});
it('returns ok', function () {
        $response = $this->postJson('/webhook');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ok'
             ]);
});
it('Fakes product', function () {
    $mock = mock(Product::class, function (MockInterface $mock) {
        $mock->shouldReceive('luis')->andReturn('hola');
    });

    $this->assertEquals('hola', $mock->luis());
});
it('Fakes GitHub', function () {
    $mock = mock(ProviderUser::class, function ($mock) {
        $mock->shouldReceive('getName')->andReturn('Example');
        $mock->shouldReceive('getEmail')->andReturn('example@gmail.com');
    });

    Socialite::shouldReceive('driver->user')->andReturn($mock);

    $response = $this->get('/auth/callback');

    $response->assertRedirect('/home');
});

it('Fakes Email', function (){
    Mail::fake();

    $user = User::factory()->create();

    $response = $this->get('send-mail/' . $user->id);

    Mail::assertQueued(NoticeInvoiceCreated::class);

    $response->assertStatus(200);
});
