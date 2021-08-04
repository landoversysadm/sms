/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import VueRouter from 'vue-router';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faUserSecret, faHeart as fasHeart } from '@fortawesome/free-solid-svg-icons';
import { faHeart as farHeart } from '@fortawesome/free-regular-svg-icons';
import moment from 'vue-moment';
import VueCarousel from 'vue-carousel';
import Axios from 'axios';
import { Form, HasError, AlertError } from 'vform';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Swal from 'sweetalert2';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
import VueApexCharts from 'vue-apexcharts';
import * as jsPDF from 'jspdf';
import jwPagination from 'jw-vue-pagination';

require('./bootstrap');


window.Vue = require('vue');

$.fn.modal.Constructor.prototype._enforceFocus = function () {};


Vue.use(VueApexCharts);
Vue.component('apexchart', VueApexCharts);

Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);

window.NProgress = NProgress;
window.Form = Form;
window.jsPDF = jsPDF;

library.add(faUserSecret, farHeart, fasHeart);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('CourseGrid', require('./components/CourseGrid.vue').default);
Vue.component('coursemodal', require('./components/CourseModal.vue').default);
Vue.component('PaymentSlipModal', require('./components/PaymentSlipModal').default);
Vue.component('ProfileModal', require('./components/ProfileModal').default);
Vue.component('FacultyChart', require('./components/FacultyChart').default);
// Vue.component('Enroll', require('./components/Enroll.vue').default);
Vue.component('pagination', jwPagination);

Vue.use(VueRouter);
Vue.use(moment);
Vue.use(VueCarousel);


const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
});

window.Toast = Toast;
window.Swal = Swal;


//   Toast.fire({
//     type: 'success',
//     title: 'Signed in successfully'
//   })

const routes = [
  { path: '/student/dashboard', component: require('./components/student/Dashboard.vue').default },
  { path: '/student/notifications', component: require('./components/student/Notifications.vue').default },
  { path: '/student/courses', component: require('./components/student/Courses.vue').default },
  { path: '/student/assessment', component: require('./components/student/Assessment.vue').default },
  { path: '/student/profile/:operation', component: require('./components/student/Profile.vue').default },
  { path: '/student/profile', component: require('./components/student/Profile.vue').default },
  { path: '/student/payments', component: require('./components/student/Payments.vue').default },
  { path: '/student/', component: require('./components/student/Dashboard.vue').default },

  { path: '/admin/dashboard', component: require('./components/admin/Dashboard.vue').default },
  { path: '/admin/applications', component: require('./components/admin/Applications.vue').default },
  { path: '/admin/notifications', component: require('./components/admin/Notifications.vue').default },
  { path: '/admin/faculties', component: require('./components/admin/Faculties.vue').default },
  { path: '/admin/assessment', component: require('./components/admin/Assessment.vue').default },
  { path: '/admin/payments', component: require('./components/admin/Payments.vue').default },
  { path: '/admin/report', component: require('./components/admin/Report.vue').default },
  { path: '/admin/settings', component: require('./components/admin/Settings.vue').default },
  { path: '/admin/student', component: require('./components/admin/Student.vue').default },
  { path: '/admin/instructors', component: require('./components/admin/Instructors.vue').default },
  { path: '/admin/', component: require('./components/admin/Dashboard.vue').default },

  { path: '/instructor/', component: require('./components/instructor/Dashboard.vue').default },
  { path: '/instructor/dashboard', component: require('./components/instructor/Dashboard.vue').default },
  { path: '/instructor/students', component: require('./components/instructor/Students.vue').default },
  // { path: '/instructor/notifications', component: require('./components/instructor/Notification.vue').default },
  { path: '/instructor/assessment', component: require('./components/instructor/Assessment.vue').default },
  { path: '/instructor/settings', component: require('./components/instructor/Settings.vue').default },
  { path: '/instructor/courses', component: require('./components/instructor/Courses.vue').default },

  { path: '/', component: require('./components/Main.vue').default },

];

const router = new VueRouter({
  mode: 'history',
  routes,
});
window.Fire = new Vue();


router.beforeResolve((to, from, next) => {
  NProgress.start();
  next();
});
router.afterEach((to, from) => {
  NProgress.done();
});

window.def = {
  course: 'olay',
  showCourseModal: false,
  updateModalCourse(course) {
    this.course = course;
    Fire.$emit('modalCourseUpdated');
  },
};


new Vue({
  el: '#app',
  router,
  data: {
    user_token: '',
    user: {},
    role: {},
    showTopDropdown: false,
    showCourseModal: false,
    isBusy: false,
  },
  methods: {
    getUser() {
      Axios.get('/api/v1/user').then((response) => {
        this.user = response.data.data[0];
        this.role = this.user.role;
      });
    },
  },
  created() {
    Fire.$on('pageBusy', () => {
      this.isBusy = true;
    });
    Fire.$on('pageFree', () => {
      this.isBusy = false;
    });
    this.getUser();
  },
}).$mount('#app');
