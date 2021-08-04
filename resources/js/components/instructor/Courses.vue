
<template>
  <div class="container-fluid">
    <ol class="breadcrumb" v-show="!showSingle">
      <li class="breadcrumb-item">
        <a href="/instructor/"><i class="fa fa-fw fa-home"></i></a>
      </li>
      <li class="breadcrumb-item active">Courses</li>
    </ol>

    <ol class="breadcrumb" v-show="showSingle">
      <li class="breadcrumb-item">
        <a href="#"><i class="fa fa-fw fa-home"></i></a>
      </li>
      <li
        class="breadcrumb-item active clickable"
        @click.prevent="showSingle = false"
      >
        {{ currentBreadcumb }}
      </li>
      <li class="position-absolute" style="right:2em; top">
        <a
          style="font-size:0.9em; margin-top:-0.4em"
          class="btn clickable"
          role="button"
          aria-roledescription="edit banner"
          @click.prevent="showSingle = false"
        >
          <i class="icon-back"></i> Go back
        </a>
      </li>
    </ol>

    <div class="row mb-4" v-show="!showSingle">
      <div class="col-md-12">
        <div class="card course-card px-3">
          <div
            class="card-body d-flex flex-md-row flex-sm-column flex-column flex-wrap"
          >
            <div
              class="col-xl-4 col-lg-6 col-md-6"
              v-for="course in assignedCourses"
              :key="course.id"
            >
              <div
                class="home-course-grid box_grid wow animated"
                style="visibility: visible;"
              >
                <figure class="block-reveal">
                  <div class="block-horizzontal"></div>
                  <div class="tint-bg"></div>
                  <a href="#"
                    ><img
                      :src="course.banner | fullPath"
                      class="img-fluid course-list-img"
                      alt=""
                  /></a>
                  <div class="price">{{ formatter.format(course.price) }}</div>
                </figure>
                <div class="wrapper">
                  <small>Faculty</small>
                  <p>{{ course.faculty.name }}</p>
                  <h3>{{ course.title }}</h3>
                  <p>{{ course.description | truncateText(50) }}</p>
                </div>
                <ul>
                  <li>
                    <i class="icon_clock_alt"> </i
                    > {{ course.start_date | moment("MMMM Do YYYY") }}
                  </li>
                  <li>
                    <a href="#" @click.prevent="showSingleCourse(course)"
                      > <i class="fa fa-eye" aria-hidden="true"></i> View</a
                    >
                  </li>
                </ul>
              </div>
              <!-- /box_grid -->
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 text-center" v-if="loading">
        <small>loading...</small>
      </div>
    </div>

    <div class="row mb-4" v-show="showSingle">
      <div class="col-md-12">
        <div class="card course-card px-3">
          <div class="card-body">
            <div>
              <div class="box_list wow animated" style="visibility: visible;">
                <div class="row no-gutters">
                  <div class="col-md-12">
                    <table
                      class="responsive-table table table-striped vertical border-bottom mb-0"
                    >
                      <tr>
                        <th scope="col">Cost</th>
                        <td>{{ formatter.format(showedCourse.price) }}</td>
                      </tr>
                      <tr>
                        <th scope="col">No. of enrolled student</th>
                        <td scope="col" v-if="showedCourse.students">
                          {{ showedCourse.students.length }}
                        </td>
                        <td scope="col" v-else>0</td>
                      </tr>
                      <tr>
                        <th scope="col">Current Session</th>
                        <td>
                          {{
                            showedCourse.start_date | moment(" MMMM Do YYYY")
                          }}
                          /
                          {{ showedCourse.end_date | moment(" MMMM Do YYYY") }}
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="row no-gutters">
                  <div class="col-lg-5">
                    <figure class="block-reveal">
                      <div class="block-horizzontal"></div>
                      <a href=""
                        ><img
                          :src="showedCourse.banner | fullPath"
                          class="img-fluid course-list-img"
                          id="courseBanner"
                          alt=""
                      /></a>
                      <div class="preview"></div>
                      <div class="tint-bg"></div>
                    </figure>
                  </div>
                  <div class="col-lg-7">
                    <div class="wrapper">
                      <small>Course</small>
                      <h3>{{ showedCourse.title }}</h3>
                      <p>{{ showedCourse.description }}</p>
                      <p><b>Schedules </b> {{ showedCourse.schedule }}</p>
                      <b
                        class="section-header"
                        @click="showRequirementDrop = !showRequirementDrop"
                        >Requirements
                        <i
                          class="fa fa-chevron-up ml-4"
                          v-if="!showRequirementDrop"
                        >
                        </i>
                        <i class="ml-4 fa fa-chevron-down" v-else> </i
                      ></b>
                      <div
                        class=""
                        v-if="Object.keys(showedCourse.requirement).length > 0"
                        v-show="showRequirementDrop"
                      >
                        <p
                          v-for="i in Object.keys(showedCourse.requirement)"
                          :key="i"
                        >
                          <b class="font-small">{{
                            showedCourse.requirement[i].title
                          }}</b
                          ><br />
                          {{ showedCourse.requirement[i].text }}
                        </p>
                      </div>
                      <ul v-else>
                        <li>NO requirement for this course</li>
                      </ul>
                      <br /><br />
                      <p><b>Instructors </b></p>
                      <p
                        v-for="instructor in showedCourse.instructors"
                        :key="instructor.id"
                      >
                        {{ instructor.first_name + " " + instructor.last_name }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row no-gutters d-flex flex-column align-items-center">
                <div
                  class="col-md-12 justify-content-between d-flex mb-3"
                  v-for="(material, index) in showedCourse.materials"
                  :key="material.id"
                >
                  <span class="material-title"
                    >{{ material.title }} (upload by
                    {{
                      material.user.first_name + " " + material.user.last_name
                    }}
                    )
                  </span>
                  <div>
                    <a
                      target="_blank"
                      class="btn btn-apply"
                      :href="material.file_url | fullPath"
                    >
                      <i class="fa fa-download"> Download Course</i
                    ></a>
                    <!-- <a
                      class="btn btn-danger"
                      @click.prevent="deleteMaterial(index)"
                    >
                      <i class="fa fa-trash"></i
                    ></a> -->
                  </div>
                </div>
                <span v-if="showedCourse.materials < 1"
                  >NO COURSE MATERIAL</span
                >
                <!-- <div class="col-md-9">
                  <button
                    class=" w-100 m-4 btn btn-success"
                    data-toggle="modal"
                    data-target="#newMaterialModal"
                  >
                    Upload new material
                  </button>
                </div> -->
              </div>

              <div class="row no-gutters d-flex justify-content-end"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      id="newMaterialModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newMaterial"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newMatrerial">
              Upload new course material
            </h5>
            <button
              type="button"
              class="btn-dismiss-modal close"
              data-dismiss="modal"
              aria-label="Close"
              id="newMaterialModalCloseButton"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="uploadNewMaterial">
            <div class="modal-body">
              <div class="form-group">
                <div class="mb-4">
                  <label for="inputDuration">Title/Short Description</label>
                  <input
                    type="text"
                    name="title"
                    v-model="form.title"
                    class="form-control"
                    placeholder="Title of material"
                  />
                  <has-error :form="form" field="title"></has-error>
                </div>
                <div class="form-group">
                  <label for="material">Course Material (Max size 1mb)</label>
                  <input
                    type="file"
                    name="material"
                    id="material"
                    class="form-control"
                    required
                  />
                  <has-error :form="form" field="file"></has-error>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
                id="closeModal"
              >
                Close
              </button>
              <button type="submit" class="btn btn-apply" :disabled="form.busy">
                <span
                  class="spinner-border spinner-border-sm spinner-submit"
                  role="status"
                  aria-hidden="true"
                ></span>
                Upload Material
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined } from "../mixins/enrollmentMixin";
import { instructorMixin } from "../mixins/instructorMixin";
import Multiselect from "vue-multiselect";
Vue.component("multiselect", Multiselect);
export default {
  mixins: [enrollmentMixin, instructorMixin],
  data() {
    return {
      instructors: [],
      facultyLoading: true,
      showSingle: false,
      showRequirementDrop: false,
      showedCourse: {
        requirement: {},
        students: []
      },
      currentBreadcumb: "",
      faculties: {},
      currentFacultyId: 0,
      currentFaculty: "",
      form: new Form({
        title: "",
        file: ""
      })
    };
  },
  methods: {
    showSingleCourse(course) {
      this.showSingle = true;
      try {
        course.requirement = JSON.parse(course.requirement);
        if (isNullOrUndefined(course.requirement)) {
          course.requirement = {};
        }
      } catch (error) {
      } finally {
        this.showedCourse = course;
        this.currentBreadcumb = course.title;
      }
    },
    deleteMaterial(index) {
      let id = this.showedCourse.materials[index].id;
      const vm = this;
      Swal.fire({
        title: "Caution!",
        text: "Deleted Materials cannot be recycled",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, I want to delete material!",
        allowOutsideClick: true
      }).then(input => {
        if (input.value === true) {
          Fire.$emit("pageBusy");
          axios
            .delete(`/api/v1/material/${id}`)
            .then(response => {
              if (response.data.status === "success") {
                Toast.fire({
                  type: "success",
                  title: "Material deleted"
                });
                Fire.$emit("coursesChanged");
                vm.showSingle = false;
              }
            })
            .catch(d => {})
            .finally(d => {
              Fire.$emit("pageFree");
            });
        }
      });
    },
    uploadNewMaterial() {
      const vm = this;
      const url = `/api/v1/instructor/material/${this.showedCourse.id}`;
      let supportedFormat = [
        "application/pdf",
        "image/png",
        "image/jpg",
        "image/jpeg",
        "application/x-zip-compressed"
      ];
      let e = document.getElementById("material");
      let id = e.id;
      let file = e.files[0];
      if (file.size <= 1024 * 1024 && supportedFormat.includes(file.type)) {
        NProgress.start();
        Fire.$emit("pageBusy");
        let reader = new FileReader();
        reader.onloadend = function() {
          vm.form.file = {
            name: "material",
            data: reader.result,
            type: file.type
          };
          vm.form
            .post(url)
            .then(response => {
              if (response.data.status === "success") {
                Fire.$emit("coursesChanged");
                vm.showSuccess(
                  "Material added successfully",
                  "Course material would now be available to students"
                );
                document.getElementsByClassName("btn-dismiss-modal")[0].click();
                vm.showSingle = false;
              } else {
                vm.showError();
              }
            })
            .catch(response => {
              vm.showError();
            })
            .finally(response => {
              NProgress.done();
              Fire.$emit("pageFree");
            });
        };
        reader.readAsDataURL(file);
      } else {
        vm.showErrorWithMessage(
          "Maximum File size is 1mb and supported formats are .pdf .png .jpg .jpeg and .zip"
        );
      }
    }
  },
  created() {
    Fire.$on("coursesChanged", () => {
      this.fetchProfile();
    });
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style></style>