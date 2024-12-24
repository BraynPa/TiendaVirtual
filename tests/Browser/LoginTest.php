<?php

use Database\Seeders\CategorySeeder;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;
use App\Models\User;

test('LoginPage', function () {
    
    $this->browse(function (Browser $browser, Browser $browserTwo) {
        $response =  $browser->visit('/')
                ->type('email','hola@gmail.com')
                ->type('password','hola')
                ->press('Login');
        $browser->screenshot("FailLogin");
        $response->assertSee('These credentials do not match our records.');

        $responseTwo = $browserTwo->visit('/')
                ->type('email','Test@gmail.com')
                ->type('password','741852963')
                ->press('Login');
        $browserTwo->screenshot("SuccesLogin");
        $responseTwo->assertSee('Dashboard'); 
    });
    
});
test('Login with page', function () {
    $this->browse(function (Browser $browser) {
        $user = User::factory()->create([
            'password' => "testroot",
            'role' => User::ADMIN
        ]);

        $response = $browser->visit(new LoginPage)
            ->type('@email', $user->email)
            ->type('@password', 'testroot')
            ->press("Login");
        
        $response->userAuthenticated();
    });
});

test('Create product', function () {
    $this->browse(function (Browser $browser) {
        $user = User::factory()->create([
            'password' => "testroot",
            'role' => User::ADMIN
        ]);

        $browser->loginAs($user);

        $browser->visit('/home')
            ->clickLink("+ Product")
            ->type('name', "Producto test")
            ->type('description', "Producto test")
            ->type('price', 100);

        $browser->attach('image', __DIR__ . '/Ciudad1_Cajamrca.jpg');

        $browser->press('Crear producto');
    });
});
