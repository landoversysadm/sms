<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Overland Learning management system">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('img/favicon.png')}}" rel="shortcut icon" />
    <link href="{{asset('css/all.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/enroll.css')}}">
    <title>Landover LABS</title>

<style>
    .list-roman li{
        list-style: inside upper-roman;
        text-align: left;
        margin-top: 12px;
        font-size: 13px;
    }
    .list-roman{
        padding: 15px;
    }

</style>
</head>

<body>

    <div id="app">
            @include('errors')
  <!-- multistep form -->
<form id="msform" method="POST" action="{{route('enroll',$course->id)}}" enctype="multipart/form-data">
    {{ csrf_field() }}
        <!-- progressbar -->
        @if (isset($error))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>An error occured</strong> An error occured while submitting the form
            </div>
        @endif

        <div class="col-md-12 text-center"><h3 style="color:#fff !important; text-transform:uppercase">{{$course->title}}</h3></div>
        <ul id="progressbar">
          <li class="active">Personal Details</li>
          <li>Requirements</li>
          <li>others</li>
          <li>Terms and Condition</li>


        </ul>
        <!-- fieldsets -->

        <fieldset class="personalField">
          <h3 class="fs-subtitle">Fill in your personal details? (Please complete all sections of this application form) </h3>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="passport float-right d-flex justify-content-center" style="">
                        @if (!empty(Auth::user()->student->profile_picture_url))
                        <img src="/storage/{{ str_replace('public','',Auth::user()->student->profile_picture_url.' ')}}" id="uploadedImg" style="display:block;"/>
                        @else
                        <img src="" id="uploadedImg"/>
                        <span class="align-self-center"><i class="fa fa-camera"></i></span>
                        @endif

                    </div>
                    <input type="file" class="custom-file-input passport-upload" name="passport" id="passportUpload"  @if (empty(Auth::user()->student->profile_picture_url))required @endif>
                    <label class="custom-file-label passport-upload-label hidden" for="customFile">Choose Passport </label>
                </div>
                <div class="col-md-12">
                    <span class="float-right d-block">Upload your passport photograph, white background</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <select name="title" id="title" class="form-control" required>
                            <option value="">Select title</option>
                            @if (!empty(Auth::user()->student->title))
                            <option @if (Auth::user()->student->title == 'Miss')
                                    selected
                                @endif value="Miss">Miss</option>
                                <option @if (Auth::user()->student->title == 'Master')
                                        selected
                                    @endif value="Master">Master</option>
                                <option @if (Auth::user()->student->title == 'Mrs')
                                        selected
                                    @endif value="Mrs">Mrs.</option>
                                <option @if (Auth::user()->student->title == 'Mr')
                                        selected
                                    @endif value="Mr">Mr.</option>
                            @else
                            <option value="Miss">Miss</option>
                            <option value="Master">Master</option>
                            <option value="Mrs">Mrs.</option>
                            <option value="Mr">Mr.</option>
                            @endif

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="surname" id="surname" placeholder="Surname" readonly value="{{Auth::user()->last_name}}" >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                            <input type="text" name="firstname" id="firstname" placeholder="Firstname" readonly value="{{Auth::user()->first_name}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                    <input type="text" name="middlename" id="middlename" placeholder="Middlename" @if (!empty(Auth::user()->midlle_name))
                        value="{{ Auth::user()->midlle_name }}"
                    @else
                      value="{{old('middlename') }}"
                    @endif >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                            <input type="date" name="dob" id="dob" placeholder="Date of Birth" data-placeholder="Date of birth" required @if (!empty(Auth::user()->student->date_of_birth))
                            value="{{ Auth::user()->student->date_of_birth }}"
                        @else
                          value="{{old('dob')}}"
                        @endif >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{-- <input type="text" name="pob" id="pob" placeholder="Nationality" required  @if (!empty(Auth::user()->student->place_of_birth))
                            value="{{Auth::user()->student->place_of_birth}}"
                        @else
                        value="{{old('pob')}}"
                        @endif> --}}
                        <select name="pob" id="pob" class="form-control crs-country"
                        data-default-option="Nationality" data-preferred="NG,GH"
                          required
                        @if (!empty(Auth::user()->student->place_of_birth))
                            data-default-value="{{Auth::user()->student->place_of_birth}}"
                        @else
                        data-default-value={{old('pob')}}
                        @endif

                        >
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <select name="rCountry" id="rCountry" class="form-control crs-country"
                        data-default-option="Select country of residence" data-preferred="NG,GH"
                         data-region-id="rcountry-crs" required
                        @if (!empty(Auth::user()->student->residential_country))
                            data-default-value="{{Auth::user()->student->residential_country}}"
                        @else
                        data-default-value={{old('residential_country')}}
                        @endif
                        >
                        </select>
                </div>

              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <select name="rState" id="rcountry-crs" class="form-control"
                        data-default-option="Select state of residence" required
                        @if (!empty(Auth::user()->student->place_of_birth))
                            data-default-value="{{Auth::user()->student->residential_state}}"
                        @else
                        data-default-value={{old('residential_state')}}
                        @endif
                        >
                        </select>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                            <textarea name="rAddress" placeholder="Street Address" id="rAddress" cols="30" rows="2" required >@if (!empty(Auth::user()->student->residential_address)){{Auth::user()->student->residential_address}}
                                  @endif</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                            <textarea name="mAddress" placeholder="Mailing Address" id="mAddress" cols="30" rows="2" >@if (!empty(Auth::user()->student->mailing_address)) {{Auth::user()->student->mailing_address}}
                                @endif</textarea>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <input type="tel" name="mobilePhone" id="mobilePhone" placeholder="Mobile Phone" required @if (!empty(Auth::user()->student->mobile_phone))
                        value="{{Auth::user()->student->mobile_phone}}"
                    @else
                        value="{{old('mobilePhone')}}""
                    @endif >
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <input type="tel" name="workPhone" id="workPhone" placeholder="Work Phone" @if (!empty(Auth::user()->student->work_phone))
                          value="{{Auth::user()->student->work_phone}}"
                      @else
                          value="{{old('workPhone')}}"
                      @endif >
                  </div>
              </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="email" name="email" id="email" placeholder="Email Address" readonly value="{{Auth::user()->email}}" >
                    </div>
                </div>
            </div>

            <input type="button" name="next" class="next action-button mt-2" id="personalFieldNext" value="Next" />
        </fieldset>

        <fieldset class="educationField">
          <span>Dear applicant, kindly note that the aceptable file formats are images, .PDF, .DOC and .DOCX (Max file size 1mb)</span>
            <?php
                $requirements = json_decode($course->requirement);
                for($i=0; $i<count($requirements); $i++){
            ?>
             <h3 class="fs-subtitle mt-4">{{$requirements[$i]->title}}
                @if ($requirements[$i]->type !== 'check')
                <i class="fa fa-plus-circle one addMore" aria-hidden="true" style="font-size: 20px; color: #16462b;" data-target="requirement{{$i}}" data-type="{{$requirements[$i]->type}}"></i>
                <i class="fa fa-minus-circle hidden subtractMore" aria-hidden="true" style="font-size: 20px; color: #16462b;" id="subtractrequirement{{$i}}" data-target="requirement{{$i}}"></i>
                @endif
              </h3>
              <div class="addSection requirement{{$i}} " >
              <div class="education">
                  @if ($requirements[$i]->type === 'file')
                    <div class="row">
                      <div class="col-md-12 mb-2">
                      <small class="form-text text-muted">{{$requirements[$i]->text}}</small>
                    </div>
                        <div class="col-md-6 mb-2">
                        <input type="text" placeholder="E.g. File tiltle" name="requirement_title[]" required>
                    </div>
                    <div class="col-md-6 mb-2">
                            <input type="file" placeholder="Select file" name="requirement_file[]" required>
                    </div>
                    </div>
                  @elseif($requirements[$i]->type === 'text')
                  <div class="row">
                    <div class="col-md-12 mb-2">
                      <small class="form-text text-muted">{{$requirements[$i]->text}}</small>
                    </div>
                    <div class="col-md-12 mb-2">
                        <input type="hidden" value="{{$requirements[$i]->title}}" name="textQuestion[]" required>
                        <input type="text" placeholder="Response" name="textResponse[]" required>
                    </div>

                  </div>
                  @else
                  <div class="row"><div class="col-md-6 mb-2">
                        <label>{{$requirements[$i]->text}}</label>
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="hidden" name="checkTitle[]"  value="{{$requirements[$i]->title}}">
                        <input type="checkbox" name="check[]" required>
                    </div></div>
                  @endif

                </div>
            </div>
             <?php
                }
             ?>



          <input type="check" name="previous" class="previous action-button" value="Previous" />
            <input type="button" name="next" class="next action-button mt-2" value="Next" id="educationFieldNext" />
        </fieldset>

        <fieldset class="alumniField">
          <h3 class="fs-subtitle">Are you an alumni?</h3>
            <div class="d-flex" style="margin:auto; width:113px;">
                <span class="fs-subtitle">Yes</span> <input type="radio" name="alumni" value="yes" class="alumniCheck">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <span class="fs-subtitle">No</span><input type="radio" name="alumni" value="no"  checked  class="alumniCheck">
            </div>


            <div class="hidden row" id="alumniInput">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="alumni_course[]" placeholder="Enter course title" required/>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="date" name="alumni_date[]" placeholder="Date" required data-placeholder="Course date"/>
                    </div>

                </div>

            </div>


          <h3 class="fs-subtitle">Incase of Emergency? Call</h3>
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                       <input type="text" name="emergency_name" placeholder="Name"  required @if (!empty(Auth::user()->student->emergency_name))
                        value="{{Auth::user()->student->emergency_name}}"
                    @else
                        value="{{old('emergency_name')}}"
                    @endif />
                  </div>

              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <input type="text" name="emergency_relationship" placeholder="Relationship" required @if (!empty(Auth::user()->student->emergency_relationship))
                    value="{{Auth::user()->student->emergency_relationship}}"
                    @else
                    value="{{old('emergency_relationship')}}"
                    @endif />
                  </div>

              </div>
          </div>
          <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <input type="tel" name="emergency_number" placeholder="Phone Number" required @if (!empty(Auth::user()->student->emergency_mobile_phone))
                    value="{{Auth::user()->student->emergency_mobile_phone}}"
                    @else
                    value="{{old('emergency_number')}}"
                    @endif />
                  </div>

              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <input type="email" name="emergency_email" placeholder="Email Address" required  @if (!empty(Auth::user()->student->emergency_email))
                    value="{{Auth::user()->student->emergency_email}}"
                    @else
                    value="{{old('emergency_email')}}"
                    @endif />
                  </div>

              </div>
          </div>

          <h3 class="fs-subtitle mt-4">Please give name and address of two references</h3>
          <div class="row">


            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="education" name="ref_name1" placeholder="Full Name" required @if (!empty(Auth::user()->student->ref_name1))
                            value="{{Auth::user()->student->ref_name1}}"
                        @else
                        value="{{old('ref_name1')}}"
                        @endif >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                     <input type="text" class="education" name="ref_name2" placeholder="Full Name" required @if (!empty(Auth::user()->student->ref_name2))
                        value="{{Auth::user()->student->ref_name2}}"
                    @else
                    value="{{old('ref_name2')}}"
                    @endif >
                </div>
               </div>
          </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <input type="text" class="education" name="ref_relationship1" placeholder="Relationship" required @if (!empty(Auth::user()->student->ref_relationship1))
                        value="{{Auth::user()->student->ref_relationship1}}"
                    @else
                    value="{{old('ref_relationship1')}}"
                    @endif >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                        <input type="text" class="education" name="ref_relationship2" placeholder="Relationship" required @if (!empty(Auth::user()->student->ref_relationship2))
                        value="{{Auth::user()->student->ref_relationship2}}"
                    @else
                    value="{{old('ref_relationship2')}}"
                    @endif>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <input type="text" class="education" name="ref_address1" placeholder="Address" required @if (!empty(Auth::user()->student->ref_address1))
                            value="{{Auth::user()->student->ref_address1}}"
                        @else
                        value="{{old('ref_address1')}}"
                        @endif>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                        <input type="text" class="education" name="ref_address2" placeholder="Address" required @if (!empty(Auth::user()->student->ref_address2))
                        value="{{Auth::user()->student->ref_address2}}"
                    @else
                    value="{{old('ref_address2')}}"
                    @endif>
                </div>
                </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <input type="tel" class="education" name="ref_number1" placeholder="Phone number" required @if (!empty(Auth::user()->student->ref_number1))
                        value="{{Auth::user()->student->ref_number1}}"
                    @else
                    value="{{old('ref_number1')}}"
                    @endif>
                </div>
                </div>
            <div class="col-md-6">
                <div class="form-group">
                        <input type="tel" class="education" name="ref_number2" placeholder="Phone number" required @if (!empty(Auth::user()->student->ref_number2))
                        value="{{Auth::user()->student->ref_number2}}"
                    @else
                    value="{{old('ref_number2')}}"
                    @endif>
                </div>
             </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                        <input type="email" class="education" name="ref_email1" placeholder="Email Address" required @if (!empty(Auth::user()->student->ref_email1))
                        value="{{Auth::user()->student->ref_email1}}"
                    @else
                    value="{{old('ref_email1')}}"
                    @endif>
                </div>
                </div>
            <div class="col-md-6">
                <div class="form-group">
                        <input type="email" class="education" name="ref_email2" placeholder="Email Address" required @if (!empty(Auth::user()->student->ref_email2))
                        value="{{Auth::user()->student->ref_email2}}"
                    @else
                    value="{{old('ref_email2')}}"
                    @endif>
                </div>
                </div>

        </div>
          <input type="check" name="previous" class="previous action-button" value="Previous" />
          <input type="button" name="next" class="next action-button" value="Next" id="alumniFieldNext" />
        </fieldset>


        <fieldset class="termsField">
          <h2 class="fs-title">Terms and Condition <span class="terms-invalid hidden">The school terms and conditions is required to coontinue</span></h2>
          <div class="scrollMe">
          <ul class="listTag fs-title bullets requirement-bullets">
            <li><h3 class="fs-subtitle"> Payment of tuition fees is required to accompany enrolment form. </h3></li>
            <li><h3 class="fs-subtitle"> Students may apply for an alteration in their enrolment not later than 2 weeks to course commencement date. </h3></li>
            <li><h3 class="fs-subtitle"> Students should please note that only one deferment/change of date of course/examination at the instance of the student will be permitted. </h3></li>
            <li><h3 class="fs-subtitle"> Students must adhere to the School’s rules and regulations. A brochure containing school rules and regulations will be provided on resumption. </h3></li>
            <li><h3 class="fs-subtitle"> Students must have 80% Class Attendance to qualify for the Landover Aviation Business School Certificate.</h3></li>
            <li><h3 class="fs-subtitle"> Students should note that Certificates will be issued based on the name indicated in Column A above. The company will not accede to any request that same be re-issued in a name other than that stated.</h3></li>
            <li><h3 class="fs-subtitle"> The school reserves the right to reschedule the course commencement date. Notice will be given within seven (7) days prior to its commencement; and in such case, the course fee will be fully refundable. LABS will not be liable for any other miscellaneous expenses incurred by the course participants(s) such as airfare charges and hotel bills etc.</h3></li>
            <li><h3 class="fs-subtitle"> In the event that a student fails to meet up with the deadline for payment of Cabin Crew or Flight Dispatchers NCAA examination and license fee, such defaulting student will pay a penalty fee of N2,500 to the school before license processing.</h3></li>
            <li><h3 class="fs-subtitle"> Students must be physically and medically fit to withstand practical drills, such as ditching, fire fighting and evacuation exercise. The school will not be liable either directly or indirectly for bodily injury sustained, exhaustion, illness or incapacitation whether permanent or temporary arising from the students participation in these activities. </h3></li>
            <li><h3 class="fs-subtitle"> In the event of inadequate enrolment, LABS reserves the right to re-schedule any course 7 days prior to course commencement; in such case course fee isfully refundable. LABS will not be liable for any other expenses incurred by course participants, i.e airfare or hotel charges.</h3></li>
          </ul>

            <h3>Refund Policy</h3>
            <span>Where application for refund is made, the following penalty will apply:</span>
            <ul class="listTag fs-title bullets requirement-bullets">
                <li><h3 class="fs-subtitle"> More than 4 weeks before course commencement. - 15% administrative charge. </h3></li>
                <li><h3 class="fs-subtitle"> 2-4 weeks before course commencement. - 50% administrative charge.</h3></li>
                <li><h3 class="fs-subtitle"> Less than 2 weeks to course commencement. - No refund.</h3></li>
                <li><h3 class="fs-subtitle"> No refund will be granted to students that paid for Advanced Fight Dispatch (AFD) Course after participating in the On-The-Job Training (OJT) programme organized by LABS.</h3></li>
                <li><h3 class=fs-subtitle> Enrolment and tuition fees are non-refundable where students are unable to attend their courses or in the event that the student is certified medically unfit to complete training.</h3></li>
                <li><h3 class=fs-subtitle> Tuition fee is non refundable in the event of non-issuance of license by NCAA (Applicable to courses that require NCAA licensing).</h3></li>

            </ul>

            <h3>Additional Information</h3>
            <span>(For Cabin Crew and Flight Dispatch Students): </span>
            <ol class="list-roman fs-title">
                <li> Students are advised to read Part two (2) of the Nigerian Civil Aviation Regulations (Nig. CARs 2015) on the requirement for the issuance of Cabin Crew and Flight Dispatchers’ Licenses by the NCAA. Please note that LABS will not be liable in the event of non-issuance of license by the NCAA.</li>
                <li> The minimum processing time for NCAA examination and licenses is six (6) weeks from the date of submission of all required documents.</li>
                <li> Drills and NCAA Examination must be completed within one year of course completion. Failure to do so will require a re-training the cost of which will be borne by the Student. </li>
                <li> Dress code is formal.</li>
                <li> Pregnant women would not be able to participate in practical drills.</li>
            </ol>

            <p class="text-info">Cost for NCAA Examination and license issuance will be communicated before the end of the programme.</p>

            <input type="checkbox" name="acceptTerms" id="acceptTerms" required  style="width:unset">
            <label for="customCheck">Accept</label>

              </div>

            <input type="check" name="previous" class="previous action-button" value="Previous" />

          <button type="button" class="action-button mt-2 mt-2 submit" >Submit</button>

        </fieldset>

      </form>

      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="greet"></h4>
            </div>
            <div class="modal-body">
              <p>Congrats! <i class="fa fa-thumbs-up" aria-hidden="true" style="font-size: 70px;"></i></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
            </div>
          </div>

        </div>
      </div>
    </div>






<script src="{{asset('js/jquery-2.2.4.min.js')}}"></script>
<script src="{{asset('js/jquery.easing.min.js')}}"></script>
<script src="{{asset('js/jquery.validate.js')}}"></script>
<script src="{{asset('js/additional-methods.min.js')}}"></script>
<script src="{{asset('js/enroll.js')}}"></script>
<script src="{{asset('js/crs.js')}}"></script>
<script src="{{asset('js/token_refresh.js')}}"></script>


<script>

</script>

</body>

</html>
