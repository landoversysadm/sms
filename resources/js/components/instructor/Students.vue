<template>
  <div class="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <router-link to="/instructor/"><i class="fa fa-fw fa-home"></i></router-link>
        </li>
        <li class="breadcrumb-item active">Students </li>
    </ol>

    <div class="row" style="margin-top:30px;">
       <div class="col-md-12">
         <div class="card course-card">
           <div class="card-header text-center">
             <CourseFilter v-if="!loading" :courses="assignedCourses" :button-text="`Get students data`" v-on:search="(term)=>{searchTable(term)}" v-on:filter="(filter)=>{newFilter(filter)}"></CourseFilter>
             <span v-else>loading...</span>
           </div>
           <div class="card-body text-center">
            <span v-if="filtering">processing request ...</span>

             <table class="table table-responsive table-students table-deep display" id="table-students">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col"></th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Mobile Phone</th>
                    <th scope="col" class="filter-date">Date Created</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                  <tr v-for="(room, index) in classRoom" :key="room.id" >
                    <td scope="row">{{index + 1}}</td>
                    <td scope="row">
                        <img :src="room.student.profile_picture_url | fullPath" alt="" style="width:35px; height:35px; float:right" class="circle">
                    </td>
                    <td scope="row">{{room.student.user.first_name}}</td>
                    <td scope="row">{{room.student.user.last_name}}</td>
                    <td scope="row">{{room.student.user.email}}</td>
                    <td scope="row">{{room.student.mobile_phone}}</td>

                    <td scope="row" >{{ room.student.created_at | moment("dddd, MMMM Do YYYY")  }}</td>
                    <!-- <td scope="row">{{status}}</td> -->
                    <td scope="row"><button class="btn btn-edit" data-toggle="modal" data-target="#studentProfileModal" @click="showStudentProfile(room.student)"> More</button></td>
                </tr>
              </tbody>

             </table>
           </div>
         </div>
       </div>
    </div>
    <ProfileModal :student-profile="studentProfile" :clicked-student="clickedStudent" :show-operation="false"></ProfileModal>
  </div>
</template>
<script>
// import buttons and plugins
import 'datatables.net-buttons/js/dataTables.buttons.js';
import 'datatables.net-buttons/js/buttons.html5.js';
import 'datatables.net-buttons/js/buttons.print.js';
// import the rest
import 'datatables.net-buttons-bs4';
import 'datatables.net-select-bs4';
import 'datatables.net-select-bs4/css/select.bootstrap4.min.css';
import 'datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css';
import '../../lib/range_dates';
import { isNull } from 'util';
import { enrollmentMixin} from '../mixins/enrollmentMixin';
import { instructorMixin } from '../mixins/instructorMixin';
import CourseFilter from '../CourseFilter';
export default {
  mixins : [instructorMixin, enrollmentMixin],
  components: {
    CourseFilter
  },
  data(){
    return {
      clickedStudent : {},
      studentProfile : false,
    }
  },
  methods:{
    loadTable(){
      const vm = this;
        Vue.nextTick(function() {
        vm.DataTable = $('.table-students').DataTable({"paging": false,
          "pageLength": 50,
          "responsive":true,
          "info": false,
          "columnDefs": [{
              'targets' : [1,4,5,7],
              'orderable': false,
          }],
          dom: 'Bfrtip',
          "buttons": ['copy','csv','pdf','print']
          });
          });
      return true;
    },
    refreshTable(){
      this.DataTable.rows().every(function(){
        // console.log('hello');
      });
    },
    searchTable(searchTerm){
      this.DataTable.search(searchTerm).draw();
    },
    showStudentProfile(student) {
        this.clickedStudent = student;
        this.studentProfile = true;
    },
  },
  watch :{
  classRoom : function(newVal, oldVal){
      if(newVal.length > 0){
        if(isNull(this.DataTable)){
              this.loadTable();
        }else{
            this.refreshTable();
        }
      }
    }
  },
  created(){
    this.DataTable = null;
  }
}
</script>
<style scoped>
</style>
