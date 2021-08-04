<template>
  <div
    class="modal fade"
    id="studentProfileModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby=""
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content" v-if="true">
        <div class="modal-header">
          <h3>Student Profile</h3>
          <button
            class="btn btn-sm btn-primary float-right"
            style="margin-right: 20px"
            id="btnPrint"
            @click="printStudentData"
          >
            <i class="fa fa-print"></i> Print
          </button>

          <button
            type="button"
            class="close position-absolute"
            id="closeModal"
            data-dismiss="modal"
            aria-label="Close"
            style="right: 10px"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body" id="printSection" v-if="studentProfile">
          <section>
            <div class="passport d-flex justify-content-end">
              <img
                :src="clickedStudent.profile_picture_url | fullPath"
                alt=""
                style="width: 100px; height: 100px"
              />
            </div>
            <div class="row mt-4">
              <table class="table vertical">
                <tr>
                  <th colspan="2" scope="colgroup" class="row-head">
                    Personal Information
                  </th>
                </tr>
                <tr>
                  <th>First Name</th>
                  <td>{{ clickedStudent.user.first_name }}</td>
                </tr>
                <tr>
                  <th>Surname</th>
                  <td>{{ clickedStudent.user.last_name }}</td>
                </tr>
                <tr>
                  <th>Middle Name</th>
                  <td>{{ clickedStudent.user.midlle_name }}</td>
                </tr>

                <tr>
                  <th>Date of Birth</th>
                  <td>{{ clickedStudent.date_of_birth }}</td>
                </tr>
                <tr>
                  <th>Place of Birth</th>
                  <td>{{ clickedStudent.place_of_birth }}</td>
                </tr>
                <tr>
                  <th>Residential Address</th>
                  <td>{{ clickedStudent.residential_address }}</td>
                </tr>
                <tr>
                  <th>Mailing Address</th>
                  <td>{{ clickedStudent.user.email }}</td>
                </tr>
                <tr>
                  <th>Work Phone</th>
                  <td>{{ clickedStudent.work_phone }}</td>
                </tr>
                <tr>
                  <th>Mobile Phone</th>
                  <td>{{ clickedStudent.mobile_phone }}</td>
                </tr>
                <tr>
                  <th colspan="2" scope="colgroup" class="row-head">
                    Emergency Information
                  </th>
                </tr>
                <tr>
                  <th>Full Name</th>
                  <td>{{ clickedStudent.emergency_name }}</td>
                </tr>
                <tr>
                  <th>Mobile Phone</th>
                  <td>{{ clickedStudent.emergency_mobile_phone }}</td>
                </tr>
                <tr>
                  <th>Relationship</th>
                  <td>{{ clickedStudent.emergency_relationship }}</td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td>{{ clickedStudent.emergency_email }}</td>
                </tr>
                <tr>
                  <th colspan="2" scope="colgroup" class="row-head">
                    First Reference
                  </th>
                </tr>
                <tr>
                  <th>Full Name</th>
                  <td>{{ clickedStudent.ref_name1 }}</td>
                </tr>
                <tr>
                  <th>Mobile Phone</th>
                  <td>{{ clickedStudent.ref_number1 }}</td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td>{{ clickedStudent.ref_email1 }}</td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td>{{ clickedStudent.ref_address1 }}</td>
                </tr>
                <tr>
                  <th colspan="2" scope="colgroup" class="row-head">
                    Second Reference
                  </th>
                </tr>
                <tr>
                  <th>Full Name</th>
                  <td>{{ clickedStudent.ref_name2 }}</td>
                </tr>
                <tr>
                  <th>Mobile Phone</th>
                  <td>{{ clickedStudent.ref_number2 }}</td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td>{{ clickedStudent.ref_email2 }}</td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td>{{ clickedStudent.ref_address2 }}</td>
                </tr>
                <tr>
                  <th colspan="2" scope="colgroup" class="row-head">
                    Enrollments
                  </th>
                </tr>
                <tr>
                  <th colspan="2" class="w-100">
                    <table class="w-100 table">
                      <thead>
                        <tr>
                          <th>Course</th>
                          <th>Date Applied</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="enr in clickedStudent.enrollments"
                          :key="enr.id"
                        >
                          <td v-if="enr.course">{{ enr.course.title }}</td>
                          <td v-else><small>(course deleted)</small></td>
                          <td>{{ enr.created_at }}</td>
                          <td>{{ enr.status }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </th>
                </tr>
              </table>
            </div>

            <div class="row" v-show="showOperation" v-if="$root.role === 2">
              <div class="col-md-12">
                <button
                  class="btn btn-danger float-right"
                  @click="deactivate(clickedStudent.user.id)"
                  v-if="clickedStudent.user.active === 1"
                >
                  Deactivate Student
                </button>
                <button
                  class="btn btn-success float-right"
                  @click="activate(clickedStudent.user.id)"
                  v-else
                >
                  Activate Student
                </button>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
</template>

<style>
@media screen {
  #print {
    display: none;
  }
}
@media print {
  /* .content_wrapper  {
    visibility: collapse;
  } */
  #print,
  #print * {
    visibility: visible;
  }
  #print {
    color: #ddd !important;
    position: absolute;
    left: 0;
    top: 0;
  }
  .table {
    color: #ddd !important;
    font-size: 14px !important;
  }
}
</style>

<script>
import { enrollmentMixin } from "../components/mixins/enrollmentMixin";
export default {
  props: {
    studentProfile: Boolean,
    clickedStudent: Object,
    showOperation: {
      default: true,
      type: Boolean,
    },
  },
  mixins: [enrollmentMixin],
  methods: {
    printStudentData() {
      const modal = document.getElementById("printSection");
      const cloned = modal.cloneNode(true);
      let section = document.getElementById("print");
      //   $("#content_wrapper").css({ visibility: "collapse" });
      $("head").append(
        '<style type="text/css" media="print">#content_wrapper{ visibility:collapse; }</style>'
      );
      if (!section) {
        section = document.createElement("div");
        section.id = "print";
        document.body.appendChild(section);
      }
      section.innerHTML = "";
      section.appendChild(cloned);
      window.print();
    },
    deactivate(userId) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, deactivate student account",
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          Fire.$emit("pageBusy");
          axios
            .post("/api/v1/admin/deactivate", { userId: userId })
            .then((data) => {
              if (data.data.status) {
                this.showSuccess(
                  "Deactivation successful",
                  "Student account deactivated"
                );
                document.getElementById("closeModal").click();
                Fire.$emit("userModified");
              }
            })
            .catch((data) => {
              this.showError();
            })
            .finally((data) => {
              Fire.$emit("pageFree");
            });
        }
      });
    },
    activate(userId) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, activate student account",
        allowOutsideClick: false,
      }).then((result) => {
        if (result.value) {
          Fire.$emit("pageBusy");
          axios
            .post("/api/v1/admin/activate", { userId: userId })
            .then((data) => {
              if (data.data.status) {
                this.showSuccess(
                  "Activation successful",
                  "Student account successfully activated"
                );
                document.getElementById("closeModal").click();
                Fire.$emit("userModified");
              }
            })
            .catch((data) => {
              this.showError();
            })
            .finally((data) => {
              Fire.$emit("pageFree");
            });
        }
      });
    },
  },
};
</script>