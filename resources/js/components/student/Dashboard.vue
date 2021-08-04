<template>


    <div class="container-fluid">
                    <ol class="breadcrumb background-none">

                            <li class="breadcrumb-item active welcome-user" >Welcome, {{user.fullName}}! </li>
                    </ol>
                        <div class="row px-3" style="margin-top:30px;">

                                    <div class="summary col-xl-3 col-sm-6 mb-4">
                                    <div class="card dashboard text-white o-hidden h-100">
                                        <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-envelope-open"></i>
                                        </div>
                                        <div class="mr-5"><h5><b>{{notifications.length}}</b> All Notifications!</h5></div>
                                        </div>
                                        <router-link class="card-footer text-white clearfix small z-1" to="/student/notifications">
                                        <span class="float-left">View Details</span>
                                        <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
                                        </router-link>
                                    </div>
                                    </div>
                                    <div class="summary col-xl-3 col-sm-6 mb-4">
                                    <div class="card dashboard text-white o-hidden h-100">
                                        <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-book"></i>
                                        </div>
                                            <div class="mr-5"><h5><b>{{activeCourses.length}}</b> Active course</h5></div>
                                        </div>
                                        <router-link class="card-footer text-white clearfix small z-1" to="/student/courses">
                                        <span class="float-left">View Details</span>
                                        <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
                                        </router-link>
                                    </div>
                                    </div>

                                    <div class="summary col-xl-3 col-sm-6 mb-4">
                                    <div class="card dashboard text-white o-hidden h-100" >
                                        <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-book"></i>
                                        </div>
                                            <div class="mr-5"><h5><b>{{pending.length}}</b> Course awaiting approval</h5></div>
                                        </div>
                                        <router-link class="card-footer text-white clearfix small z-1" to="/student/courses">
                                        <span class="float-left">View Details</span>
                                        <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
                                        </router-link>
                                    </div>
                                    </div>

                                    <div class="summary col-xl-3 col-sm-6 mb-4">
                                    <div class="card dashboard text-white o-hidden h-100">
                                        <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fa fa-fw fa-shopping-cart"></i>
                                        </div>
                                        <div class="mr-5"><h5><b>{{reviewed.length}}</b> Payment awaiting approval</h5></div>
                                        </div>
                                        <router-link class="card-footer text-white clearfix small z-1" to="/student/payments">
                                        <span class="float-left">View Details</span>
                                        <span class="float-right">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
                                        </router-link>
                                    </div>
                                    </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card course-card px-3">
                                    <div class="card-header text-center">
                                        Enrolled Courses
                                    </div>
                                    <div class="card-body text-center">

                                            <div v-if="activeCourses.length>0">
                                                <!-- enrolled courses -->
                                                <div class="box_list wow animated" style="visibility: visible;" v-for="course in activeCourses" :key="course.id">
                                                    <div class="row no-gutters">
                                                        <div class="col-lg-5">
                                                            <figure class="block-reveal">
                                                                <div class="block-horizzontal"></div>
                                                                <a href=""><img :src="course.course.banner | fullPath" class="img-fluid course-list-img" alt=""></a>
                                                                <div class="preview"></div>
                                                                <div class="tint-bg"></div>
                                                            </figure>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            <div class="wrapper">
                                                                <small>Course</small>
                                                                <h3>{{course.course.title}}</h3>
                                                                <p>{{course.course.description}} </p>
                                                                <h1 v-if="courseisOngoing(course.course.start_date)">
                                                                     Began {{course.course.start_date | moment('from','now') }}
                                                                </h1>
                                                                <h1 v-else-if="courseisYetToBegin(course.course.start_date)">
                                                                    Begins {{course.course.start_date | moment('from','now') }}
                                                                </h1>
                                                                <h1 v-else>
                                                                       End
                                                                </h1>
                                                            </div>
                                                            <ul>
                                                                <li><i class="icon_clock_alt"></i> {{ course.course.start_date | moment(" MMMM Do YYYY") }}</li>
                                                                <li><router-link to="/student/courses">View</router-link></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            <!-- // enrolled courses -->
                                            </div>

                                        <span class="text-center" v-else>You're not enrolled to any course</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card course-card px-3">
                                    <div class="card-header text-center">
                                        Download Center <i class="fa fa-download"></i>
                                    </div>
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-md-8">
                                                <div class="card">
                                                    <div class="card-body bs">
                                                        <span style="font-size:1.2em">Performance Evaluation Form</span><span>
                                                    <a class="btn btn-apply float-right" style="color:#fff" target="_blank" href="/storage/downloads/PERFORMANCE EVALUATION QUESTIONNAIRE FORM.pdf" >Download</a>
                                                    </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
        <div class="col-md-12">
          <div class="card course-card px-3"><div class="card-header text-center"> Course Materials <i class="fa fa-download"></i> </div>
            <div class="card-body">
              <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-body bs">
                      <div class="row no-gutters d-flex flex-column align-items-center" v-for="enrollment in activeCourses" :key="enrollment.id">
                        <b>{{enrollment.course.title}}</b>
                        <div class="col-md-12 justify-content-between d-flex mb-3" v-for=" material in enrollment.course.materials" :key="material.id">
                          <span class="material-title">{{material.title}} </span> <a target="_blank" class="btn btn-apply" :href="material.file_url | fullPath"> <i class="fa fa-download"></i></a>
                        </div>
                        <span v-if="enrollment.course.materials.length <1">NO COURSE MATERIAL</span>
                      </div>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>




