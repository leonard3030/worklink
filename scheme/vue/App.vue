<template>
  <div id="app">
    <router-view />
    <notifications group="status" position="bottom right" :duration="5000" />

    <vue-confirm-dialog></vue-confirm-dialog>
    <vue-particles
      color="#dedede"
    >
    </vue-particles>
    <div class="app-loader" v-if="$store.state.main_loader">
      <div class="loading-panel">
        <a-spin :spinning="true"></a-spin>
        <span class="text">Loading...</span>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
* {
  font-family: "Poppins", sans-serif;
  outline: none;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
$orange: #151a57;
$green: #151948;
.help {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  padding: 1.1rem;
  background-color: $orange;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  outline: none;
  box-shadow: 0px 0px 10px 2px #0000004f;
  transition: all 0.4s ease-in-out;
  z-index: 99999;
  &:hover,
  &:focus {
    background: $green;
  }
  i {
    color: white;
    font-size: 2rem;
  }
}
.app-loader {
  position: fixed;
  z-index: 9999999999999999999999999;
  bottom: 20px;
  right: 25px;
  .loading-panel {
    width: 174px;
    display: flex;
    justify-content: center;
    background: #000;
    padding: 10px 13px;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    .text {
      font-size: 19px;
      margin-left: 19px;
    }
  }
}
.my-title {
  font-size: 2.2rem;
  font-weight: 500;
  line-height: 2;
  @media screen and (max-width: 900px) {
    font-size: 2rem;
  }
  @media screen and (max-width: 800px) {
    font-size: 1.8rem;
    line-height: 1.25;
    padding-bottom: 0.5rem;
  }
}
.intro-header {
  text-shadow: 0 4px 2px #0000007e;
  color: white;
  font-size: 4rem;
  line-height: 1.25em;
  font-weight: 700;
  text-align: center;
  @media screen and (max-width: 900px) {
    font-size: 3rem;
  }
  @media screen and (max-width: 800px) {
    font-size: 2.25rem;
    line-height: 1.25;
    padding-bottom: 0.5rem;
  }
}
.intro-desc {
  color: #fff;
  font-weight: 400;
  font-size: 1.25rem;
  text-shadow: 0 4px 2px #0000007e;
  text-align: center;
  @media screen and (max-width: 900px) {
    font-size: 1rem;
  }
  @media screen and (max-width: 800px) {
    font-size: 0.9rem;
  }
}
.myhr {
  border: none;
  border-top: 3px $orange solid;
  width: 70px;
  margin-bottom: 1rem;
}
.mybtn {
  padding: 0.8rem 1.5rem;
  border: none;
  color: white;
  background: $orange;
  border-radius: 3px;
  transition: all 0.7s ease-in-out;
  display: inline-block;
  cursor: pointer;
  &:hover,
  &:focus {
    box-shadow: inset 20em 3rem 0 0 $green;
    color: white;
  }
  @media screen and (max-width: 600px) {
    font-size: 0.8rem;
    padding: 0.6rem 1.2rem;
  }
}
.mybtn-o {
  padding: 0.8rem 1.5rem;
  border: none;
  color: $orange;
  border-radius: 3px;
  transition: all 0.7s ease-in-out;
  display: inline-block;
  border: 2px $orange solid;
  background-color: #fff;
  cursor: pointer;
  &:hover,
  &:focus {
    box-shadow: inset 20em 3rem 0 0 $orange;
    color: white;
  }
  @media screen and (max-width: 600px) {
    font-size: 0.8rem;
    padding: 0.6rem 1.2rem;
  }
}
.txt-shad {
  text-shadow: 0 4px 2px #0000007e;
}
.txt-shad-sm {
  text-shadow: 0 2px 2px #0000007e;
}
.d-flex {
  display: flex;
}
.jc-center {
  display: flex;
  justify-content: center;
}
.ai-center {
  display: flex;
  align-items: center;
}

.intro {
  background: url("/assets/images/pe3.jpg");
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  position: relative;
  .intro-content {
    padding: 3rem 0;
    width: 100%;
    height: 100%;
    background: #000000a4;
    display: flex;
    align-items: center;
    justify-content: center;
    @media screen and (max-width: 900px) {
      padding: 0;
    }
    .intro-body {
      width: 100%;
      padding: 0 15rem;
      @media screen and (max-width: 1700px) {
        padding: 4rem 10rem;
      }
      @media screen and (max-width: 1300px) {
        padding: 3rem 5rem;
      }
      @media screen and (max-width: 900px) {
        padding: 1rem;
      }
    }
  }
}

.admin-content {
  display: flex;
  height: 100vh;
  width: 100vw;
  overflow-y: auto;
  overflow-x: hidden;
}
.admin-body {
  width: calc(100vw - 200px);
}
.page-brand {
  font-size: 1rem;
  color: #2a2a2a;
  padding: 1rem;
  border-bottom: 1px #eee solid;
  width: 100%;
}

.table {
  padding: 0 2rem;
  overflow-x: auto;
  max-height: 90vh;
  .table-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    @media only screen and(max-width: 600px) {
      flex-direction: column;
      align-items: flex-start;
      row-gap: 1rem;
    }
    label {
      font-size: 1rem;
      font-weight: 500;
      padding: 1rem 0;
      border-bottom: 1px #f0f0f0 solid;
      line-height: 2;
    }
    .add-new {
      padding: 5px 1rem;
      font-size: 0.9rem;
      color: white;
      font-weight: 500;
      border-radius: 3000px;
      border: none;
      outline: none;
      cursor: pointer;
      background: $orange;
      box-shadow: 0px 0px 10px 2px #015b0a32;
    }
  }
  table {
    width: 100%;
    tr {
      border-bottom: 1px #f0f0f0 solid;
      th {
        line-height: 2;
        padding: 0 1rem;
        text-transform: capitalize;
        font-size: 0.9rem;
        font-weight: 500;
      }
      td {
        font-weight: 300;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0 1rem;
        img {
          width: 50px;
          height: 50px;
          object-fit: cover;
          border-radius: 50%;
          padding: 3px;
          &.alt {
            width: auto;
            border-radius: 0;
          }
        }
        button,
        .button,
        a {
          padding: 5px 1rem;
          font-size: 0.7rem;
          color: white;
          font-weight: 500;
          border-radius: 3000px;
          border: none;
          outline: none;
          cursor: pointer;
          margin-right: 0.25rem;
          &.edit {
            background: #02b4eb;
            box-shadow: 0px 0px 10px 2px #02b5eb4d;
          }
          &.videos {
            background: #01cc60;
            box-shadow: 0px 0px 10px 2px #01cc6055;
          }
          &.delete {
            background: #b80303;
            box-shadow: 0px 0px 10px 2px #b8030363;
          }
          a {
            color: white;
          }
        }
      }
      .noData {
        text-align: center;
        padding: 3rem;
        color: #f54d4d;
      }
    }
    &.pads {
      th,
      td {
        padding: 10px 0;
      }
    }
  }
}
.uploader-btn {
  background: $orange;
  color: white;
  cursor: pointer;
  padding: 0.4rem 1rem;
  border-radius: 3px;
}

.ant-upload-list-item {
  position: relative !important;
  display: inline-block !important;
  float: none !important;
}
.disabled {
  cursor: no-drop !important;
  // background: #e9e9e9 !important;
  color: #141414 !important;
}
td {
  &.green {
    color: green !important;
  }
}

.all-content {
  display: flex;
}
</style>
