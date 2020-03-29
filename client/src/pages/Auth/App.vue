<template>

  <div id="app">
    <h1>Welcome to the Authorization</h1>
    <label for="login">Login: </label>
    <input type="text" id="login">
    <br>

    <label for="pass">Password: </label>
    <input type="text" id="pass">
    <br>
    
    <a href="home.html">Back to the home screen</a>

  </div>

</template>

<script>

export default {
  name: 'App',
  components: {
    
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

input {
  border-radius: 5px;
  margin: 10px;
}
</style>