<div class="modal fade bd-example-modal-lg course-full-detail" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header p-0 border-bottom-0">
        <img :src="clickedCourse.banner | fullPath" style="min-width:100%; height:300px;"/>

        <div class="price">{{formatter.format(clickedCourse.price)}}</div>
        <div class="title">{{clickedCourse.title}}</div>
        <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="right:10px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body " v-show="showCourseDetail">
        <section>
             <h5 class="section-header">Description</h5>
             <p>
                 {{clickedCourse.description }}
             </p>
        </section>
        <section>
             <h5 class="section-header">Duration</h5>
             <p>
                The course would run from the {{ clickedCourse.start_date | moment("dddd, MMMM Do YYYY") }} to {{ clickedCourse.end_date | moment("dddd, MMMM Do YYYY") }}
             </p>
        </section>
        <section>
             <h5 class="section-header">Requirements</h5>
             <ul class="bullets requirement-bullets" v-if="Object.keys(clickedCourse.requirement).length > 0">
                 <li v-for="i in Object.keys(clickedCourse.requirement)" :key="i" > <b>{{i}}</b> {{clickedCourse.requirement[i]}} </li>
             </ul>
             <ul class="bullets requirement-bullets" v-else>
                 <li> NO requirement for this course</li>
             </ul>
        </section>

      </div>
      <form action="" enctype="multipart/form-data" @submit.prevent="enrollForCourse()">
      <div class="modal-body" v-show="!showCourseDetail">
        <section>
            <h5 class="section-header">Application Form</h5><br>
            <small>Ensure all forms are properly filled without errors <br/> supported format are <b>.jpg .png .pdf</b> max size 1mb</small>
        </section>
        <section>
            <div class="form-group" v-for="i in Object.keys(clickedCourse.requirement)" :key="i">
                <label for="input">{{i}}<em class="form-help"> * {{clickedCourse.requirement[i]}} </em></label>
                <input  type="file" :name="i|formArrayInput('requirement_uploads')" :id="i" class="form-control requirement_uploads" :class="{ 'is-invalid': form.errors.has('requirement') }" placeholder="select a file"  required/>
                <span style="color:red; float:right; margin-top:-10px;" class="hidden" :id="i+'error'">invalid file submitted </span>
            </div>
        </section>
      </div>
      <div class="modal-footer">
        <button type="button " class="btn btn-apply" data-dismiss="odal" @click="showCourseDetail = !showCourseDetail" v-text="(showCourseDetail)?'Apply Now':'Go back'">Apply Now</button>
          <button type="button " class="btn btn-apply btn-submit-application" data-dismiss="odal" v-show="!showCourseDetail">Submit Application</button>
      </div>
      </form>
    </div>
  </div>
</div>

            </div>

</template>

