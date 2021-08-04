<template>

    <div class="container-fluid">
                    <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                            <a href="#"><i class="fa fa-fw fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Notifications</li>
                    </ol>

    <div class="box_general notifications">
			<h4>Notifications</h4>
			<div class="list_general" v-if="notifications.length > 0">
				<ul >
					<li v-for="notification in notifications" :key="notification.id">
						<span>{{notification.created_at | moment('from')}}</span>
						<h4>{{notification.data.title}}
                            <i v-if="notification.read_at == null" class="unread">Unread</i> <i v-else="" class="read">Read</i>
                            <i v-if="notification.read_at == null" class="read markRead clickable"><a @click="markAsRead(notification)"><i class="fa fa-fw fa-check-circle-o"></i>mark as read</a></i>
                        </h4>
						<p>{{notification.data.body}} </p>
                        <p v-if="notification.data.other">
                            <strong>Note from Admin: </strong> {{notification.data.other}}
                        </p>
					</li>
				</ul>
			</div>
            <div class=" row page-default mt-4 py-4" v-else>
                <div class="col-md-12 text-center">
                    <h1 class="text-center" v-if="!notificationLoading"> No new notifications</h1>
                    <small v-else> loading...</small>
                </div>
            </div>
		</div>

    </div>
</template>

<script>
export default {
    data(){
        return{
            notifications: {},
            notificationLoading : true,
        }
    },
    methods: {
        fetchNotifications(){
            NProgress.start();
            axios.get('/api/v1/user/student/notification').then((data)=>{
                if(data.data.status){
                    this.notifications = data.data.data;
                    this.notificationLoading = false;
                    NProgress.done();
                }
            }).catch((data)=>{

                NProgress.done();
            });
        },
        markAsRead(notification){
            NProgress.start();
            Fire.$emit('pageBusy');
            axios.patch('/api/v1/user/student/notification',{notificationId: notification.id}).then((data)=>{
                if(data.data.status){
                    Fire.$emit('notificationStatusChanged');
                    NProgress.done();
                    Fire.$emit('pageFree');
                }else{
                    NProgress.done();
                    Fire.$emit('pageFree');
                }
            }).catch((data)=>{
                NProgress.done();
                Fire.$emit('pageFree');
            });
        }
    },
    created(){
        this.fetchNotifications();
        Fire.$on('notificationStatusChanged', ()=>{
             this.fetchNotifications();
        });
    },
    mounted(){

    }
}
</script>

<style>

</style>
