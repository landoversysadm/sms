<template>
    <div class="modal fade payment-slip" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="paymentModal-v">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0">
      <div class="modal-header">
          <h3>Payment slip</h3>
        <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close" style="right:10px;" id="closePaymentSlipModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <section>
             <h5 class="section-header">Description</h5>
             <table class="table table-stripped table-bordered">
                 <tr>
                     <td>Depositor Name</td>
                     <td>{{this.$parent.clickedPayment.payee_name}}</td>
                 </tr>
                 <tr>
                     <td>Amount Paid</td>
                     <td>{{this.$parent.clickedPayment.amount_paid}}</td>
                 </tr>
                 <tr>
                     <td>Date of Payment</td>
                     <td>{{this.$parent.clickedPayment.date_of_payment}}</td>
                 </tr>
                 <tr>
                     <td>Uploaded Slip</td>
                     <td><a :href="this.$parent.clickedPayment.slip_upload | fullPath" target="_blank">View</a></td>
                 </tr>
             </table>
        </section>



      </div>
      <div class="modal-footer mt-2">
          <button class="btn btn-primary" @click="approvePay()"><i class="fa fa-check"></i> Approve Pay</button>
           <button class="btn btn-danger" @click="rejectPay()"><i class="fa fa-remove"></i> Reject Pay</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { enrollmentMixin, isNullOrUndefined} from '../components/mixins/enrollmentMixin';
export default {
    mixins: [enrollmentMixin],
    data(){
        return{

        }
    },

    methods: {
         approvePay(){
            let payment = this.$parent.clickedPayment;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Accept payment!',
                allowOutsideClick: false,
            }).then((result)=>{
                if (result.value) {
                    NProgress.start();
                    Fire.$emit('pageBusy');
                    axios.patch('/api/v1/admin/payments/'+payment.id,{
                    'validated':2,
                    }).then((data)=>{
                        if(data.data.status == "success"){
                            document.getElementById('closePaymentSlipModal').click();
                            Fire.$emit('paymentStatusChanged');
                            Swal.fire(
                            'Approved!',
                            'Student payment has been approved.',
                            'success'
                            )
                            NProgress.done();
                            Fire.$emit('pageFree');
                        }else{
                            this.showError();
                            NProgress.done();
                            Fire.$emit('pageFree');
                        }
                    }).catch((error)=>{
                        this.showError();
                        NProgress.done();
                        Fire.$emit('pageFree');
                    });

                }
            })
        },
        rejectPay(){
            let payment = this.$parent.clickedPayment;
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            input: 'text',
            inputPlaceholder: 'Give reason for payment rejection',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject payment!',
            allowOutsideClick: false,
             inputValidator: (value) => {
                if (value.length<3) {
                return 'You need to provide a reason!'
                }
            }
            }).then((result) => {
            if (result.value) {
                NProgress.start();
                Fire.$emit('pageBusy');
                axios.patch('/api/v1/admin/payments/'+payment.id,{
                    'validated':1,
                    'adminNote':result.value
                }).then((data)=>{
                    if(data.data.status == "success"){
                        document.getElementById('closePaymentSlipModal').click();
                        Swal.fire(
                        'Successfull!',
                        'The payment has been invalidated.',
                        'success'
                        )
                        NProgress.done();
                        Fire.$emit('pageFree');
                    }else{
                        NProgress.done();
                        Fire.$emit('pageFree');
                    }
                }).catch((error)=>{
                    this.showError();
                    NProgress.done();
                    Fire.$emit('pageFree');
                });
            }
            });
        }
    }
}
</script>

<style>

</style>
