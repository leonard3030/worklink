<template>
  <div class="upload-wrapper">
    <transition name="fadeanim">
      <section class="upload-overlay" v-if="upload_widget" @click.self="upload_widget = false">
        <!-- Starting of widget -->
        <div class="upload-window">
          <div class="upload-header">
            <div class="upload-ribbon">
              <span class="title">
                <i class="mdi mdi-file-document"></i> File manager
              </span>
              <a
                href="javascript:void(0)"
                @click="upload_widget = false"
                class="upload-close-btn"
              >&times;</a>
            </div>
          </div>

          <div class="upload-body">
            <a-tabs :defaultActiveKey="default_tab" @change="onTabChange">
              <a-tab-pane key="1">
                <span slot="tab">
                  <a-icon type="upload" />UPLOAD
                </span>
                <div class="upload-container">
                  <div class="upload-section">
                    <!-- Stating of upload section -->
                    <div class="uploading-list">
                      <ul v-if="files.length > 0">
                        <li
                          class="uploading-item"
                          v-for="(file,index) in files"
                          :key="index"
                          :class="{error:file.error,success: file.success}"
                        >
                          <div class="image-container">
                            <img :src="file.blob" width="50" height="50" />
                          </div>
                          <span class="file-name">{{file.name}}</span>
                          <div class="progress-widget">
                            <a-progress
                              :percent="parseInteger(file.progress)"
                              :status="file.progress == 100 && file.success ? 'success' : 'active'"
                              :format="percent => `${parseInt(percent)}%`"
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
                              v-if="file.progress == 0 && !file.success && !file.error"
                              message="Pending"
                              type="info"
                              show-icon
                            />
                            <a-alert
                              v-if="file.progress > 0 && !file.success && !file.error"
                              message="Uploading"
                              type="warning"
                              show-icon
                            />
                            <a-alert v-if="file.error" message="Failed" type="error" show-icon />
                          </div>
                          <div class="action">
                            <button
                              type="button"
                              class="btn btn-default"
                              @click.prevent="removeFileFromUploadList(file)"
                            >Remove</button>
                          </div>
                        </li>
                      </ul>
                      <!-- When no file selected -->
                      <div v-else class="widget-empty">
                        <p>No image selected, Click [Add image] button below</p>
                      </div>
                    </div>

                    <div class="uploading-option">
                      <button type="button" class="btn-add-file">
                        <file-upload
                          ref="upload"
                          v-model="files"
                          name="file"
                          :multiple="true"
                          :post-action="uploadurl"
                          @input-file="inputFile"
                          @input-filter="inputFilter"
                          :chunk-enabled="true"
                          :chunk="{
                              action: url+'upload/chunk',
                              minSize: 83886080,
                              maxActive: 1,
                              maxRetries: 4
                              }"
                          :headers="{'Authorization': 'Bearer ' + JSON.parse(localStorage.writter_access_token)}"
                        >
                          <a-icon type="plus" />Add image
                        </file-upload>
                      </button>
                      <button
                        v-show="!$refs.upload || !$refs.upload.active"
                        @click.prevent="$refs.upload.active = true"
                        type="button"
                        class="btn-start-upload-file"
                      >Start upload</button>
                      <button
                        v-show="$refs.upload && $refs.upload.active"
                        @click.prevent="$refs.upload.active = false"
                        type="button"
                        class="btn-stop-upload-file"
                      >Stop upload</button>
                    </div>
                    <!-- End of upload section  -->
                  </div>
                </div>
              </a-tab-pane>
              <a-tab-pane key="2">
                <span slot="tab">
                  <a-icon type="hdd" />FILES
                </span>
                <div class="upload-container">
                  <!-- Uploaded file list -->
                  <div class="uploade-list">
                    <div
                      class="ant-upload-list ant-upload-list-picture-card"
                      @scroll="onScollFileList"
                    >
                      <!-- Start of uploader item -->
                      <a-spin :spinning="loading">
                        <a-tooltip
                          v-for="(file,index) in  uploaded_files"
                          :key="index+'uploaded_file'"
                        >
                          <template slot="title">{{file.virtual_name}}</template>
                          <div
                            class="ant-upload-list-item ant-upload-list-item-done"
                            :class="{active:file.active}"
                          >
                            <a-checkbox
                              class="file-checkbox"
                              v-if="multiple"
                              @change="onSelectFile($event,file,index)"
                            ></a-checkbox>
                            <div class="ant-upload-list-item-info" @click="viewFile(file)">
                              <span>
                                <div class="ant-upload-list-item-thumbnail">
                                  <img
                                    v-if="file.ext == 'jpeg' || file.ext == 'jpg' || file.ext == 'png' || file.ext == 'gif' "
                                    :src="`${url+location}thumbnail/${file.file_name}`"
                                  />
                                  <img
                                    v-if="file.ext == 'mp3' || file.ext == 'ogg' || file.ext == 'acc'"
                                    :src="`${url}assets/images/ext/audio.png`"
                                  />
                                  <img
                                    v-if="file.ext == 'mp4' || file.ext == 'avi' || file.ext == 'vob' || file.ext == 'mov'"
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
                                    v-if="file.ext == 'xls' || file.ext == 'xlsx' || file.ext == 'xlsm' || file.ext == 'xlt' || file.ext == 'xltx' || file.ext == 'xltm' || file.ext == 'xla' || file.ext == 'xlam'"
                                    :src="`${url}assets/images/ext/excel.png`"
                                  />
                                </div>
                              </span>
                            </div>
                          </div>
                        </a-tooltip>
                      </a-spin>
                      <!-- End of uploader item -->
                      <!-- When empty -->
                      <a-empty style="padding-top:50px;" v-if="uploaded_files.length === 0" />
                    </div>
                    <!-- file list option -->
                    <div class="file-list-option">
                      <div class="select-widget">
                        <c-button
                          class="btn btn-success"
                          v-if="selected_files.length !== 0 && multiple"
                          :loading="update_loading"
                          @click="approveMultipleSelect()"
                        >
                          SELECT
                          <span>({{selected_files.length}})</span>
                        </c-button>

                        <c-button
                          v-if="selected_file && !multiple"
                          class="btn btn-success"
                          :loading="update_loading"
                          @click="approveSingleSelect()"
                        >SELECT</c-button>

                        <a-popconfirm
                          title="Are you sure to delete?"
                          v-if="selected_file && !multiple"
                          @confirm="DeleteFile(selected_file.id)"
                          okText="Yes"
                          cancelText="No"
                        >
                          <c-button class="btn-default" :loading="update_loading">DELETE</c-button>
                        </a-popconfirm>
                      </div>

                      <a-input-search
                        placeholder="Search file name"
                        style="width: 100%"
                        size="large"
                        class="search-input"
                        @change="onSearch"
                      />
                      <!-- <a-radio-group
                    @change="SeachFileByType"
                    style="width: 100%;margin-left:20px"
                    size="large"
                  >
                    <a-radio-button value="mp3,ogg,acc">Audio</a-radio-button>
                    <a-radio-button value="mp4,avi,vob,mov">Video</a-radio-button>
                    <a-radio-button value="png,gif,jpg,jpeg">Images</a-radio-button>
                    <a-radio-button
                      value="doc,docs,xlsx,pdf,xls,xlsm,xlt,xltx,xltm,xla,xlam"
                    >Documents</a-radio-button>
                    <a-radio-button
                      value="mp3,ogg,acc,mp4,avi,vob,mov,png,gif,jpg,jpeg,doc,docs,xlsx,pdf,xls,xlsm,xlt,xltx,xltm,xla,xlam"
                    >All</a-radio-button>
                      </a-radio-group>-->
                    </div>
                    <!-- End of file option -->
                  </div>
                  <!-- End of file list -->
                  <!-- Start of file details -->
                  <div class="file-details">
                    <div class="file-info" v-if="selected_file">
                      <div class="file-preview">
                        <img
                          v-if="selected_file.ext == 'jpeg' || selected_file.ext == 'jpg' || selected_file.ext == 'png' || selected_file.ext == 'JPG' || selected_file.ext == 'gif' "
                          :src="`${url+location}thumbnail/${selected_file.file_name}`"
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
                          v-if="selected_file.ext == 'xls' || selected_file.ext == 'xlsx' || selected_file.ext == 'xlsm' || selected_file.ext == 'xlt' || selected_file.ext == 'xltx' || selected_file.ext == 'xltm' || selected_file.ext == 'xla' || selected_file.ext == 'xlam'"
                          :src="`${url}assets/images/ext/excel.png`"
                        />
                      </div>
                      <div class="file-metadata">
                        <div class="file-name">
                          <strong>File name</strong>
                          <p>{{selected_file.virtual_name}}</p>
                        </div>
                        <div class="file-size">
                          <strong>File size</strong>
                          <p>{{ BytesToSize(selected_file.size)}}</p>
                        </div>
                      </div>
                    </div>

                    <div class="no-file-selected" v-if="!selected_file">
                      <a-icon type="file" class="icon" />
                      <p>No file selected</p>
                    </div>

                    <!-- Select file widget -->
                    <div class="select-widget">
                      <c-button
                        class="btn btn-success"
                        :loading="update_loading"
                        v-if="selected_files.length !== 0 && multiple"
                        @click="approveMultipleSelect()"
                      >
                        SELECT
                        <span>({{selected_files.length}})</span>
                      </c-button>

                      <c-button
                        class="btn btn-success"
                        v-if="selected_file && !multiple"
                        :loading="update_loading"
                        @click="approveSingleSelect()"
                      >SELECT</c-button>
                      <a-popconfirm
                        title="Are you sure to delete?"
                        v-if="selected_file && !multiple"
                        @confirm="DeleteFile(selected_file.id)"
                        okText="Yes"
                        cancelText="No"
                      >
                        <c-button class="btn-default" :loading="update_loading">DELETE</c-button>
                      </a-popconfirm>
                    </div>

                    <!-- End file widget -->
                  </div>
                  <!-- end of file details -->
                </div>
              </a-tab-pane>
            </a-tabs>
          </div>
        </div>
        <!-- End of widget -->
      </section>
    </transition>
  </div>
