<template>
    <div class="container-fluid">
        <ol class="breadcrumb">
              <li class="breadcrumb-item">
               <a href="#"><i class="fa fa-fw fa-home"></i></a>
              </li>
             <li class="breadcrumb-item active">My Dashboard</li>
        </ol>
        <div class="row" style="margin-top:30px;">
                                <div class="summary col-xl-4 col-sm-6 mb-4">
                                <div class="card dashboard text-white o-hidden ">
                                    <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-users"></i>
                                    </div>
                                    <div class="mr-5"><h5>{{students.length}} Registered Students</h5></div>
                                    </div>
                                    <router-link class="card-footer text-white clearfix small z-1" to="/admin/student">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                    </router-link>
                                </div>
                                </div>
                                <div class="summary col-xl-4 col-sm-6 mb-4">
                                <div class="card dashboard text-white o-hidden " >
                                    <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-book"></i>
                                    </div>
                                        <div class="mr-5"><h5>{{courses.length}} Courses</h5></div>
                                    </div>
                                    <router-link class="card-footer text-white clearfix small z-1" to="/admin/faculties">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                    </router-link>
                                </div>
                                </div>

                                <div class="summary col-xl-4 col-sm-6 mb-4">
                                <div class="card dashboard text-white o-hidden " >
                                    <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-book"></i>
                                    </div>
                                        <div class="mr-5"><h5>{{pendingEnrollments.length}} Pending Applications</h5></div>
                                    </div>
                                    <router-link class="nav-link card-footer text-white clearfix small z-1" to="/admin/applications">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                    </router-link>
                                </div>
                                </div>

                                <div class="summary col-xl-4 col-sm-6 mb-4">
                                <div class="card dashboard text-white o-hidden " >
                                    <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-book"></i>
                                    </div>
                                        <div class="mr-5"><h5>{{activeStudents.length}} Active students</h5></div>
                                    </div>
                                    <router-link class="card-footer text-white clearfix small z-1" to="/admin/student#active">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                    </router-link>
                                </div>
                                </div>

                                <div class="summary col-xl-4 col-sm-6 mb-4">
                                <div class="card dashboard text-white o-hidden " >
                                    <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-shopping-cart"></i>
                                    </div>
                                    <div class="mr-5"><h5>{{pendingPayment.length}} Pending Payment Approval</h5></div>
                                    </div>
                                    <router-link class="card-footer text-white clearfix small z-1" to="/admin/payments">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                    </router-link>
                                </div>
                                </div>

                                <div class="summary col-xl-4 col-sm-6 mb-4">
                                <div class="card dashboard text-white o-hidden " >
                                    <div class="card-body">
                                    <div class="card-body-icon">
                                        <i class="fa fa-fw fa-shopping-cart"></i>
                                    </div>
                                    <div class="mr-5"><h5>{{validatedPayment.length}} Validated Payment</h5></div>
                                    </div>
                                    <router-link class="card-footer text-white clearfix small z-1" to="/admin/payments">
                                    <span class="float-left">View Details</span>
                                    <span class="float-right">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                    </router-link>
                                </div>
                                </div>
                                </div>

                                <div class="row mt-6">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Top recent students</h5>
                                            </div>
                                        <div class="card-body">
                                                <table class="table table-students table-striped display" id="table-students" v-if="students.length > 0" >
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col"></th>
                                                        <th scope="col">First Name</th>
                                                        <th scope="col">Last Name</th>
                                                        <th scope="col">Enrolled Courses</th>
                                                        <th scope="col" class="filter-date">Date Created</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(student, index) in limitStudents" :key="student.id" class="text-center">
                                                        <td scope="row">{{index + 1}}</td>
                                                        <td scope="row">
                                                            <img :src="student.profile_picture_url | fullPath" alt="" style="width:35px; height:35px; float:right" class="circle">
                                                        </td>
                                                        <td scope="row">{{student.user.first_name}}</td>
                                                        <td scope="row">{{student.user.last_name}}</td>
                                                        <td scope="row"> {{student.enrollments.length}}</td>
                                                        <td scope="row" >{{ student.created_at | moment("dddd, MMMM Do YYYY")  }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    </div>


                                    <div class="col-md-12">

                                        <div class="card">
                                            <div class="card-header">
                                            <h5>Faculty chart</h5>
                                        </div>
                                            <div class="card-body">
                                                <FacultyChart :showDownloadPdf="false"></FacultyChart>
                                            </div>
                                        </div>
                                    </div>
                                </div>



    </div>
</template>

<script>
import carousel from 'vue-owl-carousel';
import { isNullOrUndefined } from 'util';
import { enrollmentMixin } from '../mixins/enrollmentMixin';
export default {
    mixins : [enrollmentMixin],
    data(){
        return{
            showDownloadPdf : false,
            courses: {},
            pendingEnrollments: {},
            reviewedEnrollments: {},
            activeStudents: {},
            students: {},
            pendingPayment: {},
            validatedPayment: {},
            courseStudentOptions: {
          chart: {
            id: 'courses-enrolled-student'
          },
          yaxis: {
              decimalsInFloat: 0,
              forceNiceScale: true,
              labels: {
                  show : false
              }
          },
          xaxis: {
            categories: [],
            labels: {
                rotate: -77.5
            }
          },
          title: {
            text: 'Courses and Students',
             align: 'center',
             style: {
                 fontSize : '18px',
                 color : '#16462b',
             }
          }
        },
        courseStudentSeries: [{
          name: 'courses and enrolled students',
          data: []
        }]
        }
    },
    computed:{
        limitStudents(){
            return this.students.slice(0,5);
        }
    },
    methods:{
        getCourses() {
             axios.get('/api/v1/admin/courses').then((data)=>{
                 this.courses = data.data.data;
                this.updateCourseChart();
             });
        },
        updateCourseChart(){
            let courses = this.courses;
            let newCourseTitle = [];
            let newCourseSeries = [];
            courses.forEach(element => {
                newCourseTitle.push(element.title);
                newCourseSeries.push(element.students_count);
            });

            this.courseStudentSeries = [{
                data: newCourseSeries
            }];
            this.courseStudentOptions = {...this.courseStudentOptions, ...{
                xaxis: {
                    categories : newCourseTitle
                }
            }}

        },
        getStudents(){
            axios.get('/api/v1/admin/students').then((data)=>{
                this.students = data.data.data;
            });
        },
        getPayments(){
            axios.get('/api/v1/admin/payments').then((data)=>{
                let payments = data.data.data;
                this.pendingPayment = payments.filter((payment)=>{ return payment.validated == 0});
                this.validatedPayment = payments.filter((payment)=>{ return payment.validated == 2});
            });
        },
        getEnrollments() {
            axios.get('/api/v1/admin/enrollment').then((data)=> {
                let enrollments = data.data.data;
                this.reviewedEnrollments = (enrollments.filter((enrollment)=>{return enrollment.status == "reviewed"}));
                this.pendingEnrollments = (enrollments.filter((enrollment)=>{return enrollment.status == "pending"}));
                //this.active = (enrollments.filter((course)=>{return course.status == "active"}));
            });
        },
        getCourseStudents()
        {
            axios.get('/api/v1/admin/courseStudent').then((data)=>{
                this.activeStudents = data.data.data;
            });
        },
    },
    filters: {

    },
    created() {

    },
    mounted(){
        this.getCourses();
        this.getStudents();
        this.getPayments();
        this.getEnrollments();
        this.getCourseStudents();
    }
}
</script>
