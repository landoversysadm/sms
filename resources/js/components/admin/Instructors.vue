<template>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link to="/admin/dashboard"><i class="fa fa-fw fa-home"></i></router-link>
            </li>
            <li class="breadcrumb-item active">Instructors </li>
            <li class="position-absolute" style="right:2em; top">
                 <a class="btn btn-apply" style="font-size:0.9em; margin-top:-0.4em" href="#" data-toggle="modal" data-target="#addInstructorModal" @click="newInstructorForm.password = alphaNumPassword(8)">add instructor
                     <i class="fa fa-plus"></i></a></li>
        </ol>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-deep responsive">
                <thead>
                    <tr><td>First Name</td> <td>Last Name</td> <td>Email Address</td> <td></td></tr>
                </thead>
                <tbody>
                    <tr v-for="(instructor) in pageItems" v-bind:key="instructor.id">
                        <td>{{instructor.first_name}}</td>
                        <td>{{instructor.last_name}}</td>
                        <td>{{instructor.email}}</td>
                        <td><a href="#" v-if="$root.role === 2" @click.prevent="deleteInstructor(instructor.id)"><i class="fa fa-trash text-danger"></i></a>
                        <a href="#" v-if="$root.role === 2" @click.prevent="prepareForUpdate(instructor)" class="ml-4" data-toggle="modal" data-target="#updateInstructorModal">
                            <i class="fa fa-edit text-info"></i></a> </td>

                    </tr>
                </tbody>
            </table>
            <pagination :items="instructors" @changePage="onChangePage" :key="paginationKey" class="float-right"></pagination>
        </div>
        <div class="text-center col-md-12" v-if="instructorLoading">
              <small style="color:#16462b">loading...</small>
        </div>
    </div>
    <!-- add instructor modal -->
<div class="modal fade" id="addInstructorModal" tabindex="-1" role="dialog" aria-labelledby="addInstructor" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addInstructor">New Instructor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="addModalCloseButton">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="addInstructor()">
      <div class="modal-body">
          <div class="form-group">
              <label>Instructor First Name</label>
            <input type="text" name="firstName" id="instructorFirstName" class="form-control" :class="{ 'is-invalid': newInstructorForm.errors.has('lastName') }"  placeholder="new instructor first name" v-model="newInstructorForm.firstName"
            />
            <has-error :form="newInstructorForm" field="firstName"></has-error>
          </div>
          <div class="form-group">
              <label>Instructor Last Name</label>
            <input type="text" name="lastName" id="instructorLastName" class="form-control" :class="{ 'is-invalid': newInstructorForm.errors.has('firstName') }"  placeholder="new instructor last name" v-model="newInstructorForm.lastName"
            />
            <has-error :form="newInstructorForm" field="lastName"></has-error>
          </div>
          <div class="form-group">
              <label>Instructor Email</label>
            <input type="text" name="email" id="instructorEmail" autocomplete="false" class="form-control" :class="{ 'is-invalid': newInstructorForm.errors.has('email') }"  placeholder="new instructor email" v-model="newInstructorForm.email"
            />
            <has-error :form="newInstructorForm" field="email"></has-error>
          </div>
          <div class="form-group">
              <label>Login Password <i @click="toClipboard" class="fa fa-clipboard" role="button"></i></label>
            <input type="text" autocomplete="false" id="instructorEmail" class="form-control"
            ref="autoPassword"
            :class="{ 'is-invalid': newInstructorForm.errors.has('password') }"  placeholder="instructor password" v-model="newInstructorForm.password"
             disabled />
            <has-error :form="newInstructorForm" field="password"></has-error>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close</button>
        <button type="submit" class="btn btn-apply"   :disabled="newInstructorForm.busy" ><span class="spinner-border spinner-border-sm spinner-submit" role="status" aria-hidden="true"></span> Add Instructor</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--  // add instructor modal -->

<!-- update instructor modal  -->
<div class="modal fade" id="updateInstructorModal" tabindex="-1" role="dialog" aria-labelledby="updateInstructor" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateInstructor">Update Instructor Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="updateModalCloseButton">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="updateInstructor">
      <div class="modal-body">
          <div class="form-group">
              <label>Instructor First Name</label>
            <input type="text" name="firstName" id="instructorFirstName" class="form-control" :class="{ 'is-invalid': updateInstructorForm.errors.has('lastName') }"  placeholder="new first name" v-model="updateInstructorForm.firstName"
            />
            <has-error :form="updateInstructorForm" field="firstName"></has-error>
          </div>
          <div class="form-group">
              <label>Instructor Last Name</label>
            <input type="text" name="lastName" id="instructorLastName" class="form-control" :class="{ 'is-invalid': updateInstructorForm.errors.has('firstName') }"  placeholder="new last name" v-model="updateInstructorForm.lastName"
            />
            <has-error :form="updateInstructorForm" field="lastName"></has-error>
          </div>
          <div class="form-group">
              <label>Instructor Email</label>
            <input type="text" name="email" id="instructorEmail" class="form-control" :class="{ 'is-invalid': updateInstructorForm.errors.has('email') }"  placeholder="new email" v-model="updateInstructorForm.email"
            />
            <has-error :form="updateInstructorForm" field="email"></has-error>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close</button>
        <button type="submit" class="btn btn-apply"  :disabled="updateInstructorForm.busy" ><span class="spinner-border spinner-border-sm spinner-submit" role="status" aria-hidden="true"></span> Update Instructor</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--// update instructor modal  -->

