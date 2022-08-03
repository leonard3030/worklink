<template>
  <div class="rsl-home">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :on-cancel="onCancel"
      :color="color"
      :is-full-page="fullPage"
    ></loading>
    <clientHeader />
    <div class="content-widget">
      <div class="top-part">
        <div class="logo">
          <img src="/assets/images/worklogo.png" alt="" srcset="" />
        </div>
        <div class="greetings">
          <label for="">Great Day To You!</label>
          <label for="" class="name">{{ person.names }}</label>
          <label for="" class="company">{{ person.company }}</label>
        </div>
        <div class="video-section">
          <label for="" class="title"
            >We prepared this short video to share how we can help you, your
            team and you organization to be more productive and
            profitable.</label
          >
          <div class="video-widget">
            <video class="myvid" controls>
              <source src="/assets/uploaded/mov.mp4" type="video/mp4" />
            </video>
          </div>
          <label for="" class="after-video"
            >Productivity & Profitability-driven training programs for</label
          >
          <p>{{ person.company }}</p>
        </div>
        <div class="flyers">
          <div class="flyer-item">
            <img src="/assets/images/fl2.png" alt="" srcset="" />
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Architecto quia inventore, tempore obcaecati deleniti laudantium
              molestiae odit aperiam officiis quo quidem dolor dignissimos est
              quasi earum? Laboriosam laudantium laborum hic. Lorem ipsum dolor,
              sit amet consectetur adipisicing elit. Vero expedita libero,
              quidem iure quos eaque nulla! Quisquam error unde ea blanditiis
              voluptatum? Dolorum enim unde modi accusamus error perferendis
              velit!
            </p>
          </div>
          <div class="flyer-item">
            <img src="/assets/images/fl1.png" alt="" srcset="" />
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Architecto quia inventore, tempore obcaecati deleniti laudantium
              molestiae odit aperiam officiis quo quidem dolor dignissimos est
              quasi earum? Laboriosam laudantium laborum hic. Lorem ipsum dolor,
              sit amet consectetur adipisicing elit. Vero expedita libero,
              quidem iure quos eaque nulla! Quisquam error unde ea blanditiis
              voluptatum? Dolorum enim unde modi accusamus error perferendis
              velit!
            </p>
          </div>
        </div>
        <div class="form-widget">
          <p class="title">
            Register yourself or your team
          </p>
          <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSevQjvAY_zLNFTQKFeAKEdZWX9WVM_5ZtCnUkATKZLfMq2jNg/viewform" frameborder="0"></iframe>
        </div>
        <div class="last-widget">
          <p for="" class="title"></p>
            We prepared this short video to share how we can help you, your team
            and you organization to be more productive and profitable.
          </p>
          <div class="greetings">
            <label for=""
              >Thank you!
              <label for="" class="name">{{ person.names }}</label></label
            >
            <label for=""
              >Let's Partner with
              <label for="" class="company">{{ person.company }}</label></label
            >
            <label for="">to drive productivity & profitability</label>
          </div>
        </div>
      </div>
    </div>
    <clientFooter />
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      isLoading: false,
      color: "#072e4d",
      fullPage: true,
      person: null,
    };
  },
  methods: {
    onCancel() {
      console.log();
    },
    getPerson() {
      this.isLoading = true;
      this.$store
        .dispatch("VIEW_PERSON", this.$route.params.id)
        .then((response) => {
          this.person = response.data;
          this.isLoading = false;
        });
    },
  },
  mounted() {
    let vm = this;
    this.getPerson();
  },
};
</script>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&display=swap");
$maincolor: #151948;
.content-widget {
  font-family: "Montserrat", sans-serif;
  background: white;
  padding: 2.5rem 12rem;
  @media only screen and (max-width: 1100px) {
    padding: 2.5rem 6rem;
  }
  @media only screen and (max-width: 700px) {
    padding: 1rem 1rem;
  }
  .top-part {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem;
    .logo {
      img {
        width: 200px;
      }
    }
    .greetings {
      display: flex;
      flex-direction: column;
      align-items: center;
      row-gap: 0;
      margin-top: 1.5rem;
      label {
        font-size: 1.5rem;
        line-height: 20px;
        &.name {
          margin-top: 20px;
          color: black;
          font-size: 1.75rem;
          font-family: "Charm", cursive;
          font-weight: 700;
        }
        &.company {
          margin-top: 0;
          font-size: 1.2rem;
          font-weight: 600;
          color: $maincolor;
        }
      }
    }
    .video-section {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 3rem;
      .title {
        font-size: 0.8rem;
        color: black;
        font-weight: 400;
        text-align: center;
        width: 80%;
        margin-bottom: 1.5rem;
      }
      .video-widget {
        video {
          width: 600px;
          @media only screen and (max-width: 700px) {
            width: 80vw;
          }
        }
      }
      .after-video {
        margin-top: 1rem;
      }
      p {
        margin-top: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: $maincolor;
      }
    }
    .flyers {
      display: grid;
      grid-template-columns: 46% 46%;
      grid-column-gap: 4%;
      @media only screen and (max-width: 700px) {
        grid-template-columns: 100%;
      }
      .flyer-item {
        display: flex;
        flex-direction: column;
        row-gap: 20px;
        img {
          width: 100%;
        }
        p {
          font-size: 0.8rem;
          text-align: left;
          padding-left: 1rem;
        }
      }
    }
    .form-widget{
      display: flex;
      flex-direction: column;
      align-items: center;
      .title{
        background: darkred;
        color: white;
        padding: 5px 15px;
        border-radius: 1000px;
        margin-top: 2rem;
      }
      iframe{
        width: calc(100vw - 24rem);
        height: 1000px;
      }
    }
    .last-widget {
      display: flex;
      flex-direction: column;
      align-items: center;
      .title {
        font-size: 0.8rem;
        color: black;
        font-weight: 400;
        text-align: center;
        width: 70%;
        margin-bottom: 1.5rem;
        text-align: center;
      }
      .greetings {
        display: flex;
        flex-direction: column;
        align-items: center;
        row-gap: 0;
        margin-top: 2rem;
        label {
          font-size: 1rem;
          line-height: 20px;
          &.name {
            margin-top: 20px;
            color: black;
            font-size: 1.1rem;
            font-family: "Charm", cursive;
            font-weight: 700;
          }
          &.company {
            margin-top: 0;
            font-size: 1rem;
            font-weight: 600;
            color: $maincolor;
          }
        }
      }
    }
  }
}
</style>
