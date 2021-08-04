<template>
   <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#"><i class="fa fa-fw fa-home"></i></a>
            </li>
            <li class="breadcrumb-item active">Settings </li>
        </ol>

        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class=" "><a href="#tab_3" data-toggle="tab" aria-expanded="false" class="active">Update Account</a></li>
                        <li class=" "><a href="#tab_4" data-toggle="tab" aria-expanded="false" class="">Change Password</a></li>
                    </ul>
                    <div class="tab-content pl-3">
                        <div class="tab-pane active" id="tab_3">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box_general padding_bottom">
                                    <form action="" @submit.prevent="updatePersonalInfo()">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" type="text" name="firstName" v-model="updateForm.firstName"
                                            required :class="{ 'is-invalid': updateForm.errors.has('firstName') }">
                                            <has-error :form="updateForm" field="firstName"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" type="text" name="lastName"  v-model="updateForm.lastName"
                                            required :class="{ 'is-invalid': updateForm.errors.has('lastName') }">
                                            <has-error :form="updateForm" field="lastName"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input class="form-control" type="text" name="middleName"  v-model="updateForm.middleName"
                                             :class="{ 'is-invalid': updateForm.errors.has('middleName') }">
                                            <has-error :form="updateForm" field="middleName"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="text" name="email"  v-model="updateForm.email"
                                            required :class="{ 'is-invalid': updateForm.errors.has('email') }">
                                            <has-error :form="updateForm" field="email"></has-error>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-apply w-100">
                                            Update
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_4">
                            <div class="box_general padding_bottom">
                                <form action="" @submit.prevent="updatePassword()">
                                    <div class="header_box version_2">
                                        <h2><i class="fa fa-lock"></i>Change password</h2>
                                    </div>
                                    <div class="form-group">
                                        <label>Old password</label>
                                        <input class="form-control" type="password" name="oldPassword" id="oldPassword" v-model="passwordForm.password"
                                        required :class="{ 'is-invalid': passwordForm.errors.has('password') }">
                                        <has-error :form="passwordForm" field="password"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>New password</label>
                                        <input class="form-control" type="password" name="newPassword" v-model="passwordForm.newPassword" id="newPassword"
                                        required :class="{ 'is-invalid': passwordForm.errors.has('newPassword') }">
                                        <has-error :form="passwordForm" field="newPassword"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm new password</label>
                                        <input class="form-control" type="password" name="cNewPassword" id="cNewPassword" required v-model="passwordForm.confirmPassword"
                                        :class="{ 'is-invalid': passwordForm.errors.has('confirmPassword') }">
                                        <has-error :form="passwordForm" field="confirmPassword"></has-error>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-apply" type="submit">Update Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

   </div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined} from '../mixins/enrollmentMixin';
export default {
    mixins : [enrollmentMixin],
    data() {
        return {
            admins : [],
            myEmail : '',
            bankForm : new Form({
                accountName : '',
                accountNumber : '',
                bankName : '',
                id : '',
            }),

            instructorForm : new Form ({
                firstName : '',
                lastName : '',
                middleName : '',
                email : '',
                password : '',
            }),
            passwordForm : new Form({
                section : 'password',
                password : '',
                newPassword : '',
                confirmPassword : ''
            }),
            updateForm : new Form({
                section : 'personalInfo',
                firstName : '',
                lastName : '',
                middleName : '',
                email : '',
            })
        }
    },
    methods: {
        resetForm(){
            this.instructorForm.firstName = '';
            this.instructorForm.lastName = '';
            this.instructorForm.middleName = '';
            this.instructorForm.email = '';
            this.instructorForm.password = '';
        },
        updatePassword(){
            this.updateInfo('passwordForm');
        },
        updatePersonalInfo(){
            this.updateInfo('updateForm')
        },
        updateInfo($info) {
            Fire.$emit('pageBusy');
            let $url ='';
            if($info === 'passwordForm'){
              $url = '/api/v1/instructor/updatePassword';
            }else{
              $url = '/api/v1/instructor/updateInfo';
            }
           this[$info].patch($url)
            .then(function(data){
                if(data.data.status){
                    Toast.fire({
                        type: 'success',
                        title: 'Profile update successfully'
                    });
                }else{
                    Swal.fire({
                    title: 'Error!',
                    text: 'Error occured while performing operation',
                    type: 'error',
                    confirmButtonText: 'Continue'
                });
                }
            }).catch(function(data){
                Swal.fire({
                    title: 'Error!',
                    text: 'Error occured while performing operation',
                    type: 'error',
                    confirmButtonText: 'Continue'
                });
            }).finally((data)=>{
                Fire.$emit('pageFree');
            });
        },
        getProfile(){
            axios.get('/api/v1/instructor/profile').then((data)=>{
                if(data.data.status){
                    this.populateUpdateForm(data.data.data)
                }
            }).catch((data)=>{
                console.log(data);
            });
        },
        populateUpdateForm(profile){
            this.updateForm.firstName = profile.first_name;
            this.updateForm.lastName = profile.last_name;
            this.updateForm.middleName = profile.user.midlle_name;
            this.updateForm.email = profile.email;
            this.myEmail = profile.email;
        }
    },
    created() {
        this.getProfile();
    },
    mounted() {
    }

}
</script>

<style>

</style>


