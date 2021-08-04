<template>

    <div class="container-fluid">
                    <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                            <a href="#"><i class="fa fa-fw fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Payment</li>
                    </ol>

                    <div class="row">
            <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
            <li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="true" class="active">Pending Payment</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Payment History</a></li>
            </ul>
            <div class="tab-content" style="background-color: #e9ecef;">
              <div class="tab-pane active" id="tab_1">

                  <div class="row" v-if="rejectedPayments.length>0">
                    <div class="col-md-12 ">
                        <div class="card course-card ">
                        <div class="alert alert-info ">
                            <h4><i class="icon fa fa-info"></i> Alert!</h4>
                            You have a declined payment

                            <div class="card-body text-center overflow-auto">
                                <table class="table table-deep">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Uploaded on</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Slip</th>
                                            <th scope="col">Reason</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="payment in rejectedPayments" :key="payment.id" class="clickable" >
                                            <td scope="row">#</td>
                                            <td scope="row">{{ payment.enrollment.course.title}}</td>
                                            <td scope="row">{{ payment.created_at | moment("MMMM Do YYYY")  }}</td>
                                            <td scope="row">{{ payment.validated | isValidated() }}</td>
                                            <td scope="row"><a :href="payment.slip_upload | fullPath" target="_blank">View</a></td>
                                            <td scope="row">{{ payment.admin_note }}</td>
                                            <td scope="row"><button class="btn btn-edit" style="float:right;" data-toggle="modal" data-target="#enrollmentModal-v" @click="showReUpload(payment)">re-try</button></td>
                                        </tr>
                                    </tbody>



                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                  <div class="row mt-4">
                    <div class="col-md-12 ">
                        <div class="card course-card text-center">
                            <div class="card-header text-center">
                                                Pending Payment
                            </div>
                            <div class="card-body overflow-auto">
                                <table class="table table-deep" v-if="approvedEnrollments.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Date Applied</th>
                                            <th scope="col">Date Approved</th>
                                            <th scope="col">Upload payment slip</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="enrollment in approvedEnrollments" :key="enrollment.id" class="clickable" @click="showMore(enrollment)">
                                            <td scope="row">#</td>
                                            <td scope="row">{{ enrollment.course.title}}</td>
                                            <td scope="row">{{ enrollment.created_at | moment("dddd, MMMM Do YYYY")  }}</td>
                                            <td scope="row">{{ enrollment.updated_at | moment("dddd, MMMM Do YYYY")  }}</td>
                                            <td scope="row"><button class="btn btn-edit" style="float:right;" data-toggle="modal" data-target="#enrollmentModal-v" @click="showMore(enrollment)">Make Payment</button></td>
                                        </tr>
                                    </tbody>
                            </table>
                             <small v-else> <p v-if="!paymentLoading"> no pending payment yet</p><p v-else>loading...</p></small>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card course-card text-center">
                            <div class="card-header text-center">
                                    Payment Awaiting Validation
                            </div>
                            <div class="card-body overflow-auto">
                                <table class="table table-deep" v-if="nonValidatedPayments.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Date Uploaded</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Uploaded Slip</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="payment in allPayments" :key="payment.id" class="clickable" @click="showMore(enrollment)">
                                            <td scope="row">#</td>
                                            <td scope="row">{{ payment.enrollment.course.title}}</td>
                                            <td scope="row">{{ payment.created_at | moment("dddd, MMMM Do YYYY")  }}</td>
                                            <td scope="row">{{ payment.validated | isValidated() }}</td>
                                            <td scope="row"><a :href="payment.slip_upload | fullPath" target="_blank">Download</a></td>
                                        </tr>
                                    </tbody>
                            </table>
                            <small v-else> <p v-if="!paymentLoading">  no payment is awaiting validation</p><p v-else>loading...</p></small>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="tab-pane" id="tab_2">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="card course-card text-center">
                            <div class="card-header text-center">
                                                Payment History
                            </div>
                            <div class="card-body overflow-auto">
                                <table class="table table-deep" v-if="validatedPayments.length > 0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Date Uploaded</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Uploaded Slip</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="payment in allPayments" :key="payment.id" class="clickable" @click="showMore(enrollment)">
                                            <td scope="row">#</td>
                                            <td scope="row">{{ payment.enrollment.course.title}}</td>
                                            <td scope="row">{{ payment.created_at | moment("dddd, MMMM Do YYYY")  }}</td>
                                            <td scope="row">{{ payment.validated | isValidated() }}</td>
                                            <td scope="row"><a :href="payment.slip_upload | fullPath" target="_blank">Download</a></td>
                                        </tr>
                                    </tbody>



                            </table>
                            <small v-else> <p v-if="!paymentLoading"> no validated payment yet</p><p v-else>loading...</p></small>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>

              <!-- /.tab-pane -->

              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
         </div>
        </div>

        <div class="modal fade course-full-detail" id="enrollmentModal-v" tabindex="-1" role="dialog" aria-labelledby="enrollmentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" v-if="enrollmentShow">
     <div class="modal-header p-0 border-bottom-0">
        <img :src="clickedEnrollment.course.banner | fullPath" style="min-width:100%; height:300px;"/>
        <div class="price">{{formatter.format(clickedEnrollment.course.price)}}</div>
        <div class="title">{{clickedEnrollment.course.title}}</div>
        <button type="button" class="close position-absolute btn-dismiss-modal" data-dismiss="modal" aria-label="Close" style="right:10px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body" v-show="showCourseDetail">
        <section>
             <h5 class="section-header">Description</h5>
             <p>
                 {{clickedEnrollment.course.description }}
             </p>
        </section>
        <section>
             <h5 class="section-header">Duration</h5>
             <p>
                The course would run from the {{ clickedEnrollment.course.start_date | moment("dddd, MMMM Do YYYY") }} to {{ clickedEnrollment.course.end_date | moment("dddd, MMMM Do YYYY") }}
             </p>
        </section>
        <!-- <section>
             <h5 class="section-header">Requirements</h5>
             <ul class="bullets requirement-bullets" v-if="Object.keys(clickedEnrollment.course.requirement).length > 0">
                 <li v-for="i in Object.keys(clickedEnrollment.course.requirement)" :key="i" > <b>{{i}}</b> {{clickedEnrollment.course.requirement[i]}} </li>
             </ul>
             <ul class="bullets requirement-bullets" v-else>
                 <li> NO requirement for this course</li>
             </ul>
        </section> -->
        <section>
             <h5 class="section-header">Requirements</h5>
             <ul class="bullets requirement-bullets" v-if="Object.keys(clickedEnrollment.course.requirement).length > 0">
                 <li v-for="i in Object.keys(clickedEnrollment.course.requirement)" :key="i" > <b>{{clickedEnrollment.course.requirement[i].title}}</b><br> {{clickedEnrollment.course.requirement[i].text}} </li>
             </ul>
             <ul class="bullets requirement-bullets" v-else>
                 <li> NO requirement for this course</li>
             </ul>
        </section>
        <!-- <section>
            <h5 class="section-header">Uploaded Requirements</h5>

            <table class="table">
                <tr>
                    <th scope="col">title</th>
                    <th scope="col">Your upload</th>
                </tr>
                <tr v-for="i in Object.keys(clickedEnrollment.requirement_uploads)" :key="i">
                    <td scope="row">{{i}}</td>
                    <td scope="row"><a :href="clickedEnrollment.requirement_uploads[i] | fullPath" target="_blank">View</a></td>
                </tr>
            </table>
        </section> -->
        <section>
            <h5 class="section-header">Applicant filled Requirements</h5>

            <table class="table">
                <tr>
                    <th scope="col">description</th>
                    <th scope="col">Submission</th>
                </tr>
                <tr v-for="i in Object.keys(clickedEnrollment.requirement_uploads)" :key="i">
                    <td scope="row">{{i}}</td>
                    <td scope="row" v-if="clickedEnrollment.requirement_uploads[i].type === 'file'"><a :href="clickedEnrollment.requirement_uploads[i].file | fullPath" target="_blank" >View</a></td>
                    <td scope="row" v-else>{{clickedEnrollment.requirement_uploads[i].duration}}</td>
                </tr>
            </table>
        </section>
     </div>
        <form action="" enctype="multipart/form-data" @submit.prevent="payForCourse()">
      <div class="modal-body" v-show="!showCourseDetail">
        <section>
            <h5 class="section-header">Payment Slip</h5><br>
        </section>
        <section>
            <div class="form-group">
                <label for="amount">Depositor Name</label>
                <input type="text" name="payee_name" id="" required class="form-control" placeholder="Depositor Name" v-model="form.payee_name"/>
            </div>
            <div class="form-group">
                <label for="amount">Amount paid</label>
                <input type="text" name="amount_paid" id="" required class="form-control" placeholder="Amount Paid" v-model="form.amount_paid"/>
            </div>
            <div class="form-group">
                <label for="amount">Date of Payment</label>
                <input type="date" name="date_of_payment" id="" required class="form-control" placeholder="Date of Payment" v-model="form.date_of_payment" max/>
            </div>
            <div class="form-group">
                <small><b>Upload a scan copy of your bank payment slip in either .pdf or .png format (max size 1mb)</b></small>
                <label for="input"><em class="form-help"> ensure document is clear </em></label>
                <input  type="file" name="slip_upload" id="slip_upload_input" class="form-control slip_upload" :class="{ 'is-invalid': form.errors.has('slip_upload') }" placeholder="select a file"  required/>
                <span style="color:red; float:right; margin-top:-10px;" class="hidden" id="slip_uploadError">invalid file submitted, ensure the file size meets the file upload requirement</span>
            </div>
        </section>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-apply " data-dismiss="odal" @click="showCourseDetail = !showCourseDetail" v-text="(showCourseDetail)?'Make Payment':'Go back'">Make Payment</button>
        <button type="submit" class="btn btn-apply btn-submit-slip" data-dismiss="odal" v-show="!showCourseDetail">Upload Slip</button>
      </div>
    </form>
    </div>
  </div>
