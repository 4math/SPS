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
        <label for="switch">Turn {{ state ? "off" : "on" }}</label>
        <b-input-group-prepend is-text>
          <b-form-checkbox
            switch
            class="mr-n2"
            :checked="state"
            @change="put"
          />
        </b-input-group-prepend>
      </b-input-group>

      <b-dropdown id="actions" text="Actions" class="lg" menu-class="w-100">
        <b-dropdown-item-button>
          Go to the chart tab
        </b-dropdown-item-button>
        <b-dropdown-item-button>
          Go to the event tab
        </b-dropdown-item-button>
        <b-dropdown-item-button variant="danger" @click="commit">
          Delete Socket
        </b-dropdown-item-button>
      </b-dropdown>
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
    },
    title: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      default: "",
    },
    state: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {};
  },
  computed: {
    image() {
      const ext = this.state ? "_on" : "_off";
      // eslint-disable-next-line no-undef
      return require(`@/assets/images/socket${ext}.jpg`);
    },
  },
  methods: {
    put(value) {
      this.$emit("put", this.id, value);
    },
    commit() {
      this.$emit("commitDeletion", this.id);
    },
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

.input-group-prepend {
  display: inline-block !important;
  margin: 0 auto;
  width: 25%;
  height: 25%;
}

.input-group-text {
  height: 50px;
}

label {
  display: inline-block !important;
  padding: 1em;
}

#actions {
  width: 100%;
}
</style>
