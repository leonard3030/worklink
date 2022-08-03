<template>
  <div class="Dashboard">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :on-cancel="onCancel"
      :color="color"
      :is-full-page="fullPage"
    ></loading>
    <div class="all-content">
      <adminSidebar />
      <div class="admin-body">
        <div class="table">
          <div class="table-title">
            <label for="Dashboard For Ren">Links</label>
            <button class="add-new" @click="addModal = true">
              <i class="fa fa-plus"></i> Add
            </button>
          </div>
          <table class="pads">
            <thead>
              <tr>
                <th>#</th>
                <th>Names</th>
                <th>Company</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.id">
                <td>{{ item.num }}</td>
                <td>{{ item.names }}</td>
                <td>{{ item.company }}</td>
                <td>
                  <router-link :to="'/' + item.id" class="edit"
                    >View</router-link
                  >
                  <button class="edit" @click="startUpdate(item)">Edit</button>
                  <button class="delete" @click="startDelete(item)">
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <Model v-if="addModal == true">
          <div class="model-header">
            <h4>Add Person</h4>
            <button class="close" @click="addModal = false">X</button>
          </div>
          <div class="model-body">
            <form>
              <div class="form-group">
                <label for="Names">Names:</label>
                <input
                  type="text"
                  Name="Names"
                  placeholder="Names"
                  v-model="newItem.names"
                />
              </div>
              <div class="form-group">
                <label for="Company">Company:</label>
                <input
                  type="text"
                  id=""
                  placeholder="Company"
                  v-model="newItem.company"
                />
              </div>
            </form>
          </div>
          <div class="model-footer">
            <button class="submit" @click.prevent="addItems()">Save</button>
            <button class="close" @click="addModal = false">Close</button>
          </div>
        </Model>
        <Model v-if="updateModal == true">
          <div class="model-header">
            <h4>Edit Attraction</h4>
            <button class="close" @click="updateModal = false">X</button>
          </div>
          <div class="model-body">
            <form>
              <div class="form-group">
                <label for="Names">Names:</label>
                <input
                  type="text"
                  Name="Names"
                  placeholder="Names"
                  v-model="selectedItem.names"
                />
              </div>
              <div class="form-group">
                <label for="Company">Company:</label>
                <input
                  type="text"
                  id=""
                  placeholder="Company"
                  v-model="selectedItem.company"
                />
              </div>
            </form>
          </div>
          <div class="model-footer">
            <button class="submit" @click.prevent="updateItems()">
              Update
            </button>
            <button class="close" @click="updateModal = false">Close</button>
          </div>
        </Model>
        <Model v-if="deleteModal == true">
          <div class="model-header">
            <h4>Delete Attraction</h4>
            <button class="close" @click="deleteModal = false">X</button>
          </div>
          <div class="model-body">
            <h4 class="delete-label">
              Are you sure you want to delete
              <strong>{{ selectedItem.names }}</strong
              >?
            </h4>
          </div>
          <div class="model-footer">
            <button class="submit" @click.prevent="deleteItems()">Yes</button>
            <button class="close" @click="deleteModal = false">Cancel</button>
          </div>
        </Model>
      </div>
    </div>
  </div>
</template>

<script>
import Model from "./components/Model.vue";
export default {
  name: "Dashboard",
  components: {
    Model,
  },
  data() {
    return {
      isLoading: false,
      fullPage: true,
      color: "#015b0a",
      addModal: false,
      updateModal: false,
      deleteModal: false,
      newItem: {},
      selectedItem: {},
      items: [],
      token: null,
    };
  },
  methods: {
    onCancel() {
      console.log();
    },
    getItems() {
      this.isLoading = true;
      this.$store.dispatch("GET_PEOPLE").then((response) => {
        this.items = response.data;
        this.isLoading = false;
      });
    },
    addItems() {
      this.isLoading = true;
      this.$store
        .dispatch("ADD_PERSON", this.newItem)
        .then((response) => {
          if (response.data.status == "ok") {
            this.$notify({
              group: "status",
              title: "Important message",
              text: response.data.message,
              type: "success",
            });
            this.addModal = false;
            this.newItem.names = "";
            this.newItem.company = "";
            this.getItems();
          } else {
            this.$notify({
              group: "status",
              title: "Important message",
              text: response.data.message,
              type: "error",
            });
          }
        })
        .catch((error) => {
          console.error({
            error,
          });
        });
      this.isLoading = false;
    },
    startUpdate(item) {
      this.selectedItem = item;
      this.updateModal = true;
    },
    updateItems() {
      this.isLoading = true;
      this.$store
        .dispatch("UPDATE_PERSON", this.selectedItem)
        .then((response) => {
          if (response.data.status == "ok") {
            this.$notify({
              group: "status",
              title: "Important message",
              text: response.data.message,
              type: "success",
            });
            this.updateModal = false;
            this.selectedItem = "";
            this.getItems();
          } else {
            this.$notify({
              group: "status",
              title: "Important message",
              text: response.data.message,
              type: "error",
            });
          }
        })
        .catch((error) => {
          console.error({
            error,
          });
        });
      this.isLoading = false;
    },
    startDelete(item) {
      this.selectedItem = item;
      this.deleteModal = true;
    },
    deleteItems() {
      this.isLoading = true;
      this.$store
        .dispatch("DELETE_PERSON", this.selectedItem.id)
        .then((response) => {
          if (response.data.status == "ok") {
            this.$notify({
              group: "status",
              title: "Important message",
              text: response.data.message,
              type: "success",
            });
            this.deleteModal = false;
            this.selectedItem = "";
            this.getItems();
          } else {
            this.$notify({
              group: "status",
              title: "Important message",
              text: response.data.message,
              type: "error",
            });
          }
        })
        .catch((error) => {
          console.error({
            error,
          });
        });
      this.isLoading = false;
    },
  },
  mounted() {
    this.getItems();
  },
};
</script>

<style lang="scss" scoped></style>
