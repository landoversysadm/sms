<template>
    <div class="container-fluid">
        <ol class="breadcrumb position-relative" v-show="!showSingle">
              <li class="breadcrumb-item">
               <a href="/admin/"><i class="fa fa-fw fa-home"></i></a>
              </li>
             <li class="breadcrumb-item active">Faculties</li>
             <li>
               <div class="searchbar faculty_filter position-absolute float-sm" >
                  <input type="search" class="search_input" name="" v-model="searchTerm" placeholder="search" aria-controls="table-students">
                  <a href="#" class="search_icon"><i class="fa fa-search"></i></a>
                </div>
             </li>
             <li class="position-absolute" style="right:2em; top">
                 <a class="btn btn-apply" style="font-size:0.9em; margin-top:-0.4em" href="#" data-toggle="modal" data-target="#addFacultyModal" >add faculty
                     <i class="fa fa-plus"></i></a></li>
        </ol>

        <ol class="breadcrumb" v-show="showSingle">
              <li class="breadcrumb-item">
               <a href="#"><i class="fa fa-fw fa-home"></i></a>
              </li>
             <li class="breadcrumb-item active clickable"  @click.prevent="showSingle = false">{{currentBreadcumb}}</li>
              <li class="position-absolute" style="right:2em; top">
                  <a  style="font-size:0.9em; margin-top:-0.4em" class="btn clickable" role="button" aria-roledescription="edit banner" @click.prevent="showSingle = false">
                      <i class="icon-back"></i > Go back
                </a>
            </li>
        </ol>

         <div class="row mb-4" v-show="!showSingle">
            <div class="col-md-12" v-for="(faculty) in faculties" :key="faculty.id">
                <div class="card course-card px-3">
                    <div class="card-header">
                        <span @click="faculty.shown = !faculty.shown" class="clickable text-uppercase font-weight-bold"><i class="fa fa-chevron-up ml-4" v-if="faculty.shown || searching">
                              </i> <i class="ml-4 fa fa-chevron-down" v-else> </i>
                              {{faculty.name}}
                        </span>
                          <a @click="deleteFaculty(faculty.id)" class="float-right cursor-pointer ml-2 text-danger" >  delete faculty <i class="fa fa-trash"></i> </a>
                        <a @click="addCourseClicked(faculty.id)" class="float-right cursor-pointer" data-toggle="modal" data-target="#addCourseModal" >add course <i class="fa fa-plus"></i></a>

                        <!-- <a class="float-right t mr-3 cursor-pointer"> edit faculty <i class="fa fa-edit"></i></a> -->
                    </div>
                    <div class="card-body d-flex flex-md-row flex-sm-column flex-column flex-wrap" >
                        <div class="col-xl-4 col-lg-6 col-md-6"  v-for="course in faculty.courses" :key="course.id" v-show="faculty.shown || searching">

                      <div class="home-course-grid box_grid wow animated" style="visibility: visible;" v-show="matchSearch(course.title)">
                                    <figure class="block-reveal">
                                        <div class="block-horizzontal"></div>
                                        <div class="tint-bg"></div>
                                        <a href="#"><img :src="course.banner | fullPath" class="img-fluid course-list-img" alt=""></a>
                                        <div class="price">{{formatter.format(course.price)}}</div>
                                    </figure>
                                    <div class="wrapper">
                                        <small>Faculty</small>
                                        <p>{{faculty.name}}</p>
                                        <h3>{{course.title}}</h3>
                                        <p>{{course.description | truncateText(50)}}</p>
                                        </div>
                                    <ul>
                                        <li><i class="icon_clock_alt"> </i> {{ course.start_date | moment("MMMM Do YYYY") }}</li>
                                        <li><a href="#"  @click.prevent="showSingleCourse(faculty, course)" > <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                    </ul>
                        </div>
                        <!-- /box_grid -->

                        </div>

                    </div>

                </div>
            </div>
            <div class="col-md-12 text-center" v-if="facultyLoading">
                <small>loading...</small>
            </div>
         </div>

         <div class="row mb-4" v-show="showSingle">
             <div class="col-md-12">
                <div class="card course-card px-3">
                    <div class="card-body">
                            <div>
                                <div class="box_list wow animated" style="visibility: visible;" >
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <table class="responsive-table table table-striped vertical border-bottom mb-0">
                                                <tr>
                                                    <th scope="col">Cost</th>
                                                    <td>{{formatter.format(showedCourse.price)}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">No. of enrolled student</th>
                                                    <td scope="col" v-if="showedCourse.students">{{showedCourse.students.length}}</td>
                                                    <td scope="col" v-else>0</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col">Current Session</th>
                                                    <td>{{ showedCourse.start_date | moment(" MMMM Do YYYY")}} /  {{showedCourse.end_date | moment(" MMMM Do YYYY") }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row no-gutters">
                                        <div class="col-lg-5">
                                            <figure class="block-reveal">
                                                <div class="block-horizzontal"></div>
                                                <a href=""><img :src="showedCourse.banner | fullPath" class="img-fluid course-list-img" id="courseBanner" alt=""></a>
                                                <div class="preview"></div>
                                                <div class="tint-bg"></div>
                                                <a href="#0" class="edit_bt icon-edit" @click.prevent="clickBannerInput"></a>
                                                  <input type="file" class="custom-file-input banner-upload hidden" name="banner" id="bannerUpload" @change="updateCourseBanner" accept=".jpg, .png, .jpeg">
                                            </figure>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="wrapper">
                                                <small>Course</small>
                                                <a href="#" class="edit_bt icon-edit" data-toggle="modal" data-target="#updateCourseModal" @click="showCourseModal(showedCourse)"></a>
                                                <h3>{{showedCourse.title}}</h3>
                                                <p>{{showedCourse.description}} </p>
                                                <p><b>Schedules </b> {{showedCourse.schedule}}</p>
                                                <b class="section-header" @click="showRequirementDrop = !showRequirementDrop">Requirements <i class="fa fa-chevron-up ml-4" v-if="!showRequirementDrop"> </i> <i class="ml-4 fa fa-chevron-down" v-else> </i></b>
                                                <div class="" v-if="Object.keys(showedCourse.requirement).length > 0" v-show="showRequirementDrop">
                                                    <p v-for="i in Object.keys(showedCourse.requirement)" :key="i" > <b class="font-small">{{showedCourse.requirement[i].title}}</b><br> {{showedCourse.requirement[i].text}} </p>
                                                </div>
                                                <ul v-else>
                                                    <li> NO requirement for this course</li>
                                                </ul>
                                                <br><br>
                                                <p><b>Instructors </b></p>
                                                <p v-for="instructor in showedCourse.instructors" :key="instructor.id">{{instructor.first_name+ ' '+instructor.last_name}}</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row no-gutters d-flex flex-column align-items-center">
                                  <div class="col-md-12 justify-content-between d-flex mb-3" v-for=" (material,index) in showedCourse.materials" :key="material.id">
                                    <span class="material-title">{{material.title}}   (upload by {{material.user ? material.user.first_name  + ' '+ material.user.last_name : '---'}} ) </span>
                                    <div>

                                      <!-- <a target="_blank"  class="btn btn-apply" :href="material.file_url | fullPath"> <i class="fa fa-download"></i></a> -->
                                      <!-- <div class="row no-gutters d-flex justify-content-end"> -->
                                        <!-- <button class="m-4 btn btn-success btn-lg" data-toggle="modal" data-target="#newSessionModal">Start a new session</button> -->
                                        <!-- <button class="m-4 btn btn-danger btn-lg" @click="deleteCourse" ><i class="fa fa-trash"></i> Delete Course</button> -->
                                    <!-- </div> -->

                                    <div class="btn-group">
                                        <a target="_blank"  class="btn btn-apply" :href="material.file_url | fullPath"> <i class="fa fa-download"></i> Download Course</a>
                                        <a class="btn btn-danger" style="color:white !important;" @click.prevent="deleteMaterial(index)" >
                                            <i class="fa fa-trash"></i>
                                            Delete Material
                                        </a>
                                    </div>
                                        <!--  -->

                                    </div>
                                  </div>
                                  <span v-if="showedCourse.materials <1">NO COURSE MATERIAL</span>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column align-items-end">
                              
                                <div class="btn-group">
                                  <button type="button" class="btn btn-apply"  data-toggle="modal" data-target="#newSessionModal"><i class="fa fa-hourglass-start" aria-hidden="true"></i>
                                      Start a new session
                                  </button>
                                  <button type="button" class="btn btn-danger"  @click="deleteCourse"><i class="fa fa-trash"></i> Delete Course</button>
                              <!-- </div> -->
                              </div>
                            </div>

                            <!-- modal button  -->
                                <div>
                                    <div class="col-md-9" style="margin-top:25px; text-align:center; margin:auto;">
                                      <button
                                      class=" w-100 mt-5 btn btn-success"
                                      data-toggle="modal"
                                      data-target="#newMaterialModal"
                                      >
                                      Upload new material
                                      </button>
                                  </div>
                                </div>


                              <!-- modal -->
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
                </div>
            </div>

         </div>



<!-- add Faculty modal -->
<div class="modal fade" id="addFacultyModal" tabindex="-1" role="dialog" aria-labelledby="addFacultyModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFacultyModal">New Faculty</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addNewFaculty()">
      <div class="modal-body">
          <div class="form-group">
              <label>Faculty Name</label>
            <input type="text" name="name" id="facultyName" class="form-control" :class="{ 'is-invalid': newFacultyForm.errors.has('name') }"  placeholder="enter new faculty name" v-model="newFacultyForm.name"
            />
            <has-error :form="newFacultyForm" field="name"></has-error>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close</button>
        <button type="submit" class="btn btn-apply"  :disabled="newFacultyForm.busy" ><span class="spinner-border spinner-border-sm spinner-submit" role="status" aria-hidden="true"></span> Add Faculty</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--  // add Faculty modal -->


<!-- add course modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCourseModal">New Course</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="exitAddCourseModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addCourse()">
      <div class="modal-body">

    <div class="form-group col-md-12">
      <label for="title">Banner</label>
      <span id="custom-file-error" class="invalid-feedback mb-2">Banner must be an image with size less than 1mb</span>
      <div class="custom-file">
        <input type="file" class="form-control" id="newCourseBanner" required  :class="{ 'is-invalid': form.errors.has('banner') }" @change="updateCustomFileLabel" accept=".jpg, .png, .jpeg">
        <label class="" for="customFile">{{choosen_custom_banner}} </label>

        </div>
        <has-error :form="form" field="banner"></has-error>
    </div>

    <div class="form-group col-md-12">
      <label for="title">Title</label>
      <input type="title" class="form-control" id="title" placeholder="Title" v-model="form.title" :class="{ 'is-invalid': form.errors.has('title') }"  required>
      <has-error :form="form" field="title"></has-error>
    </div>
    <div class="form-group col-md-12">
      <label for="description">Description</label>
      <textarea type="text" class="form-control" id="description" placeholder="Description" v-model="form.description" :class="{ 'is-invalid': form.errors.has('description') }" required rows="2" cols="30"></textarea>
        <has-error :form="form" field="description"></has-error>
    </div>

  <div class="form-group">
      <div class="mb-4">
        <label for="inputDuration">Duration</label>
        <VueCtkDateTimePicker v-model="form.duration" :format="'YYYY/MM/DD'" :range="true" formatted="ll" :no-shortcuts="true" :button-color="'#16462b'" :color="'#16462b'"
        :only-date="false" :min-date="new Date()| moment()" :label="'select course duration'" :custom-shortcuts="durationShortcut" :class="{ 'is-invalid': form.errors.has('start_date')|| form.errors.has('end_date') || durationError}" required>
        </VueCtkDateTimePicker>
        <has-error :form="form" field="start_date"></has-error>
        <has-error :form="form" field="end_date"></has-error>
      </div>
      <div>
            <label for="inputAddress">Schedule</label>
            <div class="row durationField">
                <div class="col">
                <textarea type="text" class="form-control" placeholder="title e.g. Theory" name="schedule" v-model="form.schedule" required :class="{ 'is-invalid': form.errors.has('schedule') }" > </textarea>
                <has-error :form="form" field="schedule"></has-error>
            </div>
            </div>
      </div>
  </div>
  <div class="form-group">
      <label for="title">Assign instructor(s)</label>
      <multiselect v-model="form.instructors" :options="instructorOptions" :multiple="true" :close-on-select="false" :clear-on-select="false"
             placeholder="Select Course Instructors" label="name" track-by="id" :preselect-first="true" :class="{ 'is-invalid': form.errors.has('instructors') }" required>
        </multiselect>
        <has-error :form="form" field="price"></has-error>
  </div>
  <div class="form-group">
      <label for="inputPrice">Price</label>
    <div class="input-group mb-2 mr-sm-2">
        <div class="input-group-prepend">
        <div class="input-group-text">&#8358;</div>
        </div>
        <input type="number" class="form-control" id="coursePrice" placeholder="Course price" v-model="form.price" :class="{ 'is-invalid': form.errors.has('price') }"  required>
        <has-error :form="form" field="price"></has-error>
    </div>
  </div>
  <div class="form-group">
    <label for="inputAddress2">Requirements</label>
     <div class="row requirementField" >
        <div class="col">
            <multiselect v-model="form.requirement" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="false"
             placeholder="Select Requirement" label="title" track-by="title" :preselect-first="true" :class="{ 'is-invalid': form.errors.has('requirement') }" required>
            </multiselect>
            <has-error :form="form" field="requirement"></has-error>
            <span v-for="v in form.requirement" :key="v.text" class="selected-requirement mt-4">
                <b>{{v.title}}</b><br>
                {{v.text}}
            </span>
        </div>
     </div>
  </div>

  <div class="form-group">
    <label for="courseVisible"><b>Hide from Homepage</b></label>
    <input type="checkbox" name="courseVisible" id="courseVisible" class="ml-4" v-model="form.hide" :class="{ 'is-invalid': form.errors.has('hide') }">
    <div class="row ">
      <div class="col">
        <has-error :form="form" field="hide"></has-error>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="courseComingSoon"><b>Coming Soon</b></label>
    <input type="checkbox" name="courseComingSoon" id="courseComingSoon" class="ml-4" v-model="form.coming_soon" :class="{ 'is-invalid': form.errors.has('coming_soon') }">
    <div class="row ">
      <div class="col">
        ( this would hide the current set date from the Homepage and display a coming soon text instead)
        <has-error :form="form" field="coming_soon"></has-error>
      </div>
    </div>
  </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closeModalCourse" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-apply" :disabled="form.busy">Add Course</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- // add course modal -->

<!-- update course modal -->
<div class="modal fade" id="updateCourseModal" tabindex="-1" role="dialog" aria-labelledby="updateCourseModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCourseModal">Update Course</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="exitModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="updateCourse()">
      <div class="modal-body">
  <div class="form-row">

    <div class="form-group col-md-12">
      <label for="title">Title</label>
      <input type="title" class="form-control" id="title" placeholder="Title" v-model="updateForm.title" :class="{ 'is-invalid': updateForm.errors.has('title') }"  required>
      <has-error :form="updateForm" field="title"></has-error>
    </div>
    <div class="form-group col-md-12">
      <label for="description">Description</label>
      <textarea type="text" class="form-control" id="description" placeholder="Description" v-model="updateForm.description" :class="{ 'is-invalid': updateForm.errors.has('description') }" required rows="2" cols="30"></textarea>
        <has-error :form="updateForm" field="description"></has-error>
    </div>
  </div>
  <div class="form-group">
      <div class="mb-4">
        <label for="inputDuration">Duration</label>
        <VueCtkDateTimePicker v-model="updateForm.duration" :format="'YYYY/MM/DD'" :range="true" formatted="ll" :no-shortcuts="true" :button-color="'#16462b'" :color="'#16462b'"
        :only-date="false" :min-date="new Date()| moment()" :label="'select course duration'" :custom-shortcuts="durationShortcut" :class="{ 'is-invalid': updateForm.errors.has('start_date')|| updateForm.errors.has('end_date') || updateDurationError}" required>
        </VueCtkDateTimePicker>
        <has-error :form="updateForm" field="start_date"></has-error>
        <has-error :form="updateForm" field="end_date"></has-error>
      </div>
      <div>
            <label for="inputAddress">Schedule</label>
            <div class="row durationField">
                <div class="col">
                <textarea type="text" class="form-control" placeholder="title e.g. Theory" name="schedule" v-model="updateForm.schedule" required :class="{ 'is-invalid': updateForm.errors.has('schedule') }" > </textarea>
                <has-error :form="updateForm" field="schedule"></has-error>
            </div>
            </div>
      </div>
  </div>
  <div class="form-group">
      <label for="title">Assign instructor(s)</label>
      <multiselect v-model="updateForm.instructors" :options="instructorOptions" :multiple="true" :close-on-select="false" :clear-on-select="false"
             placeholder="Select Course Instructors" label="name" track-by="id" :preselect-first="true" :class="{ 'is-invalid': updateForm.errors.has('instructors') }" required>
      </multiselect>
      <has-error :form="updateForm" field="instructors"></has-error>
  </div>
  <div class="form-group">
      <label for="inputPrice">Price</label>
    <div class="input-group mb-2 mr-sm-2">
        <div class="input-group-prepend">
        <div class="input-group-text">&#8358;</div>
        </div>
        <input type="number" class="form-control" id="coursePrice" placeholder="Course price" v-model="updateForm.price" :class="{ 'is-invalid': updateForm.errors.has('price') }"  required>
        <has-error :form="updateForm" field="price"></has-error>
    </div>
  </div>
  <div class="form-group">
    <label for="inputAddress2">Requirements</label>
     <div class="row requirementField" >
        <div class="col">
            <multiselect v-model="updateForm.requirement" :options="options" :multiple="true" :close-on-select="false" :clear-on-select="false"
             placeholder="Select Requirement" label="title" track-by="title" :preselect-first="true" :class="{ 'is-invalid': updateForm.errors.has('requirement') }" required>
            </multiselect>
            <has-error :form="updateForm" field="requirement"></has-error>
            <span v-for="v in updateForm.requirement" :key="v.text" class="selected-requirement mt-4">
                <b>{{v.title}}</b><br>
                {{v.text}}
            </span>
        </div>
     </div>
  </div>

  <div class="form-group">
    <label for="courseVisible"><b>Hide from Homepage</b></label>
    <input type="checkbox" name="courseVisible" id="courseVisible" class="ml-4" v-model="updateForm.hide" :class="{ 'is-invalid': form.errors.has('hide') }">
    <div class="row ">
      <div class="col">
        <has-error :form="updateForm" field="hide"></has-error>
      </div>
    </div>
  </div>

    <div class="form-group">
    <label for="courseComingSoon"><b>Coming Soon</b></label>
    <input type="checkbox" name="courseComingSoon" id="courseComingSoon" class="ml-4" v-model="updateForm.coming_soon" :class="{ 'is-invalid': updateForm.errors.has('coming_soon') }">
    <div class="row ">
      <div class="col">
        ( this would hide the current set date from the Homepage and display a coming soon text instead)
        <has-error :form="updateForm" field="coming_soon"></has-error>
      </div>
    </div>
  </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-apply" :disabled="updateForm.busy">Update Course</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- // update course modal -->

<!-- start new seession modal -->
        <div class="modal fade" id="newSessionModal" tabindex="-1" role="dialog" aria-labelledby="newSession" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newSession">Set session start and end date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="newSessionModalCloseButton">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="startNewSession">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="mb-4">
                            <label for="inputDuration">Duration</label>
                            <VueCtkDateTimePicker v-model="newSessionForm.duration" :format="'YYYY/MM/DD'" :range="true" formatted="ll" :no-shortcuts="true" :button-color="'#16462b'" :color="'#16462b'"
                            :only-date="false" :min-date="new Date()| moment()" :label="'select course duration'" :custom-shortcuts="durationShortcut" :class="{ 'is-invalid': newSessionForm.errors.has('start_date')|| newSessionForm.errors.has('end_date')}" required>
                            </VueCtkDateTimePicker>
                            <has-error :form="newSessionForm" field="start_date"></has-error>
                            <has-error :form="newSessionForm" field="end_date"></has-error>
                        </div>
                        <div>
                                <label for="inputAddress">Schedule</label>
                                <div class="row durationField">
                                    <div class="col">
                                    <textarea type="text" class="form-control" placeholder="title e.g. Theory" name="schedule" v-model="newSessionForm.schedule" required :class="{ 'is-invalid': newSessionForm.errors.has('schedule') }" > </textarea>
                                    <has-error :form="newSessionForm" field="schedule"></has-error>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close</button>
                    <button type="submit" class="btn btn-apply"  :disabled="newSessionForm.busy"><span class="spinner-border spinner-border-sm spinner-submit" role="status" aria-hidden="true"></span> Start session</button>
                </div>
                </form>
                </div>
            </div>
        </div>

<!--// start new session modal -->

    </div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined} from '../mixins/enrollmentMixin';
import Multiselect from 'vue-multiselect'
Vue.component('multiselect', Multiselect)
export default {
    mixins: [enrollmentMixin],
    data(){
        return{
            cacheFaculties : null,
            instructors :[],
            searchTerm : '',
            durationError : false,
            updateDurationError : false,
            facultyLoading : true,
            choosen_custom_banner : '',
            value: null,
            showSingle: false,
            showRequirementDrop: true,
            showedCourse:{
                requirement :{},
                students : []
            },
            currentBreadcumb: '',
            options: [
                {title: 'General Education Qualification', text: 'Educational Qualification (Minimum of Five (5) credits in O’Levels, GCE, NECO ; (Maths & English inclusive of the five credits), OND, HND, BSc, BA, MBA and MSc)',type:'file'},
                {title: 'Engineering Education Qualification', text: 'Educational qualification (Minimum of HND or BTech in Engineering)',type:'file'},
                {title: 'Valid Identification', text: 'Valid means of identification', type:'file'},
                {title: 'Birth Certificate or Age Declaration', text: 'Age declaration or Birth Certificate', type:'file'},
                {title: 'Work Experience', text: 'Minimum of two years work experience in the Nigerian Aviation Industry as Engineers', type:'text'},
                {title: 'Entry Level Test' , text: 'Entry level test  for O’levels students', type:'file'},
                {title: 'Basic Airfares Certificate from LABS', text: 'Basic Airfares & Ticketing Certificate from LABS', type:'file'},
                {title: 'IATA Diploma', text: 'IATA Foundation Diploma /IATA Consultant Certificate ', type:'file'}
                 ],
            instructorOptions: [],
            durationShortcut:[
                    { label: 'This Week', value: 'week', isSelected: false },
                    { label: 'This iso Week', value: 'isoWeek', isSelected: false },
                    { label: 'This Month', value: 'month', isSelected: false },
                    { label: 'This Month', value: 'year', isSelected: false },
            ],
            faculties: {},
            requirementFieldCount: 1,
            durationFieldCount: 1,
            currentFacultyId: 0,
            currentFaculty : '',
            form: new Form({
                title: "",
                description: "",
                banner: "",
                duration: "",
                schedule: "",
                requirement: null,
                faculty_id: "",
                price: "",
                start_date: "",
                end_date: "",
                instructors: null,
                hide: false,
                coming_soon: false,
            }),
            updateForm: new Form({
                title: "",
                description: "",
                duration: {
                    start: '',
                    end: '',
                    shortcut: ''
                },
                schedule: "",
                requirement: null,
                faculty_id: "",
                price: "",
                start_date: "",
                end_date: "",
                instructors: null,
                hide: '',
                coming_soon: '',
            }),
            newFacultyForm: new Form({
                name: '',
            }),
            newSessionForm :new Form({
                duration : {
                    start: null,
                    end: null,
                    shortcut: '',
                },
                start_date : "",
                end_date : "",
                schedule: "",
            }),
        }
    },
    computed : {
      searching : function(){
        return this.searchTerm.length > 2;
      }
    },
    methods: {
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
        const url = `/api/v1/admin/material/${this.showedCourse.id}`;
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
      },
      matchSearch(title){
        if(this.searchTerm<3 ||
        title.toLowerCase().includes(this.searchTerm.toLowerCase())){
          return true;
        }
        return false;
      },
      deleteFaculty(facultyId){
        Swal.fire({
          title: 'Caution!',
          text: "Associated courses and related information would be erased completely from the system",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, I want to delete faculty!',
          allowOutsideClick: true,
        }).then((input)=>{
          if(input.value === true){
            Fire.$emit('pageBusy');
            axios.delete(`/api/v1/admin/faculty/${facultyId}`).then((payload)=>{
              console.log(payload.data.status);
              if(payload.data.status == 'success'){
                Fire.$emit('coursesChanged');
                  Toast.fire({
                    type: 'success',
                    title: 'Faculties updated'
                  });
              }
            }).catch((payload)=>{}).finally((payload)=>{
                Fire.$emit('pageFree');
              });
          }
        });
      },
      deleteCourse(){
        Swal.fire({
              title: 'Caution!',
              text: "Enrolled students and associated data would be erased completely from the system",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, I want to delete course!',
              allowOutsideClick: true,
          }).then((input)=>{
              if(input.value === true){
                Fire.$emit('pageBusy');
                axios.delete(`/api/v1/admin/course/${this.showedCourse.id}`).then((payload)=>{
                  if(payload.data.data){
                    this.showSingle = false;
                    Fire.$emit('coursesChanged');
                      Toast.fire({
                        type: 'success',
                        title: 'Course updated'
                      });
                  }
                }).catch((payload)=>{}).finally((payload)=>{
                  Fire.$emit('pageFree');
                });
              }
          });
      },
      clickBannerInput(){
          document.getElementById('bannerUpload').click();
          return true;
      },
      getInstructors(){
          axios.get('/api/v1/admin/instructors').then(
              (data)=>{
                  this.instructors = data.data.data;
                  this.initInstructorOptions();
              }
          );
      },
      initInstructorOptions(){
          this.instructors.forEach((instructor)=>{
              this.instructorOptions.push({'id':instructor.id,'name':instructor.first_name+' '+instructor.last_name});
          });
      },
      showCourseModal(course){
          this.updateForm.title = course.title;
          this.updateForm.description = course.description;
          this.updateForm.duration.start = course.start_date;
          this.updateForm.duration.end = course.end_date;
          this.updateForm.start_date = course.start_date;
          this.updateForm.end_date = course.end_date;
          this.updateForm.price = course.price;
          this.updateForm.schedule = course.schedule;
          this.updateForm.requirement = course.requirement;
          this.updateForm.hide = course.hide;
          this.updateForm.coming_soon = course.coming_soon;
          this.updateForm.instructors = course.instructors.map((instructor)=>{
              return {'id':instructor.id,'name':instructor.first_name+' '+instructor.last_name} });
      },
      updateCourse(){
          Fire.$emit('pageBusy');
          if(this.updateForm.duration === null ){
            this.updateDurationError = true;
            Fire.$emit('pageFree');
            return false;
          }
          this.updateForm.start_date = this.updateForm.duration.start;
          this.updateForm.end_date = this.updateForm.duration.end;
          this.updateForm.patch('/api/v1/admin/faculty/'+this.currentFacultyId+'/course/'+this.showedCourse.id).then((data)=>{
              if(data.data.status){
                  this.showSuccess('Operation successful', 'Courseadded successfully');
                  Fire.$emit('coursesChanged');
                  this.showSingleCourse(this.currentFaculty, data.data.data);
                  document.getElementById('exitModal').click();
                  NProgress.done();
              }else{
                  this.showError();
              }
          }).catch((data)=>{
              this.showError();
          }).finally((data)=>{
              Fire.$emit('pageFree');
              this.form.busy = false;
              NProgress.done();
          });
      },
      updateCourseBanner(){
          let supportedFormat = ["image/jpeg", "image/jpg", "image/png"];
          let banner = document.getElementById('bannerUpload').files[0];
          const vm = this;
          if (supportedFormat.includes(banner.type) && (banner.size/1024)<1030){
              Fire.$emit('pageBusy');
              let reader = new FileReader();
              reader.onloadend = function(){
                  document.getElementById('courseBanner').setAttribute('src', reader.result);
                  let newBanner = {'name':'banner','data':reader.result,'type':banner.type};
                  axios.patch('/api/v1/admin/faculty/'+vm.currentFacultyId+'/course/'+vm.showedCourse.id+'/banner',
                          {banner: newBanner}).then(function(data){
                              Fire.$emit('coursesChanged');
                              Toast.fire({
                                  type: 'success',
                                  title: 'Course updated'
                              });
                          }).catch(function(data){
                          }).finally((data)=>{
                              Fire.$emit('pageFree');
                          });
              }
              reader.readAsDataURL(banner);
          }else{
              this.showErrorWithMessage('Banner must be an image with size less than 1mb');
          }
      },
      showSingleCourse(faculty, course){
          this.showSingle = true;
          try{
              course.requirement = JSON.parse(course.requirement);
              if(isNullOrUndefined(course.requirement))
              {
                  course.requirement = {};
              }
          }catch(error){
          }finally{
              this.showedCourse = course;
              this.currentBreadcumb = faculty.name+' / '+course.title;
              this.currentFacultyId = faculty.id;
              this.currentFaculty = faculty;
          }
      },
      fetchAllCourses(){
          axios.get('/api/v1/faculties').then((data)=>{
              this.faculties = data.data.data;
              this.facultyLoading = false;
              this.cacheFaculties = data.data.data;
              this.initFacultyCourseShown();
          });
      },
      initFacultyCourseShown(){
          let f = this.faculties.map((e)=>{
              return {...e,"shown":false};
          });
          this.faculties = f;
      },
      addNewFaculty(){
          NProgress.start();
          Fire.$emit('pageBusy');
          this.newFacultyForm.busy = true;
          this.newFacultyForm.post('/api/v1/admin/faculty').then((data)=>{
              if(data.data.status){
                  this.showSuccess('Operation successful', 'Faculty added successfully');
                    Fire.$emit('newFacultyAdded');
                  NProgress.done();
                  Fire.$emit('pageFree');
                    document.getElementById('closeModal').click();
              }else{
                  this.showError();
                  NProgress.remove();
                  Fire.$emit('pageFree');
              }
              this.newFacultyForm.busy = false;
          }).catch((data)=>{
              this.showError();
              NProgress.remove();
              Fire.$emit('pageFree');
              this.newFacultyForm.busy = false;
          });
      },
      addCourse(){
          let e = document.getElementById('newCourseBanner');
          let fileError = document.getElementById('custom-file-error');
          let supportedFormat = ["image/jpg", "image/png", "image/jpeg"];
          const vm = this;
          let id = e.id;
          e.classList.remove('has-error');
          this.form.faculty_id = this.currentFacultyId;
          fileError.classList.remove('show');
          let file = e.files[0];
          if (supportedFormat.includes(file.type) && (file.size/1024)<1030){
              NProgress.start();
              Fire.$emit('pageBusy');
              vm.form.busy = true;
              let reader = new FileReader();
              reader.onloadend = function(){
                  vm.form.banner= {'name':'course_banner','data':reader.result,'type':file.type};
                  if(vm.form.duration === null ){
                    vm.durationError = true;
                    NProgress.done();
                    Fire.$emit('pageFree');
                    return false;
                  }
                  vm.durationError = false;
                  vm.form.start_date = vm.form.duration.start;
                  vm.form.end_date = vm.form.duration.end;
                  vm.form.post('/api/v1/admin/faculty/'+vm.currentFacultyId+'/course').then((data)=>{
                      if(data.data.status){
                          vm.showSuccess('Operation successful', 'Courseadded successfully');
                          document.getElementById('exitAddCourseModal').click();
                          Fire.$emit('coursesChanged');
                          NProgress.done();
                          Fire.$emit('pageFree');
                          vm.resetAddCourseForm();
                      }else{
                          vm.showError();
                      }
                  }).catch((data)=>{
                      vm.showError();
                  }).finally((data)=>{
                      NProgress.done();
                      Fire.$emit('pageFree');
                      vm.form.busy = false;
                  });
              }
              reader.readAsDataURL(file);
              }else{
                  e.classList.add('has-error');
                  fileError.classList.add('show');
                  NProgress.remove();
                  Fire.$emit('pageFree');
                  vm.form.busy = false;
              }
      },
      resetAddCourseForm(){
          this.form.title= "";
          this.form.description= "";
          this.form.banner= "";
          this.form.duration= "";
          this.form.schedule= "";
          this.form.requirement= null;
          this.form.faculty_id= "";
          this.form.price= "";
          this.form.start_date= "";
          this.form.end_date= "";
      },
      addCourseClicked(id){
          this.currentFacultyId = id;
      },
      updateCustomFileLabel(e){
          let fileName = e.value;
      },
      startNewSession(){
          if(isNullOrUndefined(this.newSessionForm.duration.start) || isNullOrUndefined(this.newSessionForm.duration.end)){
              return true;
          }
          this.newSessionForm.start_date = this.newSessionForm.duration.start;
          this.newSessionForm.end_date = this.newSessionForm.duration.end;
          Swal.fire({
              title: 'Are you sure?',
              text: "Status of every student enrolled to the current session would be changed to completed, and will no longer receive notifications from the school.",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Start new session!',
              allowOutsideClick: false,
          }).then((input)=>{
            if(input.value === true){
                Fire.$emit('pageBusy');
              this.newSessionForm.post('/api/v1/admin/faculty/session/'+this.currentFacultyId+'/course/'+this.showedCourse.id).then((data)=>{
                  if(data.data.status){
                      this.showSuccess('Operation successful', 'Course Session Updated successfully');
                      this.showSingleCourse(this.currentFaculty, data.data.data);
                      Fire.$emit('coursesChanged');
                      document.getElementById('newSessionModalCloseButton').click();
                  }else{
                      this.showError();
                  }
              }).catch((data)=>{
                  this.showError();
              }).finally((data)=>{
                  Fire.$emit('pageFree');
              });
            }
          });
      }
  },
    created(){
        this.fetchAllCourses();
        this.getInstructors();
        Fire.$on('newFacultyAdded',()=>{this.fetchAllCourses()} );
        Fire.$on('coursesChanged',()=>{this.fetchAllCourses()} );
    },
    mounted() {
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
</style>