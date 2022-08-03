<template>
  <div class="model">
    <div class="model-dialog">
      <div class="model-content">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Model",
};
</script>

<style lang="scss" scoped>
.model {
  position: fixed;
  height: 100vh;
  width: calc(100vw - 200px);
  background: #00000070;
  top: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: auto;
  flex-wrap: wrap;
  @media only screen and(max-width: 600px) {
    width: calc(100vw - 100px);
  }
  .model-dialog {
    background: #fff;
    width: 25vw;
    border-radius: 10px;
    overflow: hidden;
    margin: 3rem 0;
    @media only screen and(max-width: 1500px) {
      width: 40vw;
    }
    @media only screen and(max-width: 800px) {
      width: 60vw;
    }
    .model-content {
      .model-header {
        border-bottom: 1px #f0f0f0 solid;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        .close {
          outline: none;
          border: none;
          cursor: pointer;
          background: #b80000;
          height: 23px;
          width: 23px;
          border-radius: 50%;
          color: white;
          font-size: .8rem;
          font-weight: 500;
          display: flex;
          justify-content: center;
          align-items: center;
          &:hover {
            background: #880000;
            color: #5e4444;
          }
        }
      }
      .model-body {
        border-bottom: 1px #f0f0f0 solid;
        padding: 10px;
        display: block;
        form {
          padding: 0 1rem;
          .form-group {
            display: block;
            margin-bottom: 1.5rem;
            label {
              display: block;
              margin-bottom: 0rem;
            }
            input,
            textarea,
            select {
              padding: 10px;
              border: 1px #eee solid;
              border-radius: 3px;
              width: 100%;
            }
            input[type="checkbox"] {
              width: auto;
            }
            .checkbox {
              display: flex;
              align-items: center;
              column-gap: 1rem;
              label {
                margin-bottom: 0;
              }
            }
            span {
              &.optional {
                color: red;
                font-size: 10px;
              }
            }
          }
        }
      }
      .model-footer {
        border-bottom: 1px #f0f0f0 solid;
        padding: 10px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        column-gap: 0.5rem;
        button {
          padding: 10px 1rem;
          font-size: 0.7rem;
          color: white;
          font-weight: 500;
          border-radius: 3000px;
          border: none;
          outline: none;
          cursor: pointer;
          &.submit {
            background: #3d003d;
            box-shadow: 0px 0px 10px 2px #3d003d54;
          }
          &.close {
            background: #b80303;
            box-shadow: 0px 0px 10px 2px #b8030363;
          }
        }
      }
    }
  }
}
</style>