</template>

<script lang="js">
import VueUploadComponent from 'vue-upload-component'
import {
    events
} from './events'
export default {
    name: 'upload-helper',
    components: {
        FileUpload: VueUploadComponent
    },
    props: {

    },
    mounted() {
      let vm =this;
        events.$on('open-upload', vm.openUpload);
        events.$on('close-upload', vm.closeUpload);
        setTimeout(() => {
           vm.$watch(
            () => {
              if(vm.$refs.upload && vm.$refs.upload.uploaded){
                return  vm.$refs.upload.uploaded;
              }
            },
            (val) => {
              console.log("ska")
                if (val) {
                  
                    vm.default_tab = "2";
                    vm.GetAllFiles();
                }
            }
        )
        }, 2000);
       
    },
    
    data() {
        return {
            files: [],
            upload_widget: false,
            upload_search_panel: false,
            uploaded_files: [],
            fileList: [],
            previewVisible: false,
            previewImage: '',
            loading: false,
            selected_file: null,
            is_editing_info: false,
            update_loading: false,
            selected_files: [],
            input_value: null,
            callback: (params) => {},
            open: false,
            enableinput: false,
            required: true,
            location: "",
            uploadurl: "",
            url: "",
            name: "",
            value: "",
            multiple: "",
            classlist: "",
            default_tab: "2"
        }
    },
    methods: {
        removeFileFromUploadList(file) {
            let vm = this;
            vm.$refs.upload.remove(file);
        },
        openUpload(settings) {
            let vm = this;
            vm.upload_widget = true;
            vm.callback = settings.callback;
            vm.open = settings.open;
            vm.enableinput = settings.enableinput;
            vm.required = settings.required;
            vm.location = settings.location;
            vm.uploadurl = settings.uploadurl;
            vm.url = settings.url;
            vm.name = settings.name;
            vm.value = settings.value;
            vm.multiple = settings.multiple;
            vm.classlist = settings.classlist;
          
            vm.GetAllFiles();
        },
        closeUpload() {
            let vm = this;
            vm.callback = null;
            vm.upload_widget = false;
        },
        /**
         * Has changed
         * @param  Object|undefined   newFile   Read only
         * @param  Object|undefined   oldFile   Read only
         * @return undefined
         */
        inputFile: function (newFile, oldFile) {
            if (newFile && oldFile && !newFile.active && oldFile.active) {
                // Get response data
                // console.log('response', newFile.response)
                if (newFile.xhr) {
                    //  Get the response status code
                    // console.log('status', newFile.xhr.status)
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
            if (newFile && !oldFile) {
                // Add file

                // Filter non-image file
                // Will not be added to files
                if (!/\.(jpeg|jpe|jpg|gif|png|webp)$/i.test(newFile.name)) {
                    return prevent()
                }

                // Create the 'blob' field for thumbnail preview
                newFile.blob = ''
                let URL = window.URL || window.webkitURL
                if (URL && URL.createObjectURL) {
                    newFile.blob = URL.createObjectURL(newFile.file)
                }
            }

            if (newFile && oldFile) {
                // Update file

                // Increase the version number
                if (!newFile.version) {
                    newFile.version = 0
                }
                newFile.version++
            }

            if (!newFile && oldFile) {
                // Remove file

                // Refused to remove the file
                // return prevent()
            }
        },
        handleCancel() {
            this.previewVisible = false;
        },
        handlePreview(file) {
            this.previewImage = file.url || file.thumbUrl;
            this.previewVisible = true;
        },
        handleChange({
            fileList
        }) {
            this.fileList = fileList;
        },
        BytesToSize(bytes) {
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) {
                return '0 Byte';
            }
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
        GetExt(file) {
            return /(?:\.([^.]+))?$/.exec(file)[1];
        },
        GetAllFiles() {
            let vm = this;
            vm.loading = true;
            vm.$store.dispatch('UPLOAD_GET_ALL_FILES').then((response) => {
                vm.loading = false;
                if (response.data.length) {
                    vm.uploaded_files_backup = response.data.map(item => {
                        vm.$store.state.eventBus.$set(item, "active", false);
                        return item;
                    });
                    vm.uploaded_files = response.data.slice(0, 40).map(item => {
                        vm.$store.state.eventBus.$set(item, "active", false);
                        return item;
                    });
                }
            }).catch(error => {
                vm.loading = false;
            });
        },
        DeleteFile(id) {
            let vm = this;
            vm.$store.dispatch('UPLOAD_DELETE_FILE', {
                id: id
            }).then((response) => {
                if (response.data != null) {
                    vm.input_value = "";
                    vm.selected_file = null;
                    vm.callback(vm.input_value);
                    vm.GetAllFiles();
                }
            })
        },
        onSearch({
            target
        }) {
            let vm = this;
            let keyword = target.value;
            vm.selected_file = null;
            if (keyword != '') {
                vm.loading = true;
                vm.$store.dispatch('UPLOAD_SEARCH_FILE', {
                    keyword: keyword
                }).then((response) => {
                    if (response.data != null) {
                        vm.uploaded_files_backup = response.data.map(item => {
                            vm.$store.state.eventBus.$set(item, "active", false);
                            return item;
                        });
                        vm.uploaded_files = response.data.slice(0, 40).map(item => {
                            vm.$store.state.eventBus.$set(item, "active", false);
                            return item;
                        });
                        vm.loading = false;
                    }
                }).catch(error => {
                    vm.loading = false;
                });
            } else {
                vm.GetAllFiles();
            }
        },
        SeachFileByType({
            target
        }) {
            let vm = this;
            var keyword = target.value;
            vm.selected_file = null;
            vm.loading = true;
            vm.$store.dispatch('UPLOAD_SEARCH_FILE_BY_TYPE', {
                keyword: keyword
            }).then((response) => {
                if (response.data != null) {
                    vm.uploaded_files_backup = response.data.map(item => {
                        vm.$store.state.eventBus.$set(item, "active", false);
                        return item;
                    });
                    vm.uploaded_files = response.data.slice(0, 40).map(item => {
                        vm.$store.state.eventBus.$set(item, "active", false);
                        return item;
                    });
                    vm.loading = false;
                }
            }).catch(error => {
                vm.loading = false;
            });
        },
        onTabChange(index) {
            if (parseInt(index) === 2) {
                this.loading = true;
                setTimeout(() => {
                    this.GetAllFiles();
                }, 2000);
            }
        },
        viewFile(file) {
            let vm = this;
            if (!vm.multiple) {
                vm.selected_file = file;
                vm.uploaded_files_backup = vm.uploaded_files_backup.map(item => {
                    vm.$store.state.eventBus.$set(item, "active", false);
                    return item;
                });
                vm.uploaded_files = vm.uploaded_files.slice(0, 40).map(item => {
                    vm.$store.state.eventBus.$set(item, "active", false);
                    return item;
                });
                file.active = true;
            }
        },
        updateFile() {
            let vm = this;
            vm.update_loading = true;
            let data = {
                file_name: vm.selected_file.virtual_name,
                file_title: vm.selected_file.title,
                file_content: vm.selected_file.content,
                file_keywords: vm.selected_file.keyword,
                id: vm.selected_file.id
            }
            vm.$store.dispatch('UPLOAD_UPDATE_FILE', data).then((response) => {
                if (response.data != null) {
                    vm.update_loading = false;
                    vm.$message.success(response.data.message);
                    vm.is_editing_info = false;
                }
            }).catch(error => {
                vm.$message.error("Error found, " + error.response.data.message);
                vm.update_loading = false;
            });;

        },
        onSelectFile({
            target
        }, file, index) {
            let vm = this;
            if (target.checked) {
                vm.selected_files.push(file.file_name);
                file.active = true;
            } else {
                const index = vm.selected_files.indexOf(file.file_name);
                if (index > -1) {
                    vm.selected_files.splice(index, 1);
                }
                file.active = false;
            }
        },
        approveMultipleSelect() {
            let vm = this;
            if (vm.selected_files) {
                vm.input_value = JSON.stringify(vm.selected_files);
                vm.upload_widget = false;
                vm.callback(vm.input_value);
            }
        },
        approveSingleSelect() {
            let vm = this;
            if (vm.selected_file) {
                vm.input_value = vm.selected_file.file_name;
                vm.upload_widget = false;
                vm.callback(vm.input_value);
            }
        },
        onScollFileList({
            target
        }) {
            let vm = this;
            vm.loading = true;
            if (target.scrollTop - 200 === (target.scrollHeight - 200 - target.offsetHeight)) {
                let last_length = vm.uploaded_files.length;
                if (last_length !== vm.uploaded_files_backup.left) {
                    let next_files = vm.uploaded_files_backup.slice(last_length, last_length + 40);
                    next_files.forEach((item) => {
                        vm.uploaded_files.push(item);
                    });
                }
            }
            vm.loading = false;
        },
        parseInteger(parc) {
            return parseInt(parc);
        }
    },
    computed: {},
    watch: {

        open: function (val) {
            let vm = this;
            this.upload_widget = val;
            vm.GetAllFiles();
        },
        value: function (value) {
            let vm = this;
            if (value) {
                vm.input_value = value;
                if (vm.multiple) {
                    vm.selected_files = JSON.parse(value);
                }
            }
        }
    }
}
</script>

<style lang="scss">
.upload-wrapper {
  width: 100%;
  z-index: 9999999999999999999999999;

  .upload-overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9999999999999999999999999;
    background: rgba($color: #fff, $alpha: 0.8);
    display: flex;
    justify-content: center;
    padding-top: 15px;

    .upload-window {
      width: 1110px;
      height: 600px;
      background: #fff;
      border: 1px solid #ddd;
      position: relative;
      overflow-y: hidden;
      overflow-x: hidden;

      @media screen and (max-width: 1040px) {
        position: fixed;
        top: 0px;
        right: 0px;
        left: 0px;
        bottom: 0px;
        width: 100%;
        height: 100%;
        z-index: 999999999999999999999;
      }

      .upload-header {
        background: #fff;
        padding-bottom: 4px;
        border-bottom: 1px solid #eaeaea;
        height: 40px;

        .upload-ribbon {
          width: 100%;
          height: 40px;
          background: #8dc73f;
          position: relative;
          top: -2px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 0px 11px;

          span.title {
            color: #fff;
            font-weight: 800;
          }

          .upload-close-btn {
            color: #f7f7f7;
            cursor: pointer;
            font-size: 30px;
          }

          .upload-close-btn:hover {
            color: #fff;
          }
        }
      }

      .upload-body {
        height: calc(100% - 40px);
        width: 100%;

        .upload-container {
          height: 514px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;

          @media screen and (max-width: 1040px) {
            flex-direction: column;
            height: calc(100vh - 86px);
          }

          .upload-section {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;

            .uploading-list {
              height: 100%;
              padding-top: 10px;
              overflow-y: auto;

              ul {
                list-style: none;
                padding: 0px;

                .uploading-item {
                  height: 80px;
                  border-bottom: 1px solid #f7f7f7;
                  display: flex;
                  align-items: center;
                  justify-content: space-between;
                  padding-left: 10px;
                  padding-right: 10px;

                  @media screen and (max-width: 1040px) {
                    height: unset;
                    display: grid;
                    grid-template-columns: 100%;
                    row-gap: 9px;
                    padding: 13px 14px;
                  }

                  .image-container {
                    img {
                      object-fit: cover;
                      margin-right: 15px;
                    }
                  }

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
                    width: 263px;
                    padding-right: 29px;
                  }

                  .status {
                    width: 180px;
                  }

                  .action {
                    padding-left: 15px;

                    @media screen and (max-width: 1040px) {
                      padding-left: 0px;
                    }
                  }
                }
              }

              .widget-empty {
                height: 400px;
                display: flex;
                align-items: center;
                justify-content: center;

                p {
                  text-align: center;
                }
              }
            }

            .uploading-option {
              height: 73px;
              width: 100%;
              display: flex;
              align-items: center;
              flex-direction: row;
              align-items: center;
              border-top: 1px solid #ccc;
              padding: 0px 10px;
              background: #ededed;

              @media screen and (max-width: 1040px) {
                height: 153px;
                align-items: flex-start;
                padding-top: 13px;
              }

              .btn-add-file {
                font-size: 14px;
                display: flex;
                margin-right: 10px;
                height: 36px;
                width: 100px;
                justify-content: center;
                align-items: center;
                background: #fff;
                color: #8cc73f;
                border: 1px solid #8cc73f;
                font-weight: 600;
                overflow: hidden;
                text-decoration: none;
                text-overflow: ellipsis;
                white-space: nowrap;
              }

              .btn-start-upload-file {
                font-size: 14px;
                display: flex;
                margin-right: 10px;
                height: 36px;
                width: 100px;
                justify-content: center;
                align-items: center;
                background: #8cc73f;
                color: #fff;
                border: none;
                font-weight: 600;
                overflow: hidden;
                text-decoration: none;
                text-overflow: ellipsis;
                white-space: nowrap;
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
                overflow: hidden;
                text-decoration: none;
                text-overflow: ellipsis;
                white-space: nowrap;
              }
            }
          }

          .uploade-list {
            width: 100%;
            padding-top: 10px;
            border-right: 1px solid #ccc;
            display: flex;
            flex-direction: column;
            justify-content: space-between;

            @media screen and (max-width: 1040px) {
              height: calc(100% - 0px);
            }

            .ant-upload-list-item {
              position: relative;

              &.active {
                border: 2px solid #8dc73f;
              }

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
              padding-left: 10px;
              padding-right: 10px;
            }

            .file-list-option {
              height: 73px;
              width: 100%;
              display: flex;
              justify-content: space-between;
              align-items: center;
              flex-direction: row;
              border-top: 1px solid #ccc;
              padding: 0px 10px;
              background: #ededed;

              @media screen and (max-width: 1040px) {
                height: 153px;
                align-items: flex-start;
                padding-top: 13px;
              }

              .select-widget {
                display: none;

                @media screen and (max-width: 1040px) {
                  display: flex;

                  .btn-success {
                    margin-right: 20px;
                  }
                }
              }

              .search-input {
                @media screen and (max-width: 1040px) {
                  display: none;
                }
              }
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

            @media screen and (max-width: 1040px) {
              display: none;
            }

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

              .btn-success {
                margin-right: 20px;
              }
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
      }
    }
  }
}

.ant-popover {
  z-index: 9999999999999999 !important;
}

.ant-message {
  z-index: 999999999999999999999;
}

.selected-widget {
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
      position: relative;

      .remove-selected-btn {
        border: none;
        position: absolute;
        top: 0px;
        right: 0px;
        background: #fff;
        height: 51px;
        width: 51px;
        border-radius: 12px;
      }

      img {
        position: static;
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
  }
}

.anticon {
  vertical-align: 0.025em !important;
}

.ant-tabs-bar {
  margin: 0 0 0px 0 !important;
}
</style>
