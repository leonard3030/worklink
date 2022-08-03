<template>
  <section class="v-upload">
    <input
      type="hidden"
      :name="name"
      :value="input_value"
      :required="required"
      v-if="enableinput"
    />
    <a
      :class="classlist"
      @click="upload_widget = true"
      style="color:#ffffff"
      v-if="enableinput"
    >
      <slot></slot>
    </a>

    <!-- List selected files -->
    <div
      class="selected-container"
      v-if="selected_files.length !== 0 && multiple && enableinput"
    >
      <div
        class="ant-upload-list ant-upload-list-picture-card"
        data-aos="fade-in"
        data-aos-offset="200"
        data-aos-easing="ease-in"
        data-aos-duration="500"
        style="width: 100%;"
      >
        <div
          class="ant-upload-list-item ant-upload-list-item-done"
          v-for="(file, index) in selected_files"
          :key="index + 'selected_file'"
        >
          <div class="ant-upload-list-item-info">
            <span>
              <div class="ant-upload-list-item-thumbnail">
                <img
                  v-if="
                    GetExt(file) == 'jpeg' ||
                      GetExt(file) == 'jpg' ||
                      GetExt(file) == 'png' ||
                      GetExt(file) == 'gif'
                  "
                  :src="`${url + location}thumbnail/${file}`"
                />
                <img
                  v-if="
                    GetExt(file) == 'mp3' ||
                      GetExt(file) == 'ogg' ||
                      GetExt(file) == 'acc'
                  "
                  :src="`${url}assets/images/ext/audio.png`"
                />
                <img
                  v-if="
                    GetExt(file) == 'mp4' ||
                      GetExt(file) == 'avi' ||
                      GetExt(file) == 'vob' ||
                      GetExt(file) == 'mov'
                  "
                  :src="`${url}assets/images/ext/video.png`"
                />
                <img
                  v-if="GetExt(file) == 'pdf'"
                  :src="`${url}assets/images/ext/pdf.png`"
                />
                <img
                  v-if="GetExt(file) == 'docs' || GetExt(file) == 'doc'"
                  :src="`${url}assets/images/ext/word.png`"
                />
                <img
                  v-if="
                    GetExt(file) == 'xls' ||
                      GetExt(file) == 'xlsx' ||
                      GetExt(file) == 'xlsm' ||
                      GetExt(file) == 'xlt' ||
                      GetExt(file) == 'xltx' ||
                      GetExt(file) == 'xltm' ||
                      GetExt(file) == 'xla' ||
                      GetExt(file) == 'xlam'
                  "
                  :src="`${url}assets/images/ext/excel.png`"
                />
              </div>
            </span>
          </div>
          <span class="ant-upload-list-item-actions">
            <a-popconfirm
              title="Are you sure？"
              @confirm="removeFileFromSelected(index)"
              okText="Yes"
              cancelText="No"
            >
              <a-icon slot="icon" type="question-circle-o" style="color: red" />
              <a-icon style="color:#fff" type="delete" />
            </a-popconfirm>
          </span>
        </div>
      </div>
    </div>
    <!-- End of selected file list -->

    <!-- Start of single file selected -->
    <div
      class="selected-container"
      v-if="input_value && !multiple && enableinput"
    >
      <div class="image-widget">
        <img
          v-if="
            GetExt(input_value) == 'jpeg' ||
              GetExt(input_value) == 'jpg' ||
              GetExt(input_value) == 'png' ||
              GetExt(input_value) == 'gif'
          "
          :src="`${url + location}thumbnail/${input_value}`"
        />
        <img
          v-if="
            GetExt(input_value) == 'mp3' ||
              GetExt(input_value) == 'ogg' ||
              GetExt(input_value) == 'acc'
          "
          :src="`${url}assets/images/ext/audio.png`"
        />
        <img
          v-if="
            GetExt(input_value) == 'mp4' ||
              GetExt(input_value) == 'avi' ||
              GetExt(input_value) == 'vob' ||
              GetExt(input_value) == 'mov'
          "
          :src="`${url}assets/images/ext/video.png`"
        />
        <img
          v-if="GetExt(input_value) == 'pdf'"
          :src="`${url}assets/images/ext/pdf.png`"
        />
        <img
          v-if="GetExt(input_value) == 'docs' || GetExt(input_value) == 'doc'"
          :src="`${url}assets/images/ext/word.png`"
        />
        <img
          v-if="
            GetExt(input_value) == 'xls' ||
              GetExt(input_value) == 'xlsx' ||
              GetExt(input_value) == 'xlsm' ||
              GetExt(input_value) == 'xlt' ||
              GetExt(input_value) == 'xltx' ||
              GetExt(input_value) == 'xltm' ||
              GetExt(input_value) == 'xla' ||
              GetExt(input_value) == 'xlam'
          "
          :src="`${url}assets/images/ext/excel.png`"
        />
      </div>
    </div>
    <!-- End of single file selected -->

    <!-- Starting of widget -->
    <div class="upload-widget" v-if="upload_widget">
      <div class="upload-overlay" @click="upload_widget = false"></div>
      <div
        class="upload-wrapper"
        data-aos="fade-in"
        data-aos-offset="200"
        data-aos-easing="ease-in"
        data-aos-duration="400"
      >
        <div class="upload-window fadeInUp">
          <div class="upload-header">
            <div class="upload-ribbon">
              <span class>
                <i class="mdi mdi-file-document"></i> File manager
              </span>
              <a
                href="javascript:void(0)"
                @click="upload_widget = false"
                class="upload-close-btn"
              >
                <span class="mdi mdi-circle"></span>
              </a>
            </div>
          </div>

          <a-tabs defaultActiveKey="1" @change="onTabChange">
            <a-tab-pane key="1">
              <span slot="tab"> <a-icon type="upload" />UPLOAD </span>
              <div class="upload-container">
                <div class="upload-section">
                  <!-- Stating of upload section -->
                  <div class="uploading-list">
                    <ul>
                      <li
                        class="uploading-item"
                        v-for="(file, index) in files"
                        :key="index"
                        :class="{ error: file.error, success: file.success }"
                      >
                        <span class="file-name">{{ file.name }}</span>
                        <div class="progress-widget">
                          <a-progress
                            :percent="parseInteger(file.progress)"
                            :status="
                              file.progress == 100 && file.success
                                ? 'success'
                                : 'active'
                            "
                            :format="(percent) => `${parseInt(percent)}%`"
                          />
                        </div>

                        <div class="status">
                          <a-alert
                            v-if="file.success"
                            message="Success"
                            type="success"
                            show-icon
                          />
                          <a-alert
                            v-if="
                              file.progress == 0 && !file.success && !file.error
                            "
                            message="Pending"
                            type="info"
                            show-icon
                          />
                          <a-alert
                            v-if="
                              file.progress > 0 && !file.success && !file.error
                            "
                            message="Uploading"
                            type="warning"
                            show-icon
                          />
                          <a-alert
                            v-if="file.error"
                            message="Failed"
                            type="error"
                            show-icon
                          />
                        </div>
                      </li>
                    </ul>
                  </div>

                  <div class="uploading-option">
                    <button type="button" class="btn-add-file">
                      <file-upload
                        v-if="token != null"
                        ref="upload"
                        v-model="files"
                        name="file"
                        :multiple="true"
                        :post-action="uploadurl"
                        @input-file="inputFile"
                        @input-filter="inputFilter"
                        :chunk-enabled="true"
                        :chunk="{
                          action: url + 'upload/chunk/' + token,
                          minSize: 83886080,
                          maxActive: 1,
                          maxRetries: 4,
                        }"
                        :headers="{ Authorization: 'Bearer ' + token }"
                      >
                        <a-icon type="plus" />Add file
                      </file-upload>

                      <file-upload
                        v-else
                        ref="upload"
                        v-model="files"
                        name="file"
                        :multiple="true"
                        :post-action="uploadurl"
                        @input-file="inputFile"
                        @input-filter="inputFilter"
                        :chunk-enabled="true"
                        :chunk="{
                          action: url + 'upload/chunk/empty',
                          minSize: 83886080,
                          maxActive: 1,
                          maxRetries: 4,
                        }"
                        :headers="{ Authorization: 'Bearer ' + token }"
                      >
                        <a-icon type="plus" />Add file
                      </file-upload>
                    </button>
                    <button
                      v-show="!$refs.upload || !$refs.upload.active"
                      @click.prevent="$refs.upload.active = true"
                      type="button"
                      class="btn-start-upload-file"
                    >
                      Start upload
                    </button>
                    <button
                      v-show="$refs.upload && $refs.upload.active"
                      @click.prevent="$refs.upload.active = false"
                      type="button"
                      class="btn-stop-upload-file"
                    >
                      Stop upload
                    </button>
                    <span
                      style="color: #000;font-weight: 500;"><i style="color:red"><a-icon type="notification" /></i> (max = 5mbs) | After Uploading click files and select the uploaded
                      file</span
                    >
                  </div>
                  <!-- End of upload section  -->
                </div>
              </div>
            </a-tab-pane>
            <a-tab-pane key="2">
              <span slot="tab"> <a-icon type="hdd" />FILES </span>
              <div class="upload-container">
                <!-- Uploaded file list -->
                <div class="uploade-list">
                  <div
                    class="ant-upload-list ant-upload-list-picture-card"
                    data-aos="fade-right"
                    data-aos-offset="200"
                    data-aos-easing="ease-in"
                    data-aos-duration="500"
                    @scroll="onScollFileList"
                  >
                    <!-- Start of uploader item -->
                    <a-spin :spinning="loading">
                      <a-tooltip
                        v-for="(file, index) in uploaded_files"
                        :key="index + 'uploaded_file'"
                      >
                        <template slot="title">{{
                          file.virtual_name
                        }}</template>
                        <div
                          class="ant-upload-list-item ant-upload-list-item-done"
                        >
                          <a-checkbox
                            class="file-checkbox"
                            v-if="multiple"
                            @change="onSelectFile($event, file, index)"
                          ></a-checkbox>
                          <div
                            class="ant-upload-list-item-info"
                            @click="viewFile(file)"
                          >
                            <span>
                              <div class="ant-upload-list-item-thumbnail">
                                <img
                                  v-if="
                                    file.ext == 'jpeg' ||
                                      file.ext == 'jpg' ||
                                      file.ext == 'png' ||
                                      file.ext == 'gif'
                                  "
                                  :src="
                                    `${url + location}thumbnail/${
                                      file.file_name
                                    }`
                                  "
                                />
                                <img
                                  v-if="
                                    file.ext == 'mp3' ||
                                      file.ext == 'ogg' ||
                                      file.ext == 'acc'
                                  "
                                  :src="`${url}assets/images/ext/audio.png`"
                                />
                                <img
                                  v-if="
                                    file.ext == 'mp4' ||
                                      file.ext == 'avi' ||
                                      file.ext == 'vob' ||
                                      file.ext == 'mov'
                                  "
                                  :src="`${url}assets/images/ext/video.png`"
                                />
                                <img
                                  v-if="file.ext == 'pdf'"
                                  :src="`${url}assets/images/ext/pdf.png`"
                                />
                                <img
                                  v-if="file.ext == 'docs' || file.ext == 'doc'"
                                  :src="`${url}assets/images/ext/word.png`"
                                />
                                <img
                                  v-if="
                                    file.ext == 'xls' ||
                                      file.ext == 'xlsx' ||
                                      file.ext == 'xlsm' ||
                                      file.ext == 'xlt' ||
                                      file.ext == 'xltx' ||
                                      file.ext == 'xltm' ||
                                      file.ext == 'xla' ||
                                      file.ext == 'xlam'
                                  "
                                  :src="`${url}assets/images/ext/excel.png`"
                                />
                              </div>
                            </span>
                          </div>

                          <span class="ant-upload-list-item-actions">
                            <a-icon
                              style="color:#fff"
                              @click="viewFile(file)"
                              type="eye"
                            />
                            <a-popconfirm
                              title="Are you sure？"
                              @confirm="DeleteFile(file.id, index)"
                              okText="Yes"
                              cancelText="No"
                            >
                              <a-icon
                                slot="icon"
                                type="question-circle-o"
                                style="color: red"
                              />
                              <a-icon style="color:#fff" type="delete" />
                            </a-popconfirm>
                          </span>
                        </div>
                      </a-tooltip>
                    </a-spin>
                    <!-- End of uploader item -->
                    <!-- When empty -->
                    <a-empty
                      style="padding-top:50px;"
                      v-if="uploaded_files.length === 0"
                    />
                  </div>
                  <!-- file list option -->
                  <div class="file-list-option">
                    <a-input-search
                      placeholder="Search file name"
                      style="width: 100%"
                      size="large"
                      @change="onSearch"
                    />
                    <a-radio-group
                      @change="SeachFileByType"
                      style="width: 100%;margin-left:20px"
                      size="large"
                    >
                      <a-radio-button value="mp3,ogg,acc">Audio</a-radio-button>
                      <a-radio-button value="mp4,avi,vob,mov"
                        >Video</a-radio-button
                      >
                      <a-radio-button value="png,gif,jpg,jpeg"
                        >Images</a-radio-button
                      >
                      <a-radio-button
                        value="doc,docs,xlsx,pdf,xls,xlsm,xlt,xltx,xltm,xla,xlam"
                        >Documents</a-radio-button
                      >
                      <a-radio-button
                        value="mp3,ogg,acc,mp4,avi,vob,mov,png,gif,jpg,jpeg,doc,docs,xlsx,pdf,xls,xlsm,xlt,xltx,xltm,xla,xlam"
                        >All</a-radio-button
                      >
                    </a-radio-group>
                  </div>
                  <!-- End of file option -->
                </div>
                <!-- End of file list -->
                <!-- Start of file details -->
                <div class="file-details">
                  <div class="file-info" v-if="selected_file">
                    <div class="file-preview">
                      <img
                        v-if="
                          selected_file.ext == 'jpeg' ||
                            selected_file.ext == 'jpg' ||
                            selected_file.ext == 'png' ||
                            selected_file.ext == 'JPG' ||
                            selected_file.ext == 'gif'
                        "
                        :src="
                          `${url + location}thumbnail/${
                            selected_file.file_name
                          }`
                        "
                      />
                      <img
                        v-if="selected_file.ext == 'mp3'"
                        :src="`${url}assets/images/ext/audio.png`"
                      />
                      <img
                        v-if="selected_file.ext == 'mp4'"
                        :src="`${url}assets/images/ext/video.png`"
                      />
                      <img
                        v-if="selected_file.ext == 'pdf'"
                        :src="`${url}assets/images/ext/pdf.png`"
                      />
                      <img
                        v-if="selected_file.ext == 'docs'"
                        :src="`${url}assets/images/ext/word.png`"
                      />
                      <img
                        v-if="
                          selected_file.ext == 'xls' ||
                            selected_file.ext == 'xlsx' ||
                            selected_file.ext == 'xlsm' ||
                            selected_file.ext == 'xlt' ||
                            selected_file.ext == 'xltx' ||
                            selected_file.ext == 'xltm' ||
                            selected_file.ext == 'xla' ||
                            selected_file.ext == 'xlam'
                        "
                        :src="`${url}assets/images/ext/excel.png`"
                      />
                    </div>
                    <!-- File metadata -->
                    <div class="file-metadata" v-if="!is_editing_info">
                      <div class="file-name">
                        <strong>File name</strong>
                        <p>{{ selected_file.virtual_name }}</p>
                      </div>
                      <div class="file-size">
                        <strong>File size</strong>
                        <p>{{ BytesToSize(selected_file.size) }}</p>
                      </div>
                      <div class="file-title" v-if="selected_file.title">
                        <strong>File title</strong>
                        <p>{{ selected_file.title }}</p>
                      </div>
                      <div class="file-content" v-if="selected_file.content">
                        <strong>File content</strong>
                        <p>{{ selected_file.content }}</p>
                      </div>
                      <div class="file-keywords" v-if="selected_file.keyword">
                        <strong>File keywords</strong>
                        <p>{{ selected_file.keyword }}</p>
                      </div>
                      <div class="file-option">
                        <a-button
                          class="btn"
                          @click="is_editing_info = true"
                          type="primary"
                          >UPDATE INFO</a-button
                        >
                      </div>
                    </div>
                    <!-- End of file metadata -->
                    <!-- Start of Edit file form -->
                    <div
                      class="edit-file-metadata"
                      v-if="is_editing_info"
                      data-aos="fade-left"
                      data-aos-offset="200"
                      data-aos-easing="ease-in"
                      data-aos-duration="700"
                    >
                      <div class="file-name">
                        <strong>Filename</strong>
                        <a-input
                          size="large"
                          v-model="selected_file.virtual_name"
                          placeholder="Enter filename"
                        />
                      </div>
                      <div class="file-title">
                        <strong>File title</strong>
                        <a-input
                          size="large"
                          v-model="selected_file.title"
                          placeholder="Enter title"
                        />
                      </div>
                      <div class="file-content">
                        <strong>File content</strong>
                        <a-textarea
                          placeholder="Enter content"
                          v-model="selected_file.content"
                          :rows="4"
                        />
                      </div>

                      <div class="file-keywords">
                        <strong>File tags</strong>
                        <a-input
                          size="large"
                          v-model="selected_file.keyword"
                          placeholder="Ex: eating,joking,face"
                        />
                      </div>
                      <div class="file-option">
                        <a-button
                          class="btn"
                          :loading="update_loading"
                          @click="updateFile()"
                          type="primary"
                          >UPDATE</a-button
                        >
                      </div>
                    </div>
                    <!-- End of edit file form -->
                  </div>

                  <div class="no-file-selected" v-if="!selected_file">
                    <a-icon type="file" class="icon" />
                    <p>No file selected</p>
                  </div>

                  <!-- Select file widget -->
                  <div
                    class="select-widget"
                    v-if="selected_files.length !== 0 && multiple"
                  >
                    <a-button
                      class="btn btn-success"
                      :loading="update_loading"
                      @click="approveMultipleSelect()"
                      type="primary"
                      size="large"
                      style="background-color:rgb(31, 195, 216)"
                      icon="right"
                    >
                      SELECT
                      <span>({{ selected_files.length }})</span>
                    </a-button>
                  </div>
                  <div class="select-widget" v-if="selected_file && !multiple">
                    <a-button
                      class="btn btn-success"
                      :loading="update_loading"
                      @click="approveSingleSelect()"
                      type="primary"
                      size="large"
                      style="background-color:rgb(31, 195, 216)"
                      icon="right"
                      >SELECT</a-button
                    >
                  </div>
                  <!-- End file widget -->
                </div>
                <!-- end of file details -->
              </div>
            </a-tab-pane>
          </a-tabs>
        </div>
      </div>
    </div>
    <!-- End of widget -->
  </section>
