<template>
  <div class="row">
    <div class="col-md-12">
        <div class="form-group row">
          <div class="col-md-12" >
            <div class="d-flex mb-2">
              <label for="shots">Update Application banner (16:9)</label>
            </div>
            <ImageFile :showDesc="false" v-model="bannerForm.banner" :placeholder="''" :name="'shot 1'"  id="image1" v-slot="slotProps" class="mr-1" key="banner">
            <div class="has-tooltip banner-upload br-4" @click="slotProps.clickUpload" :style="{ backgroundImage: 'url(' + bannerForm.banner.data.replace(/(\r\n|\n|\r)/gm, '') + ')'}">
              <i class="fa fa-plus" v-show="bannerForm.banner.data === '' || bannerForm.banner.data === '/storage/'  "></i>
              <span class="tooltiptext">Click to select a new banner</span>
            </div>
            </ImageFile>
          </div>
          <div class="col-md-12 mt-4">
            <button class="btn  btn-secondary ml-auto" @click="updateBanner()" :disabled="!this.systemsSettings || this.bannerForm.banner.data == this.systemsSettings.app_banner">Update banner</button>
          </div>
        </div>
    </div>
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <div class="my-2">
            <label for="shots">Application Name</label>
          </div>
          <div>
            <input type="text" class="form-control" v-model="systemForm.app_name">
          </div>
        </div>
        <div class="col-md-12 flex">
          <button class="btn  btn-secondary ml-auto" @click="updateSystemInfo()">update</button>
        </div>
      </div>
    </div>
  </div>

</template>
<script>
import { enrollmentMixin } from "../mixins/enrollmentMixin";
import ImageFile from "../ImageFile";
export default {
  mixins: [enrollmentMixin],
  components: { ImageFile },
  data(){
    return {
      bannerForm: new Form({
        banner: {data:''},
      }),
      systemForm: new Form({
        app_title: '',
        app_name: '',
      }),
      loginBannerForm: new Form({
        banner: {data:''},
      }),
      systemsSettings: null
    }
  },
  methods: {
    getSystemSettings() {
      axios
        .get('/api/v1/system')
        .then(response => {
          this.systemsSettings = response.data.data;
          this.systemsSettings.app_banner = '/storage/'+this.systemsSettings.app_banner;
          this.systemForm.app_name = this.systemsSettings.app_name;
          this.systemForm.app_title = this.systemsSettings.app_title;
          this.bannerForm.banner.data = this.systemsSettings.app_banner;
        }).catch(e => {
          // console.log(e)
        })
    },
    updateBanner() {
      Fire.$emit("pageBusy");
      this.bannerForm
        .patch('/api/v1/admin/system/banner')
          .then(response => {
            Fire.$emit("systemSettingsUpdated");
            this.showSuccess(
							"Operation Successful",
							"Application banner updated successfully"
						);
          }).catch(e => {
            this.showError();
          })
          .finally(f => {
            Fire.$emit("pageFree");
          })
    },
    updateSystemInfo() {
      Fire.$emit("pageBusy");
      this.systemForm
        .patch('/api/v1/admin/system')
        .then( response => {
          Fire.$emit("systemSettingsUpdated");
          this.showSuccess(
							"Operation Successful",
							"Application information updated successfully"
						);
        })
        .catch(e => {
          this.showError();
        })
        .finally(f => {
          Fire.$emit("pageFree");
        })
    }
  },
  created(){
    this.getSystemSettings();
    Fire.$on("systemSettingsUpdated", () => {
			this.getSystemSettings();
		});
  }
}
</script>
<style scoped>
.banner-upload{
  width: auto;
  height: 450px;
  background-color: #eff0f2;
  position: relative;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
.banner-upload > i{
  position: relative;
  font-size: 3em;
}
</style>
