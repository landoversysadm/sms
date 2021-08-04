<template>
    <div>
        <form id="msform">
  <!-- progressbar -->
  <ul id="progressbar">
    <li class="active">Educational Background</li>
    <li>Course Program</li>
    <li>Are you an alumni?</li>
    <li>Terms and Condition</li>


  </ul>
  <!-- fieldsets -->



  <fieldset>

    <h3 class="fs-subtitle">Your educational background</h3>
    <div id="addmore">
    <span class="education">
      <input type="file" placeholder="School">
      <input type="file" placeholder="Qualification">
      <i class="fa fa-plus-circle one" aria-hidden="true" style="font-size: 20px; color: #16462b;" id= "addMore"></i>
    </span>
  </div>

      <input type="button" name="next" class="next action-button" value="Next" />
  </fieldset>


  <fieldset>

    <h3 class="fs-subtitle">Which course are you applying for?(please fill appropiately)</h3>
    <div id="addmore2">
    <span class="education2">
        <input type="text" placeholder="Course title" >
        <input type="text" placeholder="Course fee" >
        <i class="fa fa-plus-circle one" aria-hidden="true" style="font-size: 20px; color: #16462b; visibility: visible;" onclick="addMore2()"></i>

      </span>
    </div>
    <input type="check" name="previous" class="previous action-button" value="Previous" />
      <input type="button" name="next" class="next action-button" value="Next" />
  </fieldset>




  <fieldset>
    <h3 class="fs-subtitle">Are you an alumni?</h3>
    <span style="display: flex; flex-direction:column">
      <div style="display: flex; flex-direction:row; width:50px; height: 30px; margin-left: 45%;">
          <h3 class="fs-subtitle">Yes</h3> &nbsp; &nbsp;<input type="checkbox">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
          <h3 class="fs-subtitle">No</h3>&nbsp; &nbsp;<input type="checkbox"  checked>
      </div>


    </span>


    <input type="text" name="fname" placeholder="If yes please enter course title" />
    <input type="date" name="lname" placeholder="Date" />

    <h3 class="fs-subtitle">Incase of Emergency? Call</h3>
    <input type="text" name="phone" placeholder="Name" />
    <input type="text" name="phone" placeholder="Relationship" />
    <input type="text" name="phone" placeholder="Phone Number" />
    <input type="text" name="phone" placeholder="Email Address" />

    <h3 class="fs-subtitle">Please give name and addres of two refrences</h3>
    <table>

      <tr>

      <td><input type="text" class="education" placeholder="Name"></td>
      <td><input type="text" class="education" placeholder="Name"></td>

      </tr>
      <tr>

      <td><input type="text" class="education" placeholder="Relationship"></td>
      <td><input type="text" class="education" placeholder="Relationship"></td>
      </tr>
      <tr>

      <td><input type="text" class="education" placeholder="Address"></td>
      <td><input type="text" class="education" placeholder="Address"></td>
      </tr>
      <tr>

      <td><input type="text" class="education" placeholder="Phone number"></td>
      <td><input type="text" class="education" placeholder="Phone number"></td>

      </tr>
      <tr>

      <td><input type="text" class="education" placeholder="Email Address"></td>
      <td><input type="text" class="education" placeholder="Email Address"></td>

      </tr>
    </table>
    <input type="check" name="previous" class="previous action-button" value="Previous" />
    <input type="button" name="next" class="next action-button" value="Next" />
  </fieldset>


  <fieldset>
    <h2 class="fs-title">Terms and Condition</h2>
    <div class="scrollMe">
    <ul class="listTag fs-title">
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Payment of appropriate tuition fees is required to accompany enrolment. </h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Where request for refund is made more than 4 weeks before course commencement, refund will be made subject to 15% administrative charge.</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Where request for refund is made 2-4 weeks before course commencement, such refund will be subjected to 50% administrative charge.</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> No refund will be granted for refund request made less than 2 weeks to course commencement</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Students may apply for an alteration in their enrolment not later than 2 weeks to course commencement date. Enrolment and tuition fees are non-refundable in cases where students are unable to attend their courses enrolment.</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Students must adhere to the Schools rules and regulations. A brochure containing school rules and regulations will be provided on enrolment.</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Students must have 80% School Attendance to qualify for the Landover Certificate.</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Students should note that Certificate will be issued based on the name indicated in Column A above. The company will not accede to any request that samebe re-issued in a name other than that stated.</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> Students must strictly adhere to Business dress code. Casual dressing is not allowed.</h3></li>
      <li><h3 class="fs-subtitle"><i class="fa fa-circle" aria-hidden="true"></i> In the event of inadequate enrolment, LABS reserves the right to re-schedule any course 7 days prior to course commencement; in such case course fee isfully refundable. LABS will not be liable for any other expenses incurred by course participants, i.e airfare or hotel charges.</h3></li>
    </ul>

      <input type="checkbox">
      <label for="customCheck">Accept</label>

        </div>

      <input type="check" name="previous" class="previous action-button" value="Previous" />

    <button type="button" class="action-button" data-toggle="modal" data-target="#myModal" onclick="greetFunc()">Submit</button>

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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    </div>
</template>


<script>
export default {

}
</script>

<style>

</style>
