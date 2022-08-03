<template>
  <section class="editor-widget">
    <input type="hidden" :name="name" :value="content" :required="required" />

    <div :id="id + 'toolbar-container'">
      <span class="ql-formats">
        <select class="ql-font"></select>
        <select class="ql-size"></select>
      </span>
      <span class="ql-formats">
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
        <button class="ql-strike"></button>
      </span>
      <span class="ql-formats">
        <select class="ql-color"></select>
        <select class="ql-background"></select>
      </span>
      <span class="ql-formats">
        <button class="ql-script" value="sub"></button>
        <button class="ql-script" value="super"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-header" value="1"></button>
        <button class="ql-header" value="2"></button>
        <button class="ql-blockquote"></button>
        <button class="ql-code-block"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
        <button class="ql-indent" value="-1"></button>
        <button class="ql-indent" value="+1"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-direction" value="rtl"></button>
        <select class="ql-align"></select>
      </span>
      <span class="ql-formats">
        <button class="ql-link"></button>
        <button class="ql-image"></button>
        <button class="ql-video"></button>
        <button class="ql-formula"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-clean"></button>
      </span>
    </div>
    <div :id="id" style="height: 400px;"></div>

    <vupload
      classlist="btn btn-primary btn-lg btn-block"
      :open="open_upload_modal"
      :enableinput="false"
      :required="false"
      :multiple="false"
      location="assets/uploaded/"
      :url="url"
      :uploadurl="uploadurl"
      name
      value
      @onFileSelected="onImageSelected"
    ></vupload>
  </section>
</template>

<script lang="js">
import Quill from "quill";
import VUpload from "./VUpload.vue";
import "quill/dist/quill.snow.css"

export default {
    components: { vupload: VUpload },
    name: 'veditor',
    props: ['url', 'uploadurl', 'required', 'name', 'value','id'],
    mounted() {
        let vm = this;

        vm.quill = new Quill('#'+this.id, {
            modules: {
                syntax: false,
                toolbar: '#'+this.id + 'toolbar-container'
            },
            placeholder: '',
            theme: 'snow'
        });
        vm.quill.clipboard.dangerouslyPasteHTML(vm.value)

;
        vm.quill.on('text-change', (delta, oldDelta, source) => {
            let html = vm.quill.root.innerHTML;
            vm.textChanged(html, delta)
            this.$emit('change', html);
        });

        var toolbar = vm.quill.getModule("toolbar");
        toolbar.addHandler("image", vm.customImageHandler);

    },
    data() {
        return {
            content: "",
            open_upload_modal: false,
            Editor: null,
            quill: null,
            range: null
        }
    },
    methods: {
        customImageHandler(image, feedback) {
            this.open_upload_modal = true;
            this.Editor = this.quill;
            this.range = this.Editor.getSelection();
        },
        onImageSelected(file_name) {
            let vm = this;
            var cursorLocation = this.range.index;
            var url = `${this.url}assets/uploaded/${file_name}`;
            this.Editor.insertEmbed(cursorLocation, "image", url);
            this.open_upload_modal = false;
        },
        textChanged(html, delta) {
            let vm = this;
            vm.content = html;
        }
    },
    computed: {

    }
}
</script>

<style scoped lang="scss"></style>
