<template>
  <div class="container-fluid">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#"><i class="fa fa-fw fa-home"></i></a>
      </li>
      <li class="breadcrumb-item active">Students</li>
      <li class="position-absolute" style="right:2em; top">
        <a
          class="btn btn-apply"
          style="font-size:0.9em; margin-top:-0.4em"
          href="#"
          @click="showFilter = !showFilter"
          >Filter <i :class="showFilter ? 'fa fa-minus' : 'fa fa-plus'"></i>
        </a>
      </li>
      <li class="floatt"></li>
    </ol>

    <div class="row" style="margin-top:30px;">
      <div class="col-md-12 ">
        <div class="card course-card ">
          <div class="card-header" v-if="showFilter">
            <div class="row">
              <div class="col-md-12">
                <div class="card border-none">
                  <div class="card-body bs">
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="searchbar dataTables_filter">
                          <input
                            type="search"
                            class="search_input"
                            name=""
                            placeholder="search"
                            aria-controls="table-students"
                            @keyup="searchTable($event.target.value)"
                          />
                          <a href="#" class="search_icon"
                            ><i class="fa fa-search"></i
                          ></a>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 col-sm-6 form-group font-small">
                        <label for="filter_course">Course</label>
                        <select
                          name="course"
                          id="filrer_course"
                          class="form-control select-with-border"
                          v-model="filter.course"
                          @change="updateTable()"
                        >
                          <option value="">All course</option>
                          <option
                            v-for="course in courses"
                            :key="course.id"
                            :value="course.id"
                            >{{ course.title }}</option
                          >
                        </select>
                      </div>
                      <div class="col-md-3 col-sm-6 form-group font-small">
                        <label for="fiter_from">From</label>
                        <input
                          v-model="filter.minDate"
                          type="date"
                          name="filter_from"
                          id="filter_from"
                          class="form-control"
                          :max="todayDate"
                          @change="updateFilterTo($event.target.value)"
                        />
                      </div>
                      <div class="col-md-3 col-sm-6 form-group font-small">
                        <label for="filter_from">To</label>
                        <input
                          v-model="filter.maxDate"
                          type="date"
                          name="filter_to"
                          id="filter_to"
                          class="form-control"
                          @change="updateTable()"
                          :max="todayDate"
                          disabled="true"
                        />
                      </div>
                      <div class="col-md-3 col-sm-6 form-group font-small">
                        <label for="filter_status">Status</label>
                        <select
                          v-model="filter.status"
                          name="course"
                          id="filrer_course"
                          class="form-control select-with-border"
                          @change="updateTable()"
                        >
                          <option value="">all student status</option>
                          <option value="approved">approved</option>
                          <option value="enrolled">pending</option>
                          <option value="completed">completed</option>
                          <!-- <option value="trminated">terminated</option> -->
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body text-center">
            <table
              class="table table-responsive table-students table-deep display"
              id="table-students"
              v-if="studentsCache.length > 0 && showStudent"
            >
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col"></th>
                  <th scope="col">First Name</th>
                  <th scope="col">Last Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Mobile Phone</th>
                  <th scope="col">Enrolled Courses</th>
                  <th scope="col" class="filter-date">Date Created</th>
                  <th scope="col"></th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="(student, index) in students" :key="student.id">
                  <td scope="row">{{ index + 1 }}</td>
                  <td scope="row">
                    <img
                      :src="student.profile_picture_url | fullPath"
                      alt=""
                      style="width:35px; height:35px; float:right"
                      class="circle"
                    />
                  </td>
                  <td scope="row">{{ student.user.first_name }}</td>
                  <td scope="row">{{ student.user.last_name }}</td>
                  <td scope="row">{{ student.user.email }}</td>
                  <td scope="row">{{ student.mobile_phone }}</td>
                  <td scope="row">
                    <!-- <ul class="student_course_list" v-if="student.enrollments.length > 0">
                                        <li v-for="enrollment in student.enrollments" :key="enrollment.id">{{enrollment.course.title}}</li>
                                    </ul>
                                    <ul v-else>
                                        _____
                                    </ul> -->
                    {{ student.enrollments.length }} {{checkOwing(student.enrollments)}}
                  </td>
                  <td scope="row">
                    {{ student.created_at | moment("dddd, MMMM Do YYYY") }}
                  </td>
                  <!-- <td scope="row">{{status}}</td> -->
                  <td scope="row">
                    <button
                      class="btn btn-edit"
                      data-toggle="modal"
                      data-target="#studentProfileModal"
                      @click="showStudentProfile(student)"
                    >
                      More
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <small v-else>
              <p v-if="!studentLoading">no registered students</p>
              <p v-else>loading...</p></small
            >
          </div>
        </div>
      </div>
    </div>

    <ProfileModal
      v-bind:student-profile="studentProfile"
      v-bind:clicked-student="clickedStudent"
    ></ProfileModal>
  </div>
