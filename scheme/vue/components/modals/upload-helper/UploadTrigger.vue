<template>
  <div class="upload-trigger">
    <!-- Upload triger -->
    <div :class="classlist" @click="OpenUpload()" v-if="!input_value">
      <slot></slot>
    </div>

    <!-- Selected file section -->
    <div class="selected-widget">
      <!-- List selected files -->
      <div class="selected-container" v-if="selected_files.length !== 0 && multiple">
        <div class="ant-upload-list ant-upload-list-picture-card" style="width: 100%;">
          <div
            class="ant-upload-list-item ant-upload-list-item-done"
            v-for="(file,index) in  selected_files"
            :key="index+'selected_file'"
          >
            <div class="ant-upload-list-item-info">
              <span>
                <div class="ant-upload-list-item-thumbnail">
                  <img
                    v-if="GetExt(file) == 'jpeg' || GetExt(file) == 'jpg' || GetExt(file) == 'png' || GetExt(file) == 'gif' "
                    :src="`${url+location}${file}`"
                  />
                  <img
                    v-if="GetExt(file) == 'mp3' || GetExt(file) == 'ogg' || GetExt(file) == 'acc'"
                    :src="`${url}assets/images/ext/audio.png`"
                  />
                  <img
                    v-if="GetExt(file) == 'mp4' || GetExt(file) == 'avi' || GetExt(file) == 'vob' || GetExt(file) == 'mov'"
                    :src="`${url}assets/images/ext/video.png`"
                  />
                  <img v-if="GetExt(file) == 'pdf'" :src="`${url}assets/images/ext/pdf.png`" />
                  <img
                    v-if="GetExt(file) == 'docs' || GetExt(file) == 'doc'"
                    :src="`${url}assets/images/ext/word.png`"
                  />
                  <img
                    v-if="GetExt(file) == 'xls' || GetExt(file) == 'xlsx' || GetExt(file) == 'xlsm' || GetExt(file) == 'xlt' || GetExt(file) == 'xltx' || GetExt(file) == 'xltm' || GetExt(file) == 'xla' || GetExt(file) == 'xlam'"
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

          <div class="ant-upload-list-item ant-upload-list-item-done" @click="OpenUpload()">
            <div class="ant-upload-list-item-thumbnail add-card">
              <a-icon style="color:#ccc" type="plus" />
            </div>
          </div>
        </div>
      </div>
      <!-- End of selected file list -->
      <!-- Start of single file selected -->
      <div class="selected-container" v-if="input_value && !multiple">
        <div class="image-widget">
          <img
            v-if="GetExt(input_value) == 'jpeg' || GetExt(input_value) == 'jpg' || GetExt(input_value) == 'png' || GetExt(input_value) == 'gif' "
            :src="`${url+location}${input_value}`"
          />
          <img
            v-if="GetExt(input_value) == 'mp3' || GetExt(input_value) == 'ogg' || GetExt(input_value) == 'acc'"
            :src="`${url}assets/images/ext/audio.png`"
          />
          <img
            v-if="GetExt(input_value) == 'mp4' || GetExt(input_value) == 'avi' || GetExt(input_value) == 'vob' || GetExt(input_value) == 'mov'"
            :src="`${url}assets/images/ext/video.png`"
          />
          <img v-if="GetExt(input_value) == 'pdf'" :src="`${url}assets/images/ext/pdf.png`" />
          <img
            v-if="GetExt(input_value) == 'docs' || GetExt(input_value) == 'doc'"
            :src="`${url}assets/images/ext/word.png`"
          />
          <img
            v-if="GetExt(input_value) == 'xls' || GetExt(input_value) == 'xlsx' || GetExt(input_value) == 'xlsm' || GetExt(input_value) == 'xlt' || GetExt(input_value) == 'xltx' || GetExt(input_value) == 'xltm' || GetExt(input_value) == 'xla' || GetExt(input_value) == 'xlam'"
            :src="`${url}assets/images/ext/excel.png`"
          />
          <a-popconfirm
            title="Are you sure？"
            @confirm="removeFileFromSelected(index)"
            okText="Yes"
            cancelText="No"
          >
            <button class="remove-selected-btn" @click="removeSingleSelected()">
              <a-icon style="color:red" type="delete" />
            </button>
          </a-popconfirm>
        </div>
      </div>
      <!-- End of single file selected -->
    </div>
  </div>
</template>

<script>
export default {
  name: "upload-trigger",
  components: {},
  props: {
    open: {
      type: Boolean,
      default: false,
    },
    enableinput: {
      type: Boolean,
      default: false,
    },
    required: {
      type: Boolean,
      default: true,
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    location: {
      type: String,
      default: "",
    },
    uploadurl: {
      type: String,
      default: "",
    },
    url: {
      type: String,
      default: "",
    },
    name: {
      type: String,
      default: "",
    },
    value: {
      type: String,
      default: "",
    },
    classlist: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      input_value: "",
      selected_files: [],
    };
  },
  mounted() {
    let vm = this;
    if (vm.value) {
      if (vm.multiple) {
        vm.selected_files = JSON.parse(vm.value);
      } else {
        vm.input_value = vm.value;
      }
    }
  },
  methods: {
    removeSingleSelected() {
      let vm = this;
      if (vm.input_value) {
        vm.input_value = "";
        vm.upload_widget = false;
        vm.$emit("onFileSelected", vm.input_value);
      }
    },
    removeFileFromSelected(index) {
      let vm = this;
      if (index > -1) {
        vm.selected_files.splice(index, 1);
        if (vm.selected_files.length > 0) {
          vm.input_value = JSON.stringify(vm.selected_files);
        } else {
          vm.input_value = "";
        }
        vm.$emit("onFileSelected", vm.input_value);
      }
    },
    GetExt(file) {
      return /(?:\.([^.]+))?$/.exec(file)[1];
    },
    OpenUpload() {
      let vm = this;
      let settings = {
        callback: (params) => {
          if (params) {
            if (vm.multiple) {
              vm.selected_files = JSON.parse(params);
            } else {
              vm.input_value = params;
            }
          }
          vm.$emit("onFileSelected", params);
        },
        open: vm.open,
        enableinput: vm.enableinput,
        required: vm.required,
        location: vm.location,
        uploadurl: vm.uploadurl,
        url: vm.url,
        name: vm.name,
        value: vm.value,
        multiple: vm.multiple,
        classlist: vm.classlist,
      };
      vm.$Uploader(settings);
    },
  },
  watch: {
    value() {
      let vm = this;
      if (vm.value) {
        if (vm.multiple) {
          vm.selected_files = JSON.parse(vm.value);
        } else {
          vm.input_value = vm.value;
        }
      }
    },
  },
};
</script>


<style lang="scss">
.upload-trigger {
  width: 100%;
}
</style>