<!-- assign course -->
<div class="modal fade" id="assignCourseModal" tabindex="-1" role="dialog" aria-labelledby="assignCourse" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignCourse">{{clickedInstructor.first_name+"'s"}} assigned courses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="updateModalCloseButton">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit.prevent="updateInstructor">
      <div class="modal-body">
          <table class="table table-dark table-responsive text-center">
              <thead>
                  <tr><td>Course Title</td><td>Total Students</td><td>#</td></tr>
              </thead>
              <tbody v-if="clickedInstructor.courses.length > 0">
                  <tr v-for="course in clickedInstructor.courses" :key="course.id">
                      <td>{{course.title}}</td> <td></td>
                  </tr>
              </tbody>
              <tfoot v-else class="">
                  <tr><td colspan="3">No course assigned yet</td></tr>

              </tfoot>
          </table>




      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close</button>
        <button type="submit" class="btn btn-apply"  :disabled="updateInstructorForm.busy" ><span class="spinner-border spinner-border-sm spinner-submit" role="status" aria-hidden="true"></span> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--  -->

    </div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined} from '../mixins/enrollmentMixin';
export default {
    mixins: [enrollmentMixin],
    data(){
        return{
            instructors :[],
            instructorLoading : true,
            paginationKey : 0,
            pageItems: [],
            clickedInstructor : {
                courses : []
            },
            newInstructorForm : new Form({
                firstName : '',
                lastName : '',
                email : '',
                password : '',
            }),
            updateInstructorForm : new Form({
                id : '',
                firstName : '',
                lastName : '',
                email : '',
            })
        }
    },
    methods:{
        onChangePage(pageItems){
          this.pageItems = pageItems;
        },
        getInstructors(){
            axios.get('/api/v1/admin/instructors').then(
                (data)=>{
                    this.instructors = data.data.data;
                    this.instructorLoading = false;
                    this.paginationKey += 1;
                }
            );
        },
        addInstructor(){
            Fire.$emit('pageBusy');
            this.newInstructorForm.post('/api/v1/admin/instructors').then(
                data=>{
                    if(data.data.status == "success"){
                        this.showSuccess('Operation successful',
                            'new instructor added succeessfully');
                        Fire.$emit('instructorChanged');
                        document.getElementById('addModalCloseButton')
                                .click();
                        this.newInstructorForm.reset();
                    }else{
                        this.showError();
                    }
                }
            ).catch(
                data=>{
                    this.showError();
                }
            ).finally(
                data=>{
                    Fire.$emit('pageFree');
                }
            );
        },
        prepareForUpdate(instructor){
            this.updateInstructorForm.firstName = instructor.first_name;
            this.updateInstructorForm.lastName = instructor.last_name;
            this.updateInstructorForm.email = instructor.email;
            this.updateInstructorForm.id = instructor.id;
        },
        updateInstructor(){
            Fire.$emit('pageBusy');
            this.updateInstructorForm.patch('/api/v1/admin/instructor/'+this.updateInstructorForm.id).then(
                data=>{
                    if(data.data.status == "success"){
                        this.showSuccess('Operation successful',
                            'new instructor added succeessfully');
                        Fire.$emit('instructorChanged');
                        document.getElementById('updateModalCloseButton')
                            .click();
                    }else{
                        this.showError();
                    }
                }
            ).catch(
                data=>{
                    this.showError();
                }
            ).finally(
                data=>{
                    Fire.$emit('pageFree');
                }
            );
        },
        deleteInstructor(instructorId){
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete instructor',
            allowOutsideClick: false,
            }).then(result => {
                if (result.value) {
                    Fire.$emit('pageBusy');
                    axios.post('/api/v1/admin/instructor/'+instructorId).then(
                        data=>{
                            if(data.data.status = "success"){
                                this.showSuccess('Deleted Successfully');
                                Fire.$emit('instructorChanged');
                            }
                        }
                    ).catch(
                        data=>{
                            this.showError();
                        }
                    ).finally(
                        data=>{
                            Fire.$emit('pageFree');
                        }
                    );
                }
            });
        },
        prepareForCourseAssign(instructor){
            this.clickedInstructor = instructor;
        },
        alphaNumPassword(length){
          return Math.round((Math.pow(36, length + 1) - Math.random() * Math.pow(36, length))).toString(36).slice(1);
        }
    },
    created(){
        this.getInstructors();
        Fire.$on('instructorChanged',()=> this.getInstructors());
    }
}
</script>

<style>

</style>