</template>

<script lang="js">

import VueUploadComponent from 'vue-upload-component'

  export default  {
    name: 'v-upload',
    components:{
      FileUpload: VueUploadComponent
    },
    props: ['open','enableinput','required','location','uploadurl','url','name','value','multiple','classlist'],
    mounted () {
      let vm = this;
      vm.upload_widget = vm.open;
      vm.input_value = vm.value;
      if(vm.multiple){
        if(vm.value){
          vm.selected_files = JSON.parse(vm.value);
        }
      }
    },
    data () {
      return {
            files:[],
            upload_widget: false,
            token: localStorage.getItem('writter_access_token'),
            upload_search_panel:false,
            uploaded_files:[],
            fileList: [],
            previewVisible: false,
            previewImage: '',
            loading:false,
            selected_file:null,
            is_editing_info:false,
            update_loading:false,
            selected_files:[],
            input_value:null
      }
    },
    methods: {

       /**
     * Has changed
     * @param  Object|undefined   newFile   Read only
     * @param  Object|undefined   oldFile   Read only
     * @return undefined
     */
    inputFile: function (newFile, oldFile) {
      if (newFile && oldFile && !newFile.active && oldFile.active) {
        // Get response data
        console.log('response', newFile.response)
        if (newFile.xhr) {
          //  Get the response status code
          console.log('status', newFile.xhr.status)
        }
      }
    },
    /**
     * Pretreatment
     * @param  Object|undefined   newFile   Read and write
     * @param  Object|undefined   oldFile   Read only
     * @param  Function           prevent   Prevent changing
     * @return undefined
     */
    inputFilter: function (newFile, oldFile, prevent) {

    },
      handleCancel() {
        this.previewVisible = false;
      },
      handlePreview(file) {
        this.previewImage = file.url || file.thumbUrl;
        this.previewVisible = true;
      },
      handleChange({ fileList }) {
         this.fileList = fileList;
      },
      BytesToSize(bytes) {
         var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) return '0 Byte';
                var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
      },
      GetExt(file) {
        return /(?:\.([^.]+))?$/.exec(file)[1];
      },
      GetAllFiles(){
        this.loading = true;
        this.axios.get(this.url+"upload/all-files/"+localStorage.getItem('writter_access_token'), {
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('writter_access_token')},
        })
                    .then((response)=>{
                        if(response.data){
                           this.uploaded_files_backup = response.data;
                           this.uploaded_files = response.data.slice(0,40);
                           this.loading = false;
                        }
        });
      },
      DeleteFile(id,key){
        this.axios.delete(this.url+"upload/delete/"+id)
            .then((response)=>{
            if(response.data != null){
              this.uploaded_files.splice(key, 1);
             }
        })
      },
      onSearch({target}){
        let keyword = target.value;
        this.selected_file = null;
         if(keyword != '') {
           this.loading = true;
           this.axios.post(this.url+"upload/search/"+this.token,{keyword:keyword})
           .then((response)=>{
            if(response.data != null){
               this.uploaded_files_backup = response.data;
               this.uploaded_files = response.data.slice(0,40);
               this.loading = false;
            }
           });
         }else{
            this.GetAllFiles();
         }
      },
      SeachFileByType({target}){
          var keyword = target.value;
          this.selected_file = null;
          this.loading = true;
          this.axios.post(this.url+"upload/search-by-type/"+this.token,{keyword: keyword})
              .then((response)=>{
                  if (response.data != null) {
                     this.uploaded_files_backup = response.data;
                     this.uploaded_files = response.data.slice(0,40);
                     this.loading = false;
                  }
          });
      },
      onTabChange(index){
        if(parseInt(index) === 2){
          this.loading = true;
          setTimeout(() => {
             this.GetAllFiles();
          }, 2000);
        }
      },
      viewFile(file){
        this.selected_file = file;
      },
      updateFile(){
        let vm = this;
        vm.update_loading = true;
        let data = {
          file_name: vm.selected_file.virtual_name,
          file_title: vm.selected_file.title,
          file_content: vm.selected_file.content,
          file_keywords: vm.selected_file.keyword
        }
        this.axios.post(this.url+"upload/update-file/"+vm.selected_file.id,data)
              .then((response)=>{
                  if (response.data != null) {
                    vm.update_loading = false;
                     this.$message.success(response.data.message);
                    this.is_editing_info = false;
                  }
          }).catch(error => {
          this.$message.error("Error found, "+ error.response.data.message);
           vm.update_loading = false;
        });;

      },
      onSelectFile({target},file,index){
         let vm = this;
         if(target.checked){
           vm.selected_files.push(file.file_name);
         }else{
           const index = vm.selected_files.indexOf(file.file_name);
           if (index > -1) {
               vm.selected_files.splice(index, 1);
            }
         }
      },
      approveMultipleSelect(){
        let vm = this;
        if(vm.selected_files){
           vm.input_value = JSON.stringify(vm.selected_files);
           vm.upload_widget = false;
        }
      },
      approveSingleSelect(){
         let vm = this;
        if(vm.selected_file){
           vm.input_value = vm.selected_file.file_name;
           vm.upload_widget = false;
           vm.$emit('onFileSelected', vm.input_value);
        }
      },
      removeFileFromSelected(index){
        let vm = this;
        if (index > -1) {
            vm.selected_files.splice(index, 1);
            vm.input_value = JSON.stringify(vm.selected_files);
        }
      },
      onScollFileList({target}){
        let vm = this;
        vm.loading = true;
        if( target.scrollTop - 200 === (target.scrollHeight - 200 - target.offsetHeight)){
           let last_length = vm.uploaded_files.length;
           if(last_length !== vm.uploaded_files_backup.left){
           let next_files = vm.uploaded_files_backup.slice(last_length,last_length + 40);
           next_files.forEach((item)=>{
              vm.uploaded_files.push(item);
           });
          }
        }
          vm.loading = false;
      },
      parseInteger(parc){
        return parseInt(parc);
      }
    },
    computed: {

    },
     watch: {
     open: function (val) {
      this.upload_widget = val;
    },
  }
}
</script>

