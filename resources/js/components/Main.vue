<template>

    <div>
       <main>
		<section id="hero_in" class="courses">
			<div class="wrapper">
				<div class="container">
					<h1 class="fadeInUp"><span></span>Landover Aviation Business School</h1>
				</div>
			</div>
		</section>
		<!--/hero_in-->
    <!--  -->
		 <div class="filters_listing sticky_horizontal">
			<div class="container">
				<ul class="clearfix">
					<!-- <li>
						<span><i class="fa fa-filter"></i></span>
					</li> -->
					<li>
						<select name="orderby" class="selectbox" id="facultySelect" >

                            <option value="">All</option>

                            <option  v-for="faculty in faculties" :key="faculty.id" :value="faculty.id">{{faculty.name}}</option>
							</select>
					</li>
          <li>

          </li>
				</ul>
			</div>
			<!-- /container -->
		 </div>
		<!-- /filters -->

		<div class="container margin_60_35">


                <CourseGrid></CourseGrid>




		</div>
		<!-- /container -->

		<!-- /bg_color_1 -->
<!-- <div class="features clearfix background-white">
			<div class="container">
        <div class="row text-dark">
          <div class="col-md-3">
            <img src="/img/trophy.gif" alt="LANDOVER AWARDS">
          </div>
          <div class="col-md-9 d-flex flex-column justify-content-center font-weight-bold trophy-section">
            <p class=""><i class="fa fa-quote-left" aria-hidden="true"></i>
            We made IATA TOP 10 Aviation Training Institutions in Africa 3 times in a row in 2011, 2012, and 2013,
Also LABS produced IATA Best performer in Nigeria in 2012, 2013, 2014, 2016, 2017, 2018 and most recently in 2019.
              <i class="fa fa-quote-right" aria-hidden="true"></i>
          </p>
          </div>
        </div>
			</div>
		</div> -->
    <div class="features clearfix background-white">
			<div class="container">
				<ul>
					<li>
            <img src="/img/iata-approval.png" alt="">
					</li>
					<li>
						<img src="/img/icao-approval.png" alt="">
					</li>
					<li>
						<img src="/img/NCAA-approval.png" alt="">
					</li>
				</ul>
			</div>
		</div>
	    </main>


   <footer>
		<div class="container margin_120_95">
			<div class="row">
				<div class="col-lg-5 col-md-12 p-r-5">
					<p><img src="img/logo.png" width="149" height="42" data-retina="true" alt=""></p>
					<p>
            LABS is a Nigerian Civil Aviation Authority (NCAA) Approved Training Organization (ATO) and an
            International Air Transport Association (IATA) Authorized
            Training Centre (ATC) that offer wide range of aviation related programmes for individuals aspiring to start  and develop a career in the aviation
             industry.  We are equipped
            with State-of-the-Art facilities that enhance learning and guarantee trainees an outstanding value for the resources invested with us.
             <a href="#" @click.prevent="readMoreAbout = !readMoreAbout" class="readMore"><span v-if="!readMoreAbout">read more</span> <span v-else>hide more</span></a>
					</p>
          <p v-show="readMoreAbout">
            In addition, we provide high quality, customized and result oriented training and support
             services to our students and corporate clients with qualified instructors who create master minds and transform learning.
             <br>
             Landover Aviation Business School (LABS) became operational in 2002.

             <br>
             We made IATA TOP 10 Aviation Training Institutions in Africa 3 times in a row in 2011, 2012,
              and 2013. Also LABS produced IATA Best performer in Nigeria in 2012, 2013,
              2014, 2016, 2017, 2018 and most recently in 2019
          </p>
					<div class="follow_us">
						<ul>
							<li>Follow us on</li>
							<li><a href="https://www.facebook.com/landoveraviationbusinessschool" target="__blank"><i class="ti-facebook"></i></a></li>
							<li><a href="https://www.instagram.com/landoveraviationschool" target="__blank"><i class="ti-instagram"></i></a></li>
              <li><a href="https://www.linkedin.com/in/landover-aviation-school-38826415a/" target="__blank"><i class="ti-linkedin"></i></a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 ml-lg-auto">
					<h5>Useful links</h5>
					<ul class="links">
						<li><a href="/login">Login</a></li>
						<li><a href="/register">Register</a></li>
						<li><a href="https://landover.aero/contact-us/" target="__blank">Contact Us</a></li>
					</ul>
				</div>
				<div class="col-lg-3 col-md-6">
					<h5>Contact Us</h5>
					<ul class="contacts">
            <li> <a href="#"><i class="fa fa-map-marker"></i>17, Simbiat Abiola Road, Ikeja Lagos</a></li>
						<li><a href="mailto:trg@landover.aero"><i class="fa fa-envelope"></i> trg@landover.aero</a></li>
            <li><a href="tel:+2348131742179"><i class="fa fa-phone"></i> +2348131742179</a>
            <a href="tel:+2348037007026"><i class="fa fa-phone"></i> +2348037007026</a></li>
					</ul>
				</div>
			</div>
			<!--/row-->
			<hr>
			<div class="row">
				<div class="col-md-8">
					<ul id="additional_links">
						<li><a href="#0">Terms and conditions</a></li>
						<li><a href="#0">Privacy</a></li>
					</ul>
				</div>
        <!-- <div class="container">
          <div class="">
            <span class="float-left"><small>Copyright © <b id="dateHolder"></b> LABS</small></span>
          </div>
        </div> -->
				<div class="col-md-4">
					<div id="copy">© 2019 LABS |
            <small>Made with <i class="fa fa-heart"></i> by <a target="_blank" href="https://penciledge.net">Penciledge</a> </small>
          </div>
				</div>
			</div>
		</div>
	</footer>

     </div>
	<!-- /header -->


	<!--/main-->


    <!--/footer-->


</template>

<script>
export default {
    data() {
        return{
            faculties :{},
            readMoreAbout : false,
        }
    },
    methods: {
       getAllFaculties(){
           axios.get('/api/v1/faculties').then((data)=>{
               if(data.data.status){
                   this.faculties = data.data.data;
                   let newFacultyListItems = this.getFacultyAsListItems();
                   this.updateFacultySelect(newFacultyListItems);
               }
           }).catch((data)=>{
           });
       },
       getFacultyAsListItems(){
           let listItems = '';
           this.faculties.forEach(faculty => {
               listItems += `<li><a href="#${faculty.name.toLowerCase()}">${faculty.name}</a></li>`
           });
            return listItems;
       },
       updateFacultySelect(newFacultyListItems) {
           let sbSuffix = document.querySelector('#facultySelect').getAttribute("sb");
           let sbOptions = document.querySelector("#sbOptions"+"_"+sbSuffix);
           sbOptions.innerHTML += newFacultyListItems;
            console.log();
       }
    },
    created() {
    },
    mounted() {
        this.getAllFaculties();
    }
}
</script>

<style>
</style>