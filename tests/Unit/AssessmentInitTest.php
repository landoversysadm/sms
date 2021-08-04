<?php

namespace Tests\Unit;

use App\Assessment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssessmentInitTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public $scoreCount;

  public function testInitScoreSheet()
  {

    //action
    $newScores = $this->initScoreSheet();

    //assert
    $this->assertEquals($this->scoreCount, count($newScores));
  }

  public function initScoreSheet()
  {
    $initScore  = [];
    $this->scoreCount = 0;
    $top = Assessment::all();
    if (count($top) < 1) {
      return $initScore;
    } else {
      $scores = json_decode($top->first()->scores);
      $this->scoreCount = count($scores);
      for ($i = 0; $i < $this->scoreCount; $i++) {
        $initScore[] = [
          "title" => $scores[$i]->title,
          "value" => 0, "resits" => []
        ];
      }
      return $initScore;
    }
  }
}