<style lang="scss">
.ant-popover {
  z-index: 9999999999999999 !important;
}
.ant-message {
  z-index: 999999999999999999999;
}
.v-upload {
  .anticon {
    vertical-align: 0.025em !important;
  }
  .ant-tabs-bar {
    margin: 0 0 0px 0 !important;
  }

  .upload-overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    filter: blur(8px);
    -webkit-filter: blur(126px);
    z-index: 999999999;
    background: rgb(255, 255, 255);
  }
  .upload-wrapper {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: transparent;
    z-index: 999999999;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .upload-window {
    width: 1208px;
    background: #fff;
    box-shadow: 0px 0px 43px #5d5d5d;
    position: relative;
    overflow-y: hidden;
    overflow-x: hidden;
  }
  .upload-ribbon {
    width: 100%;
    height: 40px;
    background: linear-gradient(120deg, #00e4d0, #5983e8);
    position: relative;
    top: -2px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0px 11px;
  }
  .upload-header {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 9;
    background: #fff;
    padding-bottom: 4px;
    border-bottom: 1px solid #eaeaea;
  }
  .upload-container {
    height: 500px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .upload-section {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      .uploading-list {
        height: 100%;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 10px;
        overflow-y: auto;
        ul {
          list-style: none;
          margin-left: -40px;
          .uploading-item {
            height: 80px;
            border-bottom: 1px solid #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0px 20px;
            &.error {
              border-bottom: 1px solid rgb(250, 9, 109);
            }
            &.success {
              border-bottom: 1px solid rgb(15, 203, 37);
            }
            .file-name {
              width: calc(100% - 480px);
            }
            .progress-widget {
              width: 250px;
            }
            .status {
              width: 180px;
            }
          }
        }
      }
      .uploading-option {
        height: 60px;
        display: flex;
        align-items: center;
        background: #f4f4f4;
        padding: 0px 20px;
        .btn-add-file {
          font-size: 14px;
          display: flex;
          margin-right: 10px;
          height: 36px;
          width: 100px;
          justify-content: center;
          align-items: center;
          background: #4f8ee5;
          color: #fff;
          border: none;
        }
        .btn-start-upload-file {
          font-size: 14px;
          display: flex;
          margin-right: 10px;
          height: 36px;
          width: 100px;
          justify-content: center;
          align-items: center;
          background: #009d6e;
          color: #fff;
          border: none;
        }
        .btn-stop-upload-file {
          font-size: 14px;
          display: flex;
          margin-right: 10px;
          height: 36px;
          width: 100px;
          justify-content: center;
          align-items: center;
          background: #bccd06;
          color: #fff;
          border: none;
        }
      }
    }
    .uploade-list {
      width: 100%;
      padding-left: 10px;
      padding-right: 10px;
      padding-top: 10px;
      border-right: 1px solid #ccc;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      .ant-upload-list-item {
        position: relative;
        .file-checkbox {
          position: absolute;
          z-index: 9999;
          top: 4px;
          left: 4px;
        }
      }
      .ant-upload-list {
        height: 100%;
        width: 100%;
        overflow-y: auto;
      }
      .file-list-option {
        height: 100px;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-direction: row;
        border-top: 1px solid #ccc;
      }
    }
    .file-details {
      width: 366px;
      padding-left: 10px;
      padding-right: 10px;
      padding-top: 10px;
      display: flex;
      justify-content: space-between;
      flex-direction: column;
      .file-info {
        height: 100%;
        overflow-y: auto;
        .file-preview {
          width: 100%;
          height: 240px;
          border: 1px solid #ccc;
          padding: 15px;
          margin-bottom: 20px;
          display: flex;
          justify-content: center;
          align-items: center;
          background: #f7f7f7;
          img {
            position: static;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
          }
        }
        .file-metadata {
          padding-bottom: 15px;
          .file-name {
            p {
              word-wrap: break-word;
            }
          }
          .file-option {
            margin-top: 20px;
            .btn {
              width: 100%;
            }
          }
        }
        .edit-file-metadata {
          padding-bottom: 15px;
          .file-option {
            margin-top: 20px;
          }
        }
      }

      .select-widget {
        height: 100px;
        border-top: 1px solid #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .no-file-selected {
        height: 100%;
        width: 100%;
        height: 500px;
        padding: 15px;
        display: flex;
        align-items: center;
        flex-direction: column;
        .icon {
          font-size: 75px;
          color: #ccc;
        }
        p {
          color: #969696;
          margin-top: 20px;
        }
      }
    }
  }
  .upload-close-btn {
    color: rgb(210, 52, 224);
    cursor: pointer;
  }
  .upload-close-btn:hover {
    color: rgb(250, 9, 109);
  }
  .selected-container {
    margin-top: 11px;
    width: 100%;
    .image-widget {
      width: 280px;
      height: 280px;
      border: 1px solid #ccc;
      padding: 11px;
      display: flex;
      justify-content: center;
      align-items: center;
      img {
        position: static;
        display: block;
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: contain;
      }
    }
  }
}
</style>
