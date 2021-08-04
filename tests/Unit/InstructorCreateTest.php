<?php

namespace Tests\Unit;

use App\Instructor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstructorCreateTest extends TestCase
{
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function testInsctructorCreate()
  {
    $beforeInsert = Instructor::all()->count();

    //arrngement
    $instructor = Instructor::create([
      'first_name' => 'Emmanuella',
      'last_name' => 'Igbeniedon',
      'email' => 'Emmanuella@gmail.com'
    ]);
    //action
    $afterInsert = Instructor::all()->count();

    //asert
    $this->assertGreaterThan($beforeInsert, $afterInsert);
  }
}
