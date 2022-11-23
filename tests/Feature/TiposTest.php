<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TiposTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = ['name'=>'Juanasdita','rol'=>1,'email'=>'Crismoo@asdasdasdmailas.com','password'=>'1asasddas34'];
        $response = $this->post('/api/Usuarios/insertar', $data);

        $response->assertStatus(201);
        
        
    }
}
