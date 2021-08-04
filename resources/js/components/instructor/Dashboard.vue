<template>
  <div class="container-fluid">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">
          <i class="fa fa-fw fa-home"></i>
        </a>
      </li>
      <li class="breadcrumb-item active">My Dashboard</li>
    </ol>
    <div class="row" style="margin-top:30px;">
      <div class="summary col-xl-12 col-sm-12 mb-4">
        <div class="card dashboard text-white o-hidden">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <div class="mr-5">
              <h5>
                <span>{{ instructor.courses.length }}</span> Assigned Courses
              </h5>
            </div>
          </div>
          <router-link
            class="card-footer text-white clearfix small z-1"
            to="/instructor/courses"
          >
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
            Course Materials
            <i class="fa fa-download"></i>
          </div>
          <div class="card-body">
            <div class="row d-flex justify-content-center">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body bs">
                    <div
                      class="row no-gutters d-flex flex-column align-items-center"
                      v-for="course in assignedCourses"
                      :key="course.id"
                    >
                      <b>{{ course.title }}</b>
                      <div
                        class="col-md-12 justify-content-between d-flex mb-3"
                        v-for="material in course.materials"
                        :key="material.id"
                      >
                        <span class="material-title" v-if="material.user"
                          >{{ material.title }} (upload by
                          {{
                            material.user.first_name +
                              " " +
                              material.user.last_name
                          }}
                          )</span
                        >
                        <small v-else> {{ material.title }} (upload by deleted instuctor)</small>
                        <a
                          target="_blank"
                          class="btn btn-apply"
                          :href="material.file_url | fullPath"
                        >
                          <i class="fa fa-download"></i>
                        </a>
                      </div>
                      <span v-if="course.materials.length < 1"
                        >NO COURSE MATERIAL</span
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { isNullOrUndefined } from "util";
import { instructorMixin } from "../mixins/instructorMixin";
import { enrollmentMixin } from "../mixins/enrollmentMixin";
export default {
  mixins: [enrollmentMixin, instructorMixin]
};
</script>
