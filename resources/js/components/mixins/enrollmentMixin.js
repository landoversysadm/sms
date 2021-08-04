import { isNullOrUndefined } from 'util';

const enrollmentMixin = {
  data() {
    return {
      formatter: new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN',
        currencyDisplay: 'symbol',
      }),
    };
  },
  methods: {
    toClipboard() {
      const copyText = this.$refs.autoPassword;
      copyText.disabled = false;
      copyText.select();
      copyText.setSelectionRange(0, 99999); /* For mobile devices */
      document.execCommand('copy');
      copyText.disabled = true;
      alert('password copied to clipboard');
    },
    showMore(enrollment) {
      this.clickedEnrollment = enrollment;
      this.enrollmentShow = true;
      try {
        this.clickedEnrollment.requirement_uploads = JSON.parse(enrollment.requirement_uploads);
        this.clickedEnrollment.course.requirement = JSON.parse(enrollment.course.requirement);
        if (isNullOrUndefined(this.clickedEnrollment.course.requirement)) {
          this.clickedEnrollment.course.requirement = {};
        }
        if (isNullOrUndefined(this.clickedEnrollment.requirement_uploads)) {
          this.clickedEnrollment.requirement_uploads = {};
        }
      } catch (error) {
        console.error(error);
      }
    },
    showReUpload(payment) {
      this.clickedEnrollment = payment.enrollment;
      this.clickedPayment = payment;
      this.reuploadShow = true;
      this.enrollmentShow = true;
      try {
        this.clickedEnrollment.requirement_uploads = JSON.parse(
          payment.enrollment.requirement_uploads,
        );
        this.clickedEnrollment.course.requirement = JSON.parse(
          payment.enrollment.course.requirement,
        );
        if (isNullOrUndefined(this.clickedEnrollment.course.requirement)) {
          this.clickedEnrollment.course.requirement = {};
        }
        if (isNullOrUndefined(this.clickedEnrollment.requirement_uploads)) {
          this.clickedEnrollment.requirement_uploads = {};
        }
      } catch (error) {
        console.error(error);
      }
    },
    showError() {
      Swal.fire({
        title: 'Error!',
        text: 'Error occured while performing operation',
        type: 'error',
        confirmButtonText: 'Continue',
      });
    },
    showErrorWithMessage(message) {
      Swal.fire({
        title: 'Error!',
        text: message,
        type: 'error',
        confirmButtonText: 'Continue',
      });
    },
    showSuccess(title, text) {
      Swal.fire(title, text, 'success');
    },
    toastSuccess(title) {
      Toast.fire({
        type: 'success',
        title,
      });
    },
    toastError(title) {
      Toast.fire({
        type: 'error',
        title,
      });
    },
  },
  filters: {
    // fullPath: (value) => {
    //   if (!value) return '';
    //   value = value.replace(/public/, '');
    //   return (`https://s3-penciledge-dev.s3.eu-north-1.amazonaws.com/public${value}`);
    // },
    fullPath: (value) => {
      if (!value) return '';
      value = value.replace(/public/, '');
      return `/storage${value}`;
    },
    truncateText: (text, maxLength) => (text.length > maxLength ? `${text.substr(0, maxLength)}  ...` : text),
    parseJson: string => JSON.parse(string),
    formArrayInput: (index, arrayName) => `${arrayName} [ ${index}  ]`,
  },
};

export { enrollmentMixin, isNullOrUndefined };