</template>

<script>
// import buttons and plugins
import "datatables.net-buttons/js/dataTables.buttons.js";
import "datatables.net-buttons/js/buttons.html5.js";
import "datatables.net-buttons/js/buttons.print.js";
// import the rest
import "datatables.net-buttons-bs4";
import "datatables.net-select-bs4";
import "datatables.net-select-bs4/css/select.bootstrap4.min.css";
import "datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css";
import "../../lib/range_dates";

import { enrollmentMixin } from "../mixins/enrollmentMixin";
import { rangeFilter } from "../../lib/range_dates";
import { filter } from "minimatch";
import DashboardVue from "../student/Dashboard.vue";
import { isNull } from "util";
export default {
  mixins: [enrollmentMixin],
  data() {
    return {
      students: {},
      studentLoading: true,
      studentsCache: {},
      courses: {},
      showStudent: true,
      clickedStudent: {},
      studentProfile: false,
      todayDate: new Date().toISOString().split("T")[0],
      showFilter: true,
      filter: {
        minDate: "",
        maxDate: new Date().toISOString().split("T")[0],
        course: "",
        status: ""
      }
    };
  },
  watch: {
    $route(to, from) {
      this.filterStudent(this.$route.hash.replace("#", ""));
    }
  },
  methods: {
    checkOwing(enrollments){
      let owedCoursesLength = enrollments.filter((e)=>
        e.payment_status == 0 ).length;
      return owedCoursesLength > 0? `(${owedCoursesLength} unpaid)`:"";
    },
    filterStudent(filter) {
      if (filter.length > 0 && filter === "#active") {
        this.filter.status = "approved";
        this.updateTable();
      }
    },
    fetchAllStudents() {
      this.studentLoading = true;
      const vm = this;
      axios.get("/api/v1/admin/students").then(data => {
        this.students = data.data.data;
        this.studentsCache = data.data.data;
        if (isNull(vm.DataTable)) {
          $(".table-students").hide();
          vm.loadTable();
        } else {
          vm.refreshTable();
        }
        vm.filterStudent(window.location.hash);
        this.studentLoading = false;
      });
    },
    fetchAllCourses() {
      axios.get("/api/v1/courses").then(data => {
        this.courses = data.data.data;
      });
    },
    showStudentProfile(student) {
      this.clickedStudent = student;
      this.studentProfile = true;
    },
    showMore(student) {},
    refreshTable() {
      $(".table-students")
        .DataTable()
        .destroy();
      this.loadTable();
      this.DataTable.rows().every(function() {
        // console.log('hello');
      });
    },
    loadTable() {
      const vm = this;
      $.fn.dataTable.ext.errMode = 'none';
      Vue.nextTick(function() {
        vm.DataTable = $(".table-students").DataTable({
          paging: false,
          pageLength: 50,
          responsive: true,
          info: false,
          columnDefs: [
            {
              targets: [1, 4, 5, 8],
              orderable: false
            }
          ],
          dom: "Bfrtip",
          buttons: ["copy", "csv", "pdf", "print"]
        });
      });

      return true;
    },
    updateFilterTo(value) {
      let $filter_to = document.getElementById("filter_to");
      $filter_to.removeAttribute("disabled");
      $filter_to.setAttribute("min", value);
      $filter_to.setAttribute("value", this.todayDate);
      this.updateTable();
    },
    searchTable(searchTerm) {
      this.DataTable.search(searchTerm).draw();
    },
    updateTable() {
      const vm = this;
      let filterCourse = function(student) {
        if (vm.filter.course !== "") {
          // console.log(student);
          return student.enrollments.some(function(enrollment) {
            // console.log(enrollment);
            return enrollment.course_id == vm.filter.course;
          });
        }
        return true;
      };

      let filterDate = function(student) {
        if (vm.filter.minDate.length > 0) {
          return vm
            .$moment(student.created_at)
            .isBetween(vm.filter.minDate, vm.filter.maxDate, "day", "[]");
        }
        return true;
      };

      let filterStatus = function(student) {
        if (vm.filter.status.length > 1) {
          return student.enrollments.some(function(enrollment) {
            return enrollment.status == vm.filter.status;
          });
        }
        return true;
      };

      let fStudent = this.studentsCache.filter(function(student) {
        return (
          filterCourse(student) && filterDate(student) && filterStatus(student)
        );
      });

      this.students = fStudent;
      this.DataTable = null;
      this.refreshTable();
      /**
       *  this is to trick the the table in updating its data
       * by iterating each rows after filtering
       *  */

      try {
        this.DataTable.rows().every(function() {});
      } catch (error) {}
    }
  },
  created() {
    const vm = this;
    Fire.$on("userModified", function() {
      vm.fetchAllStudents();
    });
    this.fetchAllStudents();
    this.fetchAllCourses();
    this.DataTable = null;
  },
  mounted() {}
};
</script>

<style></style>
