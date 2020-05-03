<template>
  <div id="socket-card">
    <b-card
      :title="title"
      :img-src="image"
      img-alt="Image"
      img-top
      tag="article"
      style="max-width: 20rem;"
      class="mb-2"
    >
      <b-card-text>
        {{ description }}
      </b-card-text>

      <b-input-group id="switch" size="lg">
        <b-input-group-prepend is-text>
          <b-form-checkbox
            switch
            class="mr-n2"
            :checked="state"
            @change="put"
          />
        </b-input-group-prepend>
      </b-input-group>
    </b-card>
  </div>
</template>

<script>
export default {
  name: "Socket",
  props: {
    id: {
      type: Number,
      required: true,
      default: 0,
    },
    title: {
      type: String,
      required: true,
      default: "",
    },
    description: {
      type: String,
      required: true,
      default: "",
    },
    state: {
      type: Boolean,
      required: true,
      default: false,
    },
  },
  computed: {
    image() {
      const image = this.state ? "bulb_on" : "bulb";
      // eslint-disable-next-line no-undef
      return require(`@/assets/images/${image}.jpg`);
    },
  },
  methods: {
    put(value) {
      this.$emit("put", this.id, value);
    }
  },
};
</script>

<style scoped>
#socket-card {
  display: inline-block;
  margin: 10px;
}

#switch {
  margin: 0 auto;
  display: inline;
}
</style>
