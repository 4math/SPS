<template>
  <div class="hello">
    <h1>Welcome to the project Smart Power Socket!</h1>
    <p>
      It is a small experimental project where we tried to implement our own
      system of controlling Smart IOT device which we call as Smart Power Socket.
    </p>
    <p>
      Main goal of the project is to gain new knowledge in creating web
      applications and thinking about end user's wishes.
    </p>
    <span>We were inspired by</span>
    <a href="https://iot.mozilla.org/framework/" target="_blank">
      Mozilla IOT WebThings framework.
    </a>
    <h3>Authors:</h3>
    <ul>
      <li>
        <a href="https://github.com/cyoq" target="_blank" rel="noopener">
          cyoq
        </a>
      </li>
      <li>
        <a
          href="https://github.com/OlegGedzjuns"
          target="_blank"
          rel="noopener"
        >
          OlegGedzjuns
        </a>
      </li>
      <li>
        <a href="https://github.com/Ri0ee" target="_blank" rel="noopener">
          Ri0ee
        </a>
      </li>
    </ul>
    <h3>Link to the Github repository:</h3>
    <a href="https://github.com/4math/SPS" target="_blank" rel="noopener">
      github
    </a>
  </div>
</template>

<script>
import { USER_REQUEST } from "@/store/actions/user";
import store from "@/store";
export default {
  name: "HomePage",
  beforeRouteEnter(to, from, next) {
    if (store.getters.isAuthenticated) {
      store
        .dispatch(USER_REQUEST)
        .then(() => {
          next();
        })
        .catch((err) => {
          console.error(err);
        });
    } else {
      next();
    }
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
