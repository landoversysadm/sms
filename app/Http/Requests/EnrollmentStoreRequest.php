<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'title' => 'required',
      'surname' => 'required',
      'firstname' => 'required',
      'dob' => 'required',
      'pob' => 'required',
      'rAddress' => 'required',
      'mobilePhone' => 'required|numeric',
      'email' => 'required|email',
      'rCountry' => 'required',
      'rState' => 'required',
      'alumni' => 'required',
      'emergency_name' => 'required',
      'emergency_relationship' => 'required',
      'emergency_number' => 'required|numeric',
      'emergency_email' => 'required|email',
      'ref_name1' => 'required',
      'ref_name2' => 'required',
      'ref_relationship1' => 'required',
      'ref_relationship2' => 'required',
      'ref_address1' => 'required',
      'ref_address2' => 'required',
      'ref_number1' => 'required',
      'ref_number2' => 'required',
      'ref_email1' => 'required|email',
      'ref_email2' => 'required|email',
      'acceptTerms' => 'required',
      'requirement_file' => 'array',
      'requirement_title' => 'array',
      'company' => 'array',
      'year' => 'array',
      'alumni_course' => 'required',
      'alumni_date' => 'required',
      'alumni' => 'required',
    ];
  }
}
