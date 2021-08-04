<template>
    <div class="container-fluid">
        <ol class="breadcrumb">
              <li class="breadcrumb-item">
               <a href="#"><i class="fa fa-fw fa-home"></i></a>
              </li>
             <li class="breadcrumb-item active">Notifications</li>
        </ol>

        <div class="row">
            <div class="col-md-3">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sent Messages</h3>
                    </div>
                    <div  v-if="customNotifications.length>0" >
                       <div class="box-body">
                        <ul class="nav nav-pills nav-stacked d-block sent-list">
                            <li v-for="notification in pageItems" :key="notification.id" class="notification-preview">
                                <a href="" @click.prevent="showSingleNotification(notification)">
                                    <span class="notification-subject">{{notification.subject}}</span>
                                    <span class="notification-body">{{notification.body | truncateText(30)}}</span>
                                </a>
                                <div class="ops">
                                  <i class="fa fa-trash text-danger" v-if="$root.role === 2" @click="deleteCustomNotification(notification.id)"></i>
                                </div>
                            </li>
                        </ul>
                      </div>
                      <div class="box-footer pb-0 pt-3">
                        <pagination :items="customNotifications" @changePage="onChangePage" :key="paginationKey"></pagination>
                      </div>
                    </div>
                    <div v-else class="text-center p-4">
                      <span v-if="!loadingNotifications">No Sent Messages</span>
                      <span v-if="loadingNotifications">loading...</span>
                    </div>

                </div>
            </div>

             <div class="col-md-9" v-if="showSingle">
                 <div class="card box-solid course-card single-notification">
                     <div class="card-body">
                         <div class="date">
                             <span><i class="icon_clock_alt"></i> {{currentSingleNotification.created_at |moment('MM/DD/YY HH:MM')}}</span>
                         </div>
                         <h6>Recipients</h6>
                         <ul class="recipients">
                             <li v-for="recipient in JSON.parse(currentSingleNotification.recipients)" :key="recipient" class="mr-2">
                                {{recipient}}
                             </li>
                        </ul>
                         <h6 class="mt-4">Subject</h6>
                         {{currentSingleNotification.subject}}

                         <h6 class="mt-4">Body</h6>
                         {{currentSingleNotification.body}}
                     </div>
                 </div>

             </div>

            <div class="col-md-9" v-else>
                <div class="card box-solid course-card">
                    <div class="card-header text-center">
                        <h4 class="box-title">Compose New Message</h4>
                        <div class="row">

                        </div>
                        <div class="row">
                             <div class="col-md-3 col-sm-6 form-group font-small">
                                <label for="filter_faculty">Faculty</label>
                                <select name="course" id="filter_faculty" class="form-control select-with-border" v-model="filter.faculty"  >
                                    <option v-for="faculty in faculties" :key="faculty.id" :value="faculty" >{{faculty.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 form-group font-small">
                                <label for="filter_faculty">Course</label>
                                <select name="course" id="filter_course" class="form-control select-with-border" v-model="filter.course" >
                                    <option v-for="course in filter.faculty.courses" :key="course.id" :value="course">{{course.title}}</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 form-group font-small">
                                <label for="filter_faculty">Session</label>
                                <select name="course" id="filter_course" class="form-control select-with-border" v-model="filter.session" >
                                    <option v-for="session in filter.course.sessions" :key="session.id" :value="session">
                                        {{session.start_date | moment("DD - MMMM - YYYY")}} / {{session.end_date | moment("DD- MMMM - YYYY")}}</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 form-group font-small">
                            <label for="paymentStatus">Payment Status</label>
                              <select name="paymentStatus" id="paymentStatus"  class="form-control select-with-border" v-model="filter.payment">
                                <option value="all" selected>All</option>
                                <option value="1" aria-selected="true">Paid</option>
                                <option value="0">Unpaid</option>
                              </select>
                          </div>
                        </div>

                    </div>
                    <form action="" @submit.prevent="sendCustomNotification">
                    <div class="card-body">
                        <div class="form-group">
                            <span class="">Select all student <input type="checkbox" name="allStudent" value="all" id="allStudentCheck" @change="checkAllSelected"></span>
                        </div>
                            <div class="form-group">
                                 <multiselect v-model="newNotification.recipients" :options="options" :multiple="true" :clear-on-select="true"
                                placeholder="Select Student" label="name" track-by="name" :preselect-first="false" required
                                :class="{ 'is-invalid': newNotification.errors.has('recipients') }" >
                                </multiselect>
                                <has-error :form="newNotification" field="recipients"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="newNotification.subject" type="text" name="text" id="" class="form-control" placeholder="Enter message subject" required
                                :class="{ 'is-invalid': newNotification.errors.has('subject') }"  >
                                <has-error :form="newNotification" field="subject"></has-error>
                            </div>

                            <div class="form-group">
                                <textarea v-model="newNotification.body" id="compose-textarea" class="form-control" style="height: 300px;"
                                placeholder="Enter message body here ...." required :class="{ 'is-invalid': newNotification.errors.has('body') }" >
                                </textarea>
                                <has-error :form="newNotification" field="body"></has-error>
                            </div>
                        <div class="form-group custom-list d-inline">
                          <input type="checkbox" name="mail" id="mail" v-model="newNotification.toMail">
                          <label for="mail">Send to Mail</label>
                        </div>

                        <button type="submit" class="btn btn-apply btn-lg pull-right">Send</button>

                    </div>
                    </form>
                </div>
            </div>

            <a href="#" class="float new-notification" v-show="showSingle" @click.prevent="showSingle = false">
            <i class="fa fa-plus my-float"></i>
            </a>
        </div>

    </div>
</template>

<script>
import CourseFilter from '../CourseFilter';
import Multiselect from 'vue-multiselect';
import {enrollmentMixin, isNullOrUndefined} from '../mixins/enrollmentMixin';

Vue.component('multiselect', Multiselect)
const tempList = [...Array(50)];
export default {
    mixins :[enrollmentMixin],
    components: {
      CourseFilter,
    },
    data(){
        return {
            tempList,
            options: [
            ],
            pageItems :[],
            faculties : [{id:-1,name:"Fetching faculties  ...."}],
            filter : {
                faculty :{
                    courses : [{id:-1,title:"Select faculty first"}]
                },
                course : '',
                session : '',
                payment: 'all'
            },
            showSingle : false,
            paginationKey : 0,
            currentSingleNotification : '',
            students : [],
            currentStudents: [],
            alumniStudent: '',
            activeStudent: '',
            customNotifications : '',
            newNotification : new Form({
                recipients : [],
                subject : '',
                body : '',
                toMail : false,
            }),
            deleteNotification : new Form({
              id : []
            }),
            loadingNotifications : true
        }
    },
    watch : {
      'filter.session'(val){
        this.filterBySession(val.id);
      },
      'filter.faculty'(val){
        this.filterByFaculty(val.id);
      },
      'filter.course'(val){
        this.filterByCourse(val.id);
      },
      'filter.payment'(val){
        this.filterByPayment(val);
      }
    },
    methods : {
      filterByPayment(status){
        if(status === "all")
          return this.populateStudentOptions(this.currentStudents);
        Fire.$emit('pageBusy');
        let filtered = this.currentStudents.filter(student =>
          student.enrollments.filter((enrollment)=> enrollment.payment_status == status).length > 0
        );
        this.populateStudentOptions(filtered);
        Fire.$emit('pageFree');
      },
      filterBySession(id){
        Fire.$emit('pageBusy');
        axios.get(`/api/v1/admin/students/${this.filter.course.id}/${id}`).then(
            (data)=>{
                let students = data.data.data.map((e)=>e.student)
                this.populateStudentOptions(students);
                this.filter.payment = "paid";
            }).finally((data)=>{
                Fire.$emit('pageFree');
            });
      },
      filterByFaculty(id){
        Fire.$emit('pageBusy');
        let filtered = this.students.filter(student =>
          student.enrollments.filter((enrollment)=> enrollment.course.faculty_id == id ).length > 0
        );
        this.currentStudents = filtered;
        this.populateStudentOptions(filtered);
        this.filter.payment = "all";
        Fire.$emit('pageFree');
      },
      filterByCourse(id){
        Fire.$emit('pageBusy');
        let filtered = this.students.filter(student =>
          student.enrollments.filter((enrollment)=> enrollment.course_id == id ).length > 0
        );
        this.currentStudents = filtered;
        this.populateStudentOptions(filtered);
        this.filter.payment = "all";
        Fire.$emit('pageFree');
      },
      onChangePage(pageItems){
        this.pageItems = pageItems;
      },
        showSingleNotification(notification){
            this.currentSingleNotification = notification;
            this.showSingle = true;
        },
        getAllcustomNotifications(){
            axios.get('/api/v1/admin/customNotifications').then((data)=>{
                this.loadingNotifications = false;
                this.customNotifications = data.data.data;
                this.paginationKey += 1;
            });
        },
        fetchAllFaculties(){
            axios.get('/api/v1/faculties').then((data)=>{
                this.faculties = data.data.data;
            });
        },
        newFilter(filter){
          console.log(filter);
        },
        getAllStudents(){
            axios.get('/api/v1/admin/students').then((data)=>{
                this.students = data.data.data
                this.activeStudent = this.students.filter((student)=>{
                  let n = student.enrollments.filter((e)=>{return e.status === 'approved'});
                  return n.length > 0;
                });
                this.alumniStudent = this.students.filter((student)=>{
                  let n = student.enrollments.filter((e)=>{return e.status === 'completed'});
                  return n.length > 0;
                });
                this.currentStudents = this.activeStudent;
                this.populateStudentOptions(this.activeStudent);
            });
        },
        populateStudentOptions(students){
          this.options = [];
            for(let student of students){
                this.options.push({name:student.user.first_name +' '+student.user.last_name, id:student.user.id})
            }
        },
        checkAllSelected(event){
            if(event.target.checked){
                this.newNotification.recipients = [];
                for (let item of this.options){ this.newNotification.recipients.push(item); }
            }else
                this.newNotification.recipients = [];
        },
        sendCustomNotification(){
            if(this.newNotification.recipients.length >0 ){
                Fire.$emit('pageBusy');
                this.newNotification.post('/api/v1/admin/customNotifications').then((data)=>{
                    if(data.data.status){
                        this.showSuccess('success', 'Notificaion sent');
                        this.clearNotificationForm();
                        Fire.$emit('notificationStatusChanged');
                    }
                }).catch((data)=>{
                    this.showError();
                }).finally(()=>{
                    Fire.$emit('pageFree');
                });
            }

        },
        deleteCustomNotification(notificationId){
          this.deleteNotification.id = notificationId;
          Fire.$emit('pageBusy');
          this.deleteNotification.delete('/api/v1/admin/customNotifications').then((data)=>{
            if(data.data.status){
              this.showSingle = false;
              Fire.$emit('notificationStatusChanged');
              Toast.fire({
                  type: 'success',
                  title: 'Notification deleted '
                });
            }
          }).catch((data)=>{}).finally((data)=>{
            Fire.$emit('pageFree');
          });
        },
        clearNotificationForm(){
            this.newNotification.recipients = [];
            this.newNotification.subject = '';
            this.newNotification.body = '';
        }
    },
    mounted() {
        Fire.$on('notificationStatusChanged',()=>{this.getAllcustomNotifications(); });
        this.fetchAllFaculties();
        this.getAllStudents();
        this.getAllcustomNotifications();
    }

}
</script>

<style>
li>div.ops{
  position: absolute;
  top: 20px;
  right: 5px;
  display: none;
}
ul.sent-list>li:hover{
  background-color: #eeeeee;
  padding: 5px;
  border-radius: 4px;
}
ul.sent-list>li:hover >div.ops{
  display: inline;
}

ul.recipients{
  max-height: 200px;
  overflow-y: auto;
}
.notification-body{
  overflow: hidden;
}

</style>