</div>

    </div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined} from '../mixins/enrollmentMixin';
export default {
    mixins: [enrollmentMixin],
    data(){
        return{
          paymentLoading : true,
            approvedEnrollments : {},
            clickedEnrollment : {},
            clickedPayment : {},
            nonValidatedPayments :{},
            validatedPayments : {},
            rejectedPayments: {},
            allPayments : {},
            enrollmentShow : false,
            reuploadShow : false,
            showCourseDetail : true,
            form: new Form({
                slip_upload : {},
                payee_name : '',
                amount_paid : '',
                date_of_payment: '',
            }),
        }
    },
    computed:{

    },
    methods:{
        fetchEnrollments(){
            axios.get('/api/v1/user/student/enrollment').then((data)=> {
                let enrollments = data.data.data;
                this.approvedEnrollments = (enrollments.filter((enrollment)=>{return enrollment.status == "approved"}));
                this.approvedEnrollments = (this.approvedEnrollments.filter((enrollment)=>{ return !this.allPayments.find(payment=>payment.enrollment_id  == enrollment.id)}));

            });
        },
        fetchPayments(){
            axios.get('/api/v1/user/student/myPayments').then((data)=> {
              this.paymentLoading = false;
                this.allPayments = data.data.data[0].payments;
                this.nonValidatedPayments = (this.allPayments.filter((payment)=>{ return payment.validated == 0 }));
                this.rejectedPayments  =  (this.allPayments.filter((payment)=>{ return payment.validated == 1 }));
                this.validatedPayments = (this.allPayments.filter((payment)=>{ return payment.validated == 2 }));
                this.fetchEnrollments();
            });
        },
        payForCourse(){
          console.log(this.clickedEnrollment);
            let courseId = this.clickedEnrollment.course.id;
            let paymentId = this.clickedPayment.id;
            let supportedFormat = ["application/pdf", "image/png", "image/jpg", "image/jpeg"];
            document.querySelector('.btn-submit-slip').setAttribute('disabled','true');
            const vm = this;
            let url = (vm.reuploadShow)?'/api/v1/user/student/payments/'+paymentId:'/api/v1/user/student/pay/course/'+courseId;

                let e = document.getElementById('slip_upload_input');
                let id = e.id;
                e.classList.remove('has-error');
                e.nextElementSibling.classList.add('hidden')
                let file = e.files[0];
                const reachMaxSize = (file.size / 1048576) > 1;
                if (supportedFormat.includes(file.type) && !reachMaxSize){
                    NProgress.start();
                    let reader = new FileReader();
                    reader.onloadend = function(){
                      Fire.$emit('pageBusy');
                        vm.form.slip_upload= {'name':'payment_slip','data':reader.result,'type':file.type};
                            vm.form.post(url).then((data)=>{
                                if(data.data.status == 'success'){
                                    vm.showSuccess('Payment Successful', 'After admin verification, you would be able to start taking the course');
                                    Fire.$emit('enrollmentStatusChanged');
                                    document.querySelector('.btn-submit-slip').removeAttribute('disabled');
                                    document.getElementsByClassName('btn-dismiss-modal')[0].click();
                                    Fire.$emit('pageFree');
                                    return true;
                                }else{
                                    vm.showError();
                                    document.querySelector('.btn-submit-slip').removeAttribute('disabled');
                                    Fire.$emit('pageFree');
                                    return true;
                                }
                            }).catch(error =>{
                                if(!isNullOrUndefined(error) ){
                                    Fire.$emit('pageFree');
                                    vm.showError();
                                }
                                document.querySelector('.btn-submit-slip').removeAttribute('disabled');

                            }).finally((response)=>{
                              NProgress.done();
                              NProgress.remove();
                            });
                    }
                    reader.readAsDataURL(file);

                }else{
                    e.classList.add('has-error');
                    NProgress.done(true);
                    e.nextElementSibling.classList.remove('hidden');
                    document.querySelector('.btn-submit-slip').removeAttribute('disabled');
                }
        }
    },filters: {
        isValidated: function(value){
            if(value == 0){
                return 'not validated';
            }else if(value == 1){
                return 'rejected'
            }
            return 'validated';
        }
    },
    mounted() {
        this.fetchPayments();
        Fire.$on('enrollmentStatusChanged', ()=>{
            this.fetchPayments();
        });
    },
    created() {

    }
}
</script>

<style>

</style>
