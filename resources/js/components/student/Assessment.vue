<template>

    <div class="container-fluid">
                    <ol class="breadcrumb hide-print">
                            <li class="breadcrumb-item">
                            <a href="#"><i class="fa fa-fw fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Assessment</li>
                    </ol>

                    <div class="row">
                    <div class="col-md-12 d-flex justify-content-center show-print mb-4">
                        <img src="/img/labs-logo.png" alt="LABS-LOGO" style="width: 300px; text-align: center;">
                        <img :src="user.profilePictureUrl | fullPath" alt="Passport Photograph" style="float:right; width:150px; height:150px">
                    </div>
                    <div class="col-md-12 hide-print">
                        <div class="card course-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-md-4 col-sm-6 form-group font-small">
                                        <label for="filter_faculty">Enrolled Courses</label>
                                        <select name="course" id="filter_course" class="form-control select-with-border" v-model="filter.courseStudent"  >
                                            <option v-for="activeCourse in activeCourses" :key="activeCourse.id" :value="activeCourse" >{{activeCourse.course.title}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-6 form-group font-small d-flex justify-content-center align-items-center">
                                        <button class="btn btn-apply" @click="fetchAssessments" :disabled="filter.activeCourse == ''">Check Assessment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="row" v-if="assessments !== null">
                        <div class="col-md-12">
                            <div class="card course-card">
                                <div class="card-body">
                                    <div class="row" >
                                        <div class="col-md-12">
                                             <table class="table text-transform-uppercase full-border table-responsive border-bottom text-center d-table">
                                                    <thead class="font-weight-bold">
                                                        <tr><td colspan="2">NAME</td><td :colspan="2" v-text="user.fullName"></td></tr>
                                                        <tr><td colspan="2">COURSE</td> <td :colspan="2" v-text="filter.courseStudent.course.title"></td></tr>
                                                        <tr><td colspan="2">DURATION</td> <td :colspan="2"> {{filter.courseStudent.session.start_date | moment("DD MMMM  YYYY")}} - {{filter.courseStudent.session.start_date | moment("DD MMMM  YYYY")}}</td></tr>
                                                        <tr><td>s/N</td><td>Topic</td>
                                                        <td>Score</td></tr>

                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(score,index) in topics" :key="score.title">
                                                            <td>{{index+1}}</td>
                                                            <td>{{score.title}}</td>
                                                            <td>{{score.value}}</td>
                                                        </tr>
                                                        <tr class="text-bold">
                                                            <td colspan="2">total</td>
                                                            <td>{{totalScore(topics)}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-end hide-print">
                                            <button class="ml-2 btn btn-apply" @click="exportTableToPrinter('table-test', 'html')">Print sheet</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row display-flex mt-4 page-default" v-else>
                        <div class="col-md-12 text-center align-self-center p-4 mt-4">
                             <h1 class="mt-4">ASSESSMENTS</h1>
                        </div>
                    </div>

    </div>
</template>

<script>
import { exportTable } from '../../lib/csvExport';
import { enrollmentMixin} from '../mixins/enrollmentMixin';
export default {
    mixins : [exportTable,enrollmentMixin],
    data(){
        return {
            assessments : null,
            topics : '',
            activeCourses : '',
            filter : {
                courseStudent : ''
            },
            cache : {
                courseStudentId : ''
            },
            user: user
        }
    },
    methods :{
        fetchAssessments(){
            Fire.$emit('pageBusy');
            axios.get(`/api/v1/user/student/assessment/${this.filter.courseStudent.id}`).then(data=>{
                if(data.data.status === 'success'){
                    this.assessments = data.data.data;
                    this.topics = JSON.parse(this.assessments.scores);
                }
            }).catch(data=>{

            }).finally(data=>{
                Fire.$emit('pageFree');
            });
        },
        fetchEnrolledCourses(){
            axios.get('/api/v1/user/student/activeCourses').then(data=>{
                this.activeCourses = data.data.data;
            });
        },
        totalScore(scores){
            let total = scores.reduce((a,b)=>{
                if(isNaN(parseInt(b.value))){
                    b.value = 0;
                }
                return a + parseInt(b.value)
            },0);
            return total;
        },
    },
    created (){
        this.fetchEnrolledCourses();
    }
}
</script>

<style>

</style>
