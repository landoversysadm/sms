<?php

namespace Tests\Unit;

use App\BankDetail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BankDetailCreateTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function testBankDetailCreate()
  {

    $countBeforeInsert = BankDetail::all()->count();
    $accountName = 'ola';

    $bankDetail = BankDetail::create([
      'accountName' => $accountName,
      'bankName' => 'scroud',
      'accountNumber' => '12345678'
    ]);

    $countAfterInsert = BankDetail::all()->count();


    $this->assertGreaterThan($countBeforeInsert, $countAfterInsert);
    $this->assertSame($accountName, $bankDetail->accountName);
  }
}
