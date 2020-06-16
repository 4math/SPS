<template>
  <div id="socket-update-info">
    <b-modal
      id="socket-upd-info-modal"
      ref="modal"
      v-model="showValue"
      centered
      title="Submit Your Socket Info"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <div class="input-group">
          <b-form-group
            id="name-input-group"
            label="Name"
            label-for="name-input"
            invalid-feedback="Name is required"
          >
            <b-form-input
              id="name-input"
              v-model="$v.form.name.$model"
              :state="validateState('name')"
              :placeholder="givenName"
              required
            />
          </b-form-group>

          <b-form-group
            id="desc-input-group"
            label="Description"
            label-for="desc-input"
          >
            <b-form-input
              id="desc-input"
              v-model="description"
              :placeholder="givenDescription ? givenDescription : 'It is my lovely socket'"
              required
            />
          </b-form-group>
        </div>
      </form>
    </b-modal>
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { validationMixin } from "vuelidate";
export default {
  name: "SocketUpdateInfo",
  mixins: [validationMixin],
  props: {
    showSocketUpdateInfo: {
      type: Boolean,
      required: true,
      default: false,
    },
    givenName: {
      type: String,
      required: true,
    },
    givenDescription: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      form: {
        name: null,
      },
      description: "",
    };
  },
  validations: {
    form: {
      name: {
        type: String,
        required,
      },
    },
  },
  computed: {
    showValue: {
      get() {
        return this.showSocketUpdateInfo;
      },
      set(value) {
        this.$emit("closeModal", value);
      },
    },
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    resetModal() {
      this.form = {
        name: null,
      };

      this.description = "";

      this.$nextTick(() => {
        this.$v.$reset();
      });
    },
    handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.handleSubmit();
    },
    handleSubmit() {
      // don't submit if input is empty
      this.$v.form.$touch();
      if (this.$v.form.$anyError && this.$v.form.$anyDirty) {
        return;
      }
      // Hide the modal manually
      this.$nextTick(() => {
        this.$emit(
          "socketUpdateInfo",
          this.form.name,
          this.description
        );
        this.$bvModal.hide("socket-upd-info-modal");
      });
    },
  },
};
</script>

<style scoped>
.form-group {
  width: 100%;
}
</style>