<script>
import carousel from 'vue-owl-carousel';
import {enrollmentMixin, isNullOrUndefined} from '../mixins/enrollmentMixin';
export default {
  mixins : [enrollmentMixin],
    data(){
        return{
            user: user,
            allCourses:{},
            notifications: 0,
            showCourseDetail : true,
            pending: '',
            reviewed: '',
            activeCourses: '',
            enrollments: '',
            clickedCourse : {
                requirement: {}
            },
            formatter : new Intl.NumberFormat('en-NG', {
                style: 'currency',
                currency: 'NGN',
                currencyDisplay: 'symbol',
            }),
            form: new Form({
                requirementFiles: [],
            }),
        }
    },
    methods:{
        courseisOngoing(start_date){
            return Date.parse(start_date) <= Date.now()
        },
        courseisYetToBegin(start_date){
            return Date.parse(start_date) > Date.now()
        },
        courseHasEnded(end_date){
            return today > Date.parse(end_date);
        },
        fetchNotifications(){
            axios.get('/api/v1/user/student/notification').then((data)=>{
                if(data.data.status){
                    this.notifications = data.data.data;
                }
            }).catch((data)=>{
            });
        },
        fetchAllCourses(){
            axios.get('/api/v1/courses').then((data)=>{
                let generalCourses = data.data.data;
                this.allCourses = data.data.data;
                this.carouselKey += 1;
                this.allCourses = (this.allCourses.filter((course)=>{ return !this.enrollments.find(enrollment=>enrollment.course_id == course.id)}));
            });
        },fetchMyEnrollments(){
            axios.get('/api/v1/user/student/enrollment/history').then((data)=>{
                if(data.data.status){
                    let courses = data.data.data;
                    this.enrollments = courses;
                    this.reviewed = (courses.filter((course)=>{return (course.status == "approved" && course.payment_status == 0 )}));
                    this.pending = (courses.filter((course)=>{return course.status == "pending"}));

                    this.fetchAllCourses();
                }
            });
        },fetchActiveCourses(){
            axios.get('/api/v1/user/student/enrollment').then((data)=>{
                this.activeCourses = data.data.data.filter((enrollment)=>{ return enrollment.payment_status == 1 });
            });
        },
        updateEnrollModal(course){
                this.clickedCourse = course;
                this.showCourseDetail = true;
                try{
                    this.clickedCourse.requirement = JSON.parse(course.requirement);
                    if(isNullOrUndefined(this.clickedCourse.requirement))
                    {
                        this.clickedCourse.requirement = {};
                    }
                }catch(error){
                }
        },enrollForCourse(){
            let course = this.clickedCourse;
            let elems = document.querySelectorAll('.requirement_uploads');
            let supportedFormat = ["image/jpeg", "application/pdf", "image/png"];
            let valid = true;
            let count = 0;
            const vm = this;
            let courseId = vm.clickedCourse.id;
            vm.requirement = [];
            document.querySelector('.btn-submit-application').setAttribute('disabled','true');
            elems.forEach((e)=>{
                let id = e.id;
                e.classList.remove('has-error');
                e.nextElementSibling.classList.add('hidden')
                let file = e.files[0];
                if (supportedFormat.includes(file.type)){
                    let reader = new FileReader();
                    reader.onloadend = function(){
                        count++
                        vm.form.requirementFiles.push({'name':id,'data':reader.result,'type':file.type});
                        if(count === elems.length){
                            vm.form.post('/api/v1/user/student/apply/course/'+courseId).then((data)=>{
                                if(data.data.status){
                                    Swal.fire(
                                        'Application successful!',
                                        'please wait while course administrator reviews your application',
                                        'success'
                                    );
                                    document.querySelector('.btn-submit-application').removeAttribute('disabled');
                                    document.querySelector('.close').click();
                                    vm.fetchMyEnrollments();
                                }else{
                                    this.showError();
                                    document.querySelector('.btn-submit-application').removeAttribute('disabled');
                                }
                            }).catch((data)=>{
                                this.showError();
                                document.querySelector('.btn-submit-application').removeAttribute('disabled');
                            });
                        }
                    }
                    reader.readAsDataURL(file);
                }else{
                    valid = false;
                    e.classList.add('has-error');
                    e.nextElementSibling.classList.remove('hidden');
                    document.querySelector('.btn-submit-application').removeAttribute('disabled');
                }

            });

        }
    },showError(){
            Swal.fire({
                    title: 'Error!',
                    text: 'Error occured while performing operation',
                    type: 'error',
                    confirmButtonText: 'Continue'
                });
        },
    created(){
    },
    mounted(){
        this.fetchMyEnrollments();
        this.fetchActiveCourses();
        this.fetchNotifications();
    }
}
</script>
