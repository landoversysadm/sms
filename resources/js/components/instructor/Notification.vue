<template>
  <div class="container-fluid">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <router-link to="/instructor/"><i class="fa fa-fw fa-home"></i></router-link>
        </li>
        <li class="breadcrumb-item active">Notifications </li>
    </ol>

    <div class="row">
            <div class="col-md-3">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sent Messages</h3>
                    </div>
                    <div  v-if="notifications.length>0" >
                       <div class="box-body">
                        <ul class="nav nav-pills nav-stacked d-block sent-list">
                            <li v-for="notification in pageItems" :key="notification.id" class="notification-preview">
                                <a href="" @click.prevent="showSingleNotification(notification)">
                                    <span class="notification-subject">{{notification.subject}}</span>
                                    <span class="notification-body">{{notification.body | truncateText(30)}}</span>
                                </a>
                                <div class="ops">
                                  <i class="fa fa-trash text-danger" @click="deleteCustomNotification(notification.id)"></i>
                                </div>
                            </li>
                        </ul>
                      </div>
                      <div class="box-footer pb-0 pt-3">
                        <pagination :items="notifications" @changePage="onChangePage" :key="paginationKey"></pagination>
                      </div>
                    </div>
                    <div v-else class="text-center p-4">
                      <span v-if="!loading">No Sent Messages</span>
                      <span v-if="loading">loading...</span>
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
                        <CourseFilter :courses="assignedCourses" :button-text="`fetch students`" :search="false" @filter="(filter)=>{newFilter(filter)}"></CourseFilter>
                    </div>
                    <form action="" @submit.prevent="sendCustomNotification">
                    <div class="card-body">
                        <div class="form-group">
                            <span class="">Send to all student <input type="checkbox" name="allStudent" value="all" id="allStudentCheck" @change="checkAllSelected"></span>
                           <span class="spinner-grow spinner-grow-sm float-right" :class="{'d-none': !filtering}" role="status" aria-hidden="true"></span>
                            <span class="float-right" v-show="!filtering">{{newNotification.recipients.length}}/{{options.length}} students</span>
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
import {instructorMixin} from '../mixins/instructorMixin';
import {enrollmentMixin, isNullOrUndefined} from '../mixins/enrollmentMixin';
export default {
  mixins : [instructorMixin, enrollmentMixin],
  components: {
    CourseFilter,
  },
  data() {
    return {
      filter: '',
      options: [],
      students:[],
      pageItems: [],
      paginationKey :0,
      notifications : {},
      showSingle : false,
      currentSingleNotification : {},
      newNotification : new Form({
        recipients : [],
        subject : '',
        body : '',
        toMail : false,
      }),
      deleteNotification : new Form({
        id : []
      }),
      loading : true
    }
  },
  watch : {
    classRoom : function(newVal, oldVal){
        this.students = [];
        newVal.forEach((e)=>{this.students.push(e.student)});
        this.populateStudentOptions();
    }
  },
  methods : {
    showSingleNotification(notification){
        this.currentSingleNotification = notification;
        this.showSingle = true;
    },
    onChangePage(pageItems){
        this.pageItems = pageItems;
      },
    checkAllSelected(event){
      if(event.target.checked){
        this.newNotification.recipients = [];
        for (let item of this.options){ this.newNotification.recipients.push(item); }
      }else
        this.newNotification.recipients = [];
    },
    populateStudentOptions(){
      this.options = [];
      for(let student of this.students){
        this.options.push({name:student.user.first_name +' '+student.user.last_name, id:student.user.id})
      }
    },
    sendCustomNotification(){
      if(this.newNotification.recipients.length >0 ){
        Fire.$emit('pageBusy');
        this.newNotification.post('/api/v1/instructor/customNotifications').then((data)=>{
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
    },
    getAllcustomNotifications(){
      axios.get('/api/v1/instructor/customNotifications').then((data)=>{
        this.loading = false;
        this.notifications = data.data.data;
        this.paginationKey += 1;
      });
    },
  },
  mounted(){
    this.getAllcustomNotifications();
    Fire.$on('notificationStatusChanged',()=>this.getAllcustomNotifications());
  }
}
</script>
<style scoped>

</style>
