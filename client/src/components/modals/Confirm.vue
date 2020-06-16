<template>
  <div id="confirm-modal">
    <b-modal
      v-model="confirmValue"
      title="Please Confirm"
      centered
      size="sm"
      ok-variant="danger"
      @ok="confirm"
    >
      Are you really sure you want to delete the socket?
    </b-modal>
  </div>
</template>


<script>
export default {
  name: "Confirm",
  props: {
    showConfirm: {
      type: Boolean,
      required: true,
      default: false,
    }
  },
  computed: {
    confirmValue: {
      get() {
        return this.showConfirm;
      },
      set(val) {
        this.$emit("closeConfirm", val);
      },
    },
  },
  methods: {
    confirm() {
      this.$emit('confirm');
    }
  }
 
};
</script>
